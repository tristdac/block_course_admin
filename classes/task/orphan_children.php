<?php

namespace block_course_admin\task;
 
function array_depth($array) {
    $max_indentation = 1;

    $array_str = print_r($array, true);
    $lines = explode("\n", $array_str);

    foreach ($lines as $line) {
        $indentation = (strlen($line) - strlen(ltrim($line))) / 4;

        if ($indentation > $max_indentation) {
            $max_indentation = $indentation;
        }
    }

    return ceil(($max_indentation - 1) / 2) + 1;
}

class orphan_children extends \core\task\scheduled_task {
 
    public function get_name() {
        return get_string('orphan_children', 'block_course_admin');
    }
 
    public function execute() {
        global $DB;
        
        $block_course_admin_config = get_config('block_course_admin');
        $debug = $block_course_admin_config->debug;

		$erroremail = '';
		
	    echo('Looking for orphans...');

		// get all parent-child relationships
		$metauserssql = "SELECT DISTINCT(l.username)
			FROM {block_course_admin_log} AS l
			INNER JOIN {block_course_admin_meta} AS m 
			ON l.course1 = m.parentid AND l.metachild = m.childid
			WHERE l.action = 'Add' 
			AND l.context = 'Meta'";
		$metausers = $DB->get_fieldset_sql($metauserssql);
		$enrol_collegedatabase_config = get_config('enrol_collegedatabase');
		$currentyearcatID = $DB->get_field('course_categories', 'id', array('name'=>$enrol_collegedatabase_config->newcourseyear));

		foreach ($metausers as $metauser) {
			$userhascontrolsql = "SELECT unitid FROM {enrol_collegedb_teachunits} WHERE userid = '".$metauser."'";
			$userhascontrol = $DB->get_fieldset_sql($userhascontrolsql);
			// echo 'User has control of: ';
			// print_r ($userhascontrol);
			// echo '<br>';
			$userhadcontrolsql = "SELECT DISTINCT(m.id), m.parentid, m.childid
				FROM {block_course_admin_log} AS l
				INNER JOIN {block_course_admin_meta} AS m 
				ON l.course1 = m.parentid AND l.metachild = m.childid
				WHERE l.action = 'Add' 
				AND l.context = 'Meta'
				AND l.username = '".$metauser."'";
			$usrhadcontrol = $DB->get_records_sql($userhadcontrolsql);
			$meta_par = array();
			$meta_child = array();
			foreach ($usrhadcontrol as $unit) {
				$meta_par[] = $unit->parentid;
				$meta_child[] = $unit->childid;
			}
			$userhadcontrol = array_unique(array_merge($meta_par,$meta_child));
			// echo 'User had control of: ';
			// print_r ($userhadcontrol);
			// echo '<br>';

			$userlostcontrol = array_diff($userhadcontrol,$userhascontrol);
			// echo 'User lost control of: ';
			// print_r($userlostcontrol);
			// echo '<br>';
			
			if ($userlostcontrol) {
				$orphan_row_ids = array();
				foreach ($userlostcontrol as $lostunits) {
					$orphan_row_ids_sql = "SELECT id FROM {block_course_admin_meta} WHERE parentid = '".$lostunits."' OR childid = '".$lostunits."'";
					$orphan_row_ids[] = $DB->get_fieldset_sql($orphan_row_ids_sql);
				}
				// print_r($orphan_row_ids);
				$newArr = array();
				// foreach ($orphan_row_ids as $a) {
				//     $newArr[] = $a['0'];
				//     echo $a;
				// }

				foreach($orphan_row_ids as $i) {
				    foreach($i as $key => $value){
				        $newArr[] = $value;
				        // echo $value.' / ';
				    }       
				}
				// print_r($newArr);
				$orphan_row_ids = array_unique($newArr);
				// echo 'orphan rows: ';
				// print_r($orphan_row_ids);
				
				if ($orphan_row_ids) {
					$records = array();
					foreach ($orphan_row_ids as $orphan_id) {
						$recordssql = "SELECT m.id as id, m.parentid as parentid, p.fullname as parentname, p.id as parentcid, m.childid as childid, c.fullname as childname, c.id as childcid
							FROM {block_course_admin_meta} as m
							JOIN {course} as p
							ON m.parentid = p.idnumber
							JOIN {course} as c
							ON m.childid = c.idnumber
							JOIN {course_categories} AS cats ON c.category = cats.id
							WHERE c.category=cats.id AND (
								cats.path LIKE '%/".$currentyearcatID."/%'
								OR cats.path LIKE '%/".$currentyearcatID."') 
								AND m.id = '".$orphan_id."'";
						$records[] = $DB->get_records_sql($recordssql);
					}
					
					// echo "FULL RECORD";
					// print_r($records);
					$depth = array_depth($records);
					// echo $depth;
					if ($depth > 2) {
						echo '<hr><br>User: '.$metauser.'<br>';
						foreach($records as $record) {
							if (is_array($record)){
								$metas_tbr = array();
						        //  Scan through inner loop
						        foreach ($record as $value) {
						            $metas_tbr[] = $value;
						            // if there is a common teacher to parent and child we ignore it and leave the metalink in place
						            $pa_teachers = $DB->get_fieldset_select('enrol_collegedb_teachunits', 'userid', "unitid = '".$value->parentid."'");
						            // echo'Parents: ';
						            // print_r($pa_teachers);
									$ch_teachers = $DB->get_fieldset_select('enrol_collegedb_teachunits', 'userid', "unitid = '".$value->childid."'");
									// echo'<br>Children: ';
									// print_r($ch_teachers);
									$leaveitalone = (!array_intersect($pa_teachers, $ch_teachers));
									if ($leaveitalone != 1) {
										echo '<span class="dimmed">Leave it alone. Common teacher exists.<br>Row '.$value->id.' : (P) '.$value->parentname.' - (C) '.$value->childname.'</span><br>';
									} else {
							            if ($value->id) {
							            	try {
												echo 'Orphans found on row '.$value->id.' : (P) '.$value->parentname.' - (C) '.$value->childname.'... removing link<br>';
												if ($debug != 1) { 
													$DB->delete_records('block_course_admin_meta', array('id'=>$value->id));
													$DB->insert_record('block_course_admin_log', array ('username'=>'System', 'userid'=>'1', 'action'=>'Remove - has been orphaned', 'context'=>'Meta', 'course1'=>$value->parentid, 'metachild'=>$value->childid, 'timestamp'=>date('Y-m-d H:i:s'))); // Log event
												}
											} catch (Exception $e) {
												$erroremail .= "error: exception when removing meta link $par $child -> " . $e->getMessage() . " \n ";
											} 	   
										} else {
											echo 'No orphans found<br>';
										}
									}
						        }
						    }
						}
					}
				}
			}
		}
	}
} 