<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function index()
	{
	    $this->load->model('scape_model');

	    $this_week_start = $this->find_monday()->format('Y-m-d 00:00:00');
	    $last_week_start = $this->find_monday(null, 1)->format('Y-m-d 23:59:59');

		$data = $this->scape_model->get(array(
            'date >=' => date('Y-m-01 00:00:00', strtotime("-1 months"))
		))->result();

		$this->data['lastmonth'] = new Zenpost();
		$this->data['thismonth'] = new Zenpost();
		$this->data['days7'] = new Zenpost();
		$this->data['thisweek'] = new Zenpost();
		$this->data['lastweek'] = new Zenpost();
		$this->data['yesterday'] = new Zenpost();
		$this->data['today'] = new Zenpost();

		foreach($data as $row) {		    
		    $row->post = base64_decode($row->dump);
		    $row->post = unserialize($row->post);
		    unset($row->dump);

            if(date('Y-m', strtotime("-1 months")) == date('Y-m', strtotime($row->date))) {
                $this->data['lastmonth']->count++;
                $this->data['lastmonth']->dump[] = $row;
            }
            if(date('Y-m') == date('Y-m', strtotime($row->date))) {
                $this->data['thismonth']->count++;
                $this->data['thismonth']->dump[] = $row;
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
            $this->data['today_diff'] = 0;
            $this->data['yesterday_diff'] = 0;
		    $this->data['today_diff_status'] = 'same';
            $this->data['yesterday_diff_status'] = 'same';
		    
		}elseif($this->data['today']->count == 0 || $this->data['yesterday']->count == 0) {
		    $this->data['today_diff'] = 0;
            $this->data['yesterday_diff'] = 0;
		    $this->data['today_diff_status'] = 'same';
            $this->data['yesterday_diff_status'] = 'same';
		    
		}else {
            $this->data['today_diff'] = ($this->data['today']->count - $this->data['yesterday']->count) / $this->data['yesterday']->count * 100;
            $this->data['today_diff'] = round($this->data['today_diff'], 2);

            $this->data['yesterday_diff'] = ($this->data['yesterday']->count - $this->data['today']->count) / $this->data['today']->count * 100;
            $this->data['yesterday_diff'] = round($this->data['yesterday_diff'], 2);
		    
		    if($this->data['today']->count > $this->data['yesterday']->count) {
                $this->data['today_diff_status'] = 'up';
                $this->data['yesterday_diff_status'] = 'down';
		    }else{
		        $this->data['today_diff_status'] = 'down';
                $this->data['yesterday_diff_status'] = 'up';
		    }
		}

        if($this->data['thisweek']->count == $this->data['lastweek']->count) {
            $this->data['thisweek_diff_status'] = 0;
            $this->data['lastweek_diff_status'] = 0;
            $this->data['thisweek_diff'] = 'same';
            $this->data['lastweek_diff'] = 'same';
        }elseif($this->data['thisweek']->count == 0 || $this->data['lastweek']->count == 0) {
            $this->data['thisweek_diff_status'] = 0;
            $this->data['lastweek_diff_status'] = 0;
            $this->data['thisweek_diff'] = 'same';
            $this->data['lastweek_diff'] = 'same';

        }else {
            $this->data['thisweek_diff'] = ($this->data['thisweek']->count - $this->data['lastweek']->count) / $this->data['lastweek']->count * 100;
            $this->data['thisweek_diff'] = round($this->data['thisweek_diff'], 2);

            $this->data['lastweek_diff'] = ($this->data['lastweek']->count - $this->data['thisweek']->count) / $this->data['thisweek']->count * 100;
            $this->data['lastweek_diff'] = round($this->data['lastweek_diff'], 2);

            if($this->data['thisweek']->count > $this->data['lastweek']->count) {
                $this->data['thisweek_diff_status'] = 'up';
                $this->data['lastweek_diff_status'] = 'down';
            }else{
                $this->data['thisweek_diff_status'] = 'down';
                $this->data['lastweek_diff_status'] = 'up';
            }
        }

        if($this->data['thismonth']->count == $this->data['lastmonth']->count) {
            $this->data['thismonth_diff_status'] = 0;
            $this->data['lastmonth_diff_status'] = 0;
            $this->data['thismonth_diff'] = 'same';
            $this->data['lastmonth_diff'] = 'same';
        }elseif($this->data['thismonth']->count == 0 || $this->data['lastmonth']->count == 0) {
            $this->data['thismonth_diff_status'] = 0;
            $this->data['lastmonth_diff_status'] = 0;
            $this->data['thismonth_diff'] = 'same';
            $this->data['lastmonth_diff'] = 'same';

        }else {
            $this->data['thismonth_diff'] = ($this->data['thismonth']->count - $this->data['lastmonth']->count) / $this->data['lastmonth']->count * 100;
            $this->data['thismonth_diff'] = round($this->data['thismonth_diff'], 2);

            $this->data['lastmonth_diff'] = ($this->data['lastmonth']->count - $this->data['thismonth']->count) / $this->data['thismonth']->count * 100;
            $this->data['lastmonth_diff'] = round($this->data['lastmonth_diff'], 2);

            if($this->data['thismonth']->count > $this->data['lastmonth']->count) {
                $this->data['thismonth_diff_status'] = 'up';
                $this->data['lastmonth_diff_status'] = 'down';
            }else{
                $this->data['thismonth_diff_status'] = 'down';
                $this->data['lastmonth_diff_status'] = 'up';
            }
        }
		
		$this->data['thismonth']->news_diff = $this->calc_news_diff($this->data['thismonth']->dump);
		
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
     * @param null $date
     * @param int $number
     * @return DateTime
     * @throws Exception
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