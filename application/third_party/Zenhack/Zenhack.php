<?php

namespace zh;

/**
 * Class Zenhack
 * @package zh
 */
class Zenhack
{
    /**
     * @var array
     */
    protected $author_list = array();

    protected $subdomain = null;
    protected $datetime_limit = null;
    

    /**
     * @var array
     */
    protected $post_unread = array();
    /**
     * @var bool|int
     */
    protected $post_unread_limit = false;
    /**
     * @var array
     */
    protected $post_read = array();
    /**
     * @var bool|int
     */
    protected $post_read_limit = false;
    /**
     * @var bool
     */
    protected $stop_seek = false;
    /**
     * @var bool
     */
    protected $log = false;

    public function __construct($subdomain, $log = false)
    {
        $this->set_datetime_limit(date('Y-m-d H:i:s', strtotime("-730 days")));
        $this->subdomain = $subdomain;
        $this->log = $log;
    }

    public function filter_author($author, $clear = false)
    {
        if ($clear) {
            $this->author_list = array();
        }
        $this->author_list = array_merge($this->author_list, (array)$author);
    }

    /**
     * @param array $filter
     * @param bool|int $limit
     * @return array
     */
    public function get_post_unread(Array $filter = array(), $limit = false)
    {
        $this->post_unread_limit = $limit;

        if (count($this->post_unread) == 0) {
            $this->make_call();
        }
        $this->log('process ended');

        $return = $this->filter($this->post_unread, $filter);
        
        $this->log(__METHOD__ . ': ' . var_export($return, true));

        return $return;
    }

    /**
     * @param array $filter
     * @param bool|int $limit
     * @return array
     */
    public function set_datetime_limit($datetime)
    {
        $this->datetime_limit = $datetime;
    }

    /**
     * @param array $filter
     * @param bool|int $limit
     * @return array
     */
    public function get_post_read(Array $filter = array(), $limit = false)
    {
        $this->post_read_limit = $limit;

        if (count($this->post_read) == 0) {
            $this->make_call();
        }
        $return = $this->filter($this->post_read, $filter);
        
        $this->log(__METHOD__ . ': ' . var_export($return, true));

        return $return;
    }

    /**
     * @param array $array
     * @param array $filter
     * @return array
     */
    protected function filter(Array $array, Array $filter = array())
    {
        if (count($filter) > 0) {
            foreach ($array as $array_key => $array_row) {
                foreach ($array_row as $key => $row) {
                    if (!in_array($key, $filter)) {
                        unset($array_row->$key);
                    }
                }
                $array[$array_key] = $array_row;
            }
        }

        return $array;
    }

    /**
     *
     */
    protected function make_call()
    {
        $param = 'per_page=100&sort_by=recent_activity';
        $post_url = 'https://' . $this->subdomain . '.zendesk.com/api/v2/community/posts.json?' . $param;

        while (strlen($post_url) > 30 && $this->stop_seek === false) {
            
            $posts = $this->curl($post_url);

            if(!isset($posts->posts) || count($posts->posts) === 0) {
                $this->log('not found posts in $post: ' . var_export($posts, true));
                continue;
            }

            foreach ($posts->posts as $post_key => $post_row) {
                if($this->stop_seek !== false) {
                    break;
                }

                $post_row->comments = array();

                if ($post_row->comment_count == 0) {      
                    $this->log('post created at: ' . $post_row->created_at);
                    if($this->datetime_limit <= $post_row->created_at) {
                        $this->log('new post: ' . $post_row->id);
                        $this->log('post created at: ' . $post_row->created_at);
                        $this->set_post_unread($post_row);
                    }else{
                        $this->log('old post: ' . $post_row->id);
                    }
                } else {
                    $post_row->comments = $this->find_comments($post_row->id, $post_row->comment_count);

                    if (count($post_row->comments) > 0) {
                        $this->log('post with comments: ' . $post_row->id);
                        if ($this->check_author($post_row->comments, $this->author_list) === false) {
                            $this->log('post with comments - they: ' . $post_row->id);
                            $this->set_post_unread($post_row);
                        } else {
                            $this->log('post with comments - ours: ' . $post_row->id);
                            $this->set_post_read($post_row);
                        }
                    }
                }
            }

            if($posts->next_page === null) {
                $this->stop_seek = true;
            }
            $post_url = $posts->next_page . '&' . $param;
        }
    }

    /**
     * @param $post_unread
     */
    protected function set_post_unread($post_unread)
    {
        $this->post_unread[] = $post_unread;

        $this->log($this->post_unread_limit . ' - ' . count($this->post_unread));
        
        if($this->datetime_limit > $post_unread->updated_at) {
            $this->stop_seek = true;
            $this->log('Limited at :' . $post_unread->updated_at);
        }else {
            $this->log('Date :' . $post_unread->updated_at);
        }

        if ($this->post_unread_limit && count($this->post_unread) >= $this->post_unread_limit) {
            $this->log('limitado');
            $this->stop_seek = true;
        }
    }

    /**
     * @param $post_read
     */
    protected function set_post_read($post_read)
    {
        $this->post_read[] = $post_read;

        if ($this->post_read_limit && count($this->post_read) >= $this->post_read_limit) {
            $this->stop_seek = true;
        }
    }
    protected function unset_store($key) {
        if(!isset($_SESSION)) {
            session_start();
        }
        unset($_SESSION[$key]);
    }

    protected function store($key, $value = null)
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if($value === null) {
            return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        }
        $_SESSION[$key] = $value;
    }

    /**
     * @param int $id
     * @return array
     */
    protected function find_comments($post_id, $comment_count)
    {
        $store_key = 'post' . $post_id;
        $store_value = $this->store($store_key);

        if (isset($store_value['cc' . $comment_count])) {
            $this->log('Reading: store_value(' . $store_key . '[cc' . $comment_count . ')');
        } elseif ($store_value !== null) {
            $this->log('Cleaning: store_value(' . $store_key . ')');
            $this->unset_store($store_key);
        }

        if($store_value === null) {
            $param = 'per_page=1&sort_by=recent_activity';
            $data = $this->curl('https://' . $this->subdomain . '.zendesk.com/api/v2/help_center/community/posts/' . $post_id . '/comments.json?' . $param);
            
            if (isset($data->comments)) {                
                $this->log('comment date: ' . $data->comments[0]->created_at);

                if($this->datetime_limit <= $data->comments[0]->created_at) {
                    $store_value['cc' . $comment_count] = $data;
                    $this->store($store_key, $store_value);
                }else{
                    $this->log('comment skiped - ' . $store_key);
                }
            }else{
                $this->log('comment with problem - ' . $store_key);
            }
        }
        return isset($store_value['cc' . $comment_count]->comments) ? $store_value['cc' . $comment_count]->comments : array();
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function check_author(Array $data)
    {
        if (count($data) === 0) {
            $this->log('empty data: ');
            return false;
        }
        $data = current($data);

        $this->log('$data->author_id: ' . $data->author_id);

        return in_array($data->author_id, $this->author_list);
    }

    /**
     * @param string $url
     * @return array|mixed
     */
    protected function curl($url)
    {
        if ($this->stop_seek) {
            return array();
        }
        $this->log($url);

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        curl_close($curl);

        return json_decode($data);
    }
    /**
     * @deprecated No longer used by internal code and not recommended.
     */
    public function log_active($status = true)
    {
        $this->log = !$status == false;
    }

    /**
     * @param string $data
     */
    protected function log($data)
    {
        if ($this->log) {
            $text = date('Y-m-d H:i:s') . ' - ' . $data;
            $this->write_file($text);
        }
    }
    protected function write_file($text, $name = 'log.txt')
    {
        $text .= PHP_EOL;
        $file = fopen($name, 'a');
        fwrite($file, $text);
        fclose($file);
    }
}