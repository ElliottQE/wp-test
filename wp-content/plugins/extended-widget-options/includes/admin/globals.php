<?php
/**
 * Add values to global variables
 *
 *
 * @copyright   Copyright (c) 2017, Jeffrey Carandang
 * @since       4.2
 */

if( !function_exists( 'widgetopts_register_globals' ) ){
    add_action( 'init', 'widgetopts_register_globals', 90 );
    function widgetopts_register_globals(){
        global $widget_options, $widgetopts_taxonomies, $widgetopts_types;

        $widgetopts_taxonomies 	= widgetopts_global_taxonomies();
        $widgetopts_types 		= widgetopts_global_types();

        if( !empty( $widget_options['settings']['taxonomies'] ) && is_array( $widget_options['settings']['taxonomies'] ) ){
            $tax_name = array();
            foreach ( $widget_options['settings']['taxonomies'] as $tax_opt => $val ) {
                /*
                 * get terms for each selected Taxonomies
                 * Check for transient. If none, then execute Query
                 */
                $tax_name = 'widgetopts_taxonomy_'. str_replace( '-', '__', $tax_opt );
                // global $$tax_name;
                if ( false === ( $GLOBALS[ $tax_name ] = get_transient( 'widgetopts_taxonomy_'. str_replace( '-', '__', $tax_opt ) ) ) ) {

                    $GLOBALS[ $tax_name ] = get_terms( $tax_opt, array(
                                'hide_empty'    => false
                            ) );

                  // Put the results in a transient. Expire after 4 weeks.
                  set_transient( 'widgetopts_taxonomy_'.  str_replace( '-', '__', $tax_opt ), $GLOBALS[ $tax_name ], 4 * WEEK_IN_SECONDS );
                }
            }
        } //end global variables
    }
}
?>
