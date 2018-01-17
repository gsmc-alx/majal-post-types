<?php

class MAJALAdminMenu {
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'majal_remove_admin_pages' ) );
	}
	
	////////////////////////////////////////////
	// Remove admin pages for non-Admin users //
	////////////////////////////////////////////
	
	public function majal_remove_admin_pages() {
		//http://codex.wordpress.org/Function_Reference/remove_menu_page
		
		function check_user_role( $role ) {
			http://docs.appthemes.com/tutorials/wordpress-check-user-role-function/
			$user = wp_get_current_user();
			if( empty( $user ) )
				return false;
			return in_array( strtolower( $role ), (array) $user->roles );
		}
		
		if(	check_user_role( 'subscriber' ) ||
			check_user_role( 'contributor' ) ||
			check_user_role( 'author' ) || 
			check_user_role( 'editor' )
		) {
			remove_menu_page( 'edit.php' );					// Posts
			remove_menu_page( 'edit.php?post_type=page' );	// Pages
			remove_menu_page( 'edit-comments.php' );		// Comments
			remove_menu_page( 'profile.php' );				// Profile
			remove_menu_page( 'tools.php' );				// Comments
		}
	}	
}

?>