<?php
 
function xmldb_block_course_admin_upgrade($oldversion) {
    global $CFG, $DB;
 	$dbman = $DB->get_manager();
    $result = TRUE;
 
if ($oldversion < 2017051600) {

	// Changing type of field timestamp on table block_course_admin_log to text.
	$table = new xmldb_table('block_course_admin_log');
	$field = new xmldb_field('timestamp', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'metachild');

	// Launch change of type for field timestamp.
	$dbman->change_field_type($table, $field);

	// Course_admin savepoint reached.
	upgrade_block_savepoint(true, XXXXXXXXXX, 'course_admin');
} 
	
if ($oldversion < 2017042803) {

	// Define field id to be added to block_course_admin_log.
	$table = new xmldb_table('block_course_admin_log');
	$field = new xmldb_field('timestamp', XMLDB_TYPE_CHAR, '24', null, XMLDB_NOTNULL, null, null, 'metachild');

	// Conditionally launch add field id.
	if (!$dbman->field_exists($table, $field)) {
		$dbman->add_field($table, $field);
	}

	// Course_admin savepoint reached.
	upgrade_block_savepoint(true, 2017042803, 'course_admin');
}
	
	if ($oldversion < 2017042405) {

        // Define table block_course_admin_courses to be created.
        $table = new xmldb_table('block_course_admin_courses');

        // Adding fields to table block_course_admin_courses.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '15', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_course_admin_courses.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
	 	$table->add_key('unique', XMLDB_KEY_UNIQUE, array('courseid'));

        // Conditionally launch create table for block_course_admin_courses.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
	 
	 	// Define table block_course_admin_unenrol to be created.
        $table = new xmldb_table('block_course_admin_unenrol');

        // Adding fields to table block_course_admin_unenrol.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '15', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_course_admin_unenrol.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_course_admin_unenrol.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
	 
	 	// Define table block_course_admin_meta to be created.
        $table = new xmldb_table('block_course_admin_meta');

        // Adding fields to table block_course_admin_meta.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('parentid', XMLDB_TYPE_INTEGER, '15', null, XMLDB_NOTNULL, null, null);
        $table->add_field('childid', XMLDB_TYPE_INTEGER, '15', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_course_admin_meta.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
	 	$table->add_key('unique', XMLDB_KEY_UNIQUE, array('parentid', 'childid'));

        // Conditionally launch create table for block_course_admin_meta.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

	 	// Define table block_course_admin_meta to be created.
        $table = new xmldb_table('block_course_admin_log');

        // Adding fields to table block_course_admin_meta.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('username', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '15', null, XMLDB_NOTNULL, null, null);
	 	$table->add_field('action', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('context', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
	 	$table->add_field('course1', XMLDB_TYPE_CHAR, '1024', null, XMLDB_NOTNULL, null, null);
        $table->add_field('metachild', XMLDB_TYPE_CHAR, '1024', null, null, null, null);

        // Adding keys to table block_course_admin_meta.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_course_admin_meta.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

	 
        // Course_admin savepoint reached.
        upgrade_block_savepoint(true, 2017042405, 'course_admin');
    }
	
    if ($oldversion < 2017041505) {

    	// Define key unique (unique) to be dropped form block_course_admin_unenrol.
    	$table = new xmldb_table('block_course_admin_unenrol');
    	$key = new xmldb_key('unique', XMLDB_KEY_UNIQUE, array('userid', 'courseid'));

    	// Launch drop key unique.
    	$dbman->drop_key($table, $key);

    	// Course_admin savepoint reached.
    	upgrade_block_savepoint(true, 2017041505, 'course_admin');
    }

    if ($oldversion < 2019013101) {

        // Define field id to be added to block_course_admin_log.
        $table = new xmldb_table('block_course_admin_courses');
        $field = new xmldb_field('template', XMLDB_TYPE_CHAR, '15', null, XMLDB_NOTNULL, null, null, 'courseid');

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Course_admin savepoint reached.
        upgrade_block_savepoint(true, 2019013101, 'course_admin');
    }

 
    return $result;
}

?>