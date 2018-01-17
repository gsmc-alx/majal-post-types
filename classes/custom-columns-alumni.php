<?php

class MAJALCustomColumnsAlumni {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'majal_alumni_custom_columns' ) );
		add_action( 'admin_init', array( $this, 'majal_alumni_thumb_sizes' ) );
	}
	
	//////////////////////////////
	// Manage post list columns //
	//////////////////////////////
	
	public function majal_alumni_custom_columns() {

		////////////////////////////////
		// Add/Remove/Reorder columns //
		////////////////////////////////
		
		function majal_alumni_posts_columns( $columns ) {
    		$columns['majal_alumnus_photo']					= __( 'Photo' );
    		$columns['majal_alumnus_namefirst']				= __( 'First Name' );
    		$columns['majal_alumnus_namesecond']			= __( 'Second Name' );
    		$columns['majal_alumnus_interviewer_namefirst']	= __( 'Interviewed By' );
    		$columns['modified']							= __( 'Last Modified' );
    		
    		// http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
    		$customOrder = array(	'cb',
    								'majal_alumnus_photo',
    								'title',
    								'majal_alumnus_namefirst',
    								'majal_alumnus_namesecond',
    								'taxonomy-majal_graduationyear',		// WP-generated column name for custom taxonomy with 'show_admin_column' option enabled
    								'taxonomy-majal_employmentindustry',	// As above
    								'majal_alumnus_interviewer_namefirst',
    								'modified'
    							);
    		
    		foreach ( $customOrder as $colname )
    			$newcolumns[$colname] = $columns[$colname];    
    		
    		return $newcolumns;
		}	
		add_filter( 'manage_edit-majal_alumni_columns' , 'majal_alumni_posts_columns' );
		
		/////////////////////////
		// Fill custom columns //
		/////////////////////////
		
		function majal_alumni_custom_column( $column, $post_id ) {
			
			switch ( $column ) {
				case 'majal_alumnus_photo' :
					if( function_exists( 'the_post_thumbnail' ) ) {
						if( has_post_thumbnail( $post_id ) ) {
							// Get post thumb URL without link
							$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'Alumnus Admin Thumbnail');
							// Add thumb and link thumb to alumni edit page
							echo '<a href="' . get_edit_post_link( $post_id ) . '"><img src="' . $thumb[0] . '" width="60" height="60" alt="' . get_the_title() . '" /></a>';
						} else {
							// Return 'no photo' thumb linked to edit page
							echo '<a href="' . get_edit_post_link( $post_id ) . '"><img src="' . plugins_url( '../img/majal_nophoto_60x60.png' , __FILE__ ) . '" width="60" height="60" alt="No Photo" /></a>';
						}
					} else {
						echo 'Not supported in this theme';
					}
					break; 
				case 'majal_alumnus_namefirst' :
					echo get_post_meta( $post_id, '_majal_alumni_alumnus_namefirst', true );
					break;
				case 'majal_alumnus_namesecond' :
					echo get_post_meta( $post_id, '_majal_alumni_alumnus_namesecond', true ); 
					break;
				case 'majal_alumnus_interviewer_namefirst' :
					echo get_post_meta( $post_id, '_majal_alumni_alumnus_interviewer_namefirst', true ) . ' ';
					echo get_post_meta( $post_id, '_majal_alumni_alumnus_interviewer_namesecond', true );
					break;
				case 'modified' :
					echo get_the_modified_date() . '<br />' . get_the_modified_time(). '<br />' . get_the_modified_author();
					break;
			}
		}
		add_action( 'manage_majal_alumni_posts_custom_column', 'majal_alumni_custom_column', 10, 2 );
		
		///////////////////////////
		// Make columns sortable //
		///////////////////////////
		
		function majal_alumni_custom_column_sortable( $columns ) {
			// http://scribu.net/wordpress/custom-sortable-columns.html
			$columns['majal_alumnus_namefirst']				= '_majal_alumni_alumnus_namefirst';
			$columns['majal_alumnus_namesecond']			= '_majal_alumni_alumnus_namesecond';
			$columns['taxonomy-majal_graduationyear']		= 'taxonomy-majal_graduationyear';
			$columns['taxonomy-majal_employmentindustry']	= 'taxonomy-majal_employmentindustry';	
			$columns['majal_alumnus_interviewer_namefirst']	= '_majal_alumni_alumnus_interviewer_namefirst';
			return $columns;
		}
		add_filter( 'manage_edit-majal_alumni_sortable_columns', 'majal_alumni_custom_column_sortable' );
		
		////////////////////////////////////
		// Add filters for column sorting //
		////////////////////////////////////
		
		// Sort by custom columns
		function majal_alumni_custom_column_sortable_order( $vars ) {
			// Order by first name
			if ( isset( $vars['orderby'] ) && '_majal_alumni_alumnus_namefirst' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => '_majal_alumni_alumnus_namefirst',
					'orderby' => 'meta_value'
				) );
			}
			// Order by second name
			if ( isset( $vars['orderby'] ) && '_majal_alumni_alumnus_namesecond' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => '_majal_alumni_alumnus_namesecond',
					'orderby' => 'meta_value'
				) );
			}
			// Order by first name of interviewer
			if ( isset( $vars['orderby'] ) && '_majal_alumni_alumnus_interviewer_namefirst' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => '_majal_alumni_alumnus_interviewer_namefirst',
					'orderby' => 'meta_value'
				) );
			}
			
			return $vars;
		}
		add_filter( 'request', 'majal_alumni_custom_column_sortable_order' );
		
		/////////////////////////////////////
		// Sort by custom taxonomy columns //
		/////////////////////////////////////
		
		function majal_alumni_tax_columns_sort_order( $orderby, $wp_query ) {
			// http://scribu.net/wordpress/sortable-taxonomy-columns.html
			// This method is supposedly very inefficient, but seems to work OK. Can't get the more efficient method to work...
			global $wpdb;

			if ( isset( $wp_query->query['orderby'] ) && 'taxonomy-majal_graduationyear' == $wp_query->query['orderby'] ) {
				$orderby = "(
					SELECT GROUP_CONCAT(name ORDER BY name ASC)
					FROM $wpdb->term_relationships
					INNER JOIN $wpdb->term_taxonomy USING (term_taxonomy_id)
					INNER JOIN $wpdb->terms USING (term_id)
					WHERE $wpdb->posts.ID = object_id
					AND taxonomy = 'majal_graduationyear'
					GROUP BY object_id
				) ";
				$orderby .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
			}
			
			if ( isset( $wp_query->query['orderby'] ) && 'taxonomy-majal_employmentindustry' == $wp_query->query['orderby'] ) {
				$orderby = "(
					SELECT GROUP_CONCAT(name ORDER BY name ASC)
					FROM $wpdb->term_relationships
					INNER JOIN $wpdb->term_taxonomy USING (term_taxonomy_id)
					INNER JOIN $wpdb->terms USING (term_id)
					WHERE $wpdb->posts.ID = object_id
					AND taxonomy = 'majal_employmentindustry'
					GROUP BY object_id
				) ";
				$orderby .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
			}

			return $orderby;
		}
		add_filter( 'posts_orderby', 'majal_alumni_tax_columns_sort_order', 10, 2 );
		
		////////////////////////////
		// Set default post order //
		////////////////////////////
		
		function majal_alumni_column_sortorder( $query ) {
			 // http://wordpress.stackexchange.com/questions/66455/how-to-change-order-of-posts-in-admin
			 /* 
				Set post types.
				_builtin => true returns WordPress default post types. 
				_builtin => false returns custom registered post types. 
			*/
			$post_types = get_post_types( array( '_builtin' => false ), 'names' );
			/* The current post type. */
			$post_type = $query->get( 'post_type' );
			/* Check post types. */
			if( in_array( $post_type, $post_types ) && $post_type == 'majal_alumni' ) {
				/* Post Column: e.g. title */
				if( $query->get( 'orderby' ) == '' ) {
					$query->set( 'orderby', 'modified' );
				}
				/* Post Order: ASC / DESC */
				if( $query->get( 'order' ) == '' ){ 
					$query->set( 'order', 'DESC' );
				}
			}
		}
    	add_action( 'pre_get_posts', 'majal_alumni_column_sortorder' );

    	////////////////////////////////////
    	// Remove Quick Edit from Columns //
    	////////////////////////////////////
    	
		// https://wordpress.org/support/topic/remove-quick-edit-from-custom-post-type
		function majal_alumni_column_remove_quickedit( $actions ) {
			if( is_admin() ) {
				global $post;
			    if( $post->post_type == 'majal_alumni' ) {
					unset( $actions['inline hide-if-no-js'] );
				}
			}
			return $actions;
		}
		add_filter( 'post_row_actions','majal_alumni_column_remove_quickedit', 10, 2 );
    	
    	////////////////////////////////////////////////////
    	// Add custom post filter Quicklink above columns //
    	////////////////////////////////////////////////////
    	
    	// Adapted from https://github.com/pjhoberman/Featured---Published-Posts

		function majal_alumni_column_add_filter( $query ) {
		    if( is_admin() ) {
		        add_filter( 'views_edit-majal_alumni', 'majal_alumni_column_featured_quicklink' );
		    }
		    if( isset( $_GET['adminfilter'] ) && $_GET['adminfilter'] == 'featured' ) {
				$query->set( 'meta_key', '_majal_alumni_alumnus_isfeatured' );
				$query->set( 'meta_value', 'on' );
			}
		}
		add_action( 'pre_get_posts', 'majal_alumni_column_add_filter' );
		
		// add filter
		function majal_alumni_column_featured_quicklink( $views ) {
		    global $wp_query;
		    $query = array(
		        'post_type'		=> 'majal_alumni',
		        'meta_key'		=> '_majal_alumni_alumnus_isfeatured',
		        'meta_value'	=> 'on'
		    );
		    $result = new WP_Query( $query );
		    $class = ( $wp_query->query_vars['meta_key'] == '_majal_alumni_alumnus_isfeatured' ) ? ' class="current"' : '';
		    $views[ 'featured' ] = sprintf(
		    	__( '<a href="%s"'. $class .'>Featured <span class="count">(%d)</span></a>', 'Featured' ),
				admin_url( 'edit.php?post_type=majal_alumni&adminfilter=featured' ),
				$result->found_posts
			);
		    return $views;
		}
	}
	
	///////////////////////////////////////
	// Add new thumbail size for columns //
	///////////////////////////////////////
	
	public function majal_alumni_thumb_sizes() {
		add_image_size( 'Alumnus Admin Thumbnail', 60, 60, array( 'center', 'top' ) );
	}
	
	/////////////////////////////////////
	// Edit filter links above columns //
	/////////////////////////////////////
	
	public function majal_alumni_custom_columns_views( $views ) {
		//unset($views['all']);
		//unset($views['publish']);
		//unset($views['trash']);
		
		// TO DO: Add link to show Alumni posts with 'Featured' option set
		
		return $views;
	}
	
}

?>