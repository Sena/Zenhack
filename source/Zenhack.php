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
    private $author_list = array();

    private $subdomain = null;

    /**
     * @var array
     */
    private $post_unread = array();
    /**
     * @var bool|int
     */
    private $post_unread_limit = false;
    /**
     * @var array
     */
    private $post_read = array();
    /**
     * @var bool|int
     */
    private $post_read_limit = false;
    /**
     * @var bool
     */
    private $stop_seek = false;
    /**
     * @var bool
     */
    private $log = false;

    public function __construct($subdomain)
    {
        $this->subdomain = $subdomain;
    }

    public function filter_author($author, $clear = false) {
        if($clear) {
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

        return $this->filter($this->post_unread, $filter);
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
    private function filter(Array $array, Array $filter = array())
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
    private function make_call()
    {
        $post_url = 'https://' . $this->subdomain . '.zendesk.com/api/v2/community/posts.json?per_page=100';

        while (strlen($post_url) > 30 && $this->stop_seek === false) {
            $posts = $this->curl($post_url);

            foreach ($posts->posts as $post_key => $post_row) {

                $post_row->comments = array();

                if ($post_row->comment_count == 0) {
                    $this->set_post_unread($post_row);
                } else {
                    $post_row->comments = $this->find_comments($post_row->id);

                    if ($this->check_author($post_row->comments, $this->author_list) === false) {
                        $this->set_post_unread($post_row);
                    } else {
                        $this->set_post_read($post_row);
                    }
                }
            }

            $post_url = $posts->next_page . '&per_page=100';
        }
    }

    /**
     * @param $post_unread
     */
    private function set_post_unread($post_unread)
    {
        $this->post_unread[] = $post_unread;

        $this->log($this->post_unread_limit . ' - ' . count($this->post_unread));

        if($this->post_unread_limit && count($this->post_unread) >= $this->post_unread_limit) {
            $this->log('limitado');
            $this->stop_seek = true;
        }
    }

    /**
     * @param $post_read
     */
    private function set_post_read($post_read)
    {
        $this->post_read[] = $post_read;

        if($this->post_read_limit && count($this->post_read) >= $this->post_read_limit) {
            $this->stop_seek = true;
        }
    }

    /**
     * @param int $id
     * @return array
     */
    private function find_comments($id)
    {
        $data = $this->curl('https://' . $this->subdomain . '.zendesk.com/api/v2/help_center/community/posts/' . $id . '/comments.json');;
        return isset($data->comments) ? $data->comments : array();
    }

    /**
     * @param array $data
     * @return bool
     */
    private function check_author(Array $data)
    {
        if(count($data) === 0) {
            return false;
        }
        $data = current($data);

        return in_array($data->author_id, $this->author_list);
    }

    /**
     * @param string $url
     * @return array|mixed
     */
    private function curl($url)
    {
        if($this->stop_seek) {
            return array();
        }
        $this->log($url);
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/xml; charset=ISO-8859-1'));
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Accept: application/xml; charset=ISO-8859-1'));
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
    private function log($data)
    {
        if($this->log) {
            $name = 'requests.txt';
            $text = date('Y-m-d H:i:s') . ' - ' . $data . PHP_EOL;
            $file = fopen($name, 'a');
            fwrite($file, $text);
            fclose($file);
        }
    }
}