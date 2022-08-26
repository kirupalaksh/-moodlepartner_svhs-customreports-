<?php
//moodleform is defined in formslib.php
require_once($CFG->libdir.'/formslib.php');

class block_addform extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;		

        $mlform = $this->_form; // Don't forget the underscore! $radioarray=array();
        $mlform->addElement('date_selector', 'fromdate', get_string('from'));
        $year = '2022';
		$month = '01';
		$day = '10';
		$defaulttime = make_timestamp($year, $month, $day);
		$mlform->setDefault('fromdate',  $defaulttime);	
		$mlform->addElement('date_selector', 'todate', get_string('to'));	
		$this->add_action_buttons($cancel = false, $submitlabel='Submit');
	}
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
