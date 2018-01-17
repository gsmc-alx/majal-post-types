<?php
class MAJALArchiveSortorder {
	
	public function __construct() {
		add_filter( 'pre_get_posts', array( $this, 'majal_pre_get_posts_showposts_on' ) );
		add_filter( 'pre_get_posts', array( $this, 'majal_pre_get_posts_alumni_sortorder' ) );
	}
	
	////////////////////////////////////////////////////////
	// Show Alumni post type on Home/Single/Archive pages //
	////////////////////////////////////////////////////////
	
	public function majal_pre_get_posts_showposts_on( $query ) {
		if ( !is_admin() && $query->is_main_query() ) {
			if ( $query->is_home ) {
				$query->set( 'post_type', array( 'post', 'majal_alumni' ) );
			}
			/*if ( $query->is_search ) {
				$query->set( 'post_type', array( 'post', 'majal_alumni' ) );
			}*/
		}
	}	
	
	//////////////////////////////////////////////////////////////
	// Set sort order for custom post and taxonomy archive page //
	//////////////////////////////////////////////////////////////
	
	public function majal_pre_get_posts_alumni_sortorder( $query ) {
		// Is Main Query
		if ( !is_admin() && $query->is_main_query() && !isset( $_GET['orderby'] ) ) {
			// Is Majal Alumni archive or Employment Area tax archive
			if( $query->is_post_type_archive( 'majal_alumni' ) || is_tax( 'majal_employmentindustry' ) || $query->is_tax( 'majal_graduationyear' ) ) {
				// Set vars for default sort meta_key and order
				$sortby	= '_majal_alumni_alumnus_graduationyear';
				$order	= 'DESC';
				// Set default sort key and order for graduationyear tax archive page 
				if( $query->is_tax( 'majal_graduationyear' ) ) {
					$sortby	= '_majal_alumni_alumnus_namefirst';
					$order	= 'ASC';
				}
				// Check for 'sort' var in URL
				if( isset( $_GET['sort'] ) ) {
					// Set sort key and order if '?sort=graduationyear' appended to page URL
					if( $_GET['sort'] == 'graduationyear' ) {
						$sortby = '_majal_alumni_alumnus_graduationyear';
						$order	= 'DESC';
					// Set sort key and order if '?sort=firstname' appended to page URL
					} else if( $_GET['sort'] == 'firstname' ) {
						$sortby = '_majal_alumni_alumnus_namefirst';
						$order	= 'ASC';
					// Set sort key and order if '?sort=secondname' appended to page URL
					} else if( $_GET['sort'] == 'secondname' ) {
						$sortby = '_majal_alumni_alumnus_namesecond';
						$order	= 'ASC';
					}
				}
				// Sort posts by meta_key
				$query->set( 'meta_key', $sortby );
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'order', $order );
			}
			$query->set( 'posts_per_page', '16' );
			//$query->set( 'paged', true );
		}
	}

}		
?>