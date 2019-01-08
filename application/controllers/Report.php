<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function index()
	{
	    $this->load->model('scape_model');
	    
	    $this_week_start = $this->find_monday()->format('Y-m-d 00:00:00');
	    $last_week_start = $this->find_monday(null, 1)->format('Y-m-d 23:59:59');

		$data = $this->scape_model->get(array(
			'date >=' => date('Y-m-d 00:00:00', strtotime("-30 days"))
		))->result();

		$this->data['days30'] = count($data);
		$this->data['month'] = 0;
		$this->data['days7'] = 0;
		$this->data['thisweek'] = 0;
		$this->data['lastweek'] = 0;
		$this->data['yesterday'] = 0;
		$this->data['today'] = 0;

		foreach($data as $row) {
			if(date('Y-m') == date('Y-m', strtotime($row->date))) {
			    $this->data['month']++;
			}
			if(date('Y-m-d 00:00:00', strtotime("-7 days")) <= $row->date) {
				$this->data['days7']++;
			}
			if($this_week_start <= $row->date) {
			    $this->data['thisweek']++;
			}elseif($last_week_start <= $row->date) {
			    $this->data['lastweek']++;
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
    
	/**
	 * 
	 * @param $date
	 * @param number $number
	 * @return DateTime
	 */
	private function find_monday($date = NULL, $number = 0)
	{
	    $date = new DateTime($date);

		while($date->format('w') != 1)
		{
			$date->modify('-1 day');
		}
		$date->modify('-' . $number . ' weeks');
		
		return $date;
	}
}
