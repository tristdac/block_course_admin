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
 * COURSE ADMIN
 *
 * @package    block_course_admin
 * @copyright  Tristan daCosta <tristan.dacosta@edinburghcollege.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


// Add Database connection options
	$settings->add(new admin_setting_heading('block_course_admin_exdbheader', 					get_string('settingsheaderdb', 'block_course_admin'), ''));

	$settings->add(new admin_setting_configtext('block_course_admin/source', 					get_string('sourcecourse', 'block_course_admin'), get_string('sourcecourse_desc', 'block_course_admin'), ''));

	$settings->add(new admin_setting_heading('block_course_admin_otherheader', 					'Other Settings', ''));

	$settings->add(new admin_setting_configcheckbox('block_course_admin/enablevimeo', 					get_string('enablevimeo', 'block_course_admin'), '', ''));

	$settings->add(new admin_setting_configtext('block_course_admin/vimeoid', 					get_string('vimeoid', 'block_course_admin'), '', ''));

	$settings->add(new admin_setting_heading('block_course_admin_debugging', 					get_string('settingsheaderdebug', 'block_course_admin'), ''));

	$settings->add(new admin_setting_configcheckbox('block_course_admin/debug', 					get_string('debug', 'block_course_admin'), get_string('debug_desc', 'block_course_admin'), ''));