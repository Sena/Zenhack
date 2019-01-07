<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->data['post_unread'] = $this->get_zd(true);

		$this->load->view('welcome_message', $this->data);
	}

	public function listing()
	{
		$this->data['post_unread'] = $this->get_zd(true);

		$this->load->view('list', $this->data);

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
			'subdomain' => 'support',
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
			'updated_at',
		), 100);


		foreach ($post_unread as $key => $row) {
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
}
