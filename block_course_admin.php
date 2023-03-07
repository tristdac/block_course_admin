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


class block_course_admin extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_course_admin');
    }

    function get_content() {
        global $CFG, $OUTPUT, $DB, $USER, $PAGE;
		if ( (((!strstr($USER->username, 'ec1')) && (!strstr($USER->username, 'ec2'))) && (strstr($USER->email, '@edinburghcollege.ac.uk'))) || (is_siteadmin() || has_capability('block/course_admin:useasadmin', context_system::instance())) ) {
			if ($this->content !== null) {
				return $this->content;
			}

			if (empty($this->instance)) {
				$this->content = '';
				return $this->content;
			}

			$this->content = new stdClass();
			$this->content->items = array();
			$this->content->icons = array();
			$this->content->text = '';
			$this->content->footer = '';

			// user/index.php expect course context, so get one if page has module context.
			$currentcontext = $this->page->context->get_course_context(false);
			$this->content->text = '';
			if (! empty($this->config->text)) {
				$this->content->text = $this->config->text;
			}


			$this->content->text .= "<a href='".$CFG->wwwroot."/blocks/course_admin/index.php'><div id='ca_link'><i class='fa fa-database fa-4x'></i><h4>". get_string('pluginname', 'block_course_admin')."</h4><p>". get_string('plugindesc', 'block_course_admin')."</p></div></a>";


			if (! empty($this->config->text)) {
				$this->content->text .= $this->config->text;
			}

			return $this->content;
		}
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('site-index' => true,
					 'my' => true);
    }

    public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return true;}

    public function cron() {
            mtrace( "Hey, my cron script is running" );
             
                 // do something
                  
                      return true;
    }
}
