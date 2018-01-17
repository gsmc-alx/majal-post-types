<?php

class MAJALPostTypeAlumni {

	var $plugin_url;

	public function __construct() {
		$this->plugin_url = plugins_url( 'majal-post-types' ) . '/';

		add_action( 'init', array( $this, 'majal_create_alumni_post_type' ) );
		add_filter( 'post_updated_messages', array( $this, 'majal_alumni_updated_messages' ) );
	}

	////////////////////////////////////
	// Create custom post Alumni type //
	////////////////////////////////////

	public function majal_create_alumni_post_type() {

		// CODEX: http://codex.wordpress.org/Function_Reference/register_post_type
		$alumni_posttype_labels = array(
			'name'					=> _x( 'Alumni', 'post type general name' ),
			'singular_name' 		=> _x( 'Alumnus', 'post type singular name' ),
			'add_new'				=> _x( 'Add New Profile', 'majal_alumni' ),
			'add_new_item' 			=> __( "Add New Profile" ),
			'edit_item'				=> __( "Edit Profile" ),
			'new_item'				=> __( "New Profile" ),
			'view_item'				=> __( "View Profiles" ),
			'search_items'			=> __( "Search Alumni" ),
			'not_found'				=> __( 'No Alumni found' ),
			'not_found_in_trash' 	=> __( 'No Alumni found in Trash' ),
			'parent_item_colon' 	=> ''
		);

		$alumni_posttype_args = array(
			'labels'				=> $alumni_posttype_labels,
			'description'   		=> 'MA Journalism Alumni',
			'public' 				=> true,
			'publicly_queryable' 	=> true,
			'has_archive'			=> true,
			'show_ui'				=> true,
			'query_var'				=> true,
			'rewrite'				=> true,
			'hierarchical'			=> false,
			'menu_position'			=> 5,
			'capability_type'		=> 'post',
			'supports'				=> array( 'editor', 'thumbnail' ),
			'menu_icon'				=> $this->plugin_url . 'img/User-Clients-icon.png',
			'rewrite'				=> array( 'slug' => 'alumni' )
		);

		register_post_type( 'majal_alumni', $alumni_posttype_args );
	}

	////////////////////////////////////////
	// Interaction messages for post type //
	////////////////////////////////////////

	public function majal_alumni_updated_messages( $messages ) {
		global $post, $post_ID;
		$messages['majal_alumni'] = array(
			0  => '',
			1  => sprintf( __('Profile updated. <a href="%s">View alumnus</a>'), esc_url( get_permalink($post_ID) ) ),
			2  => __('Custom field updated.'),
			3  => __('Custom field deleted.'),
			4  => __('Alumnus updated.'),
			5  => isset($_GET['revision']) ? sprintf( __('Alumnus restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __('Profile published. <a href="%s">View alumnus</a>'), esc_url( get_permalink($post_ID) ) ),
			7  => __('Profile saved.'),
			8  => sprintf( __('Profile submitted. <a target="_blank" href="%s">Preview alumnus</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9  => sprintf( __('Profile scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview alumnus</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Profile draft updated. <a target="_blank" href="%s">Preview alumnus</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	  );
	  return $messages;
	}
}

?>
