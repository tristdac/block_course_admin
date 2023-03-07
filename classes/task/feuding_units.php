<?php

namespace block_course_admin\task;
 

class feuding_units extends \core\task\scheduled_task {
 
    public function get_name() {
        return get_string('feuding_units', 'block_course_admin');
    }
 
    public function execute() {
        global $DB;

        $block_course_admin_config = get_config('block_course_admin');
        $debug = $block_course_admin_config->debug;

		$erroremail = '';
		
	    $existingunits = $DB->get_records_select('block_course_admin_courses','courseid');
	    if($existingunits) {
		    foreach($existingunits as $unit) {
		    	$unitid = $unit->courseid;
		    	$unitshortname = $DB->get_field('enrol_collegedb_teachunits', 'unitshortname', array('unitid'=>$unitid), IGNORE_MULTIPLE);
				try {
					if ($DB->record_exists('block_course_admin_meta', array('childid'=>$unitid))) {
						if ($debug != 1) { 
							$DB->delete_records('block_course_admin_courses', array('courseid'=>$unitid));
							$DB->insert_record('block_course_admin_log', array ('username'=>'System', 'userid'=>'1', 'action'=>'Remove - is child', 'context'=>'Units', 'course1'=>$unitid, 'metachild'=>'', 'timestamp'=>date('Y-m-d H:i:s'))); // Log event
						}
						echo $unitshortname.' is child - removed from units table';
					}

				} catch (Exception $e) {
					$erroremail .= "error: exception when removing meta link $par $child -> " . $e->getMessage() . " \n ";
				}
			}
		} else {
				echo 'No feuding units found';
		}
	}
} 