<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // Require to use dbDelta
include('settings.php'); // Load the files to get the databse info

// ==============================================================
// install the plugin for the first time
// ==============================================================

// Making sure the table we want to create don't exists
if( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}' ") != $table_name ) {
    
    $sql = "CREATE TABLE " . $table_name . "( 
        id INTEGER(11) UNSIGNED AUTO_INCREMENT,
        page_post_id INTEGER(11),
        notes TEXT,
        PRIMARY KEY (id) 
    ) $charset_collate;";
    
    dbDelta($sql);
    
}  // if( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}' ") != $table_name ) {
