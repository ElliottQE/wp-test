<?php

/*
* Add your own functions here. You can also copy some of the theme functions into this file. 
* Wordpress will use those functions instead of the original functions then.
*/

//Adds custom css fields to all Avia builder elements
add_theme_support('avia_template_builder_custom_css');
//Removes LayerSlider
add_theme_support('deactivate_layerslider');
//Removes Dummy Data Importer
add_theme_support('avia_disable_dummy_import');


function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function intro_shortcode( $atts, $content = null ) {
return '<p class="intro-text">' . $content . '</p>';
}
add_shortcode( 'intro', 'intro_shortcode' );

add_filter('avf_default_icons','avia_replace_standard_icon', 10, 1);
function avia_replace_standard_icon($icons) {
    $icons['mobile_menu']   = array( 'font' =>'entypo-fontello', 'icon' => 'ue811');
    return $icons;
}

add_action('init','avia_remove_debug');
function avia_remove_debug(){
	remove_action('wp_head','avia_debugging_info',1000);
	remove_action('admin_print_scripts','avia_debugging_info',1000);
}