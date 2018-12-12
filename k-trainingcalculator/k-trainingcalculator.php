<?php 
    /*
    Plugin Name: K-Training Calculator
    Description: 
    Author: K. Espaldon
    Version: 1.0	
    */
define( 'REGISTRATIONS_PLUGIN', __FILE__ );
define( 'REGISTRATIONS_PLUGIN_DIR', untrailingslashit( dirname( REGISTRATIONS_PLUGIN ) ) );

require_once(REGISTRATIONS_PLUGIN_DIR . '/includes/k-tc-admin.php' );
require_once(REGISTRATIONS_PLUGIN_DIR . '/includes/k-tc-widget.php' );
require_once(REGISTRATIONS_PLUGIN_DIR . '/includes/k-tc-shortcode.php' );
