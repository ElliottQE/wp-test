<?php
/**
 * Scripts
 *
 * @copyright   Copyright (c) 2017, Jeffrey Carandang
 * @since       4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load Scripts
 *
 * Enqueues the required scripts.
 *
 * @since 4.1
 * @global $widget_options
 * @return void
 */

function widgetopts_load_scripts(){
      global $widget_options, $pagenow;

      $js_dir  = WIDGETOPTS_PLUGIN_URL . 'assets/js/';
	$css_dir = WIDGETOPTS_PLUGIN_URL . 'assets/css/';

      // Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

      wp_enqueue_style( 'widgetopts-styles', $css_dir . 'widget-options.css' , array(), null );

      //do not load when checked on settings page
      if( 'activate' == $widget_options['animation'] ):
            if( !isset( $widget_options['settings']['animation'] ) || ( isset( $widget_options['settings']['animation'] ) && !isset( $widget_options['settings']['animation']['css'] ) ) ){
                  wp_enqueue_style( 'css-animate', $css_dir . 'animate.min.css' , array(), null );
            }
      endif;

      wp_enqueue_script(
            'jquery-widgetopts',
            $js_dir .'jquery.widgetopts'. $suffix .'.js',
            array( 'jquery' ),
            '',
            true
      );

      //add localize variables to be called on jquery.widgetopts.js
      $localized = array(
            'shallNotFixed'     => ( isset( $widget_options['settings']['fixed'] ) && isset( $widget_options['settings']['fixed']['stop'] ) )               ? $widget_options['settings']['fixed']['stop']                          : '',
            'margin_top'        => ( isset( $widget_options['settings']['fixed'] ) && isset( $widget_options['settings']['fixed']['margin_top'] ) )         ? intval( $widget_options['settings']['fixed']['margin_top'] )          : 0,
            'disable_width'     => ( isset( $widget_options['settings']['fixed'] ) && isset( $widget_options['settings']['fixed']['disable_width'] ) )      ? intval( $widget_options['settings']['fixed']['disable_width'] )       : 0,
            'disable_height'    => ( isset( $widget_options['settings']['fixed'] ) && isset( $widget_options['settings']['fixed']['disable_height'] ) )     ? intval( $widget_options['settings']['fixed']['disable_height'] )      : 0
      );
      wp_localize_script( 'jquery-widgetopts', 'varWidgetOpts', $localized );
}
add_action( 'wp_enqueue_scripts', 'widgetopts_load_scripts' );
add_action( 'customize_controls_enqueue_scripts', 'widgetopts_load_scripts' );

/**
 * Load Admin Scripts
 *
 * Enqueues the required admin scripts.
 *
 * @since 5.0
 * @global $widget_options
 * @param string $hook Page hook
 * @return void
 */
function widgetopts_load_admin_scripts( $hook ) {
      // if( !in_array( $hook, apply_filters( 'widgetopts_load_admin_scripts', array( 'settings_page_widgetopts_plugin_settings', 'widgets.php' ) ) ) ){
      //       return false;
      // }

      $js_dir  = WIDGETOPTS_PLUGIN_URL . 'assets/js/';
	$css_dir = WIDGETOPTS_PLUGIN_URL . 'assets/css/';

      // Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

      wp_enqueue_style( 'widgetopts-admin-styles', $css_dir . 'admin.css' , array(), null );

      // if( in_array( $hook, apply_filters( 'widgetopts_load_option-tabs_scripts', array( 'widgets.php' ) ) ) ){
            wp_enqueue_media();

            if( !in_array( $hook, apply_filters( 'widgetopts_exclude_jqueryui', array( 'toplevel_page_et_divi_options' ) ) ) ){
                  wp_enqueue_style( 'widgetopts-jquery-ui', $css_dir . 'jqueryui/1.11.4/themes/ui-lightness/jquery-ui.css' , array(), null );
                  wp_enqueue_style( 'jquery-ui' );
            }

            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script('wp-color-picker');

            if( in_array( $hook, apply_filters( 'widgetopts_load_liveFilter_scripts', array( 'widgets.php' ) ) ) ){
                  wp_enqueue_script(
                       'jquery-liveFilter',
                       plugins_url( 'assets/js/jquery.liveFilter.js' , dirname(__FILE__) ),
                       array( 'jquery' ),
                       '',
                       true
                  );
            }

            wp_enqueue_script(
                 'jquery-widgetopts-option-tabs',
                 plugins_url( 'assets/js/widgets.js' , dirname(__FILE__) ),
                 array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-datepicker'),
                 '',
                 true
            );

            $form = '<div id="widgetopts-widgets-chooser">
              	<label class="screen-reader-text" for="widgetopts-search-chooser">'. __( 'Search Sidebar', 'widget-options' ) .'</label>
              	<input type="text" id="widgetopts-search-chooser" class="widgetopts-widgets-search" placeholder="'. __( 'Search sidebar&hellip;', 'widget-options' ) .'" />
                  <div class="widgetopts-search-icon" aria-hidden="true"></div>
                  <button type="button" class="widgetopts-clear-results"><span class="screen-reader-text">'. __( 'Clear Results', 'widget-options' ) .'</span></button>
                  <p class="screen-reader-text" id="widgetopts-chooser-desc">'. __( 'The search results will be updated as you type.', 'widget-options' ) .'</p>
              </div>';

            wp_localize_script( 'jquery-widgetopts-option-tabs', 'widgetopts10n', array( 'opts_page' => esc_url( admin_url( 'options-general.php?page=widgetopts_plugin_settings' ) ), 'search_form' => $form, 'translation' => array( 'manage_settings' => __( 'Manage Widget Options', 'widget-options' ) )) );


      // }

      if( in_array( $hook, apply_filters( 'widgetopts_load_settings_scripts', array( 'settings_page_widgetopts_plugin_settings' ) ) ) ){
            wp_register_script(
                  'jquery-widgetopts-settings',
                  $js_dir .'settings'. $suffix .'.js',
                  array( 'jquery' ),
                  '',
                  true
            );

            $translation = array(
                  'save_settings'         => __( 'Save Settings', 'widget-options' ),
                  'close_settings'        => __( 'Close', 'widget-options' ),
                  'show_settings'         => __( 'Configure Settings', 'widget-options' ),
                  'hide_settings'         => __( 'Hide Settings', 'widget-options' ),
                  'show_description'      => __( 'Learn More', 'widget-options' ),
                  'hide_description'      => __( 'Hide Details', 'widget-options' ),
                  'show_information'      => __( 'Show Details', 'widget-options' ),
                  'activate'              => __( 'Enable', 'widget-options' ),
                  'deactivate'            => __( 'Disable', 'widget-options' ),
                  'successful_save'       => __( 'Settings saved successfully for %1$s.', 'widget-options' ),
                  'deactivate_btn'        => __( 'Deactivate License', 'widget-options' ),
                  'activate_btn'          => __( 'Activate License', 'widget-options' ),
                  'status_valid' 		=> __( 'Valid', 'widget-options' ),
                  'status_invalid'        => __( 'Invalid', 'widget-options' ),
            );

            wp_enqueue_script( 'jquery-widgetopts-settings' );
            wp_localize_script( 'jquery-widgetopts-settings', 'widgetopts', array( 'translation' => $translation, 'ajax_action' => 'widgetopts_ajax_settings', 'ajax_nonce' => wp_create_nonce( 'widgetopts-settings-nonce' ), ) );
      }
}
add_action( 'admin_enqueue_scripts', 'widgetopts_load_admin_scripts', 100 );

/*
* Hide Options to Other Widgets
*/
function widgetopts_print_style(){
  global $widget_options, $current_user;

  if( 'activate' == $widget_options['permission'] ){
      $current_user->role = (isset( $current_user->caps ) && !empty( $current_user->caps )) ? array_keys( $current_user->caps ) : array();

      if( !empty( $current_user->role ) ):
      ?>
      <style type="text/css">
      <?php
          if( isset( $widget_options['settings']['permission'] ) && isset( $widget_options['settings']['permission'][ $current_user->role[0] ] ) ){
              if( '1' == $widget_options['settings']['permission'][ $current_user->role[0] ] ){
                  echo '.extended-widget-opts-form{ display: none !important; }';
              }
          }
      ?>
      </style>
      <?php
      endif;
  }
}
add_action('admin_print_styles-widgets.php', 'widgetopts_print_style' );

?>
