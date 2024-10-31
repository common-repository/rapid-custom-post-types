jQuery(function($) {

    /*-----------------------------------------------------------------------------------*/
    /*	Images TODO: make more abstract..for multiple images
    /*-----------------------------------------------------------------------------------*/
    var file_frame;
    $('.upload_image_button').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            $('.selectedimage').attr('src', attachment.url);
            $('.selectedimagestore').val(attachment.url);
        });

        // Finally, open the modal
        file_frame.open();
    });



    /*-----------------------------------------------------------------------------------*/
    /*	Lists
    /*-----------------------------------------------------------------------------------*/
    //update hidden input from lists

    $("#metaboxesdiv>.innerfields").on('input', function(){
        total = "";
        //loops through listfields and concatinates them with "|"
        $(this).parent().find(".listfield").each(function(){
            total += $(this).val() + "|";
        });
        //sets the hidden field value to the total, and strips off the last "|"
        $(this).parents("#metaboxesdiv").find(".hiddenselect").val(total.substring(0, total.length-1));
    });

    //adding listfields dynamically
    $("#metaboxesdiv>a>.fa-plus-square").on('click', function( event ){
        event.preventDefault();
        $(this).parents("#metaboxesdiv").find(".innerfields").append(
            "<br><br>" +
            "<textarea class='widefat listfield' type='text' size='80'></textarea>" +
            "<a href='#'><i class='fa fa-minus-square'></i></a>"
            );

    });

    //removing listfields dynamically
    $("#metaboxesdiv>.innerfields").on('click', 'a', function( event ){
        event.preventDefault();
        $(this).prev('.listfield').remove();
        $(this).prev('br').remove();
        $(this).prev('br').remove();
        $(this).prev('br').remove();

        total = "";
        //loops through listfields and concatinates them with "|"
        $(this).parent().find(".listfield").each(function(){
            total += $(this).val() + "|";
        });
        $(this).parents("#metaboxesdiv").find(".hiddenselect").val(total.substring(0, total.length-1));

        $(this).remove();

    });


    /*-----------------------------------------------------------------------------------*/
    /*	Selects
    /*-----------------------------------------------------------------------------------*/
    //set all select boxes to their hidden inputs (right after the select items)
    $("#metaboxesdiv>select").each(function(){
        $(this).val($(this).next().val());
    });

    //update hidden inputs based on their respective select boxes when changed
    $('select').on('change', function( event ){
        selected = $("option:selected", this);
        $(this).next().val(selected.text());
    });

    /*-----------------------------------------------------------------------------------*/
    /*	Color Picker
     /*-----------------------------------------------------------------------------------*/
    var myOptions = {
        // you can declare a default color here,
        // or in the data-default-color attribute on the input
        defaultColor: false,
        // a callback to fire whenever the color changes to a valid color
        change: function(event, ui){},
        // a callback to fire when the input is emptied or an invalid color
        clear: function() {},
        // hide the color picker controls on load
        hide: true,
        // show a group of common colors beneath the square
        // or, supply an array of colors to customize further
        palettes: true
    };

    $('.color-field').wpColorPicker(myOptions);

    $('.wp-color-result').on('click', function(event){
        $(this).parents("#metaboxesdiv").find(".hiddenselect").val($(this).parents("#metaboxesdiv").find('.color-field').val());
    });



});



