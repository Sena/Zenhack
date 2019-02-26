<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scratch extends MY_Controller {

	public function index()
	{
		$this->data['post_unread'] = $this->get_zd(true);

		$this->renderer();
	}

	public function listing()
	{
		$this->data['post_unread'] = $this->get_zd(true);

        $this->renderer();
	}

	public function next()
	{
		$this->data['post_unread'] = $this->get_zd();

		$this->redirect(end($this->data['post_unread'])->hash);exit;
		
	}

	private function get_zd($force = false)
	{
		$post_unread = $this->load_zd($force);

		$this->load->model('scape_model');

		$scape = $this->scape_model->get()->result();

		foreach($scape as $row)
		{
			unset($post_unread[$row->hash]);
		}

		$this->session->set_userdata('post_unread', $post_unread);
		return $post_unread;
	}

	private function load_zd($force)
	{		
		$post_unread = $this->session->userdata('post_unread');

		if($force === false && $post_unread && count($post_unread) > 0) {
			return $post_unread;
		}

		$this->load->library('zenhack', array(
			'subdomain' => 'pagsegurodev',
			'log' => $this->input->get('log') == '0' ? false : true,
			'use_db' => $this->input->get('use_db') == '0' ? false : true, 
		));

		$this->zenhack->set_datetime_limit(date('Y-m-d H:i:s', strtotime("-7 days")));
		$this->zenhack->set_datetime_limit('2019-01-02T18:59:00');
		

		//you may set the user that should write the last comments
		$this->zenhack->filter_author('6018311238');
		$this->zenhack->filter_author('13468838768');
		$this->zenhack->filter_author('6018277318');      // Rafael
		$this->zenhack->filter_author('5989545817');      // Patricia
		$this->zenhack->filter_author('5979349268');      // Marcelo
		$this->zenhack->filter_author('114222767674');    // Flávio
		$this->zenhack->filter_author('114314801294');
		$this->zenhack->filter_author('115633083454');    // Felipe
		$this->zenhack->filter_author('367957870554');    // Sophia
		$this->zenhack->filter_author('364288446654');    // Natalia
		$this->zenhack->filter_author('364746378953');    // Rodrigo 
		$this->zenhack->filter_author('364250871353');    // Julian 
		$this->zenhack->filter_author('114515096233');    // Adalto
		$this->zenhack->filter_author('363353383573');    // Eduardo 

		//an your request should be like it.
		$post_unread = $this->zenhack->get_post_unread(array(
			'id',
			'title',
			'details',
			'comment_count',
		    'html_url',
		    'vote_sum',
		    'comments',
		    'created_at',
		    'updated_at',
		    'comments',
		), 100);

		foreach ($post_unread as $key => $row) {
		    $row->created_at = $this->convertTimezone($row->created_at);
		    $row->updated_at = $this->convertTimezone($row->updated_at);    
		    
		    $this->response_time($row);
			$row->br_updated_at = date('d-m-Y H:i:s', strtotime($row->updated_at));
			$row->hash = md5($row->id . $row->br_updated_at);
			$return[$row->hash] = $row;
		}
		return $return;
	}

	public function redirect($hash)
	{
		$post_unread = $this->get_zd();
		if(isset($post_unread[$hash])) {
			$this->scape_post($post_unread[$hash]);
			redirect($post_unread[$hash]->html_url);
		}
		redirect();
	}

	private function scape_post($post)
	{
		$this->load->model('scape_model');

		$this->scape_model->insert(array(
			'hash' => $post->hash,
			'dump' => base64_encode(serialize($post)),
		));
	}
	
	private function response_time($post) {
	    $date = NULL;
	    if(isset($post->comments)) {
	        if(count($post->comments) === 0) {
	            $date = $post->created_at;
	        }else{
	            $comment_created_at = null;
	            $comment_we = false;
	            
	            foreach ($post->comments as $comment) {
	                $comment->created_at = $this->convertTimezone($comment->created_at);
	                $comment->updated_at = $this->convertTimezone($comment->updated_at);
	                
	                if($comment->we) {
	                    $comment_we = true;
	                    break;
	                }
	                $comment_created_at = $comment->created_at;
	            }
	            
	            if($comment_we === true) {
	                $date = $comment_created_at;
	            }else{
	                $date = $post->created_at;
	            }
	        }
	    }
	    $post->initial_date = $date;
	    
	    $date_diff = new DateTime($date);
	    $date_diff = $date_diff->diff(new DateTime());
	    $hour = $date_diff->days * 24 * 60;
	    $hour += $date_diff->h * 60;
	    $post->response_time = $hour;
	    
	}
	
	private function convertTimezone($date = null)
	{   
	    $userTimezone = new DateTimeZone(date_default_timezone_get());
	    $gmtTimezone = new DateTimeZone('GMT');
	    
	    $myDateTime = new DateTime($date, $gmtTimezone);
	    
	    $offset = $userTimezone->getOffset($myDateTime);
	    $myInterval=DateInterval::createFromDateString((string)$offset . 'seconds');
	    $myDateTime->add($myInterval);
	    return $myDateTime->format('Y-m-d H:i:s');
	}
}
