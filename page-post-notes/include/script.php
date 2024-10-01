<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<script>

jQuery(document).ready(function($){

    // dealing with adding notes when clicking on the button
    $(document).on('click', '.yydev-notes a.add-notes-button', function () {

        if( !$(this).hasClass('yy-another-notes') ) {

            $('.yydev-notes .add-notes-button').html('+ Add Another Note');
            $('.yydev-notes .add-notes-button').addClass('yy-another-notes');
            $('.yydev-notes .yydev-textarea-note').removeClass('not-active-notes');
            $('.yydev-notes .table_notes').focus();

        } // if( !$(this).hasClass('yy-another-notes') ) {

        return false;

    }); // $(document).on('click', 'a.move-keyword-up', function () {


    // dealing with creating another notes box
    $(document).on('click', '.yy-another-notes', function () {

        var lastYYnotes = $('.yydev-textarea-note').last();
        lastYYnotes.clone().insertAfter(lastYYnotes);
        $(".yydev-textarea-note").last().find('.table_notes').val('');

        return false;

    }); // $(document).on('click', 'a.move-keyword-up', function () {


    // delating with adding text separator notes
    $(document).on('click', '.yydev-notes a.text-separator_btn', function () {

        // function that helps add a line on the textarea 
        // **textareaClass**.yydevSeoAddSeparator('startCode', endCode)
        jQuery.fn.extend({
            yydevNotesAddSeparator: function(myValue, myValueE) {

                return this.each(function(i) {

                            //For browsers like Firefox and Webkit based
                            var startPos = this.selectionStart;
                            var endPos = this.selectionEnd;
                            var scrollTop = this.scrollTop;
                            this.value = this.value.substring(0,startPos)+myValue+this.value.substring(startPos,endPos)+myValueE+this.value.substring(endPos,this.value.length);
                            this.focus();
                            this.selectionStart = startPos + myValue.length;
                            this.selectionEnd = ((startPos + myValue.length) + this.value.substring(startPos,endPos).length);
                            this.scrollTop = scrollTop;

                    })

            } // return this.each(function(i) {
        }); // jQuery.fn.extend({

        var currentTextArea = $(this).parent().find(".table_notes");
        currentTextArea.yydevNotesAddSeparator("\n" + "--------------------------" + "\n", '');
        
        return false;

    }); // $(document).on('click', '.yydev-notes a.retext-separator_btn', function () {


    // delating with removing notees
    $(document).on('click', '.yydev-notes a.remove-notes-btn', function () {

            if ( confirm("Are you sure you want to permanently remove the page note?") ) {

                if( $('.yydev-textarea-note').length > 1 ) {

                    // if there is more than one note remove it
                    $(this).parent().remove();

                } else { // if( $('.yydev-textarea-note').length > 1 ) {

                    // if there is only one note hide it
                    $(this).parent().addClass('not-active-notes');
                    $(this).parent().find('.table_notes').val('');

                    $('.add-notes-button').removeClass('not-active-notes');
                    $('.yydev-notes .add-notes-button').html('+ Add Page Note');
                    $('.yydev-notes .add-notes-button').removeClass('yy-another-notes');
  
                } // if( $('.yydev-textarea-note').length > 1 ) {         
            
            } // if ( confirm("Are you sure you want to remove the page note?") ) {

            return false;

    }); // $(document).on('click', 'a.move-keyword-up', function () {


    // delating with text direction
    $(document).on('click', '.yydev-notes a.text-direction_btn', function () {

        var notesDirections = $(this).parent().find('.table_notes');

        if( notesDirections.hasClass('direction-rtl') ) {

            notesDirections.removeClass('direction-rtl');
            notesDirections.addClass('direction-ltr');
            $(this).parent().find('.yydev_direction_class').val('direction-ltr');
            $(this).parent().find('.table_notes').focus();

        } else { // if( notesDirections.hasClass('direction-rtl') ) {

            notesDirections.removeClass('direction-ltr');
            notesDirections.addClass('direction-rtl');
            $(this).parent().find('.yydev_direction_class').val('direction-rtl');
            $(this).parent().find('.table_notes').focus();

        } // } else { // if( notesDirections.hasClass('direction-rtl') ) {

        return false; 

    }); // $(document).on('click', '.yydev-notes a.text-direction_btn', function () {


    // ====================================================
    // saving data with ajax when save notes on dashboard
    // ====================================================

    $(document).on('click', '.yydev-notes a.dashbaord-save-button', function () {

        $('.yydev-notes .yydev-notes-ajax-data').html('').css('display', 'block');

        var data = {
            'action': 'yydev_notes_save_dashboard_data',
            'yydev_direction_class': $(".yydev-notes [name='yydev_direction_class[]']").map(function(){return $(this).val();}).get(),
            'yydev_notes': $(".yydev-notes [name='yydev_notes[]']").map(function(){return $(this).val();}).get(),
            'yydev_notes_id': $(".yydev-notes [name='yydev_notes_id']").val(),
            'yydev_notes_nonce': $(".yydev-notes [name='yydev_notes_nonce']").val(),
            'yydev_notes_page_id': $(".yydev-notes [name='yydev_notes_page_id']").val()
        };

        jQuery.ajax({ url: '<?php echo admin_url('admin-ajax.php'); ?>', type: 'POST', data: data,
			beforeSent: $('.yydev-notes a.dashbaord-save-button').addClass('dashbaord-save-button-ajax'),
            success: function (response) {
                $('.yydev-notes a.dashbaord-save-button').removeClass('dashbaord-save-button-ajax')
                $('.yydev-notes .yydev-notes-ajax-data').html(response).delay(1000).fadeOut();
            } //success: function (response) {
        });


        return false;

    }); // $(".yydev-notes a.dashbaord-save-button").live('click', function() {


}); // jQuery(document).ready(function($){

</script>