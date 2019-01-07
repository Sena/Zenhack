<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function index()
	{
		$this->load->model('scape_model');

		$data = $this->scape_model->get(array(
			'date >=' => date('Y-m-d', strtotime("-30 days")) . ' 00:00:00'
		))->result();

		$this->data['days30'] = count($data);
		$this->data['month'] = 0;
		$this->data['days7'] = 0;
		$this->data['yesterday'] = 0;
		$this->data['today'] = 0;

		foreach($data as $row) {
			
			if(date('Y-m') == date('Y-m', strtotime($row->date))) {
				$this->data['month']++;
			}
			if(date('Y-m-d', strtotime("-7 days")) . ' 00:00:00' <= $row->date) {
				$this->data['days7']++;
			}
			if(date('Y-m-d', strtotime("-1 days")) == date('Y-m-d', strtotime($row->date))) {
				$this->data['yesterday']++;
			}
			if(date('Y-m-d') == date('Y-m-d', strtotime($row->date))) {
				$this->data['today']++;
			}
		}
		$this->load->view('report', $this->data);
	}
}
