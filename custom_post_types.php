<?php

/*-----------------------------------------------------------------------------------*/
/*	Create Custom Post Type
/*-----------------------------------------------------------------------------------*/
//Actions
add_action( 'init', array('CustomPostTypes','create_custom_post_types' ));
add_action( 'add_meta_boxes', array('CustomPostTypes','add_custom_meta_boxes'), 3);
add_action( 'save_post', array('CustomPostTypes','save_meta'), 99);
//Filters
add_filter( 'enter_title_here', array('CustomPostTypes', 'change_default_title' ));

abstract class CustomPostTypes{

    public static $posttype;

    public static function create_custom_post_types() {

        self::$posttype[0] = array(
            'name'               => 'links',
            'singular_name'      => 'Link',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Link',
            'edit_item'          => 'Edit Link',
            'new_item'           => 'New Link',
            'all_items'          => 'All Links',
            'view_item'          => 'View Links',
            'search_items'       => 'Search Links',
            'not_found'          => 'No Links found',
            'not_found_in_trash' => 'No Links found in Trash',
            'parent_item_colon'  => '',
            'menu_name'          => 'Links',
            'metaboxes'          => array(
                                    array(
                                    'id'        => 'meta-box-id-1',
                                    'title'     => 'Link Info',
                                    'context'   => 'normal',
                                    'priority'  => 'default',
                                    'fields'    =>  array(
                                                    array(
                                                    'name' => 'color',
                                                    'desc' => 'Color',
                                                    'type' => 'colorpicker',
                                                    ),
                                                    array(
                                                    'name' => 'url',
                                                    'desc' => 'URL (Leave empty to show your own content)',
                                                    'type' => 'text',
                                                    ))
                                    ),
                                    )
        );

        //register post types
        if(!empty(self::$posttype)){
            foreach(self::$posttype as $key => $lab){
                $args[$key] = array(
                    'public' => true,
                    'labels' => $lab,
                );
                register_post_type($lab['name'], $args[$key]);
            }
        }
    }

    //change title placeholder text
    //////change title text enter
    public static function change_default_title(){
        $screen = get_current_screen();

        foreach(self::$posttype as $type){
            if  ( $screen->post_type == $type['name'] ) {
                return $type['new_item'];
            }
        }
        return '';
    }

    /*-----------------------------------------------------------------------------------*/
    /*	Add Custom Default Fields to Post Type
    /*-----------------------------------------------------------------------------------*/
    /* Create one or more meta boxes to be displayed on the post editor screen. */
    public static function add_custom_meta_boxes() {
        //Adds boxes
        foreach(self::$posttype as $ar){
            if(get_current_screen()->post_type == $ar['name'] && !empty($ar["metaboxes"])){
                foreach($ar["metaboxes"] as $arr){
                add_meta_box($arr["id"], $arr["title"], array('CustomPostTypes','display_meta_box'), $ar['name'], $arr["context"], $arr["priority"], $arr["fields"]);
                }
            }
        }
    }

    //displays metabox fields
    public static function display_meta_box($post, $metabox) { ?>

        <?php
        global $post;
        wp_nonce_field( basename( __FILE__ ), 'meta_nonce' );
        ?>

        <div id="metaboxesdiv">

            <?php

            foreach($metabox["args"] as $field){
                switch ($field['type']){
                    case ('text'):{ ?>

                        <label for=<?php $field['name'] ?> > <?php echo $field['desc']; ?></label>
                        <br />
                        <input class="widefat" type=<?php echo 'text' ?> name=<?php echo $field['name'] ?> id=<?php echo $field['name'] ?> value="<?php echo get_post_meta( $post->ID, $field['name'], true ) ?>" size="30" />
                        <br />
                        <br />

                    <?php } break;
                    case ('img'):{ ?>
                        <div>
                            <?php
                            $temp = get_post_meta( $post->ID, $field['name'], true );
                            if(empty($temp)){?>

                                <button class="upload_image_button">Select Image</button>
                                <br>
                                <img class="selectedimage" src="<?php echo  get_post_meta($post->ID, $field['name'], true ) ?>"/>
                                <input class="selectedimagestore" id=<?php echo $field['name'] ?> type='hidden' name=<?php echo $field['name'] ?> id=<?php echo $field['name'] ?> value="">

                            <?php }else{ ?>

                                <button class="upload_image_button">Change Image</button>
                                <br>
                                <img class="selectedimage" src="<?php echo get_post_meta($post->ID, $field['name'], true ) ?>"/>
                                <input class="selectedimagestore" id=<?php echo $field['name'] ?> type='hidden' name=<?php echo $field['name'] ?> id=<?php echo $field['name'] ?> value=<?php echo get_post_meta($post->ID, $field['name'], true ) ?>>

                            <?php } ?>

                        </div>


                    <?php } break;
                    case ('list'):{ ?>

                        <a href="#"><i class="fa fa-plus-square"> Add <?php echo $field['desc'] ?></i></a>

                        <?php
                        $arrayfields = explode("|", get_post_meta( $post->ID, $field['name'], true ));

                        ?>
                        <div class="innerfields">
                        <?php
                            foreach($arrayfields as $item){?>
                                <?php if($item!=''){ ?>
                                <br><br>
                                <textarea class="widefat listfield" type='text' size="80" ><?php echo $item ?></textarea>
                                <a href="#"><i class="fa fa-minus-square"></i></a>


                            <?php }} ?>
                        </div>

                        <input class="hiddenselect"  id=<?php echo $field['name'] ?> type='hidden'  name=<?php echo $field['name'] ?> value="<?php echo get_post_meta( $post->ID, $field['name'], true ) ?>" size="80" />

                        <br />

                    <?php } break;
                    case ('select'):{ ?>

                        <label for=<?php $field['name'] ?> > <?php echo $field['desc']; ?></label>
                        <br>

                        <select>
                            <?php foreach($field['options'] as $option) { ?>
                                <option value="<?php echo $option ?>"><?php echo $option ?></option>
                            <?php } ?>
                        </select>
                        <input class="hiddenselect" id=<?php echo $field['name'] ?> type='hidden'  name=<?php echo $field['name'] ?> value="<?php echo get_post_meta( $post->ID, $field['name'], true ) ?>" size="80" />
                        <br>
                        <br>

                    <?php } break;

                        case ('colorpicker'):{ ?>

                        <label for=<?php $field['name'] ?> > <?php echo $field['desc']; ?></label>
                        <br>

                        <input type="text" value="<?php echo get_post_meta($post->ID, $field['name'], true ) ?>" class="color-field" />

                        <input class="hiddenselect" id=<?php echo $field['name'] ?> type='hidden'  name=<?php echo $field['name'] ?> value="<?php echo get_post_meta( $post->ID, $field['name'], true ) ?>" size="80" />
                        <br>
                        <br>

                    <?php } break; ?>


                    <?php } ?>

            <?php } ?>

        </div>

    <?php

    }


    /* Save the meta box's post metadata. */
    public static function save_meta($post_id) {

        //filter unwanted postvalues from being saved
        $meta_post_array = array();
        foreach(self::$posttype as $type){
            if(!empty($type["metaboxes"])){
                foreach($type["metaboxes"] as $boxes){
                    foreach($boxes["fields"] as $fields){
                        array_push($meta_post_array, $fields['name']);
                    }
                }
            }
        }

        /* Verify the nonce before proceeding. */
        if ( !isset( $_POST['meta_nonce'] ) || !wp_verify_nonce( $_POST['meta_nonce'], basename( __FILE__ ) ) )
            return $post_id;

        /* Check if the current user has permission to edit the post. */
        if ( !current_user_can( 'edit_post', $post_id ) )
            return $post_id;


        /* Get the posted data */
        foreach($_POST as $field => $key){

            if(in_array($field,$meta_post_array)){


                $new_meta_value = $key;

                /* Get the meta key. */
                $meta_key = $field;

                /* Get the meta value of the custom field key. */
                $meta_value = get_post_meta( $post_id, $meta_key, true );

                /* If a new meta value was added and there was no previous value, add it. */
                if ( $new_meta_value && '' == $meta_value )
                    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

                /* If the new meta value does not match the old value, update it. */
                elseif ( $new_meta_value && $new_meta_value != $meta_value )
                    update_post_meta( $post_id, $meta_key, $new_meta_value );

                /* If there is no new meta value but an old value exists, delete it. */
                elseif ( '' == $new_meta_value && $meta_value )
                    delete_post_meta( $post_id, $meta_key, $meta_value );
            }
        }
    }


}





