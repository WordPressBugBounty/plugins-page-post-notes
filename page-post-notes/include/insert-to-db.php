<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

if( isset($_POST['yydev_notes_nonce']) ) {

    if( wp_verify_nonce($_POST['yydev_notes_nonce'], 'yydev_notes_action') ) {

        include('settings.php');

        // ====================================================
        // Checking if this is a category/tags post or regular page/post
        // ====================================================

        if( isset($_POST['yydev_notes_page_id']) ) {
               $page_id = intval($_POST['yydev_notes_page_id']);
        } // if( isset($_POST['yydev_notes_page_id']) ) {

        // ====================================================
        // Add seo data to the database
        // ====================================================

        if( isset($page_id) ) {

                // Checking if the data id exists
                global $wpdb;
                $check_database_exists = $wpdb->query("SELECT * FROM " . $table_name . " WHERE page_post_id = $page_id ");

                // sanitize the data
                $class_direction = $_POST['yydev_direction_class'];
                $table_notes_post = $_POST['yydev_notes'];

                $notes_count = count($table_notes_post);
                $table_notes_data = '';
                $table_notes_comain = '';

                // converting the data and combine it
                for ($i = 0; $i <= $notes_count; $i++) {

                    if( $table_notes_post[$i] ) {

                        $table_notes_data_note = $table_notes_post[$i];
                        $table_notes_data_direction = $class_direction[$i];
                        $table_notes_comain .= $table_notes_data_note;

                        if( !empty($table_notes_data_note) ) {

                            if($i > 0) {
                                $table_notes_data .= "###";
                            } // if($i > 0) {

                            $table_notes_data .= $table_notes_data_note . "^^" . $table_notes_data_direction;
                        } // if( !empty($table_notes_data_note) ) {

                    } // if( $table_notes_post[$i] ) {

                } // for ($i = 0; $i <= $notes_count; $i++) {

                $table_notes = sanitize_textarea_field($table_notes_data);
                $notes_id = intval($_POST['yydev_notes_id']);

                // checking if the notes are empty or not
                if( empty($table_notes_comain) ) {
                    $there_is_data_here = 1;
                } // if( empty($table_notes_comain) ) {

                // adding the data into the database
                if( $check_database_exists == 0 ) {
                    
                    // if the post doesn't exists in the database we will 
                    // make sure there is data and then we will insert it
                    if( !isset($there_is_data_here) ) {

                        // if the data not exists on the database insert new one
                        $wpdb->insert( $table_name,
                            array('page_post_id'=>$page_id,
                                'notes'=>$table_notes,
                            ), array('%d', '%s') );

                    } // if( !isset($there_is_data_here) ) {

                } else { // if( $check_database_exists == 0 ) {
                
                    // If the current page data exists in the database we will update it
                    if( !isset($there_is_data_here) ) {

                        $wpdb->update( $table_name,
                        array('page_post_id'=>$page_id,
                            'notes'=>$table_notes,
                        ), array('page_post_id'=>$page_id), array('%d', '%s') );

                    } else { // if( !isset($there_is_data_here) ) {
                        
                        // if there are no data at all (in notes and date as well) we will remove the line from the database
                        $wpdb->delete( $table_name, array('id'=>$notes_id) );

                    } // if( !isset($there_is_data_here) ) {

                } // } else { // if($check_database_exists == 0 ) {
            
        } // if( isset($page_id) ) {

    } // if( wp_verify_nonce($_POST['yydev_notes_nonce'], 'yydev_notes_action') ) {

} // if( isset($_POST['yydev_notes_nonce']) ) {

?>