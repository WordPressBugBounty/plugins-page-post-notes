<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

    include('settings.php');

    // ============================================================
    // checking if the data exists on the database
    // ============================================================
    global $wpdb;

    // -----------------------------------------------
    // incase this is a regular page or a blog post
    // -----------------------------------------------
    $this_post_id = intval( get_the_ID() );

    if( isset($yydev_notes_active_dashbaord) && ($yydev_notes_active_dashbaord == 1) ) {
        $this_post_id = 0;
    } // if( isset($yydev_notes_active_dashbaord) && ($yydev_notes_active_dashbaord == 1) ) {

    $notes_id = '';
    $notes_message = '';
    $button_text = '+ Add Note';
    $button_class = '';
    $hide_notes = 'not-active-notes';
    
    // setting the direction class from the theme direction
    if( is_rtl() ) {
        $table_direction_class = "direction-rtl";
    } else { // if( is_rtl() ) {
        $table_direction_class = "direction-ltr";
    } // } else { // if( is_rtl() ) {


    if( isset($this_post_id) ) {
    
        $yydevl_notes_data = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE page_post_id  = $this_post_id ");

        if( $wpdb->num_rows > 0 ) {

            $notes_id = $yydevl_notes_data->id;

            // checking if this is an array and if not conver it to array
            if( strstr($yydevl_notes_data->notes, '###') ) {
            
                $yydevl_notes_data_output = explode('###', $yydevl_notes_data->notes);            

            } else { // if( strstr($yydevl_notes_data->notes, '###') ) {

                $yydevl_notes_data_output = array($yydevl_notes_data->notes);

            } // } else { // if( strstr($yydevl_notes_data->notes, '###') ) { 

        } // if( $wpdb->num_rows > 0 ) {


    } // if( !empty($this_post_id) ) {

    if(!empty($text_direction)) {
        $table_direction_class = $text_direction;
    } // if(!empty($text_direction)) {

?>

<div class="yydev-notes">

<?php 

if( isset($yydevl_notes_data_output) ) { 

// ============================================================
// If there are notes on the database
// ============================================================

        foreach($yydevl_notes_data_output as $notes_data_output) {

            $yydevl_notes_data = explode("^^", $notes_data_output);
            

            $notes_message = $yydevl_notes_data[0];
            $table_direction_class = $yydevl_notes_data[1];

            $hide_notes = '';

            $button_text = '+ Add Another Note';
            $button_class = 'yy-another-notes';

?>

        <div class="yydev-textarea-note <?php echo esc_attr($hide_notes); ?>">
            <textarea cols="150" rows="8" class="table_notes <?php echo esc_attr($table_direction_class); ?>" name="yydev_notes[]" ><?php echo yydev_notes_html_output( esc_textarea($notes_message) ); ?></textarea>
            <a class='remove-notes-btn' href='#'><img  src='<?php echo esc_url( plugins_url( 'images/remove.png', dirname(__FILE__) ) ); ?>' alt='' title='Remove Notes' /></a>
            <a class='text-direction_btn' href='#'><img  src='<?php echo esc_url( plugins_url( 'images/text-direction.png', dirname(__FILE__) ) ); ?>' alt='' title='Test Direction' /></a>
            <a class='text-separator_btn' href='#'><img  src='<?php echo esc_url( plugins_url( 'images/separator.png', dirname(__FILE__) ) ); ?>' alt='' title='Separator' /></a>
            <input type="hidden" name="yydev_direction_class[]" class="yydev_direction_class" value="<?php echo esc_attr($table_direction_class); ?>" />
            <input type="hidden" name="yydev_notes_page_id" value="<?php echo intval($this_post_id); ?>" />
            <input type="hidden" name="yydev_notes_id" value="<?php echo intval($notes_id); ?>" />
        </div><!--add-table-notes-->

<?php  

        } // foreach($yydevl_notes_data_output as $notes_data_output) {

} else { // if( isset($yydevl_notes_data_output) ) { 

// ============================================================
// If there are no notes on the database
// ============================================================

?>
    <div class="yydev-textarea-note <?php echo esc_attr($hide_notes); ?>">
        <textarea cols="150" rows="8" class="table_notes <?php echo esc_attr($table_direction_class); ?>" name="yydev_notes[]" ><?php echo yydev_notes_html_output( esc_textarea($notes_message) ); ?></textarea>
        <a class='remove-notes-btn' href='#'><img  src='<?php echo esc_url( plugins_url( 'images/remove.png', dirname(__FILE__) ) ); ?>' alt='' title='Remove Notes' /></a>
        <a class='text-direction_btn' href='#'><img  src='<?php echo esc_url( plugins_url( 'images/text-direction.png', dirname(__FILE__) ) ); ?>' alt='' title='Remove Notes' /></a>
        <a class='text-separator_btn' href='#'><img  src='<?php echo esc_url( plugins_url( 'images/separator.png', dirname(__FILE__) ) ); ?>' alt='' title='Separator' /></a>
        <input type="hidden" name="yydev_direction_class[]" class="yydev_direction_class" value="<?php echo esc_attr($table_direction_class); ?>" />
        <input type="hidden" name="yydev_notes_page_id" value="<?php echo intval($this_post_id); ?>" />
        <input type="hidden" name="yydev_notes_id" value="<?php echo intval($notes_id); ?>" />
    </div><!--add-table-notes-->

<?php 

} // } else { // if( isset($yydevl_notes_data_output) ) { 

?>

        <?php
            // creating nonce to make sure the form was submitted correctly from the right page
            wp_nonce_field( 'yydev_notes_action', 'yydev_notes_nonce' ); 
        ?>

    <a href="#" class="direction-ltr add-notes-button <?php echo $button_class; ?>"><?php echo $button_text; ?></a>

<?php

    // ============================================================
    // Echo save button on dashboard
    // ============================================================
    
    $save_data_button_class = 'yydev-notes-hide-button';
    if( isset($yydev_notes_active_dashbaord) && ($yydev_notes_active_dashbaord == 1) ) {
        $save_data_button_class = '';
    } // if( isset($yydev_notes_active_dashbaord) && ($yydev_notes_active_dashbaord == 1) ) {

?>

    <a href="#" class="dashbaord-save-button <?php echo $save_data_button_class; ?>">Save Notes Data</a>
    <div class='yydev-notes-ajax-data'></div>

</div><!--yydev-notes-->