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
 * Block "course_admin" - View page
 *
 * @package    block_courseadmin
 * @copyright  2017 Tristan daCosta <tristan.dacosta@edinburghcollege.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Include config.php
require_once('../../config.php');
require_login();

// Get plugin config
$block_course_admin_config = get_config('block_course_admin');
$enrol_collegedatabase_config = get_config('enrol_collegedatabase');

// Globals
global $DB, $CFG, $USER;

// Set page context
$PAGE->set_context(context_system::instance());
$syscontext = context_system::instance();

// Set Variables
$username = $USER->username;
$userid = $USER->id;
$source = $CFG->prefix.$block_course_admin_config->source;
$source_clean = $block_course_admin_config->source;
$user = $CFG->prefix.'user';
$teachunits = 'enrol_collegedb_teachunits';
$units = 'block_course_admin_courses';
$metalist = 'block_course_admin_meta';
$metalistSQL = $CFG->prefix.'block_course_admin_meta';
$enrol = 'block_course_admin_unenrol';
$enrolSQL = $CFG->prefix.'block_course_admin_unenrol';
$log = 'block_course_admin_log';
$role = $CFG->prefix.'role_assignments';
$context = $CFG->prefix.'context';
$coursenames = $CFG->prefix.'course';
$timestamp = date('Y-m-d H:i:s');
$courseyear = substr($enrol_collegedatabase_config->newcourseyear, 0, strpos($enrol_collegedatabase_config->newcourseyear, '/'));
$courses = 'course';
$success = "<div class='DBsuccess'><span style='font-size:20px;'>".get_string('success_title', 'block_course_admin')."</span><br>".get_string('success_desc', 'block_course_admin')."</div>";
$partialsuccess = "<div class='DBpartialsuccess'><span style='font-size:20px;'>".get_string('partialsuccess_title', 'block_course_admin')."</span><br>".get_string('partialsuccess_desc', 'block_course_admin')."</div>";
$failure = "<div class='DBfailure'><span style='font-size:20px;'>".get_string('failure_title', 'block_course_admin')."</span><br>".get_string('failure_desc', 'block_course_admin')."</div>";
$nothingtosave = "<div class='DBnothing'><span style='font-size:20px;'>".get_string('nothing_title', 'block_course_admin')."</span><br>".get_string('nothing_desc', 'block_course_admin')."</div>";

// Set page layout
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('pluginname', 'block_course_admin'));
$PAGE->set_heading(get_string('pluginname', 'block_course_admin'));
$PAGE->set_url($CFG->wwwroot.'/blocks/course_admin/index.php');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/css/jquery.dataTables.min.css" />
<?php
// Print Header
echo $OUTPUT->header();	
?>

<!-- load scripts for modal vimeo auto play/pause -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> -->
<script src="https://player.vimeo.com/api/player.js"></script>

<!-- <script>
	$.holdReady( true );
	$.getScript( "https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js", function() {
		console.log('DataTables initialised... continuing');
	$.holdReady( false );
	});
</script> -->
<!-- <h1><?php echo get_string('page_title', 'block_course_admin') ?></h1> -->
<div id="loadingDiv"><div class="sk-circle"><div class="sk-circle1 sk-child"></div><div class="sk-circle2 sk-child"></div><div class="sk-circle3 sk-child"></div><div class="sk-circle4 sk-child"></div><div class="sk-circle5 sk-child"></div><div class="sk-circle6 sk-child"></div><div class="sk-circle7 sk-child"></div><div class="sk-circle8 sk-child"></div><div class="sk-circle9 sk-child"></div><div class="sk-circle10 sk-child"></div><div class="sk-circle11 sk-child"></div><div class="sk-circle12 sk-child"></div></div><span style="text-align:center;font-size:large;display:block;">Working... please wait</span></div>

<div class="ca_container">

	<script>
		$('.ca_container').css('display','none');
		// $( document ).ready(function() { 
		//   	removeLoader();
		// });
		jQuery(document).ready(function() {
		    setTimeout(function() {
		         removeLoader();
		    }, 2000);
		});
		function removeLoader(){
		    $( "#loadingDiv" ).fadeOut(500, function() {
		        // fadeOut complete. Remove the loading div
		        $( "#loadingDiv" ).remove();
		        $('.ca_container').css('display','block');
		        console.log('Page loaded... displaying content');
		        // show the confirmation modals once page is loaded, if required
			    $('#unitConf').modal('show');
				$('#metaConf').modal('show');
				$('#enrolConf').modal('show');
				$('#delConf').modal('show');
		  	});  
		};
	</script>



	<?php

	// If admin, use Site Administrator Interface for manual insertions/deletions
	if ( (is_siteadmin() || has_capability('block/course_admin:useasadmin', $syscontext)) && (!strstr($USER->username, 'ec1') && !strstr($USER->username, 'ec2')) && !(\core\session\manager::is_loggedinas()) )  {
		
		$table = '';
		$rowid = array();
		$result = '';
		if ((isset($_POST["removetable"])) && (isset($_POST["rowid"])))  {
			$table = $_POST['removetable'];
			$tablename = $table;
			if ($table == $units) {
				$rowids = '';
				$message = 'You are about to remove the following units from the creation table:<p><ul>';
				foreach($_POST['rowid'] as $row) {
					$rownum = $row;
					$fullrow = $DB->get_record($units, array('id'=>$rownum),$fields='*', $strictness=MUST_EXIST);
					if ($DB->record_exists($courses, array('idnumber'=>$fullrow->courseid))) {
						$course1name = $DB->get_record($courses, array('idnumber'=>$fullrow->courseid),$fields='fullname', $strictness=IGNORE_MISSING);
						$coursename = $course1name->fullname;
					} else {
						$course1name = $DB->get_record($source_clean, array('unitid'=>$fullrow->courseid), 'unitfullname', IGNORE_MULTIPLE);
						$coursename = $course1name->unitfullname;
					}
					$submit = '<input type="hidden" name="remTableName" value="Units">
					<input type="hidden" name="remId[]" value="'.$rownum.'">';
					$message .= '<li><i class="fa fa-minus-square-o" aria-hidden="true" style="color:red;"></i> ';
					if($coursename) {
						$message .= $coursename;
					} else {
						$message .= $fullrow->courseid;
					}
					$message .= '</li>';
					$rowids .= $rownum.',';
				}
			}
			if ($table == $enrol) {
				$rowids = '';
				$message = 'You are about to remove the following unenrolments entries. This means the users will become enrolled again:<p><ul>';
				foreach($_POST['rowid'] as $row) {
					$rownum = $row;
					$fullrow = $DB->get_record($enrol, array('id'=>$rownum),$fields='*', $strictness=MUST_EXIST);
					$course1name = $DB->get_record($courses, array('idnumber'=>$fullrow->courseid),$fields='fullname', $strictness=IGNORE_MISSING);
					$submit = '<input type="hidden" name="remTableName" value="Enrol">
					<input type="hidden" name="remId[]" value="'.$rownum.'">';
					$message .= '<li><i class="fa fa-minus-square-o" aria-hidden="true" style="color:red;"></i> ';
					$message .= $fullrow->userid.' - ';
					if($course1name->fullname) {
						$message .= $course1name->fullname;
					} else {
						$message .= $fullrow->courseid;
					}
					$message .= '</li>';
					$rowids .= $rownum.',';
				}
			}
			if ($table == $metalist) {
				$rowids = '';
				$message = 'You are about to break the relationship between the following units:<p><ul>';
				foreach($_POST['rowid'] as $row) {
					$rownum = $row;
					$fullrow = $DB->get_record($metalist, array('id'=>$rownum),$fields='*', $strictness=MUST_EXIST);
					$course1name = $DB->get_record($courses, array('idnumber'=>$fullrow->parentid),$fields='fullname', $strictness=IGNORE_MISSING);
					$course2name = $DB->get_record($courses, array('idnumber'=>$fullrow->childid),$fields='fullname', $strictness=IGNORE_MISSING);
					$submit = '<input type="hidden" name="remTableName" value="Meta">
					<input type="hidden" name="remId[]" value="'.$rownum.'">';
					$message .= '<li>';
					if($course1name->fullname) {
						$message .= $course1name->fullname;
					} else {
						$message .= $fullrow->parentid;
					}
					$message .= ' <i class="fa fa-times-circle-o" aria-hidden="true" style="color:red;"></i> ';
					if($course2name->fullname) {
						$message .= $course2name->fullname;
					} else {
						$message .= $fullrow->childid;
					}
					$message .= '</li>';
					$rowids .= $rownum.',';
				}
			}
			$rowids = rtrim($rowids,",");
			$message .= '</ul></p>'; ?>

			<div id="delConf" class="modal bd-example-modal-lg hide fade confirm in" data-backdrop="static" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close cancel" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><?php echo get_string('confirm', 'block_course_admin'); ?></h4>
						</div>
						<div class="modal-body">

							<?php echo $message; ?>

						</div>
					  <div class="modal-footer">
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
							<input type="hidden" name="remRow" value="<?php echo $rowids; ?>">
							<input type="hidden" name="remTable" value="<?php echo $table; ?>">
							<input type="hidden" name="remTableName" value="<?php echo $tablename; ?>">
							<?php echo $submit; ?>
							<input type="submit" class="btn btn-warning" value="Confirm">
						</form>
						<button data-dismiss="modal" class="btn btn-primary cancel"><?php echo get_string('cancel', 'block_course_admin'); ?></button>
					  </div>
					</div>

				</div>
			</div>		
		<?php
		}
		// DELETE ENTRIES FROM TABLES
		if ((isset($_POST["remRow"])) && (isset($_POST["remTable"]))) {
			$remRows = explode(',',$_POST["remRow"]);
			$table = $_POST["remTable"];
			$tablename = $_POST["remTableName"];
			foreach ($remRows as $row) {
				if ($table == $units) {
					$course = $DB->get_record($units, array('id'=>$row),$fields='*', $strictness=MUST_EXIST);
					$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Delete Entry', 'context'=>$tablename, 'course1'=>$course->courseid, 'metachild'=>'', 'timestamp'=>$timestamp));
				}
				if ($table == $enrol) {
					$course = $DB->get_record($enrol, array('id'=>$row),$fields='*', $strictness=MUST_EXIST);
					$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Delete Entry', 'context'=>$tablename.' - '.$course->userid, 'course1'=>$course->courseid, 'metachild'=>'', 'timestamp'=>$timestamp));
				}
				if ($table == $metalist) {
					$course = $DB->get_record($metalist, array('id'=>$row),$fields='*', $strictness=MUST_EXIST);
					$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Delete Entry', 'context'=>$tablename, 'course1'=>$course->parentid, 'metachild'=>$course->childid, 'timestamp'=>$timestamp));
				}
				$DB->delete_records($table,array('id'=>$row));
			}
			$result = $success;
			$result .= '<pre class="success">Success: Removed '.$tablename.' Entry (ID='.$_POST["remRow"].')</h3>';
			$result .= '<script>history.pushState({}, "", "")</script>';
		}

		if (isset($_POST["addtable"])) {
			$tablename = $_POST['addtable'];
			

			if (($tablename == $enrol) && (isset($_POST["usersid"])) && (isset($_POST["courseid"]))) {
				$usersid = $_POST['usersid'];
				$courseid = $_POST['courseid'];
				if (strlen($usersid)>1) {
					if ($courseid > 732000000000000) {
						$DB->insert_record($tablename, array ('userid'=>$usersid, 'courseid'=>$courseid));
						$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Insert Entry', 'context'=>'Enrolment', 'course1'=>$courseid, 'metachild'=>'', 'timestamp'=>$timestamp));
						$result = $success;
						$result .= '<pre class="success">Success</h3> '.$tablename.' - '.$usersid.'->'.$courseid;
					} else {
						$result = "<pre class='failed'>Failed: Please enter valid course ID (732)";
					}
				}
				else {
					$result = "<pre class='failed'>Failed: Action must include username";
				}
				$result .= '<script>history.pushState({}, "", "")</script>';
			}
			if (($tablename == $units) && (isset($_POST["courseid"]))) {
				$courseid = $_POST['courseid'];
				if ($courseid > 732000000000000) {
					$DB->insert_record($tablename, array ('courseid'=>$courseid));
					$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Insert Entry', 'context'=>'Units', 'course1'=>$courseid, 'metachild'=>'', 'timestamp'=>$timestamp));
					$result = $success;
					$result .= '<pre class="success">Success</h3> '.$tablename.' - '.$courseid;
				}
				else {
					$result = "<pre class='failed'>Failed: Please enter valid course ID (732)";
				}
				$result .= '<script>history.pushState({}, "", "")</script>';
			}
			if (($tablename == $metalist) && (isset($_POST["courseid"])) && (isset($_POST["childid"]))) {
				$courseid = $_POST['courseid'];
				$s_count = 0;
				$f_count = 0;
				if (($courseid > 732000000000000) && ($courseid < 732999999999999)) {
					$childids = explode(",",str_replace(' ', '', $_POST['childid']));
					foreach ($childids as $childid) {				
						if (($childid > 732000000000000) && ($childid < 732999999999999)) {
							$DB->insert_record($tablename, array ('parentid'=>$courseid, 'childid'=>$childid));
							$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Insert Entry', 'context'=>'Meta', 'course1'=>$courseid, 'metachild'=>$childid, 'timestamp'=>$timestamp));
							$result .= '<pre class="success">Success: Add</h3> '.$courseid.' <- '.$childid;
							$s_count++;
						}
						else {
							$result .= "<pre class='failed'>Failed: Child ID (732) is not valid: ".$childid;
							$f_count++;
						}
						$result .= "</pre>";
					}
					if (($s_count >= 1) && ($f_count = 0)) {
						$result .= $success;
					} else if (($s_count >= 1) && ($f_count >=1)) {
						$result .= $partialsuccess;
					} else if (($s_count = 0) && ($f_count >=1)) {
						$result .= $failure;
					} else if (($s_count = 0) && ($f_count = 0)) {
						$result .= $nothingtosave;
					}
				} else {
					$result .= "<pre class='failed'>Failed: Parent ID is not valid: ".$courseid;
				}
				$result .= '<script>history.pushState({}, "", "")</script>';
			}
		}

		if (!isset($_POST["removetable"]))  {
			$existing_metas = $DB->get_records('block_course_admin_meta');
			$existing_units = $DB->get_records('block_course_admin_courses');
			$existing_unenrols = $DB->get_records('block_course_admin_unenrol');
			?>
			
			<div id="admin-tabs" class="container">
				<div class="nav nav-tabs" role="tablist">
						<a class="nav-item nav-link active" href="#rem" data-toggle="tab" role="tab" aria-selected="true">Remove Entry</a>
						<a class="nav-item nav-link" href="#insert" data-toggle="tab" role="tab" aria-selected="false">Insert Entry</a>
				</div>
				<div class="result">
					<?php if ($result) {
						echo $result;
					} ?>
				</div>
				<div class="tab-content mt-3">
					<div class="tab-pane fade" id="insert" role="tabpanel">
			          	<h3>Insert Entries</h3>
			          	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				          	<table>
				          	<tr><td>
				          	Table :
				          	</td>
				          	<td>
				          	<select name="addtable">
								<option value="block_course_admin_unenrol">Enrolment</option>
								<option value="block_course_admin_courses">Units</option>
								<option value="block_course_admin_meta">Meta</option>
							</select>
							</td></tr>
							<tr><td>
							Course ID #: 
							</td>
							<td><input type="text" name="courseid">
							</td></tr>
							<td>
							Username (if Enrolment): 
							</td><td>
							<input type="text" name="usersid">
							</td></tr>
							<tr><td>
							Child ID # (if Meta):<br><small>Use comma seperated lists to add multiple children</small> 
							</td><td><input type="text" name="childid">
							</td></tr>
							</table>
							<br><input type="submit" class="btn btn-success" value="Add Entry">
						</form>
						<hr>
						<div class="result">
						<?php if ($result) {
							echo $result;
							} ?>
						</div>
					</div>

					<div class="tab-pane fade show active" id="rem" role="tabpanel">
			        	<h3>Remove Entries</h3>

			        	<ul class="nav nav-pills mb-3" role="tablist">
							<li class="nav-item col-4">
								<a class="nav-link active" href="#rem_unenrol" data-toggle="pill" role="tab" aria-selected="true">Enrolment</a>
							</li>
							<li class="nav-item col-4">
								<a  class="nav-link"href="#rem_unit" data-toggle="pill" role="tab" aria-selected="false">Units</a>
							</li>
							<li class="nav-item col-4">
								<a  class="nav-link"href="#rem_meta" data-toggle="pill" role="tab" aria-selected="false">Meta Links</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane fade" id="rem_meta" role="tabpanel" aria-selected="false">
								<div class="tablecont">
									<h2>Meta Links</h2>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
										<table class="table" id="meta">
											<input type="hidden" id="metaID" name="removetable" value="block_course_admin_meta">
											<thead>
											<tr>
												<th></th>
												<th>Parent</th>
												<th>Child</th>
											</tr>
										</thead>
										<tbody id="metatable">
										<?php
										$child = '';
										foreach ($existing_metas as $meta) {
											$parent = $DB->get_record('course',array('idnumber' => $meta->parentid), 'fullname', IGNORE_MISSING);
											if ($parent) {
												$meta->parent = $parent->fullname;
											}
											if (!$parent) {
												$metalookup = $DB->get_record($source_clean, array('unitid'=>$meta->parentid), 'unitfullname', IGNORE_MULTIPLE);
												if(isset($metalookup->unitfullname)) {
													$meta->parent = $metalookup->unitfullname;
												}
											}
											if (!$meta->parent) {
												$meta->parent = 'Course not present in course or enrolment table ('.$meta->parentid.')';
											}
											$child = $DB->get_record('course',array('idnumber' => $meta->childid), 'fullname', IGNORE_MISSING);
											if ($child) {
												$meta->child = $child->fullname;
											} else {
												$meta->child = 'Course not present in course or enrolment table ('.$meta->childid.')';
											}
											if (!$child) {
												$metalookup = $DB->get_record($source_clean, array('unitid'=>$meta->childid), 'unitfullname', IGNORE_MULTIPLE);
												if(isset($metalookup->unitfullname)) {	
													$meta->child = $metalookup->unitfullname;
												}
											}
											// if (!$meta->child) {
											// 	$meta->child = 'Course not present in course or enrolment table ('.$meta->childid.')';
											// }
											echo '<tr><td><input class="meta checkbox" type="checkbox" name="rowid[]" id="check' . $meta->id . '" value="' . $meta->id . '"/></td><td>'.$meta->parent.'</td><td>'.$meta->child.'</td></tr>';
										}
										?>
										</tbody>
										</table>
										<input class="submit btn btn-warning" type="submit" value="Remove Entries">
										<!-- <script>
											$('#admin-tabs table#meta').DataTable();
										</script> -->
									</form>
								</div>
							</div>

							<div class="tab-pane fade" id="rem_unit" role="tabpanel" aria-selected="false">
								<div class="tablecont">
									<h2>Created Units</h2>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
										<table class="table" id="unit">
											<input type="hidden" id="unitID" name="removetable" value="block_course_admin_courses">
											<thead>
											<tr>
												<th></th>
												<th>Unit</th>
											</tr>
										</thead>
										<tbody id="unittable">
										<?php
										foreach ($existing_units as $unit) {
											$course = $DB->get_record('course',array('idnumber' => $unit->courseid), 'fullname', IGNORE_MISSING);
											if ($course) {
												$unit->unit = $course->fullname;
											} else if (!$course) {
												$unitlookup = $DB->get_record($source_clean, array('unitid'=>$unit->courseid), 'unitfullname', IGNORE_MULTIPLE);
												if(isset($unitlookup->unitfullname)) {
													$unit->unit = $unitlookup->unitfullname;
												} else {
													$unit->unit = 'Course not present in course or enrolment table ('.$unit->courseid.')';
												}
											}
											// if (!$unit->unit) {
											// 	$unit->unit = 'Course not present in course or enrolment table ('.$unit->courseid.')';
											// }

											echo '<tr><td><input class="unit checkbox" type="checkbox" name="rowid[]" id="check' . $unit->id . '" value="' . $unit->id . '"/></td><td>'.$unit->unit.'</td></tr>';
										}
										?>
										</tbody>
										</table>
										<input class="submit btn btn-warning" type="submit" value="Remove Entries">
										<!-- <script>
											$('#admin-tabs table#unit').DataTable();
										</script> -->
									</form>
								</div>
							</div>

							<div class="tab-pane fade show active" id="rem_unenrol" role="tabpanel" aria-selected="true">
								<div class="tablecont">
									<h2>Unenrolments</h2>
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
										<table class="table" id="enrol">
											<input type="hidden" id="enrolID" name="removetable" value="block_course_admin_unenrol">
											<thead>
											<tr>
												<th></th>
												<th>User</th>
												<th>Course</th>
											</tr>
										</thead>
										<tbody id="enroltable">
										<?php
										foreach ($existing_unenrols as $enrol) {
											$course = $DB->get_record('course',array('idnumber' => $enrol->courseid), 'fullname', IGNORE_MISSING);
											if ($course) {
												$enrol->course = $course->fullname;
											} else {
												$enrollookup = $DB->get_record($source_clean, array('unitid'=>$enrol->courseid), 'unitfullname', IGNORE_MULTIPLE);
												if ($enrollookup) {
													$enrol->course = $enrollookup->courseid;
												} else {
													$enrol->course = 'Course not present in course or enrolment table';
												}
											}
											$user = $DB->get_record('user',array('username'=>$enrol->userid), $fields='*', $strictness=IGNORE_MULTIPLE);
											if ($user) {
												$user = $user->firstname.' '.$user->lastname;
											} else {
												$user = 'Unknown User ('.$enrol->userid.')';
											}
											echo '<tr><td><input class="enrol checkbox" type="checkbox" name="rowid[]" id="check' . $enrol->id . '" value="' . $enrol->id . '"/></td><td>'.$user.'<td>'.$enrol->course.'</td></tr>';
										}
										?>
										</tbody>
										</table>
										<input class="submit btn btn-warning" type="submit" value="Remove Entries">
										<!-- <script>
											$('#admin-tabs table#enrol').DataTable();
										</script> -->
									</form>
								</div>
							</div>
						</div>
					</div>
			  	</div>
			</div>
			<?php
		}
	} else { // Everyone else
		// Don't let students or external users in
		if (((!strstr($USER->username, 'ec1')) && (!strstr($USER->username, 'ec2'))) && (strstr($USER->email, '@edinburghcollege.ac.uk'))) {
			// OK, user is a staff member, do they have any enrolments?
			if ($DB->record_exists('enrol_collegedb_teachunits', array('userid'=>$USER->username), IGNORE_MULTIPLE)) {
			$needsconfirmation = 0;

			// Unit Creator Confirmation Modal
			if (isset($_POST["created"])) {
				$needsconfirmation = 1;
				?>
				<!-- Unit Creator -->
				<div id="unitConf" class="modal hide fade confirm" data-backdrop="static" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<!-- Modal content-->
						<div class="modal-content">
						  	<div class="modal-header">
							<button type="button" class="close cancel" data-dismiss="modal">×</button>
							<h4 class="modal-title"><?php echo get_string('confirm', 'block_course_admin'); ?></h4>
						  	</div>
						  	<div class="modal-body">
						  	<?php 
							if (count($_POST) > 0) {
								$created = $_POST['created'];
								array_map('intval', $created);
								$created = $_POST['created'];
								$create_param = implode(',',$created);
								if ($created) { 
									echo get_string('unitselector_desc', 'block_course_admin'); 
									echo get_string('units', 'block_course_admin'); 
									?>
								   <ul>
									<?php
										foreach ($created as $create){
											$row = $DB->get_record($source_clean, array('unitid'=>$create), 'unitfullname,unitid', IGNORE_MULTIPLE);
											echo '<li><i class="fa fa-plus-square-o" aria-hidden="true" style="color:green;"></i> '.$row->unitfullname.'</li>';
										} 
									   ?>
									</ul>
								<?php
								}
							}
							if (!$created) {
								echo get_string('nochanges', 'block_course_admin'); ?>
								<script>    
									if(typeof window.history.pushState == 'function') {
										window.history.pushState({}, "Hide", "../course_admin/index.php");
									}
									var current_tab = '#course-btn';
								</script> 
								<?php
							} ?>
							</div> 
							<div class="modal-footer">
							<?php
							if ($created) {
							  echo '<a class="btn btn-success" href="?createin=' . $create_param . '">'.get_string('confirm', 'block_course_admin').'</a>';
							} ?>
							<button type="button" class="btn btn-primary cancel" data-dismiss="modal"><?php echo get_string('cancel', 'block_course_admin'); ?></button>
							</div>
						</div>
					</div>
				</div>		
			<?php 
			}

			// Unit Link (meta) Confirmation Modal
			if (isset($_POST["meta"])) {
				$needsconfirmation =1;
				if (count($_POST) > 0) {
					$meta = $_POST['meta'];
					array_map('intval', $meta);
					$meta = $_POST['meta'];
					?>
					<!-- Meta Link Creator -->
					<div id="metaConf" class="modal hide fade confirm" data-backdrop="static" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<!-- Modal content-->
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close cancel" data-dismiss="modal">&times;</button>
								<h4 class="modal-title"><?php echo get_string('confirm', 'block_course_admin'); ?></h4>
							  </div>
							  <div class="modal-body">
								<?php
								$updatemeta = new stdClass();
								if ($meta) {
									$metain_raw[] = array();
									$metain = array();
									$metaout_raw = array();
									$metaout = array();
									foreach ($meta as $metaids){
										$ids = explode(":", $metaids);
										$updatemeta-> parentid = $ids[1];
										$updatemeta-> childid = $ids[2];
										$select = "parentid = $ids[1] AND childid = $ids[2]";
										if (($ids[0] === 'out') && ($DB->record_exists($metalist, array('parentid'=>$ids[1], 'childid'=>$ids[2])))) {
											$metaout_raw[] = $ids[1].':'.$ids[2];
										}
										if ($ids[0] === 'in') {
											$metain_raw[] = $ids[1].':'.$ids[2];
											if (!$DB->record_exists($metalist, array('parentid'=>$ids[1], 'childid'=>$ids[2]))) {
												$metain[] = $ids[1].':'.$ids[2];
											}
										}
									}
									foreach ($metaout_raw as $mor) {
										if (!in_array($mor, $metain_raw)) {
											$metaout[] = $mor;
										}
									}
									$metain_string = implode(',', $metain);
									$metaout_string = implode(',', $metaout);
									if (($metain) || ($metaout)) {
										echo get_string('checkcodes', 'block_course_admin');
									}
									if ($metain){
										echo get_string('addedlinks', 'block_course_admin');
										foreach ($metain as $in) {
											list($parent, $child) = explode(":", $in);
											$parentname = $DB->get_record($source_clean, array('unitid'=>$parent), 'unitfullname', IGNORE_MULTIPLE);
											$childname = $DB->get_record($source_clean, array('unitid'=>$child), 'unitfullname', IGNORE_MULTIPLE);
											echo $childname->unitfullname.' <i class="fa fa-arrow-right" style="color:green;"></i> '.$parentname->unitfullname.'<br>';
										}
									}
									if ($metaout){
										echo get_string('removedlinks', 'block_course_admin');
										foreach ($metaout as $out) {
											list($parent, $child) = explode(":", $out);
											$parentname = $DB->get_record($source_clean, array('unitid'=>$parent), 'unitfullname', IGNORE_MULTIPLE);
											$childname = $DB->get_record($source_clean, array('unitid'=>$child), 'unitfullname', IGNORE_MULTIPLE);
											echo $childname->unitfullname.' <i class="fa fa-arrow-left" style="color:red;"></i><i class="fa fa-arrow-right" style="color:red;"></i> '.$parentname->unitfullname.'</strong><br>';
										}
									}
									if ((!$metain) && (!$metaout)) {
										echo get_string('nochanges', 'block_course_admin'); ?>
										<script>    
											if(typeof window.history.pushState == 'function') {
												window.history.pushState({}, "Hide", "../course_admin/index.php");
											}
											var current_tab = '#meta-btn';
										</script> 
										<?php
									}
								}
								?>
								</ul>
							  </div>
							  <div class="modal-footer">
								<?php 
								if (($metain) || ($metaout)) {
									echo '<a class="btn btn-success" href="?metain=' . $metain_string . '&metaout='. $metaout_string.'">Confirm</a>'; 
								}
								?>
								<button type="button" class="btn btn-primary cancel" data-dismiss="modal"><?php echo get_string('cancel', 'block_course_admin'); ?></button>
							  </div>
							</div>

						</div>
					</div>		
					<?php
				}
			}

			// Enrolment Confirmation Modal
			$updatedunenrol = FALSE;
			if ((isset($_POST["unenrol"])) || (isset($_POST["unenrolu"]))) { 
				$needsconfirmation = 1; ?>
				<!-- Unit Creator -->
				<div id="enrolConf" class="modal hide fade confirm" data-backdrop="static" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close cancel" data-dismiss="modal">&times;</button>
								<h4 class="modal-title"><?php echo get_string('confirm', 'block_course_admin'); ?></h4>
							</div>
							<div class="modal-body">
								<ul>
									<?php
									if (isset($_POST["unenrol"])) {
										// Top Level Courses
										$unenrol = $_POST['unenrol'];
										array_map('intval', $unenrol);
										$unenrol = $_POST['unenrol'];
										$enrolinc = array();
										$enroloutc = array();
										$enrolinc_raw = array();
										$enroloutc_raw = array();
										$enrolinc_exists = array();
										$enroloutc_exists = array();
										foreach ($unenrol as $unenrolrows){
											$row = explode(":", $unenrolrows);
											if (($row[0] === 'out') ) {
												$enroloutc_raw[] = $row[1].':'.$row[2];
												if ($DB->record_exists($enrol, array('userid'=>$row[1],'courseid'=>$row[2]))) {
													$enroloutc_exists[] = $row[1].':'.$row[2];
												}
											}
											if ($row[0] === 'in') {
												$enrolinc_raw[] = $row[1].':'.$row[2];
												if ($DB->record_exists($enrol, array('userid'=>$row[1],'courseid'=>$row[2]))) {
													$enrolinc_exists[] = $row[1].':'.$row[2];
												}
											}
										}
									}
									else {
										$enroloutc_raw = array('','');
										$enrolinc_raw = array('','');
										$enrolinc_exists = array('','');
										$enroloutc_exists = array('','');
									}
									
									// Units
									if (isset($_POST["unenrolu"])) {
										$unenrolu = $_POST['unenrolu'];
										array_map('intval', $unenrolu);
										$unenrolu = $_POST['unenrolu'];
										$enrolinu = array();
										$enroloutu = array();
										$enrolinu_raw = array();
										$enroloutu_raw = array();
										$enrolinu_exists = array();
										$enroloutu_exists = array();
										foreach ($unenrolu as $unenrolurows){
											$row = explode(":", $unenrolurows);
											if (($row[0] === 'out') ) {
												$enroloutu_raw[] = '';
												$enroloutu_raw[] = $row[1].':'.$row[2];
												if ($DB->record_exists($enrol, array('userid'=>$row[1],'courseid'=>$row[2]))) {
													$enroloutu_exists[] = $row[1].':'.$row[2];
												}
											}
											if ($row[0] === 'in') {
												$enrolinu_raw[] = $row[1].':'.$row[2];
												if ($DB->record_exists($enrol, array('userid'=>$row[1],'courseid'=>$row[2]))) {

													$enrolinu_exists[] = $row[1].':'.$row[2];
												}
											}
										}
									} else {
										$enroloutu_raw = array('','');
										$enrolinu_raw = array('','');
										$enrolinu_exists = array('','');
										$enroloutu_exists = array('','');
									}
									$enrolout_raw = array_merge($enroloutc_raw, $enroloutu_raw);
									$enrolout_exists = array_merge($enroloutc_exists, $enroloutu_exists);
									foreach ($enrolout_raw as $eor) {
										if ((in_array($eor, $enrolout_exists)) && ($eor[1] !== '')) {
											$enrolout[] = $eor;
										}
									}
									$enrolin_raw = array_merge($enrolinc_raw, $enrolinu_raw);
									$enrolin_exists = array_merge($enrolinc_exists, $enrolinu_exists);
									foreach ($enrolin_raw as $eir) {
										if ((!in_array($eir, $enrolout_raw)) && (!in_array($eir, $enrolin_exists))) {
											$enrolin[] = $eir;
										}
									}
									if (($enrolin) || ($enrolout)) {
										echo get_string('checkcodes', 'block_course_admin');
									}
									if ($enrolin){
										echo get_string('unenrolledfrom', 'block_course_admin').'<div class="unenrolled">';
										$enrolin_string = implode(',', $enrolin);
										foreach ($enrolin as $in) {
											list($userid1, $courseid1) = explode(":", $in);
											$coursesid = $DB->get_record('course', array('idnumber'=>$courseid1), 'fullname');
											echo '<li><i class="fa fa-minus-square-o" aria-hidden="true" style="color:red;"></i> '.$coursesid->fullname.'</li>';
										}
										echo '</div>';
									}
									else {
										$enrolin_string = '';
									}
									if ($enrolout){
										echo get_string('reenrolledon', 'block_course_admin').'<div class="enrolled">';
										$enrolout_string = implode(',', $enrolout);
										foreach ($enrolout as $out) {
											list($userid, $courseid) = explode(":", $out);
											$coursesid = $DB->get_record('course', array('idnumber'=>$courseid), 'fullname');
											echo '<li><i class="fa fa-plus-square-o" aria-hidden="true" style="color:green;"></i> '.$coursesid->fullname.'</li>';
										}
									}
									else {
										$enrolout_string = '';
									}
									if ((!$enrolin) && (!$enrolout)) {
										echo get_string('nochanges', 'block_course_admin'); ?>
										<script>    
											if(typeof window.history.pushState == 'function') {
												window.history.pushState({}, "Hide", "../course_admin/index.php");
											}
											var current_tab = '#enrol-btn';
										</script> <?php
									}
									echo '</div>'; ?>
								</ul>
								<div class="modal-footer">
								<?php 
								if (($enrolin) || ($enrolout)) {
									echo '<a class="btn btn-success" href="?enrolin=' . $enrolin_string . '&enrolout='.$enrolout_string.'">Confirm</a>'; 
								} ?>
								<button type="button" class="btn btn-primary cancel" data-dismiss="modal"><?php echo get_string('cancel', 'block_course_admin'); ?></button>
							</div>
							</div>
						</div>
					</div>
				</div>		
			<?php	
			}

			// WRITE Unit creator selections to DB
			$updatedcourse = FALSE;
			$createdin = optional_param('createin', '', PARAM_RAW);
			if ($createdin) {
				$createin = explode(",",$createdin);
				foreach ($createin as $addin) {
					$DB->insert_record($units, array('courseid'=>$addin), $returnid=true, $bulk=false);
					$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Create', 'context'=>'Units', 'course1'=>$addin, 'timestamp'=>$timestamp));
				}
				$updatedcourse = TRUE;
				?>
				<script>    
					if(typeof window.history.pushState == 'function') {
						window.history.pushState({}, "Hide", "../course_admin/index.php");
					}
					var current_tab = '#course-btn';
				</script>
				<?php
			}

			// WRITE Meta Link selections to DB
			$updatedmeta = FALSE;
			$metain = optional_param('metain', '', PARAM_RAW);
			$metaout = optional_param('metaout', '', PARAM_RAW);
			if (($metain) || ($metaout)) {
				if ($metain) {
					$metain = explode(",",$metain);
					foreach ($metain as $addin) {
						list($parent, $child) = explode(":", $addin);
						$record1 = new stdClass();
						$record1->parentid = $parent;
						$record1->childid = $child;
						$records = array($record1);
						$DB->insert_records($metalist, $records);
						$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Add', 'context'=>'Meta', 'course1'=>$parent, 'metachild'=>$child, 'timestamp'=>$timestamp)); // Log event
						$unitincoursetable = $DB->get_record($units, array('courseid'=>$child), 'courseid', IGNORE_MULTIPLE);
						$DB->delete_records($enrol, array('courseid'=>$parent,'userid'=>$username));
						$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Remove - is parent again', 'context'=>'Enrol', 'course1'=>$parent, 'timestamp'=>$timestamp)); // Log event
						if ($DB->record_exists($units, array('courseid'=>$child)) ) {
							$DB->delete_records($units, array('courseid'=>$child));
							$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Remove - is child', 'context'=>'Units', 'course1'=>$parent, 'metachild'=>$child, 'timestamp'=>$timestamp)); // Log event
						}
						unset ($record);
					}
				}
				if ($metaout) {
					$metaout = explode(",",$metaout);
					foreach ($metaout as $out) {
						list($parent, $child) = explode(":", $out);
						$DB->delete_records($metalist, array('parentid'=>$parent, 'childid'=>$child));
						$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Remove', 'context'=>'Meta', 'course1'=>$parent, 'metachild'=>$child, 'timestamp'=>$timestamp)); // Log event
						if ((!$DB->record_exists($units, array('courseid'=>$child))) && ($DB->record_exists($courses, array('idnumber'=>$child))) ) {
							$DB->insert_record($units, array('courseid'=>$child), $returnid=true, $bulk=false);
							$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Course exists - no longer a child - added for continuity', 'context'=>'Units', 'course1'=>$child, 'timestamp'=>$timestamp));
							if (!$DB->record_exists($enrol, array('courseid'=>$child))) {
								$DB->insert_record($enrol, array('courseid'=>$child,'userid'=>$username), $returnid=true, $bulk=false);
								$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Course exists - no longer a child - added for continuity', 'context'=>'Enrol', 'course1'=>$child, 'timestamp'=>$timestamp));
							}
						}
					}
				}
					$updatedmeta = TRUE;
					?>
					<script>    
						if(typeof window.history.pushState == 'function') {
							window.history.pushState({}, "Hide", "../course_admin/index.php");
						}
						var current_tab = '#meta-btn';
					</script>
					<?php
			}

			// WRITE Enrolment selections to DB
			$updatedenrol = FALSE;
			$enrolin = optional_param('enrolin', '', PARAM_RAW);
			$enrolout = optional_param('enrolout', '', PARAM_RAW);
			if (($enrolin) || ($enrolout)) {
				if ($enrolin) {
					$enrolin = explode(",",$enrolin);
					foreach ($enrolin as $addin) {
						list($usersname, $courseid) = explode(":", $addin);
						$record1 = new stdClass();
						$record1->userid = $usersname;
						$record1->courseid = $courseid;
						$records = array($record1);
						$DB->insert_records($enrol, $records);
						$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Unenrol', 'context'=>'Enrolment', 'course1'=>$courseid, 'timestamp'=>$timestamp)); // Log event
						unset ($record);
					}
				}
				if ($enrolout) {
					$enrolout = explode(",",$enrolout);
					foreach ($enrolout as $out) {
						list($usersname, $courseid) = explode(":", $out);
						$DB->delete_records($enrol, array('userid'=>$usersname, 'courseid'=>$courseid));
						$DB->insert_record($log, array ('username'=>$username, 'userid'=>$userid, 'action'=>'Re-enrol', 'context'=>'Enrolment', 'course1'=>$courseid, 'timestamp'=>$timestamp)); // Log event
					}
				}	
				$updatedenrol = TRUE;
				?>
				<script>    
					if(typeof window.history.pushState == 'function') {
						window.history.pushState({}, "Hide", "../course_admin/index.php");
					}
					var current_tab = '#enrol-btn';
				</script>
				<?php
			}

			// Prevent loading courses if only confirmation required
			if ($needsconfirmation == 0) { 

				// Lookup new enrolment data
				$countcourses = $DB->count_records($source_clean, array('userid'=>$username));
				$result = $DB->get_records($source_clean, array('userid'=>$username),'unitfullname ASC');
				$resulte = $DB->get_records($source_clean, array('userid'=>$username),'unitfullname ASC');
				$result_changename = $DB->get_records($source_clean, array('userid'=>$username),'unitfullname ASC');
				$courselist = $DB->get_records_sql("SELECT c.id, c.fullname, r.contextid, r.userid, c.idnumber 
				FROM {$role} AS r 
				INNER JOIN {$context} AS cx 
				    ON r.contextid = cx.id 
				INNER JOIN {$coursenames} AS c
				    ON cx.instanceid = c.id
				WHERE r.userid = '$userid'
				-- AND c.shortname LIKE '%{$courseyear}-%'
				");
				$remediation = '<a data-toggle="modal" data-target="#remediation" class="inyoface"><i class="fa fa-warning"></i> Remediation</span></a>';
				$infill = '<a data-toggle="modal" data-target="#remediation"><span class="inyoface"><i class="fa fa-warning"></i> Infill</span></a>';
						$newresult = $DB->get_records($source_clean, array('userid'=>$username),'unitfullname ASC');
				$groupfullnames = array();
				foreach($newresult as $name) {
					$groupfullnames[] = $name->unitdescription;
				}
				$parent_shortnames = array();
				foreach ($groupfullnames as $groupfullname) {
					$parent_shortnames[] = substr($groupfullname, -15);
				}
				$uniquegroups = array_unique($parent_shortnames);
				$u_parent_ids = array();
				foreach ($uniquegroups as $uniquegroup) {
					$u_parent_ids[] = $DB->get_field('course', 'idnumber', array('shortname'=>$uniquegroup));
				}
				$parent_fullrecord = array();
				foreach ($u_parent_ids as $u_parent_id) {
					$parent_fullrecord[] = $DB->get_records('course', array('idnumber'=>$u_parent_id));
				}
				$newcourselist = $DB->get_records_sql("SELECT c.id, c.fullname, r.contextid, r.userid, c.idnumber 
				FROM {$role} AS r 
				INNER JOIN {$context} AS cx 
				    ON r.contextid = cx.id 
				INNER JOIN {$coursenames} AS c
				    ON cx.instanceid = c.id
				WHERE r.userid = '$userid'
				-- AND c.shortname LIKE '%{$courseyear}-%'
				");
				if ($newcourselist) {
					foreach ($newcourselist as $course) {
						$courselist_ids[] = $course->idnumber;
					}
				} else {
					$courselist_ids[] = '';
				}
				$newunitlist = $DB->get_records_sql("SELECT c.id, c.fullname, r.contextid, r.userid, c.idnumber 
				FROM {$role} AS r 
				INNER JOIN {$context} AS cx 
				    ON r.contextid = cx.id 
				INNER JOIN {$coursenames} AS c
				    ON cx.instanceid = c.id
				WHERE r.userid = '$userid'
				AND c.shortname LIKE '%/%'
				");
				$unitlist_ids = array();
				foreach ($newunitlist as $unit) {
					$unitlist_ids[] = $unit->idnumber;
				}
				$unenrolledlist = $DB->get_records_sql("SELECT c.fullname, c.shortname, c.idnumber, e.userid, e.courseid 
				FROM {$enrolSQL} AS e
				INNER JOIN {$coursenames} AS c
				    ON e.courseid = c.idnumber
				WHERE e.userid = '$username'
				-- AND c.shortname LIKE '%{$courseyear}-%'
				");
				$unenrolled_ids = array();
				foreach ($unenrolledlist as $unenrolled) {
					$unenrolled_ids[] = $unenrolled->courseid;
				}
				$unenrolledunits = $DB->get_records_sql("SELECT c.fullname, c.shortname, c.idnumber, e.userid, e.courseid 
				FROM {$enrolSQL} AS e
				INNER JOIN {$coursenames} AS c
				    ON e.courseid = c.idnumber
				WHERE e.userid = '$username'
				AND c.shortname LIKE '%/%'
				");
				if ($unenrolledunits) {
					foreach ($unenrolledunits as $unenrolledunit) {
						$unenrolledunit_ids[] = $unenrolledunit->courseid;
					}
				} else {
					$unenrolledunit_ids[] = '';
				} ?>

				<!-- Welcome user -->
				<div id="welcome">
					<?php echo '<p>'.get_string('intro1', 'block_course_admin').$USER->firstname.'</p><p>'.get_string('intro2', 'block_course_admin').$enrol_collegedatabase_config->newcourseyear.get_string('intro3', 'block_course_admin').'</p>'; ?>
				</div>

				<?php 
				if (($block_course_admin_config->enablevimeo) && ($block_course_admin_config->vimeoid)) { ?>
					<!--  Vimeo Tutorial Video Modal Button   -->
					<button type="button" class="btn btn-warning btn-lg tutorial" data-toggle="modal" data-target="#ca_tutorial"><?php echo get_string('tut_watch', 'block_course_admin'); ?></button>
					<!--  Vimeo Tutorial Video Modal Window   -->
					<div id="ca_tutorial" class="modal hide fade" data-backdrop="static" role="dialog">
					  <div class="modal-dialog modal-dialog-centered" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal">&times;</button>
					        <h4 class="modal-title"><?php echo get_string('tut_title', 'block_course_admin'); ?></h4>
					      </div>
					      <div class="modal-body">
					        <iframe src="https://player.vimeo.com/video/<?php echo $block_course_admin_config->vimeoid; ?>?api=1&player_id=vimeoplayer&title=0&amp;byline=0&amp;portrait=0" name="vimeoplayer" id="nofocusvideo" width="100%" height="415" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>"


					        ?api=1&player_id=vimeoplayer" id="nofocusvideo" width="100%" height="415" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_string('close', 'block_course_admin'); ?></button>
					      </div>
					    </div>

					  </div>
					</div>
				<?php 
				} ?>

				<!--  Remediation / Infill Modal Window   -->
				<div id="remediation" class="modal hide fade" data-backdrop="static" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title"><?php echo get_string('remediation', 'block_course_admin'); ?></h4>
							</div>
							<div class="modal-body">
								<?php echo get_string('remediation_help', 'block_course_admin'); ?>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_string('close', 'block_course_admin'); ?></button>
							</div>
						</div>
					</div>
				</div>

				<!--  Start Tabs   -->
				<div id="admin-tabs" class="container">	

				<!--  Start Tab Navigation   -->
					<ul class="nav nav-tabs">
						<li class="nav-item active"><a class="nav-link" href="#enrol" data-toggle="tab" id="enrol-btn" role="tab" aria-controls="enrol" aria-selected="true"><?php echo get_string('enrolmenttab_title', 'block_course_admin') ?></a></li>
						<li class="nav-item"><a class="nav-link" href="#course" data-toggle="tab" id="course-btn" role="tab" aria-controls="course" aria-selected="false"><?php echo get_string('coursetab_title', 'block_course_admin') ?></a></li>
						<li class="nav-item"><a class="nav-link" href="#meta" data-toggle="tab" id="meta-btn" role="tab" aria-controls="meta" aria-selected="false"><?php echo get_string('metatab_title', 'block_course_admin') ?></a></li>
					</ul>

					<!--  Start Tab Content   -->
					<div class="tab-content primary">	
					
						<!--  Start Unit Tab   -->			  			  
						<div class="tab-pane fade" id="course" role="tabpanel" aria-labelledby="course-btn">
				          	<h3><?php echo get_string('coursetab_title', 'block_course_admin') ?></h3>
				          	<?php
							if ($countcourses > 0) { ?>
								<p><?php echo get_string('coursetab_desc', 'block_course_admin') ?></p>
								<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
									<?php if ($updatedcourse === TRUE) {
										echo ($success);
										echo get_string('unitscreated', 'block_course_admin');
										foreach ($createin as $added) {
											$row = $DB->get_record($source_clean, array('unitid'=>$added), 'unitfullname', IGNORE_MULTIPLE);
											echo '<li>'.$row->unitfullname.'</li>';
										}
										echo '</ul><br>';
									} ?>
									<table id="unit_table">
										<tr>
											<th class="c0"><input type = "checkbox" id = "chkSelectAll"></input></th>
											<th class="linked linked_head"><i class="fa fa-link"></i></th>
											<th class="c1"><?php echo get_string('unitheading', 'block_course_admin') ?></th>
											<th class="c2"><i class="fa fa-graduation-cap"></i> <?php echo get_string('groupheading', 'block_course_admin') ?></th>
										</tr>
										<?php // Count unique and generate blocks of colour to indicate meta links
										foreach($result as $name) {
										  	$name->unitfullname = str_replace($name->unitshortname, "", $name->unitfullname);
											$fullnames[] = $name->unitfullname;
										}
										$name_unique = array_unique($fullnames);
										// Generate randon RGBA colour value for each group of units
										foreach ($name_unique as $nu) {
											$color ='rgba('.rand(0,200).', '.rand(0,200).', '.rand(0,200).', 0.8)';
											$unique_color[$nu] = $color;
										}
										$count_unique = count(array_unique($fullnames));
										$created = '';
										$createarray = array();
										$child_array = array();
										foreach ($result as $row => $record) {
											if ($DB->record_exists($units, array('courseid'=>$record->unitid)) ) {
												$doesunitexist = 1;
											} else {
												$doesunitexist = 0;
											}
											if (($DB->record_exists($units, array('courseid'=>$record->unitid))) && (!$DB->record_exists('course', array('idnumber'=>$record->unitid)))) {
												$unitstatus = '<span class="processing">'.get_string('processing', 'block_course_admin').'</span>';
											} else {
												$unitstatus = '';
											}
											$warning = '';
											if (strpos($record->unitdescription, '-I') !== false) {
												$warning = $infill;
											}
											if (strpos($record->unitdescription, '-R') !== false) {
												$warning = $remediation;
											}
											$i = 1;
											$teachers = array();
											$teachers_sql = "SELECT u.firstname, u.lastname FROM $CFG->prefix{$teachunits} AS tu JOIN {$user} AS u ON tu.userid = u.username WHERE tu.unitid = '$record->unitid'";
											$teacherlist = $DB->get_records_sql($teachers_sql);
											$teachers_m_p[$i] = '<ul class="teacher_list">';
											foreach ($teacherlist as $teachername) {
												$teachers_m_p[$i] .= '<li>';
												$teachers_m_p[$i] .= $teachername->firstname." ".$teachername->lastname;
												$teachers_m_p[$i] .= '</li>';
											}
											$teachers_m_p[$i] .= '</ul>';
											$teachers[$record->unitid] = $teachers_m_p[$i];
											$i++;
											$teachercount = count($teacherlist);
											if ($teachercount > 1) {
												$users = '<button type="button" class="btn btn-info teachers_btn" title="Alert" data-toggle="modal" data-target="#teachers_c-'.$record->unitid.'"><i class="fa fa-users"></i></button>';
											} else {
												$users = ''; 
											}
											if ( ($DB->record_exists($units, array('courseid'=>$record->unitid))) && ($DB->record_exists($courses, array('idnumber'=>$record->unitid))) && (!$DB->record_exists($enrol, array('userid'=>$username , 'courseid'=>$record->unitid))) && (!$DB->record_exists($metalist, array('childid'=>$record->unitid))) ) {
												$id = $DB->get_field('course', 'id', array('idnumber'=>$record->unitid));
												$link = ' <a href="'.$CFG->wwwroot.'/course/view.php?id='.$id.'" target="_blank"  title="Go to Course" title="Go to Course"><i class="fa fa-sign-in"></i></a> ';
											} else {
												$link = '';
											}
											$fullname_clean = str_replace(" ", "", $record->unitfullname);
											$checked = ($doesunitexist == 1) ? 'checked="checked"' : '';
											echo '<tr class="" id="'.$fullname_clean.'"><td class="c0"><input class="unit checkbox" type="checkbox" id="check' . $record->id . '" name="created[]" value="' . $record->unitid . '" ' . $checked . '/></td><td class="linked '.$fullname_clean.'"><div style="background:'.$unique_color[$record->unitfullname].';line-height:54px;width:15px;float:right:margin-right:10px;">&nbsp;</div></td><td class="c1"><span class="coursename">' . $record->unitfullname.' '.$record->unitshortname.$link.'</span>'.$users.$warning.$unitstatus;?>
											<div id="teachers_c-<?php echo $record->unitid; ?>" class="modal hide fade" data-backdrop="static" role="dialog" aria-hidden="true">
												<div class="modal-dialog modal-dialog-centered" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title"><?php echo get_string('multiple_teachers', 'block_course_admin'); ?></h4>
														</div>
														<div class="modal-body">
															<?php echo get_string('multiple_teachers_help', 'block_course_admin'); ?>
															<?php echo $teachers[$record->unitid]; ?>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_string('close', 'block_course_admin'); ?></button>
														</div>
													</div>
												</div>
											</div> <?php
											if ($DB->record_exists($metalist, array('parentid'=>$record->unitid))) {
												echo '<p class="parent" style="color:'.$unique_color[$record->unitfullname].' !important;"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"style="color:'.$unique_color[$record->unitfullname].' !important;"></i><i class="fa fa-link fa-stack-1x fa-inverse" style="color:#fff !important;"></i></span>  Parent </p>';
											}
											if ($DB->record_exists($metalist, array('childid'=>$record->unitid))) {
												$parentsid_arr = array();
												$parentsfullname_arr = array();
												$parentscount = $DB->count_records($metalist, array('childid'=>$record->unitid));
												$parentsid_arr = $DB->get_record($metalist, array('childid'=>$record->unitid), 'parentid', IGNORE_MULTIPLE);
												$parentsid = $parentsid_arr->parentid;
												$parentsfullname = $DB->get_field($source_clean, 'unitfullname', array('unitid'=>$parentsid), IGNORE_MULTIPLE);
												echo '<p class="child" style="color:'.$unique_color[$record->unitfullname].' !important;"><span class="fa-stack"><i class="fa fa-square-o fa-stack-2x" style="color:'.$unique_color[$record->unitfullname].' !important;"></i><i class="fa fa-link fa-stack-1x" style="color:'.$unique_color[$record->unitfullname].' !important;"></i></span> Child <a href="#info-'.$record->id.'" data-toggle="modal" data-backdrop="static"><i class="fa fa-question-circle" id="info-'.$record->id.'"  style="color:'.$unique_color[$record->unitfullname].'"></i></a></p>';
												$child_array[] .= $record->unitid;
												if ($parentscount >= 2) {
													$parentscount_minus = $parentscount - 1;
													$message = "<p>This unit has been selected as a child of ".$parentsfullname." (and ".$parentscount_minus." more) and can't be created seperately.</p><p>If you wish to create ".$record->unitshortname." as a standalone unit, please unlink this unit in the <a href='#meta' data-toggle='tab' data-dismiss='modal'>".get_string('metatab_title', 'block_course_admin')."</a> tab first.</p>";
												} elseif ($parentscount <= 1) {
													$message = "<p>This unit has been selected as a child of ".$parentsfullname." and can't be created seperately.</p><p>If you wish to create ".$record->unitshortname." as a standalone unit, please unlink this unit in the <a href='#meta' data-toggle='tab' data-dismiss='modal'>".get_string('metatab_title', 'block_course_admin')."</a> tab first.</p>";
												}
											}
											echo '</td><td><span class="dimmed">'. $record->unitdescription .'</span></td></tr>' . "\n";
											if (($checked) || (in_array($record->unitid, $child_array))) {
													?>
													<script>
														document.getElementById("check<?php echo ($record->id) ?>").disabled = true;
														document.getElementById("check<?php echo ($record->id) ?>").classList.add('linkedUnit-<?php echo ($record->id) ?>');
													</script>
													
													  <!-- Modal -->
													  <div class="modal fade" id="info-<?php echo ($record->id) ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
														<div class="modal-dialog modal-dialog-centered" role="document">
														  <div class="modal-content">
															<div class="modal-header">
															  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
															  <h4 class="modal-title"><?php echo ($record->unitfullname.' '.$record->unitshortname) ?></h4>
															</div>
															<div class="modal-body">
																<?php echo $message; ?>
															</div>
															<div class="modal-footer">
															  <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_string('gotit', 'block_course_admin'); ?></button>
															</div>
														  </div><!-- /.modal-content -->
														</div><!-- /.modal-dialog -->
													  </div><!-- /.modal -->
												<?php
											}
										} ?>
										<tr>
											<td colspan="2" id="submit">
												<script>
													$('#chkSelectAll').click(function () {
														var checked_status = this.checked;
														$('input.unit[type=checkbox]').not(":disabled").prop("checked", checked_status);
													});
												</script>
												<input type="submit" class="btn btn-success" name="submitcourses" value="<?php echo get_string('save', 'block_course_admin') ?>" style="margin:20px 0;"/>
											</td>
										</tr>
									</table>
								</form>
							<?php
							} else {
								echo "<h4>" . get_string('noenrolments', 'block_course_admin') . "</h4>";
							} ?>
						</div>
					         

						<!--  Start Meta Tab   -->         
						<div class="tab-pane fade" id="meta" role="tabpanel" aria-labelledby="meta-btn">
				        	<h3><?php echo get_string('metatab_title', 'block_course_admin') ?></h3>
				        	<?php 
							$countcreatedunits = "SELECT s.userid, s.unitid, u.courseid FROM $CFG->prefix{$units} AS u INNER JOIN {$source} AS s ON s.unitid = u.courseid WHERE s.userid = '$username'";
				        	if ($DB->record_exists_sql($countcreatedunits)) { ?>
								<p><?php echo get_string('metatab_desc', 'block_course_admin') ?></p>
								<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
									<?php
									if ($updatedmeta === TRUE) {
										echo ($success);
									}
									?>

									<!-- Start tabs left - CREATED UNITS-->
									<div class="row">
  										<div class="col-6 parents">
  											<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
										<!--  Start tabs left navigation   -->					
										<!-- <ul class="nav nav-tabs"> -->

											<!--  Start tabs left content   -->						

											<!--  Tab 1 is help & information   -->		
											<!-- <li class=""> -->
												<a class="" id="v-pills-home-tab" href="#0" role="tab" data-toggle="pill" aria-controls="v-pills-home" aria-selected="true">
													<h4><?php echo get_string('parent', 'block_course_admin') ?></h4>
												</a>
											<!-- </li> -->

											<!--  Start arrayed tabs from created units   -->		
											<?php 
											$incrementli = 1;
											$metaparents = array();
											foreach ($result as $row2 => $record2) {
												if ($DB->record_exists($units, array('courseid'=>$record2->unitid)) ) {
													$frameworkcode = substr($record2->unitdescription, -15);
													$warning = '';
													if (strpos($frameworkcode, '-I') !== false) {
														$warning = $infill;
													}
													if (strpos($frameworkcode, '-R') !== false) {
														$warning = $remediation;
													}
													$i = 1;
													$teachers = array();
													$teachers_sql = "SELECT u.firstname, u.lastname FROM $CFG->prefix{$teachunits} AS tu JOIN {$user} AS u ON tu.userid = u.username WHERE tu.unitid = '$record2->unitid'";
													$teacherlist = $DB->get_records_sql($teachers_sql);
													$teachers_m_p[$i] = '<ul class="teacher_list">';
													foreach ($teacherlist as $teachername) {
														$teachers_m_p[$i] .= '<li>';
														$teachers_m_p[$i] .= $teachername->firstname." ".$teachername->lastname;
														$teachers_m_p[$i] .= '</li>';
													}
													$teachers_m_p[$i] .= '</ul>';
													$teachers[$record2->unitid] = $teachers_m_p[$i];
													$i++;
													$teachercount = count($teacherlist);
													if ($teachercount > 1) {
														$users = '<button type="button" class="btn btn-info teachers_btn" data-toggle="modal" title="Alert" data-target="#teachers_m_p-'.$record2->unitid.'"><i class="fa fa-users"></i></button>';
													}
													else {
														$users = ''; 
													}
													echo '<a class="nav-link" href="#p-' . $record2->unitid . '" data-toggle="pill" role="tab" aria-controls="v-pills-' . $record2->unitid . '" aria-selected="false">' . $record2->unitfullname .' '.$record2->unitshortname.$users.'<br><span class="dimmed"><i class="fa fa-graduation-cap"></i> ' . $record2->unitdescription .'</span> '.$warning .'</a>' . "\n"; ?>
													<!--  Multiple Teachers Modal Window   -->
													<div id="teachers_m_p-<?php echo $record2->unitid; ?>" class="modal hide fade" data-backdrop="static" role="dialog" aria-hidden="true">
														<div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title"><?php echo get_string('multiple_teachers', 'block_course_admin'); ?></h4>
																</div>
																<div class="modal-body">
																	<?php echo get_string('multiple_teachers_help', 'block_course_admin'); ?>
																	<?php echo $teachers[$record2->unitid]; ?>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_string('close', 'block_course_admin'); ?></button>
																	<!-- <script>
																		
																	</script> -->
																</div>
															</div>
														</div>
													</div>
													<?php $incrementli++;
													$metaparent = new stdClass ();
													$metaparent->id = $record2->unitid;
													$metaparent->name = $record2->unitfullname;
													$metaparent->shortname = $record2->unitshortname;
													$metaparents[] = $metaparent;
												}
											}
											?>
										<!-- </ul> -->
										</div>
									</div>

										<!--  Start Sub-content   -->  
										<div class="col-6 children">  
											<h4>Potential Children</h4>
										<div class="tab-content" id="v-pills-tabContent">    
										<!-- <div class="tab-content children"> -->
											
											<div class="tab-pane fade show active" id="0" role="tabpanel" aria-labelledby="v-pills-home-tab">
												<p><?php echo get_string('metatab_instructions', 'block_course_admin') ?></p> <br>
												<img src="images/meta.png">
											</div>
											<?php
											$count = 1;
											foreach ($result as $row3 => $record3) {
												foreach ($metaparents as $par => $parent) {
													if ($count >= $incrementli) break;
													if ($record3->unitid = $parent->id) {
														$parentshortname = substr($parent->shortname, 0, strpos($parent->shortname, "/"));
														$parentfullname  = trim(str_replace($parent->shortname, "", $parent->name));
														echo '<div class="tab-pane fade" id="p-' . $parent->id . '" role="tabpanel">';
														echo '<h5>'.get_string('otherunits_1', 'block_course_admin').'<b>"' . $parentfullname . '"</b>'.get_string('otherunits_2', 'block_course_admin').'</h5>';
														echo '<ul id="par-'.$parent->id.'">';
														$parent_cid = $DB->get_field('course', 'id', array('idnumber'=>$parent->id), IGNORE_MISSING);
														$matchcount = 0;
														foreach ($resulte as $rowli => $recordli) {
															if (((strpos($recordli->unitshortname, $parentshortname) !== false) || (strpos($recordli->unitfullname, $parentfullname) !== false)) && ($recordli->unitshortname != $parent->shortname) ) {
																$matchcount++;
																if ($DB->record_exists($metalist, array('parentid'=>$parent->id, 'childid'=>$recordli->unitid)) ) {
																	$metachecked = 1;
																} else {
																	$metachecked = 0;
																}
																$warning = '';
																$frameworkcode = substr($recordli->unitdescription, -15);
																if (strpos($frameworkcode, '-I') !== false) {
																	$warning = $infill;
																}
																if (strpos($frameworkcode, '-R') !== false) {
																	$warning = $remediation;
																}
																$i = 1;
																$teachers = array();
																$teachers_sql = "SELECT u.firstname, u.lastname FROM $CFG->prefix{$teachunits} AS tu JOIN {$user} AS u ON tu.userid = u.username WHERE tu.unitid = '$recordli->unitid'";
																$teacherlist = $DB->get_records_sql($teachers_sql);
																$teachers_m_p[$i] = '<ul class="teacher_list">';
																foreach ($teacherlist as $teachername) {
																	$teachers_m_p[$i] .= '<li>';
																	$teachers_m_p[$i] .= $teachername->firstname." ".$teachername->lastname;
																	$teachers_m_p[$i] .= '</li>';
																}
																$teachers_m_p[$i] .= '</ul>';
																$teachers[$recordli->unitid] = $teachers_m_p[$i];
																$i++;
																$teachercount = count($teacherlist);
																if ($teachercount > 1) {
																	$users = '<button type="button" class="btn btn-info teachers_btn" data-toggle="modal" title="Alert" data-target="#teachers_m_c-'.$recordli->unitid.'"><i class="fa fa-users"></i></button>';
																}
																else {
																	$users = ''; 
																}
																$child_cid = $DB->get_field('course', 'id', array('idnumber'=>$recordli->unitid), IGNORE_MISSING);
																if ((($DB->record_exists($metalist, array('parentid'=>$parent->id, 'childid'=>$recordli->unitid))) && (!$DB->record_exists('enrol', array('courseid'=>$parent_cid, 'customint1'=>$child_cid, 'enrol'=>'meta')))) || ((!$DB->record_exists($metalist, array('parentid'=>$parent->id, 'childid'=>$recordli->unitid))) && ($DB->record_exists('enrol', array('courseid'=>$parent_cid, 'customint1'=>$child_cid, 'enrol'=>'meta'))))) {
																	$metastatus = '<span class="processing">'. get_string('processing', 'block_course_admin').'</span>';
																}
																if ((($DB->record_exists($metalist, array('parentid'=>$parent->id, 'childid'=>$recordli->unitid))) && ($DB->record_exists('enrol', array('courseid'=>$parent_cid, 'customint1'=>$child_cid, 'enrol'=>'meta')))) || ((!$DB->record_exists($metalist, array('parentid'=>$parent->id, 'childid'=>$recordli->unitid))) && (!$DB->record_exists('enrol', array('courseid'=>$parent_cid, 'customint1'=>$child_cid, 'enrol'=>'meta'))))) {
																	$metastatus = '';
																}
																$checked = ($metachecked == 1) ? 'checked="checked"' : '';
																echo '<p><li id="child-'.$recordli->unitid.'"  class="childof-'.$parent->id.' child"><input id="metacheckHidden" type="hidden" name="meta[]" value="out:'.$parent->id.':'. $recordli->unitid.'" /><input class="'.$parent->id.' checkbox" onclick="checkParent'.$parent->id,$count.'()" id="metacheck'.$recordli->unitid,$count.'" type="checkbox" name="meta[]" value="in:'.$parent->id.':'. $recordli->unitid.'" ' . $checked . '/><span class="childname">' . $recordli->unitfullname .'</span>'.$warning.$metastatus.'<br><span class="dimmed"><i class="fa fa-graduation-cap"></i> '.$recordli->unitdescription.'</span></li></p>' . "\n"; ?>
																<?php
															}
															// Disable units which are parents to prevent enrolment loops
															if ($DB->record_exists($metalist, array('parentid'=>$recordli->unitid))) {
																echo "<script type='text/javascript' >
																	$('#child-".$recordli->unitid." .checkbox').attr('disabled', 'disabled').addClass('disabled');
																</script>";
															} if ($DB->record_exists($metalist, array('childid'=>$parent->id))) {
																echo "<script type='text/javascript' >
																	$('#par-".$parent->id." .checkbox').attr('disabled', 'disabled').addClass('disabled');
																</script>";
																
															} else {
															echo "
															<script type='text/javascript'>
															window.onload = function(){
															document.getElementById('metacheck".$recordli->unitid.$count."').onclick = function disableUnits() {
																	var ckbox = $('#metacheck".$recordli->unitid,$count."');
																	if (ckbox.is(':checked')) {
																		$('#par-".$recordli->unitid." .checkbox').attr('disabled', 'disabled');
																		$('li#child-".$parent->id." input.checkbox').attr('disabled', 'disabled');
																	} else {
																		$('#par-".$recordli->unitid." .checkbox').not('.disabled').removeAttr('disabled', 'disabled');
																		$('li#child-".$parent->id." input.checkbox').not('.disabled').removeAttr('disabled', 'disabled');
																	}
																};
															};
															</script>";
															}
														}
														if ($matchcount < 1) {
															echo '<span class="nomatch">'.get_string('nomatch', 'block_course_admin').'</span>';
														}
														echo '</ul>';
														echo '</div>' . "\n";
													}
													$count++;
												}
											}
											?>
											<input type="submit" class="btn btn-success" name="submitmeta" value="<?php echo get_string('save', 'block_course_admin') ?>" style="float:right;"/>
										</div>
									</div>
								</form>
								</div>
								<?php
							} else {
								echo "<h4>".get_string('nounits', 'block_course_admin')."</h4>";
							} ?>
						</div>
					         
						<!--  Start Enrolment Tab Content   -->
						<div class="tab-pane fade show active" id="enrol" role="tabpanel" aria-labelledby="enrol-btn">
				        	<h3><?php echo get_string('enrolmenttab_title', 'block_course_admin') ?></h3>
				        	<?php
							if ($countcourses > 1) { ?>
								<p><?php echo get_string('enrolmenttab_desc', 'block_course_admin'); ?></p>
								<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
									<?php
									if ($updatedenrol === TRUE) {
										echo ($success);
									} ?>
									<table id="enrol_table">
										<tr>
											<th class="c0"></th>
											<th class="c1"><?php echo get_string('courseunit_name', 'block_course_admin') ?></th>
											<th class="c2"><?php echo get_string('group', 'block_course_admin') ?></th>
										</tr>
										<tr class="break">
											<th class="c0">
												<input type = "checkbox" id = "enrolSelectUnits" checked></input>
											</th>
											<th class="c1"><?php echo get_string('existingunits', 'block_course_admin') ?></th>
											<th class="c2"></th>
										</tr>
										<?php $record4 = array();
										if ($DB->record_exists_sql($countcreatedunits)) { 
											foreach ($resulte as $row4 => $record4) {
												if ($DB->record_exists($units, array('courseid'=>$record4->unitid)) ) {
													if ((in_array($record4->unitid, $unitlist_ids)) && (!in_array($record4->unitid, $unenrolledunit_ids))) {
														$unenrolled = 0;
														$status = '';
													} 
													if ((!in_array($record4->unitid, $unitlist_ids)) && (!in_array($record4->unitid, $unenrolledunit_ids))) {
														$unenrolled = 0;
														$status = '<span class="processing">Processing</span>';
													}
													if ((!in_array($record4->unitid, $unitlist_ids)) && (in_array($record4->unitid, $unenrolledunit_ids))) {
														$unenrolled = 1;
														$status = '';
													}
													if ((in_array($record4->unitid, $unitlist_ids)) && (in_array($record4->unitid, $unenrolledunit_ids))) {
														$unenrolled = 1;
														$status = '<span class="processing">Processing</span>';
													}
													if ($DB->record_exists($enrol, array('courseid'=>$record4->unitid,'userid'=>$username)) ) {
														$unenrolled = 1;
													}
													else {
														$unenrolled = 0;
													}
													$warning = '';
													if (strpos($record4->unitdescription, '-I') !== false) {
														$warning = $infill;
													}
													if (strpos($record4->unitdescription, '-R') !== false) {
														$warning = $remediation;
													}
													$i = 1;
													$teachers = array();
													$teachers_sql = "SELECT u.firstname, u.lastname FROM $CFG->prefix{$teachunits} AS tu JOIN {$user} AS u ON tu.userid = u.username WHERE tu.unitid = '$record4->unitid'";
													$teacherlist = $DB->get_records_sql($teachers_sql);
													$teachers_m_p[$i] = '<ul class="teacher_list">';
													foreach ($teacherlist as $teachername) {
														$teachers_m_p[$i] .= '<li>';
														$teachers_m_p[$i] .= $teachername->firstname." ".$teachername->lastname;
														$teachers_m_p[$i] .= '</li>';
													}
													$teachers_m_p[$i] .= '</ul>';
													$teachers[$record4->unitid] = $teachers_m_p[$i];
													$i++;
													$teachercount = count($teacherlist);
													if ($teachercount > 1) {
														$users = '<button type="button" class="btn btn-info teachers_btn" title="Alert" data-toggle="modal" data-target="#teachers_e_u-'.$record4->unitid.'"><i class="fa fa-users"></i></button>';
													}
													else {
														$users = ''; 
													}
													if ( ($DB->record_exists($units, array('courseid'=>$record4->unitid))) && ($DB->record_exists($courses, array('idnumber'=>$record4->unitid))) && (!$DB->record_exists($enrol, array('userid'=>$username , 'courseid'=>$record4->unitid))) && (!$DB->record_exists($metalist, array('childid'=>$record4->unitid))) ) {
														$id = $DB->get_field('course', 'id', array('idnumber'=>$record4->unitid));
														$link = ' <a href="'.$CFG->wwwroot.'/course/view.php?id='.$id.'" target="_blank" title="Go to Course"><i class="fa fa-sign-in"></i></a> ';
													} else {
														$link = '';
													}
													$checked = ($unenrolled == 0 ) ? 'checked="checked"' : '';
													echo '<tr class="c0"><td><input id="unenroluHidden" type="hidden" name="unenrolu[]" value="in:'.$username.':'. $record4->unitid.'" /><input class="enrol checkbox unitcheck" id="unenrolu" type="checkbox" name="unenrolu[]" value="out:'.$username.':'. $record4->unitid.'" ' . $checked . '/></td><td class="c1"><span class="coursename">' . $record4->unitfullname.$link .'</span>'.$users.$warning.$status.'</td><td class="c2"><span class="dimmed">'. $record4->unitdescription .'</span></td></tr>' . "\n";
													?>
													<div id="teachers_e_u-<?php echo $record4->unitid; ?>" class="modal hide fade" data-backdrop="static" role="dialog" aria-hidden="true">
														<div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title"><?php echo get_string('multiple_teachers', 'block_course_admin'); ?></h4>
																</div>
																<div class="modal-body">
																	<?php echo get_string('multiple_teachers_help', 'block_course_admin'); ?>
																	<?php echo $teachers[$record4->unitid]; ?>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo get_string('close', 'block_course_admin'); ?></button>
																	<!-- <script>
																		
																	</script> -->
																</div>
															</div>
														</div>
													</div> <?php
												}
											}
										} else {
											echo "<tr class='c0'><td></td><td><p>".get_string('nounits', 'block_course_admin')."</p></td><td></td></tr>";
										} ?>
										<tr class="break">
											<th class="c0">
												<input type = "checkbox" id = "enrolSelectCourses" checked></input>
											</th>
											<th class="c1"><?php echo get_string('existingcourses', 'block_course_admin') ?></th>
											<th class="c2"></th>
										</tr>
										<?php
										foreach ($parent_fullrecord as $fullrecords => $fullrecord_outer) {
											foreach($fullrecord_outer as $fullrecord) {
												if ((in_array($fullrecord->idnumber, $courselist_ids)) && (!in_array($fullrecord->idnumber, $unenrolled_ids))) {
													$unenrolled = 0;
													$status = '';
												} 
												if ((!in_array($fullrecord->idnumber, $courselist_ids)) && (!in_array($fullrecord->idnumber, $unenrolled_ids))) {
													$unenrolled = 0;
													$status = '<span class="processing">'.get_string('processing', 'block_course_admin').'</span>';
												}
												if ((!in_array($fullrecord->idnumber, $courselist_ids)) && (in_array($fullrecord->idnumber, $unenrolled_ids))) {
													$unenrolled = 1;
													$status = '';
												}
												if ((in_array($fullrecord->idnumber, $courselist_ids)) && (in_array($fullrecord->idnumber, $unenrolled_ids))) {
													$unenrolled = 1;
													$status = '<span class="processing">'.get_string('processing', 'block_course_admin').'</span>';
												}
												$warning = '';
												if (strpos($fullrecord->fullname, '-I') !== false) {
													$warning = $infill;
												}
												if (strpos($fullrecord->fullname, '-R') !== false) {
													$warning = $remediation;
												}
												if ($fullrecord) {
													if ($DB->record_exists($courses, array('idnumber'=>$fullrecord->idnumber))) {
														$id = $DB->get_field('course', 'id', array('idnumber'=>$fullrecord->idnumber));
														$link = ' <a href="'.$CFG->wwwroot.'/course/view.php?id='.$id.'" target="_blank" title="Go to Course"><i class="fa fa-sign-in"></i></a> ';
													} else {
														$link = '';
													}
												}
												$checked = ($unenrolled == 0 ) ? 'checked="checked"' : '';
												echo '<tr class="c0"><td><input class="enrol checkbox coursecheck" id="unenrol" type="checkbox" name="unenrol[]" value="out:'.$username.':'. $fullrecord->idnumber.'" ' . $checked . '/><input id="unenrolHidden" type="hidden" name="unenrol[]" value="in:'.$username.':'. $fullrecord->idnumber.'" /></td><td class="c1"><span class="coursename">' . $fullrecord->fullname . $link . '</span> '.$warning.$status.'</td><td class="c2"><span class="dimmed">Top Level Course</span></td></tr>' . "\n";
											}
										} ?>
										<tr>
											<td colspan="2" id="submit">
												<script>
													$('#enrolSelectUnits').click(function () {
														var checked_status = this.checked;
														$('input.unitcheck[type=checkbox]').not(":disabled").prop("checked", checked_status);
													});
													$('#enrolSelectCourses').click(function () {
														var checked_status = this.checked;
														$('input.coursecheck[type=checkbox]').not(":disabled").prop("checked", checked_status);
													});
												</script>
												<input type="submit" class="btn btn-success" name="submitenrol" value="<?php echo get_string('save', 'block_course_admin') ?>" style="margin:20px 0;"/>
											</td>
										</tr>
									</table>
								</form>
							<?php 
							} else {
								echo get_string('noenrolments', 'block_course_admin');
							} ?>
						</div>				
					</div>
				</div>
			<?php 
			}
			} else {
				echo get_string('noenrolments', 'block_course_admin');
			}
		} else {
			// echo "<script>$( '#loadingDiv' ).remove();</script>";
			echo get_string('denied', 'block_course_admin');
		}
	}

	?>
</div> <?php
	
// Print Moodle footer 
echo $OUTPUT->footer(); ?>

<script>
	$(document).ready(function(){
		$('#admin-tabs table#meta').DataTable();
		$('#admin-tabs table#unit').DataTable();	
		$('#admin-tabs table#enrol').DataTable();
		console.log('datatables done');
		$('.submit').each(function() {
		  // $(this).prop('disabled', true);
		  var $button = $(this).clone();
		  $(this).closest('form').find('.dataTables_filter label').append($button);
		  $(this).remove();
		});
		$('.meta.checkbox').change(function(){
		   $("#meta_filter .submit").prop("disabled", !$('.meta.checkbox').is(':checked'));
		});
		$('.unit.checkbox').change(function(){
		   $("#unit_filter .submit").prop("disabled", !$('.unit.checkbox').is(':checked'));
		});
		$('.enrol.checkbox').change(function(){
		   $("#enrol_filter .submit").prop("disabled", !$('.enrol.checkbox').is(':checked'));
		});
		var dt = new Date();
		var time = "<span class='time'>" + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds() + "</span>";
		$(".tab-pane .DBsuccess").prependTo(".tab-content.primary");
		$(".DBsuccess").append(time);
	});
	$('.modal .cancel').click(function() {
		$('form').trigger("reset");
		window.location.href = window.location.protocol +'//'+ window.location.host + window.location.pathname;
	});
	$('.modal').on('hidden.bs.modal', function () {
		var $this = $(this);
		//get iframe on click
		 var vidsrc_frame = $this.find("iframe");
		var vidsrc_src = vidsrc_frame.attr('src');
		console.log(`videosrc=` + vidsrc_src); 
		vidsrc_frame.attr('src', '');
		vidsrc_frame.attr('src', vidsrc_src);
	});
</script>