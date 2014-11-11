<?php

/********************************
** adamrob.co.uk - 2OCT2014
** Parallax Scroll Wordpress Plugin
**
** For Help and Support please visit www.adamrob.co.uk
**
** Shortcode
********************************/
/*
    V0.2 - 7OCT2014 - Added Header Style Parameter
    V0.4 - 31OCT2014 - Added disable on mobile parameters. Added full width option
*/

/***
** SHORTCODE
***/

function register_sc_parallax_scroll( $atts ) {

    // Attributes
    extract( shortcode_atts(
        array(
            'id' => '0',
        ), $atts )
    );

    //**
    //SANITIZE ALL SHORTCODE INPUTS

    //Check for valid ID
    $postid = intval($id);
    if ($postid==0 || !is_int($postid)){ 
        //Return error
        return '<p><strong>Invalid Parallax ID</strong></p>';
    }


    //**
    //SETUP WP QUERY TO RETREIVE POST

    //Set up the arguments for query
    $args = array( 'page_id' => $postid,
                    'post_type' => array( PARALLAX_POSTTYPE ) );
    //Look up the header
    $post = new WP_Query( $args );


    //**
    //RETRIEVE THE POST AND DISPLAY

    //Check post exist
    if ( $post->have_posts() ) {
        while ( $post->have_posts() ) {
            $post->the_post();

            //**
            //CHECK AND SANITIZE POST INPUTS

            // check if the post has a Post Thumbnail assigned to it.
            if ( !has_post_thumbnail() ) { 
                //Error because of no image
                wp_reset_postdata();
                return '<p><strong> No Feature Image Defined!</strong></p>';
            }

            //Get thumbnail url
            $thumb_id = get_post_thumbnail_id();
            $thumb_url_array = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);
            $thumb_url = $thumb_url_array[0];

            //Get the height
            $pheight = absint(get_post_meta(get_the_id(), 'parallax_meta_height', true));
            //Check the height
            if ($pheight==0 || $pheight<PARALLAX_MINHEIGHT){
                //Set minimum height
                $pheight=PARALLAX_MINHEIGHT;
            }

            //Get v pos
            $vpos = esc_attr(get_post_meta(get_the_id(), 'parallax_meta_vpos', true));
            //Check for value
            if ($vpos == ''){
                $vpos=PARALLAX_DEFVPOS;
            }

            //Get h pos
            $hpos = esc_attr(get_post_meta(get_the_id(), 'parallax_meta_hpos', true));
            //Check for value
            if ($hpos == ''){
                $hpos=PARALLAX_DEFHPOS;
            }

            //Get parallax disable options
            $disableParImg=esc_attr(get_post_meta(get_the_id(), 'parallax_meta_DisableParImg', true));
            $disablePar=esc_attr(get_post_meta(get_the_id(), 'parallax_meta_DisableParallax', true));

            //Get style
            $hStyle = esc_html(get_post_meta(get_the_id(), 'parallax_meta_hstyle', true));

            //Get post content
            $theContent=apply_filters('the_content',get_the_content());

            //Get the full width option
            $fullWidthEnable=esc_attr(get_post_meta(get_the_id(), 'parallax_meta_FullWidth', true));

            //Get padding
            $padding=20;

            //**
            //FIRST CHECK IF THE USER WANTS TO ENABLE IT
            if ($disablePar && wp_is_mobile()){
                //On a mobile device and user wants to disable
                //return nothing
                return;
            }


            //**
            //INCLUDE EXTERNAL SCRIPTS
            wp_enqueue_style('parallax-CSS');

            if (!$disableParImg || wp_is_mobile()===FALSE){
                //Only include scripts when not on a mobile.
                //or on a mobile and user wants it
                wp_enqueue_script( 'parallax-script' );
            }

            //**Full width options
            $parallaxclass="parallax-window";
            //Check if full width is enabled
            if ($fullWidthEnable){
            	//include full width java script
            	wp_enqueue_script( 'parallax-script-fullwidth' );
            	//include the full width class
            	$parallaxclass = $parallaxclass . " parallax-window-fullwidth";
        	}

            //**
            //BUILD THE HTML

            //Build the style tag for the parallax container
            $parallaxStyle='';
            if ($pheight!==PARALLAX_MINHEIGHT){
                //Use user defined height
                $parallaxStyle='style="height:'.$pheight.'px;"';
            }elseif($pheight!==PARALLAX_MINHEIGHT && $theContent ==""){
                //Define the minimum height if no post content.
                //If there is post content, use min height from css
                $parallaxStyle='style="height:'.PARALLAX_MINHEIGHT.'px;"';
            }


            //Give the entire plugin a container
            //Enables us to pad out when in full screen mode
            $output = '<div class="parallax-window-container">';
            
            //Build the parallax container
            $output = $output . '<div class="'.$parallaxclass.'" '.$parallaxStyle.' data-parallax="scroll" data-image-src="'. $thumb_url .'" data-z-Index="1" data-ios-fix="true" data-android-fix="true">';

            //Build the content if applicable
            if ($theContent ==""){
                //Build the title
                $output = $output . '<table style="width:100%; height:100%;"><tr>';
                $output = $output . '<td class="parallax-header" style="text-align:'.$hpos.'; vertical-align:'.$vpos.'; padding:'.$padding.'px;">';
                $output = $output . '<div style="'.$hStyle.'">' . get_the_title() . '</div>';
                $output = $output . '</td></tr></table>';

            }else{
                //Build the title
                $output = $output . '<div style="text-align:'.$hpos.'; padding:'.$padding.'px;">';
                $output = $output . '<div style="'.$hStyle.'">' . get_the_title() . '</div>';
                $output = $output . '</div>';

                //Build the content
                $output = $output . '<div id="'.get_the_id().'_parallax_content_post" style="padding:'.$padding.'px;">';
                $output = $output . $theContent;
                $output = $output . '</div>';
            }

            //Close the parallax and content container
            $output = $output . '</div></div>';

        }
        //Reset query data
        wp_reset_postdata();
   
    } else {
        // none were found
        wp_reset_postdata();
        return '<p><strong> No Parallax Found! Check ID</strong></p>';
    }

    //Return the result
    return $output;
}

//Add the shortcode trigger
add_shortcode( PARALLAX_SHORTCODE, 'register_sc_parallax_scroll' );




?>