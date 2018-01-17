<?php

class MAJALTaxonomiesAlumni {
	
	public function __construct() {
		add_action( 'init', array( $this, 'majal_create_alumni_taxonomies' ), 0);	
	}

	////////////////////////////
	// Create custom taxonomy //
	////////////////////////////
	
	public function majal_create_alumni_taxonomies() {		
		
		//////////////////////////////////
		//// Taxonomy for Industry Area //
		//////////////////////////////////
		
		$labels = array(
			'name'					=> _x( 'Industry Areas', 'taxonomy general name' ),
			'singular_name'			=> _x( 'Industry Area', 'taxonomy singular name' ),
			'search_items'			=> __( 'Search Industry Areas' ),
			'all_items'				=> __( 'All Industry Areas' ),
			'parent_item'			=> __( 'Parent Industry Areas' ),
			'parent_item_colon'		=> __( 'Parent Industry Areas:' ),
			'edit_item'				=> __( 'Edit Industry Area' ), 
			'update_item'			=> __( 'Update Industry Area' ),
			'add_new_item'			=> __( 'Add New Industry Area' ),
			'new_item_name'			=> __( 'New Industry Area' ),
			'menu_name'				=> __( 'Industry Areas' ),
		);	
		$args = array(
    		'public'				=> true,
    		'show_in_nav_menus'		=> true,
    		'labels'				=> $labels,
    		'hierarchical'			=> true, 
			'rewrite'				=> array( 'slug' => 'industry_area' ),
			'show_admin_column'		=> true,
			'sort'					=> false
		);		
		register_taxonomy( 'majal_employmentindustry', 'majal_alumni', $args );
		
		////////////////////////////////////
		//// Taxonomy for Graduation Year //
		////////////////////////////////////
		
		$labels = array(
			'name'					=> _x( 'Graduation Year', 'taxonomy general name' ),
			'singular_name'			=> _x( 'Graduation Year', 'taxonomy singular name' ),
			'search_items'			=> __( 'Search Graduation Years' ),
			'all_items'				=> __( 'All Graduation Years' ),
			'edit_item'				=> __( 'Edit Graduation Year' ), 
			'update_item'			=> __( 'Update Graduation Year' ),
			'add_new_item'			=> __( 'Add New Graduation Year' ),
			'new_item_name'			=> __( 'New Graduation Year' ),
			'menu_name'				=> __( 'Graduation Years' ),
		);	
		$args = array(
    		'public'				=> true,
    		'show_in_nav_menus'		=> true,
    		'labels'				=> $labels,
    		'hierarchical'			=> true, 
			'rewrite'				=> array( 'slug' => 'graduation_year' ),
			'show_admin_column' 	=> true,
			'sort'					=> false
		);		
		register_taxonomy( 'majal_graduationyear', 'majal_alumni', $args );
	}
}

?>