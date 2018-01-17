<?php

class MAJALEditPageAlumni {
	
	var $plugin_url;
	var $plugin_path;
	
	public function __construct() {
		
		$this->plugin_url = plugins_url( 'majal_post_types' ) . '/';
		$this->plugin_path = plugin_dir_path( __FILE__ );
		
		add_filter( 'tiny_mce_before_init', array( $this, 'majal_alumni_TinyMCE_options' ) );		
		add_filter( 'add_meta_boxes', array( $this, 'majal_alumni_hide_editpage_taxomomies' ) );		
		add_action( 'admin_enqueue_scripts', array( $this, 'majal_alumni_edit_enqueue_js' ) );	
		add_action( 'init', array( $this, 'majal_alumni_initialize_cmb_meta_boxes' ) );
		add_filter( 'cmb_meta_boxes', array( $this, 'majal_alumni_metaboxes' ) );		
		add_action( 'admin_head', array( $this, 'majal_alumni_edit_remove_mediabutton' ) );	
		add_action( 'the_post', array( $this, 'majal_alumni_autoset_featured' ) );
		add_action( 'save_post', array( $this, 'majal_alumni_autoset_featured' ) );
		add_action( 'draft_to_publish', array( $this, 'majal_alumni_autoset_featured' ) );
		add_action( 'new_to_publish', array( $this, 'majal_alumni_autoset_featured' ) );
		add_action( 'pending_to_publish', array( $this, 'majal_alumni_autoset_featured' ) );
		add_action( 'future_to_publish', array( $this, 'majal_alumni_autoset_featured' ) );
	}
	
	////////////////////////////////
	// Hide TinyMCE toolbar items //
	////////////////////////////////
	
	public function majal_alumni_TinyMCE_options( $opts ) {
		$opts['remove_linebreaks']=false;
		$opts['gecko_spellcheck']=false;
		$opts['keep_styles']=true;
		$opts['accessibility_focus']=true;
		$opts['tabfocus_elements']='major-publishing-actions';
		$opts['media_strict']=false;
		$opts['paste_remove_styles']=true;
		$opts['paste_remove_spans']=true;
		$opts['paste_strip_class_attributes']='none';
		$opts['paste_text_use_dialog']=true;
		$opts['wpeditimage_disable_captions']=true;
		//$opts['plugins']='tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpfullscreen';
		$opts['plugins']='tabfocus,paste,media,fullscreen,wordpress,wplink,wpdialogs,wpfullscreen';
		$opts['content_css']=get_template_directory_uri() . "/editor-style.css";
		$opts['wpautop']=true;
		$opts['apply_source_formatting']=true;
		//$opts['toolbar1']='bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,wp_fullscreen,wp_adv ';
		$opts['toolbar1']='bold,italic,blockquote,link,unlink,wp_more,spellchecker,wp_fullscreen,wp_adv ';
		//$opts['toolbar2']='formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help ';
		$opts['toolbar2']='pastetext,removeformat,charmap,undo,redo,wp_help ';
		$opts['toolbar3']='';
		$opts['toolbar4']='';
		return $opts;
	}
	
	/////////////////////
	// Hide taxonomies //
	/////////////////////
	
	public function majal_alumni_hide_editpage_taxomomies() {
		remove_meta_box('majal_employmentindustrydiv', 'majal_alumni', 'side' );
		remove_meta_box('majal_graduationyeardiv', 'majal_alumni', 'side' );
	}

	////////////////////////////////////////////////////////////////////////////////
	// Add custom Edit page meta boxes using ///////////////////////////////////////
	// Custom-Metaboxes-and-Fields-for-WordPress ///////////////////////////////////
	// Class from WebDevStudios ////////////////////////////////////////////////////
	// https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress //
	////////////////////////////////////////////////////////////////////////////////
	
	public function majal_alumni_metaboxes( $meta_boxes ) {
		
		// Prefix for all fields
		$prefix = '_majal_alumni_';
		
		// Quote
		$meta_boxes['alumnus_quote_metabox'] = array(
			'id' => 'alumnus_quote_metabox',
			'title' => 'Testimonial',
			'pages' => array('majal_alumni'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => 'Quote',
					'desc' => 'Quote from interview (optional)',
					'id' => $prefix . 'alumnus_quote',
					'type' => 'textarea_small'
				)
			)
		);
		
		// Personal Details
		$meta_boxes['alumnus_personaldetails_metabox'] = array(
			'id' => 'alumnus_personaldetails_metabox',
			'title' => 'Personal Details',
			'pages' => array('majal_alumni'),
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true,
			'fields' => array(
				array(
				    'name'    => __( 'Gender' ),
				    'id'      => $prefix . 'alumnus_gender',
				    'type'    => 'radio',
				    'options' => array(
				    	'standard' => __( 'Male' ),
				    	'custom'   => __( 'Female' )
				    ),
				),
				array(
					'name' => __( 'First Name'),
					'desc' => __( 'First/given name of alumnus'),
					'id'   => $prefix . 'alumnus_namefirst',
					'type' => 'text_medium',
				),
				array(
					'name' => __( 'Second Name'),
					'desc' => __( 'Second/family name of alumnus'),
					'id'   => $prefix . 'alumnus_namesecond',
					'type' => 'text_medium',
				),
				array(
					'name' => __( 'Nationality'),
					'desc' => __( "Nationality of alumnus ie 'British', 'Swiss' etc."),
					'id'   => $prefix . 'alumnus_nationality',
					'type' => 'text_medium',
				),
				array(
					'name' => __( 'Photo'),
					'desc' => __( 'Upload an image'),
					'id'   => $prefix . 'alumnus_photo',
					'type' => 'file',
				)
			)
		);
		
		// Education
		$meta_boxes['alumnus_education_metabox'] = array(
			'id' => 'alumnus_education_metabox',
			'title' => 'Education',
			'pages' => array('majal_alumni'),
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true,
			'fields' => array(
				array(
					'name'     => __( 'Year of Graduation'),
					'desc'     => __( 'Year of graduation from MA Journalism course'),
					'id'       => $prefix . 'alumnus_graduationyear',
					'type'     => 'taxonomy_select',
					'taxonomy' => 'majal_graduationyear', // Taxonomy Slug
				),
				array(
					'name' => __( 'Undergraduate Degree'),
					'desc' => __( 'Name of undergraduate degree studied before MA Journalism. Do NOT put name of university in here!'),
					'id'   => $prefix . 'alumnus_undergraddegree',
					'type' => 'text',
				),
				array(
					'name' => __( 'Undergraduate degree institution'),
					'desc' => __( 'Institution where undergraduate degree studied. Do NOT put name of degree in here!'),
					'id'   => $prefix . 'alumnus_undergradinstitution',
					'type' => 'text',
				)
			)
		);
		
		// Employment First Job
		$meta_boxes['alumnus_employment_jobfirst_metabox'] = array(
			'id' => 'alumnus_employment_jobfirst_metabox',
			'title' => 'First Job',
			'pages' => array('majal_alumni'),
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true,
			'fields' => array(
				array(
					'name' => __( 'First Job'),
					'desc' => __( 'First job after leaving MA Journalism course. Do NOT put name of employer in this field!'),
					'id'   => $prefix . 'alumnus_jobfirst_role',
					'type' => 'text',
				),
				array(
					'name' => __( 'First Job Employer'),
					'desc' => __( 'Employer.  Do NOT put role in this field!'),
					'id'   => $prefix . 'alumnus_jobfirst_employer',
					'type' => 'text',
				)
			)
		);
		
		// Employment Current Job
		$meta_boxes['alumnus_employment_jobcurrent_metabox'] = array(
			'id' => 'alumnus_employment_jobcurrent_metabox',
			'title' => 'Current Job',
			'pages' => array('majal_alumni'),
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true,
			'fields' => array(
				array(
					'name'     => __( 'Current Industry Area'),
					'desc'     => __( 'Area of employment (select all applicable)'),
					'id'       => $prefix . 'alumnus_jobcurrent_industryareas',
					'type'     => 'taxonomy_multicheck',
					'taxonomy' => 'majal_employmentindustry', // Taxonomy Slug
					// 'inline'  => true, // Toggles display to inline
				),
				array(
					'name' => __( 'Other Industry Area'),
					'desc' => __( 'Employment industry if not one of above'),
					'id'   => $prefix . 'alumnus_jobcurrent_industryareas_other',
					'type' => 'text',
				),
				array(
					'name' => __( 'Current Job'),
					'desc' => __( 'Current role. Do NOT put name of employer in this field!'),
					'id'   => $prefix . 'alumnus_jobcurrent_role',
					'type' => 'text',
				),
				array(
					'name' => __( 'Current Job Employer'),
					'desc' => __( 'Current employer. Do NOT put role in this field!'),
					'id'   => $prefix . 'alumnus_jobcurrent_employer',
					'type' => 'text',
				)
			)
		);
		
		// Interviewer details
		$meta_boxes['alumnus_interviewer_metabox'] = array(
			'id' => 'alumnus_interviewer_metabox',
			'title' => 'Interviewer',
			'pages' => array('majal_alumni'),
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true,
			'fields' => array(
				array(
					'name'     => __( 'First Name' ),
					'desc'     => __( 'Your First Name' ),
					'id'       => $prefix . 'alumnus_interviewer_namefirst',
					'type'     => 'text'
				),
				array(
					'name'     => __( 'Second Name'),
					'desc'     => __( 'Your Second Name'),
					'id'       => $prefix . 'alumnus_interviewer_namesecond',
					'type'     => 'text'
				)
			)
		);
		
		// Featured button (only show to Editors)
		if ( current_user_can( 'edit_others_posts' ) ) {
			$meta_boxes['alumnus_isfeatured'] = array(
				'id' => 'alumnus_isfeatured_metabox',
				'title' => 'Featured',	
				'pages' => array('majal_alumni'),
				'context' => 'normal',
				'priority' => 'high',
				'show_names' => true,
				'fields' => array(
		            array(
		                'name' => __( 'Featured' ),
		                'desc' => __( 'Check to have post profile display in featured profiles areas on site' ),
		                'id' => $prefix . 'alumnus_isfeatured',
		                'type' => 'checkbox'
		            )
		        )
				
			);
		};
		
		return $meta_boxes;
	}
	
	//////////////////////////////
	// Initialize Metabox Class //
	//////////////////////////////

	public function majal_alumni_initialize_cmb_meta_boxes() {
		if ( !class_exists( 'cmb_Meta_Box' ) ) {
			require_once( $this->plugin_path . '../lib/metabox/init.php' );
		}
	}
	
	/////////////////////////////////
	// Enqueue Alumni edit page JS //
	/////////////////////////////////

	public function majal_alumni_edit_enqueue_js() {
		global $post;
		if( $post->post_type == 'majal_alumni' ) :    		
			wp_register_script(
				'majal_alumni-post-type-edit-scripts',
				$this->plugin_url . 'js/edit-page-alumni.js',
				array( 'jquery' ),
				true
			);
			wp_enqueue_script( 'majal_alumni-post-type-edit-scripts' );
		endif;
	}

	////////////////////////////////////////////
	// Remove Add Media button from edit page //
	////////////////////////////////////////////
	
	public function majal_alumni_edit_remove_mediabutton() {
		global $post;
		if( $post->post_type == 'majal_alumni' && current_user_can('edit_post') ) {
			remove_action( 'media_buttons', 'media_buttons' );
		}
	}
	
	/////////////////////////////
	// Auto-set Featured Image //
	/////////////////////////////
	
	public function majal_alumni_autoset_featured( $post_id ) {
		// http://www.paulund.co.uk/automatically-set-post-featured-image
		global $post;
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
  			return;
		
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
			return;
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) )
				return;
		}
		
		if( !has_post_thumbnail( $post->ID ) ) {
			$attached_image = get_children( "post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );
			if ( $attached_image ) {
				foreach ( $attached_image as $attachment_id => $attachment ) {
					set_post_thumbnail( $post->ID, $attachment_id );
				}
			}
		}
	}
}

?>