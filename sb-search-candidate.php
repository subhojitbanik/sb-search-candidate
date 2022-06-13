<?php

/*
Plugin Name: SB Search Candidate
Plugin URI: #
Description: search candidates from gsheet.
Author: Subhojit Banik
Version: 1.0.0
Author URI: #
*/

define( 'SB_SEARCH_CANDIDATE_VERSION', '1.0.0' );
define( 'SB_SEARCH_CANDIDATE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SB_SEARCH_CANDIDATE_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); 
/*
* Adding required files..
*/ 

require_once( SB_SEARCH_CANDIDATE_PLUGIN_DIR . 'map.php' );
require_once( SB_SEARCH_CANDIDATE_PLUGIN_DIR . 'my-representative.php' );
require_once( SB_SEARCH_CANDIDATE_PLUGIN_DIR . 'virtual-ballot.php' );
function sb_plugin_scripts(){
    wp_enqueue_style( 'sb_style', SB_SEARCH_CANDIDATE_PLUGIN_URL.'style.css' );
    //wp_enqueue_script('jquery');
    wp_enqueue_script('sb_google_map','https://maps.googleapis.com/maps/api/js?key=AIzaSyD8Oaptp9RRD6vRW7FFC9uFunniiYiQUIg&callback=initMap&v=weekly&libraries=places',array('jquery'),SB_SEARCH_CANDIDATE_VERSION);
    wp_enqueue_script( 'sb_plugin_js', SB_SEARCH_CANDIDATE_PLUGIN_URL.'script.js', array('jquery','sb_google_map'), SB_SEARCH_CANDIDATE_VERSION );
}
add_action('wp_enqueue_scripts', 'sb_plugin_scripts',25);

function sb_defer_scripts( $tag, $handle, $src ) {
    $defer = array( 
      'sb_google_map',
      
    );  
    if ( in_array( $handle, $defer ) ) {
       return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
    }
      
      return $tag;
  } 
  
  add_filter( 'script_loader_tag', 'sb_defer_scripts', 30, 3 );
