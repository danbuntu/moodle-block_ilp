<?php

require_once($CFG->dirroot.'/blocks/ilp/classes/form_elements/ilp_element_plugin_mform.php');

class ilp_element_plugin_dd_mform  extends ilp_element_plugin_mform {
	
	public $tablename;
	
	function __construct($report_id,$plugin_id,$course_id,$creator_id,$reportfield_id=null) {
		parent::__construct($report_id,$plugin_id,$course_id,$creator_id,$reportfield_id=null);
		$this->tablename = "block_ilp_plu_dd";
	}
	  	
	
	  protected function specific_definition($mform) {
		
		/**
		textarea element to contain the options the manager wishes to add to the user form
		manager will be instructed to insert value/label pairs in the following plaintext format:
		value1:label1\nvalue2:label2\nvalue3:label3
		or some such
		*/

		$mform->addElement(
			'textarea',
			'optionlist',
			get_string( 'ilp_element_plugin_dd_optionlist', 'block_ilp' ),
			array('class' => 'form_input')
	        );

		//manager must specify at least 1 option, with at least 1 character
        	$mform->addRule('optionlist', null, 'minlength', 1, 'client');

		$typelist = array(
			OPTIONSINGLE => get_string( 'ilp_element_plugin_dd_single' , 'block_ilp' ),
			OPTIONMULTI => get_string( 'ilp_element_plugin_dd_multi' , 'block_ilp' )
		);
		$mform->addElement(
			'select',
			'selecttype',
			get_string( 'ilp_element_plugin_dd_typelabel' , 'block_ilp' ),
			$typelist,
			array('class' => 'form_input')
		);
	  }
	
	 protected function specific_validation($data) {
 	
	 	$data = (object) $data;
	 	return $this->errors;
	 }
	 
	 protected function specific_process_data($data) {
	  	
	 	$plgrec = (!empty($data->reportfield_id)) ? $this->dbc->get_plugin_record("block_ilp_plu_dd",$data->reportfield_id) : false;
	 	
	 	if (empty($plgrec)) {
	 		return $this->dbc->create_plugin_record($this->tablename,$data);
	 	} else {
	 		//get the old record from the elements plugins table 
	 		$oldrecord				=	$this->dbc->get_form_element_by_reportfield($this->tablename,$data->reportfield_id);
	
	 		//create a new object to hold the updated data
	 		$pluginrecord 			=	new stdClass();
	 		$pluginrecord->id		=	$oldrecord->id;
	 		$pluginrecord->optionlist	=	$data->optionlist;
			$pluginrecord->selecttype 	= 	$data->selecttype;
	 			
	 		//update the plugin with the new data
	 		return $this->dbc->update_plugin_record($this->tablename,$pluginrecord);
	 	}
	 }
	 
	 function definition_after_data() {
	 	
	 }
	
}
