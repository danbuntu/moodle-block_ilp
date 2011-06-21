<?php 

/**
 * Saves a change in a users status to the database  
 *
 * @copyright &copy; 2011 University of London Computer Centre
 * @author http://www.ulcc.ac.uk, http://moodle.ulcc.ac.uk
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package ILP
 * @version 2.0
 */

require_once('../configpath.php');

global $USER, $CFG, $SESSION, $PARSER, $PAGE;

//include any neccessary files

// Meta includes
require_once($CFG->dirroot.'/blocks/ilp/actions_includes.php');

//get the id of the user that is currently being used
$student_id = $PARSER->required_param('student_id', PARAM_INT);

//get the id of the course that is currently being used
$course_id = $PARSER->optional_param('course_id', NULL, PARAM_INT);


//get the selectedtab param if it exists
$selecttedtab = $PARSER->optional_param('selectedtab', NULL, PARAM_RAW);

//get the selectedtab param if it exists
$tabitem = $PARSER->optional_param('tabitem', NULL, PARAM_RAW);



//get the changed status
$ajax		= $PARSER->required_param('ajax',PARAM_RAW);

//get the changed status
$status		= $PARSER->required_param('value',PARAM_RAW);



// instantiate the db
$dbc = new ilp_db();

//retreive the user record from the database
$student	=	$dbc->get_user_by_id($student_id);

if (empty($student)) {
	//trigger error
	
}

//
$stausitem	=		$dbc->get_user_status_items();



$statusrecord						= 	new stdClass();
$statusrecord->user_id				=	$student_id;
$statusrecord->user_modified_id		=	$USER->id;
$statusrecord->value				=	'';




if ($dbc->update_userstatus($statusrecord)) {
	
	if (empty($ajax)) {
		
		 $return_url = $CFG->wwwroot.'/blocks/ilp/actions/view_main.php?user_id='.$student_id.'&tabitem='.$tabitem.'&selectedtab='.$selecttedtab;
         redirect($return_url, get_string("stausupdated", 'block_ilp'), REDIRECT_DELAY); 
		
	} else {
		
		echo $statusitem->name;
		
	}
	
	
} else {
	
	//output an error 
}




?>