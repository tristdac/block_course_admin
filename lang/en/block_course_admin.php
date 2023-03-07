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
 * Strings for component 'block_course_admin', language 'en'
 *
 * @package   block_course_admin
 * @copyright Tristan daCosta <tristan.dacosta@edinburghcollege.ac.uk>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['blockstring'] = 'Block string';
$string['descconfig'] = 'Configure Moodle Course Manager Block';
$string['descfoo'] = 'Config description';
$string['headerconfig'] = 'Moodle Course Manager';
$string['labelfoo'] = 'Config label';

$string['course_admin:addinstance'] = 'Add a Moodle Course Manager block';
$string['course_admin:myaddinstance'] = 'Add a Moodle Course Manager block to my moodle';
$string['pluginname'] = 'Moodle Course Manager';
$string['plugindesc'] = 'Create and manage your units, links and personal enrolments.';
$string['settingsheaderdb'] = 'Incoming Data from EC Views - Local Copy';
$string['sourcecourse'] = 'Source table name';
$string['sourcecourse_desc'] = 'Name of source table without prefix';

$string['unitselector'] = 'Unit Selector';
$string['unitselector_help'] = 'This is help for the unit selector';
$string['unitselector_desc'] = 'You have selected the following units for creation. This cannot be undone. Once a unit has been selected for creation here, it cannot be deleted.';

$string['page_title'] = 'Moodle Course Manager';
$string['success_title'] = 'Save Successful!';
$string['success_desc'] = 'Please allow up to an hour for these changes to be processed.';
$string['partialsuccess_title'] = 'Save Partially Successful';
$string['partialsuccess_desc'] = 'Some changes were not saved. See reason and fix where required. Please allow up to an hour for successful changes to be processed.';
$string['failure_title'] = 'Save Failed';
$string['failure_desc'] = 'No changes have been made. Please address reason listed.';
$string['nothing_title'] = 'Nothing to Change';
$string['nothing_desc'] = 'You havent requested any changes.';
$string['coursetab_title'] = 'Unit Creator';
$string['coursetab_desc'] = '<p>Select the units below that you would like created on Moodle. You may not wish to create all of those listed. For example, if you have 3 instances of the same unit and would like to link them all together, choose just one instance and tick to create. Then you can use the next tab (Unit Link) to add enrolments from the other instances.</p><p>For those of you who used ‘meta-linking’ last year, this does the trick without creating all the unit courses which you never used and sat hidden on your Dashboard</p>';
$string['unitheading'] = "Unit Name";
$string['groupheading'] = "Group";
$string['metatab_title'] = 'Unit Link';
$string['metatab_desc'] = 'Use the meta-linker to connect multiple units to a single unit.';
$string['parent'] = "Select a Parent";
$string['metatab_instructions'] = 'Select a unit from the list on the left (parent), then put a check in the corresponding box(es) in the list to the right (children) to link the childs enrolled students its parent.';
$string['enrolmenttab_title'] = 'Enrolment';
$string['enrolmenttab_desc'] = '<p>The following units and/or courses either already exist on Moodle or will be created shortly. By default, you are enrolled on newly created courses.</p><p>You are enrolled as a teacher on courses and units with a tick next to their name. Untick courses or units for which you no longer wish to participate in. You may re-enrol yourself from this page at a later time if you change your mind.</p>';
$string['noenrolments'] = "Oops... it doesn't look like you have an active timetable for the current academic session. <br><br>Please check your Celcat timetable.<br><br>Only those teaching a unit in the current academic session can use this service.";
$string['nounits'] = "Sorry, you haven't created any units yet. Please use the Unit Creator tab to create units first, then come back.";
$string['save'] = "Save selection";
$string['existingunits'] = "Your Units";
$string['existingcourses'] = "Your Courses";
// $string['vimeoid'] = "214467211";
$string['remediation_help'] = "<p>This unit is part of a Remediation or Infill course framework. There may only be as few as one student enrolled on this unit occurrence.</p><p>Remediation and Infill units are usually timetabled alongside the same unit of a different occurrence. If this is the case, you should not create the Remediation/Infill occurrence independently, and should consider linking it to another unit as a child.</p><p>If you are unsure, please contact the <a href='mailto:moodle@edinburghcollege.ac.uk'><i class='fa fa-envelope'></i> Moodle Helpdesk</a> or visit your nearest <a href='#' target='_blank'>Learning Technology workroom</a>.</p>";

$string['nochanges'] = "<p>You have not made any changes.</p>";
$string['cancel'] = "Cancel";
$string['confirm'] = "Confirm Selection";
$string['checkcodes'] = "<p>You have selected the following changes. Please check unit codes carefully and confirm.</p>";
$string['addedlinks'] = "<h5>Added links</h5>";
$string['units'] = "<p>Units:</p>";
$string['unenrolledfrom'] = "<h5>You will be unenrolled from:</h5>";
$string['reenrolledon'] = "<h5>You will be re-enrolled on:</h5>";
$string['removedlinks'] = "<h5>Removed links</h5>";
$string['tut_title'] = "Moodle Course Manager Tutorial";
$string['close'] = "Close";
$string['tut_watch'] = "<i class='fa fa-play'></i>   Watch Tutorial";
$string['remediation'] = "Remediation & Infill Units";
$string['unitscreated'] = "<p>The following units will be created:</p><ul>";
$string['processing'] = "Processing";
$string['gotit'] = "Got it! <i class='fa fa-thumbs-up'></i>";
$string['courseunit_name'] = "Course/Unit Name";
$string['otherunits_1'] = "Your other units containing ";
$string['otherunits_2'] = " in the title";

$string['nomatch'] = "No matching units";
$string['group'] = "<i class='fa fa-group'></i> Group";
$string['denied'] = "<p>You do not have permission to be here.</p><p>This attempt has been logged and the system administrator has been notified.</p>";

$string['intro1'] = "Hi ";
$string['intro2'] = "The following units and courses are linked directly to your own ";
$string['intro3'] = " timetable. Courses and units in all tabs are unique to you.";

$string['multiple_teachers'] = "Multiple Teacher Alert";
$string['multiple_teachers_help'] = "<p>Please be aware that more than one teacher is timetabled for this unit occurrence.</p>Timetabled teachers:";
$string['orphan_children'] = 'Break metalinks where creator no longer timetabled';
$string['feuding_units'] = 'Remove units which are both created AND child';
$string['useasadmin'] = 'Use as Administrator';
$string['enablevimeo'] = 'Enable Vimeo Tutorial Modal';
$string['vimeoid'] = 'Vimeo video ID number';
$string['settingsheaderdebug'] = 'Scheduled Task Debugging';
$string['debug'] = 'Enable Scheduled Task debugging';
$string['debug_desc'] = 'If debugging is on, the "Feuding Units" and "Orphaned Children" tasks will not make changes to the DB. This will allow admin to verify results by viewing the verbose output, while running manually, but without writing to the DB.';
