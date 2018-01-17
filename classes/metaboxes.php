<?php

class MAJALMetaBoxes {
	
	public function __construct() {
	
	}
	
	public function majal_make_meta_box( $items ) {
	
		function majal_add_custom_meta_box() {	
			add_meta_box(
				'majal_alumni_meta_box',						// $id
				'Custom Meta Box',								// $title 
				'majal_show_custom_meta_box',					// $callback
				'majal_alumni',									// $page
				'normal',										// $context
				'high');										// $priority	
		}

		function majal_show_custom_meta_box() {
			global $custom_meta_fields, $post;
			// Use nonce for verification
			echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
			// Begin the field table and loop
			echo '<table class="form-table">';
			foreach ($custom_meta_fields as $field) {
				// get value of this field if it exists for this post
				$meta = get_post_meta($post->ID, $field['id'], true);
				// begin a table row with
				echo '<tr>
						<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
						<td>';
						switch($field['type']) {
							// text
							case 'text':
								echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
									<br /><span class="description">'.$field['desc'].'</span>';
							break;
							// textarea
							case 'textarea':
								echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
									<br /><span class="description">'.$field['desc'].'</span>';
							break;
							// checkbox
							case 'checkbox':
								echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
									<label for="'.$field['id'].'">'.$field['desc'].'</label>';
							break;
							// select
							case 'select':
								echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
								foreach ($field['options'] as $option) {
									echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
								}
								echo '</select><br /><span class="description">'.$field['desc'].'</span>';
							break;	
						} // end switch
				echo '</td></tr>';
			} // end foreach
			echo '</table>'; // end table
		}
		
		add_action('add_meta_boxes', 'majal_add_custom_meta_box' );
	}
}

?>