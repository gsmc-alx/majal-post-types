<?php

class MAJALSearchAutoComplete {
	
	// From
	// http://code.tutsplus.com/tutorials/add-jquery-autocomplete-to-your-sites-search--wp-25155
	
	var $plugin_url;
	
	public function __construct() {
		$this->plugin_url = plugins_url( 'majal_post_types' ) . '/';
		add_action( 'init', array( $this, 'majal_autocomplete_init' ) );
	}
	
	public function majal_autocomplete_init() {
	    // Register our jQuery UI style and our custom javascript file
	    wp_register_style( 'majal-jquery-ui','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
	    wp_register_script( 'majal-acsearch', $this->plugin_url . 'js/search-autocomplete.js', array('jquery','jquery-ui-autocomplete' ), null, true );
		wp_localize_script( 'majal-acsearch', 'MajalAcSearch', array('url' => admin_url( 'admin-ajax.php' ) ) );
		
	    // Function to fire whenever search form is displayed
	    //add_action( 'get_search_form', array( $this, 'majal_autocomplete_search_form' ) );
	    
	    wp_enqueue_script( 'majal-acsearch' );
		wp_enqueue_style( 'majal-jquery-ui' );
	 
	    // Functions to deal with the AJAX request - one for logged in users, the other for non-logged in users.
	    add_action( 'wp_ajax_majal_autocompletesearch', array( $this, 'majal_autocomplete_suggestions' ) );
	    add_action( 'wp_ajax_nopriv_majal_autocompletesearch', array( $this, 'majal_autocomplete_suggestions' ) );
	}
	
	/*public function majal_autocomplete_search_form() {
		wp_enqueue_script( 'majal_acsearch' );
		wp_enqueue_style( 'majal-jquery-ui' );
	}*/
	
	function majal_autocomplete_suggestions() {
	    
	    // Query for suggestions
	    $posts = get_posts( array(
	        'post_type'	=> 'majal_alumni',
	        's'			=> $_REQUEST['term'],
	    ) );
	 
	    // Initialise suggestions array
	    $suggestions = array();
	 
	    global $post;
	    foreach ($posts as $post): setup_postdata( $post );
	        // Initialise suggestion array
	        $suggestion = array();
	        $suggestion['label'] = esc_html( $post->post_title );
	        $suggestion['link'] = get_permalink();
	 
	        // Add suggestion to suggestions array
	        $suggestions[] = $suggestion;
	    endforeach;
		
	    // JSON encode and echo
	    $response = $_GET["callback"] . "(" . json_encode( $suggestions ) . ")";
	    echo $response;
	 
	    // Don't forget to exit!
	    exit;
	}
}

?>