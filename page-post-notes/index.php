<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/*
Plugin Name: YYDevelopment - Page & Post Notes
Plugin URI:  https://www.yydevelopment.com/yydevelopment-wordpress-plugins/
Description: Simple plugin that allow you to notes on pages and posts
Version:     1.3.5
Author:      YYDevelopment
Author URI:  https://www.yydevelopment.com/
*/

include_once('include/settings.php');
require_once('include/functions.php');

// ================================================
// Creating Database when the plugin is activated
// ================================================

function yydev_notes_create_database() {
    
    require_once('include/install.php');
        
} // function yydev_notes_create_database() {

register_activation_hook(__FILE__, 'yydev_notes_create_database');  // run on plugin update

// ================================================
// display the plugin we have create on the wordpress
// post blog and pages
// ================================================

// function that will output the code to the page
function yydev_notes_output_plugin($term) {

    include('include/style.php');
    include('include/script.php');
    include('include/admin-output.php');

} // function yydev_notes_output_plugin() {

function yydev_notes_register_meta_boxes() {
    add_meta_box( 'yydev_notes', 'Page Notes', 'yydev_notes_output_plugin',null ,'side');
} // function yydev_notes_register_meta_boxes() {

add_action( 'add_meta_boxes', 'yydev_notes_register_meta_boxes' );

// ================================================
// Adding the comments to the dashboard page
// ================================================

// function that will output the code to the page
function yydev_notes_output_plugin_dashbaord($term) {

    $yydev_notes_active_dashbaord = 1;

    include('include/style.php');
    include('include/script.php');
    include('include/admin-output.php');

} // function yydev_notes_output_plugin() {
  
function yydev_notes_dashboard_widgets() {
    wp_add_dashboard_widget('yydevelopment_notes', 'Dashboard Notes', 'yydev_notes_output_plugin_dashbaord');
} // function yydev_notes_dashboard_widgets() {
 
add_action('wp_dashboard_setup', 'yydev_notes_dashboard_widgets');

// ================================================
// function that will insert the code to the database
// once the post or page is updated
// ================================================

function yydev_notes_insert_to_database( $post_id ) {

    // Check if user has capability to edit this specific post
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Verify nonce
    if ( ! isset( $_POST['yydev_notes_nonce'] ) || ! wp_verify_nonce( $_POST['yydev_notes_nonce'], 'yydev_notes_action' ) ) {
        return;
    }

    include('include/insert-to-db.php');

} // function yydev_notes_insert_to_database() {

add_action('pre_post_update', 'yydev_notes_insert_to_database');

// ================================================
// ajax function to save the data in the database
// ================================================

function yydev_notes_save_dashboard_data() {

    // Check if user has capability to edit posts/pages
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( array( 'message' => 'You do not have permission to perform this action.' ) );
        wp_die();
    }

    // Verify nonce for CSRF protection
    if ( ! isset( $_POST['yydev_notes_nonce'] ) || ! wp_verify_nonce( $_POST['yydev_notes_nonce'], 'yydev_notes_action' ) ) {
        wp_send_json_error( array( 'message' => 'Security check failed.' ) );
        wp_die();
    }

    // If this is for a specific page/post (not dashboard), verify user can edit that specific post
    if ( isset( $_POST['yydev_notes_page_id'] ) && intval( $_POST['yydev_notes_page_id'] ) > 0 ) {
        $page_id = intval( $_POST['yydev_notes_page_id'] );
        
        // Check if user can edit this specific post/page
        if ( ! current_user_can( 'edit_post', $page_id ) ) {
            wp_send_json_error( array( 'message' => 'You do not have permission to edit notes for this page.' ) );
            wp_die();
        }
    }

    include('include/insert-to-db.php');
    echo "Saved";
    wp_die();

} // function yydev_notes_save_dashboard_data() {

add_action( 'wp_ajax_yydev_notes_save_dashboard_data', 'yydev_notes_save_dashboard_data' ); // create ajax function we can call with javascript
// Removed nopriv action - only logged in users with proper capabilities should access this
// add_action( 'wp_ajax_nopriv_yydev_notes_save_dashboard_data', 'yydev_notes_save_dashboard_data' );

// ================================================
// Add donate page to the plugin menu info
// ================================================

add_filter( 'plugin_action_links', function($actions, $plugin_file) {

	static $plugin;

    if (!isset($plugin)) { $plugin = plugin_basename(__FILE__); }
    
	if ($plugin == $plugin_file) {

            $admin_page_url = esc_url( menu_page_url( 'page-post-notes', false ) );
            $donate = array('donate' => '<a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=page-post-notes">Donate</a>');
		
            $actions = array_merge($donate, $actions);
        
    } // if ($plugin == $plugin_file) {
		
    return $actions;

}, 10, 5 );

// ================================================
// including admin notices flie
// ================================================

if( is_admin() ) {
	include_once('notices.php');
} // if( is_admin() ) {