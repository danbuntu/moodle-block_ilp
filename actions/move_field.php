<?php

/**
 * Changes the position of a field in a report 
 *
 * @copyright &copy; 2011 University of London Computer Centre
 * @author http://www.ulcc.ac.uk, http://moodle.ulcc.ac.uk
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package ILP
 * @version 2.0
 */

require_once('../configpath.php');

global $USER, $CFG, $SESSION, $PARSER;

//include any neccessary files

// Meta includes
require_once($CFG->dirroot.'/blocks/ilp/admin_actions_includes.php');

//get the id of the course that is currently being used
//$course_id = $PARSER->required_param('course_id', PARAM_INT);

//the id of the report  that the field will be in 
$report_id = $PARSER->required_param('report_id', PARAM_INT);

//the id of the reportfield used when editing
$reportfield_id = $PARSER->required_param('reportfield_id' ,PARAM_INT);

//the id of the reportfield used when editing
$position = $PARSER->required_param('position' ,PARAM_INT);

//the id of the reportfield used when editing
$move = $PARSER->required_param('move' ,PARAM_INT);

// instantiate the db
$dbc = new ilp_db();

//change field position 

$reportfields 	= 	$dbc->get_report_fields_by_position($report_id,$position,$move);


$movesuc	=	true;

//loop through fields returned
if (!empty($reportfields)) {
	foreach($reportfields as $field) {
		
		if ($field->id != $reportfield_id) {
			
			
			//if the field is being moved up all other fields have postion value increased
			//if the field is being moved down all other fields have postion value decreased 
			//move up = 1 move down = 0
			$newposition = (empty($move)) ? $field->position-1 : $field->position+1;
		} else {
			//move the field 
			$newposition = (!empty($move)) ? $field->position- 1 : $field->position+1;
		}
		
		if (!$dbc->set_new_position($field->id,$newposition)) $movesuc = false;
	}
} else {
	$movesuc	=	false;
}

$resulttext = (!empty($movesuc)) ? get_string("fieldmovesuc", 'block_ilp') : get_string("fieldmoveerror", 'block_ilp');

//$return_url = $CFG->wwwroot.'/blocks/ilp/actions/edit_prompt.php?report_id='.$report_id.'&course_id='.$course_id;
$return_url = $CFG->wwwroot.'/blocks/ilp/actions/edit_prompt.php?report_id='.$report_id;
redirect($return_url, $resulttext, ILP_REDIRECT_DELAY);

?>
