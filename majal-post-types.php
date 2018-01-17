<?php
/**
 * @package MAJAL_Alumni_Posttypes
 * @version 1.0.0
 */
/*
Plugin Name: MAJAL Post-Types
Plugin URI:
Description: Custom post-types for MA Journalism Alumni website
Author: Goldsmiths Media & Communications
Version: 1.0.0
Author URI:
*/

define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/////////////////////////////////////////
// Register plugin activation function //
/////////////////////////////////////////

function majal_install() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'majal_install' );

///////////////////////////////////////////
// Register plugin deactivation function //
///////////////////////////////////////////

function majal_uninstall() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'majal_uninstall' );

/////////////////////
// Post-Type class //
/////////////////////

if( !class_exists( 'MAJALPostTypes' ) ) :
	require_once( PLUGIN_PATH . 'classes/post-type-alumni.php' );
else :
	exit ( "Class MAJALPostTypeAlumni already declared!" );
endif;

$majalPostTypes = new MAJALPostTypeAlumni();

//////////////////////////////////
// Archive Page post sort-order //
//////////////////////////////////

/*
MOVED TO THEME FUNCTIONS FILE
if( !class_exists( 'MAJALArchiveSortorder' ) ) :
	require_once( PLUGIN_PATH . 'classes/archive-posts-sort-order.php' );
else :
	exit ( "Class MAJALArchiveSortorder already declared!" );
endif;

$majalArchiveSortorder = new MAJALArchiveSortorder();*/

//////////////////////
// Taxonomies class //
//////////////////////

if( !class_exists( 'MAJALTaxonomies' ) ) :
	require_once( PLUGIN_PATH . 'classes/taxonomies-alumni.php' );
else :
	exit ( "Class MAJALTaxonomiesAlumni already declared!" );
endif;

$majalTaxonomies = new MAJALTaxonomiesAlumni();

////////////////////////////
// Alumni Edit-Page class //
////////////////////////////

if( !class_exists( 'MAJALEditPage' ) ) :
	require_once( PLUGIN_PATH . 'classes/edit-page-alumni.php' );
else :
	exit ( "Class MAJALEditPageAlumni already declared!" );
endif;

$majalEditPage = new MAJALEditPageAlumni();

//////////////////////////
// Custom columns class //
//////////////////////////

if( !class_exists( 'MAJALCustomColumnsAlumni' ) ) :
	require_once( PLUGIN_PATH . 'classes/custom-columns-alumni.php' );
else :
	exit ( "Class MAJALCustomColumnsAlumni already declared!" );
endif;

$majalCustomColumnsAlumni = new MAJALCustomColumnsAlumni();

//////////////////////
// Admin Menu class //
//////////////////////

if( !class_exists( 'MAJALAdminMenu' ) ) :
	require_once( PLUGIN_PATH . 'classes/admin-menu.php' );
else :
	exit( "Class MAJALAdminMenu already declared!" );
endif;

$majalAdminMenu = new MAJALAdminMenu();

///////////////////////////////
// Search AutoComplete Class //
///////////////////////////////

if( !class_exists( 'MAJALSearchAutoComplete' ) ) :
	require_once( PLUGIN_PATH . 'classes/search-autocomplete.php' );
else :
	exit( "Class MAJALSearchAutoComplete already declared!" );
endif;

$majalSearchAutoComplete = new majalSearchAutoComplete();

?>
