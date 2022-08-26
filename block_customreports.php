<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
* Block definition class for the block_customreports plugin.
* @package   block_customreports
* @copyright 2022, Kirupa Lakshmi <kirutry@egmail.com>
* @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_customreports extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_customreports');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array(
                'admin' => false,
                'site-index' => false,
                'course-view' => false,
                'mod' => false,
                'my' => true
         );
    }

    function specialization() {
        if (isset($this->config->title)) {
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('newhtmlblock', 'block_customreports');
        }
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_content() {
        global $CFG , $USER, $DB;

        require_once($CFG->libdir . '/filelib.php');
	require_once('addform.php');
	$webroot = $CFG->wwwroot;
	$mlform = new block_addform();

        /*if ($this->content !== NULL) {
            return $this->content;
        }*/

        $this->content = new stdClass;
       // $this->content->footer = '';
         if ($dateform = $mlform->get_data()) {  
			$sem_from 	=  $dateform->fromdate;
			$sem_to   	=  $dateform->todate;
			$fromdate = date('d-m-Y', $sem_from); 
			$fromday = date('d',$sem_from);
			$frommonth = date('m',$sem_from);
			$year = date('Y',$sem_from);
			

			$todate = date('d-m-Y', $sem_to); 

			if($frommonth <= '01') {
				$semester = "spring";
				$sem_name	= "Spring";
			}else if($frommonth <= '06') { 	
				$semester = "summer";
				$sem_name	= "Summer";	
			}else if($frommonth <= '08') { 	
				$semester = "Fall";
				$sem_name	= "Fall";	
			}	
			$from_date 	= '01-01-'.$year.' 00:00:00';
			$to_date 	= '14-06-'.$year.' 23:59:00';
			$sem_from 	=  strtotime($sem_from);
			$sem_name	= "Spring";
			$spring_from_period 	= '01-01-'.$year;
			$spring_from_dates 	= '01-01-'.$year.' 00:00:00';
			$spring_from_date = strtotime($spring_from_dates);		
			$spring_to_period 	= '14-06-'.$year;
			$spring_to_dates 	= '13-06-'.$year.' 23:59:00';
			$spring_to_date = strtotime($spring_to_dates);
			$summer_from_period 	= '14-06-'.$year;
			$summer_from_dates 	= '14-06-'.$year.' 00:00:00';
			$summer_from_date = strtotime($summer_from_dates);
			
			$summer_to_period 	= '14-08-'.$year;
			$summer_to_dates 	= '14-08-'.$year.' 23:59:00';
			$summer_to_date = strtotime($summer_to_dates);

			$fall_from_period 	= '15-08-'.$year;
			$fall_from_dates 	= '15-08-'.$year.' 00:00:00';
			$fall_from_date = strtotime($fall_from_dates);
			
			$fall_to_period 	    = '31-12-'.$year;
			$fall_to_dates 	    = '31-12-'.$year.' 23:59:00';
			$fall_to_date = strtotime($fall_to_dates); 
		
	
	$sql1="SELECT a.id,a.fullname,b.name FROM {course} a inner join {course_categories} b on a.category = b.id  order by a.fullname";
	$records1=$DB->get_records_sql($sql1);
	
	$completion_count_open=0;
	foreach($records1 as $key=>$datas){
		$item_type="Final Exam";
		$sql_grade = "SELECT u.firstname,u.lastname,b. * FROM {grade_items} a
								INNER JOIN {grade_grades} b ON b.itemid = a.id
								INNER JOIN {user} u ON b.userid = u.id
								WHERE a.itemname IN ('Final Quiz','Final Assignment ~ The Restaurant') AND a.courseid ='".$datas->id."' and 
								b.timemodified between '".$spring_from_date."' and '".$spring_to_date."'";		
									
		$records_grade=$DB->get_records_sql($sql_grade);
		$total_completion = count($records_grade);
		if(!$records_grade){
		}
		else{
			
			$completion_count_open=$completion_count_open+$total_completion;
		}
	}
	$spring_count_open = $completion_count_open;


	$sql1="SELECT a.id,a.fullname,b.name FROM {course} a inner join {course_categories} b on a.category = b.id  order by a.fullname";
	$records1=$DB->get_records_sql($sql1);
	
	
	$completion_count_open_summer=0;
	foreach($records1 as $key=>$datas){
		
		$item_type="Final Exam";
		$sql_grade = "SELECT u.firstname,u.lastname,b. * FROM {grade_items} a
								INNER JOIN {grade_grades} b ON b.itemid = a.id
								INNER JOIN {user} u ON b.userid = u.id
								WHERE  a.itemname IN ('Final Quiz','Final Assignment ~ The Restaurant') AND a.courseid ='".$datas->id."' and b.timemodified between '".$summer_from_date."' and '".$summer_to_date."'";
		$records_grade=$DB->get_records_sql($sql_grade);
		$total_completion = count($records_grade);
		
		if(!$records_grade){
		}
		else{
			
			$completion_count_open_summer=$completion_count_open_summer+$total_completion;
		}
	}
	$summer_count_open = $completion_count_open_summer;
	
		

	$sql1="SELECT a.id,a.fullname,b.name FROM {course} a inner join {course_categories} b on a.category = b.id  order by a.fullname";
		$records1=$DB->get_records_sql($sql1);
	
	
	$completion_count_open_fall=0;
	foreach($records1 as $key=>$datas){
		$item_type="Final Exam";
		 $sql_grade = "SELECT u.firstname,u.lastname,b. * FROM {grade_items} a
	INNER JOIN {grade_grades} b ON b.itemid = a.id
				INNER JOIN {user} u ON b.userid = u.id
								WHERE  a.itemname IN ('Final Quiz','Final Assignment','Final Assignment ~ The Restaurant') AND a.courseid ='".$datas->id."' and b.timemodified between '".$fall_from_date."' and '".$fall_to_date."'";
		$records_grade=$DB->get_records_sql($sql_grade);
		$total_completion = count($records_grade);
		
		if(!$records_grade){
		}
		else{
			
			$completion_count_open_fall=$completion_count_open_fall+$total_completion;
		}
	}
	
	$fall_count_open = $completion_count_open_fall;
	$spring_total = $spring_count_open +$spring_count_close+$spring_count_category_30;
	$summer_total = $summer_count_open +$summer_count_close+$summer_count_category_30;
	$fall_total = $fall_count_open +$fall_count_close+$fall_count_category_30;	
            // rewrite url
            $this->config->text = '';			
			$functionnasme = 'local_custom_service_get_grades_details';
			$restformat = 'json';
			$token = 'd4506d8f12098641e177b7ce72d016d2';
			
	
			 $mlform->get_data();
		
			$this->config->text .= '<div class="card custom_title" style="background-color:rgba(47, 93, 176, 0.16);">';
			$this->config->text .= '<p style="text-align:center;margin-top: 10px;">'.'Summary of the year - '. $year;
			$this->config->text .= '<p style="text-align:center;margin-top: 10px;">'. $fromdate.'&nbsp; to &nbsp;'.$todate.'</p>';
			$this->config->text .= '</div>';
					
			$this->config->text .=  '<table class="table table-hover" cellspacing="0" width="100%" style="background-color:#f8f8f8;">';
			$this->config->text .=  '<tr style="background-color:rgba(47, 93, 176, 0.16);"><th>Semester</th><th>Semester Dates</th><th>Course Completion</th></tr>';
	
	//spring 
	$this->config->text .=  '<tr><td>Spring&nbsp;'.$year.'</td>';
	$this->config->text .=  '<td>'.$spring_from_period.' to '.$spring_to_period.'</td>';  	  	
	$this->config->text .=  '<td>'.$spring_total.'</td></tr>';
	
	//Summer
	$this->config->text .=  '<tr><td>Summer&nbsp;'.$year.'</td>';
	$this->config->text .=  '<td>'.$summer_from_period.' to '.$summer_to_period.'</td>';  	
	$this->config->text .=  '<td>'.$summer_total.'</td></tr>';
	
	//Fall
	$this->config->text .=  '<tr><td>Fall&nbsp;'.$year.'</td>';
	$this->config->text .=  '<td>'.$fall_from_period.' to '.$fall_to_period.'</td>';  	
	$this->config->text .=  '<td>'.$fall_total.'</td></tr>';
	
	//Total
	$total = $spring_total+$summer_total+$fall_total;
	$this->config->text .=  '<tr style="background-color:rgba(47, 93, 176, 0.16);font-size: 22px;"><td colspan=2>Total</td>'; 	
	$this->config->text .=  '<td>'.$total.'</td></tr>';
	$this->config->text .=  "</table>";
            $format = FORMAT_HTML;
            // Check to see if the format has been properly set on the config
            if (isset($this->config->format)) {
                $format = $this->config->format;
            }
            $this->content->text = format_text($this->config->text, $format, $filteropt);
        } else {
			//displays the form
	if (has_capability('block/customreports:addinstance', $this->context)) {
		$this->content->text = $mlform->render();
	}        }

        unset($filteropt); // memory footprint

        //return $this->content;
    }
}
