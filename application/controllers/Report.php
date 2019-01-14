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

		$this->data['days30'] = new Zenpost();
		$this->data['month'] = new Zenpost();
		$this->data['days7'] = new Zenpost();
		$this->data['thisweek'] = new Zenpost();
		$this->data['lastweek'] = new Zenpost();
		$this->data['yesterday'] = new Zenpost();
		$this->data['today'] = new Zenpost();
		$this->data['days30']->count = count($data);
		$this->data['days30']->dump = $data;

		foreach($data as $row) {		    
		    $row->post = base64_decode($row->dump);
		    $row->post = unserialize($row->post);
		    unset($row->dump);
		    
		    if(date('Y-m') == date('Y-m', strtotime($row->date))) {
		        $this->data['month']->count++;
		        $this->data['month']->dump[] = $row;
			}
			if(date('Y-m-d 00:00:00', strtotime("-7 days")) <= $row->date) {
			    $this->data['days7']->count++;
			    $this->data['days7']->dump[] = $row;
			}
			if($this_week_start <= $row->date) {
			    $this->data['thisweek']->count++;
			    $this->data['thisweek']->dump[] = $row;
			}elseif($last_week_start <= $row->date) {
			    $this->data['lastweek']->count++;
			    $this->data['lastweek']->dump[] = $row;
			}
			if(date('Y-m-d', strtotime("-1 days")) == date('Y-m-d', strtotime($row->date))) {
			    $this->data['yesterday']->count++;
			    $this->data['yesterday']->dump[] = $row;
			}
			if(date('Y-m-d') == date('Y-m-d', strtotime($row->date))) {
			    $this->data['today']->count++;
			    $this->data['today']->dump[] = $row;
			}
		}
		
		if($this->data['today']->count == $this->data['yesterday']->count) {
		    $this->data['today_diff'] = '0';
		    $this->data['today_diff_status'] = 'same';
		    
		}elseif($this->data['today']->count == 0 || $this->data['yesterday']->count == 0) {
		    $this->data['today_diff'] = '0';
		    $this->data['today_diff_status'] = 'same';
		    
		}else {
		    $this->data['today_diff'] = ($this->data['today']->count - $this->data['yesterday']->count) / $this->data['yesterday']->count * 100;	    
		    $this->data['today_diff'] = round($this->data['today_diff'], 2);
		    
		    if($this->data['today']->count > $this->data['yesterday']->count) {
		        $this->data['today_diff_status'] = 'up';
		    }else{
		        $this->data['today_diff_status'] = 'down';
		    }
		}
		
		if($this->data['thisweek']->count == $this->data['lastweek']->count) {
		    $this->data['thisweek_diff_status'] = '0';
		    $this->data['thisweek_diff'] = 'same';
		}elseif($this->data['thisweek']->count == 0 || $this->data['lastweek']->count == 0) {
		    $this->data['thisweek_diff'] = '0';
		    $this->data['thisweek_diff_status'] = 'same';
		    
		}else {
		    $this->data['thisweek_diff'] = ($this->data['thisweek']->count - $this->data['lastweek']->count) / $this->data['lastweek']->count * 100;
		    $this->data['thisweek_diff'] = round($this->data['thisweek_diff'], 2);
		    
		    if($this->data['thisweek']->count > $this->data['lastweek']->count) {
		        $this->data['thisweek_diff_status'] = 'up';
		    }else{
		        $this->data['thisweek_diff_status'] = 'down';
		    }
		}
		
		$this->data['days30']->news_diff = $this->calc_news_diff($this->data['days30']->dump);
		
		$this->load->view('report', $this->data);
	}
	
	private function calc_news_diff($dump) 
	{
	    $count = 0;
	    $total = 0;
	    
	    foreach($dump as $row) {
	        if(isset($row->post->response_time)) {
	            $count++;
	            $total += $row->post->response_time;
	        }
	    }
	    
	    return $count > 0 ? round($total / $count) : 0; 
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

class Zenpost
{
    public $count = 0;
    public $dump = array();
}