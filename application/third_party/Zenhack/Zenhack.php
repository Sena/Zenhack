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

    public function __construct($subdomain)
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        $this->subdomain = $subdomain;
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

        return $this->filter($this->post_unread, $filter);
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
        return $this->filter($this->post_read, $filter);
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
                if($this->datetime_limit !== null && $this->datetime_limit > $array_row->updated_at) {

                    unset($array[$array_key]);                    
                    $this->log('Removed post: ' . var_export($array_row, true));
                    continue;
                }
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

            foreach ($posts->posts as $post_key => $post_row) {
                if($this->stop_seek !== false) {
                    break;
                }

                $post_row->comments = array();

                if ($post_row->comment_count == 0) {
                    $this->set_post_unread($post_row);
                } else {
                    $post_row->comments = $this->find_comments($post_row->id, $post_row->comment_count);

                    if (count($post_row->comments) > 0) {
                        $this->log('$post_row->id: ' . $post_row->id);
                        if ($this->check_author($post_row->comments, $this->author_list) === false) {
                            $this->set_post_unread($post_row);
                        } else {
                            $this->set_post_read($post_row);
                        }
                    }
                }
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

    /**
     * @param int $id
     * @return array
     */
    protected function find_comments($post_id, $comment_count)
    {
        $file_data = null;

        if (isset($_SESSION['post' . $post_id]['cc' . $comment_count])) {
            $this->log('Reading: $_SESSION[post' . $post_id . '][cc' . $comment_count . ']');
            $file_data = $_SESSION['post' . $post_id]['cc' . $comment_count];
        } elseif (isset($_SESSION['post' . $post_id])) {
            $this->log('Cleaning: $_SESSION[post' . $post_id . ']');
            unset($_SESSION['post' . $post_id]);
        }

        if($file_data === null) {
            $data = $this->curl('https://' . $this->subdomain . '.zendesk.com/api/v2/help_center/community/posts/' . $post_id . '/comments.json');
            if (isset($data->comments)) {
                $_SESSION['post' . $post_id]['cc' . $comment_count] = $data;
            }else{
                $this->log('not put' . $post_id);
            }
        }
        return isset($_SESSION['post' . $post_id]['cc' . $comment_count]->comments) ? $_SESSION['post' . $post_id]['cc' . $comment_count]->comments : array();
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
