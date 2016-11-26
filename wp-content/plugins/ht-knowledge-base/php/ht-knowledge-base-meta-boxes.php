<?php

/**
* Include and setup custom metaboxes and fields.
*/

if (!class_exists('HT_Knowledge_Base_Meta_Boxes')) {

    class HT_Knowledge_Base_Meta_Boxes {

    	//Constructor
    	public function __construct() {
    		add_filter( 'cmb_meta_boxes', array( $this, 'ht_knowledge_base_register_meta_boxes') );
    		add_action( 'init', array( $this, 'cmb_initialize_cmb_meta_boxes'), 9999 );
    	 }

    	 /**
		 * Register meta boxes
		 * @uses the meta-boxes module
		 * @param (Array) $meta_boxes The exisiting metaboxes
		 * @param (Array) Filtered metaboxes
		 */
		function ht_knowledge_base_register_meta_boxes( array $meta_boxes ) {

			$prefix = '_ht_knowledge_base_';

			//maybe upgrade meta boxes
			//$this->maybe_upgrade_meta_fields();

			// meta box definition
			$meta_boxes[] = array(
				// Meta box id, UNIQUE per meta box. Optional since 4.1.5
				'id' => 'kb_meta',
				// Meta box title - Will appear at the drag and drop handle bar. Required.
				'title' => __( 'Article Options', 'ht-knowledge-base' ),
				// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
				'pages' => array( 'ht_kb' ),
				// Where the meta box appear: normal (default), advanced, side. Optional.
				'context' => 'normal',
				// Order of meta box: high (default), low. Optional.
				'priority' => 'high',
				// Auto save: true, false (default). Optional.
				'autosave' => true,
				// List of meta fields
				'fields' => array(
					//dummy to upgrade fields
					array(
						'name' => 'update_dummy',
						'id'   => $prefix .'updade_dummy',
						'type' => 'title',
						'show_on_cb' => array( $this, 'maybe_upgrade_meta_fields' ),
					),
					//attachments
					array(
						'name' => __( 'Attachments', 'ht-knowledge-base' ),
						'description' => __( 'Add attachments to this article', 'ht-knowledge-base' ),
						'id'   => $prefix .'file_advanced',
						'type' => 'file_list',
						'max_file_uploads' => 4,
						'mime_type' => '', // Leave blank for all file types
					),
					//view count
					array(
						'name' => __( 'View Count', 'ht-knowledge-base' ),
						'description' => __( 'Set the view count for this article', 'ht-knowledge-base' ),
						'id'   => HT_KB_POST_VIEW_COUNT_KEY,
						'type' => 'text',
						'default' => 1,
						'sanitization_cb' => array($this, 'santize_view_count_field'), // custom sanitization callback parameter
						
					),					
				)
			);
			return $meta_boxes;
		}

		/**
		 * Initialize the metabox class.
		 */
		function cmb_initialize_cmb_meta_boxes() {

			if ( ! class_exists( 'cmb_Meta_Box' ) )
				require_once dirname( dirname( __FILE__ ) ) . '/custom-metaboxes/init.php';
		}

		function santize_view_count_field($new_value, $args, $field){
			$old_value = $field->value();
			if( preg_match('/^\d+$/', $new_value ) ){
				return (int) $new_value;
			} else {
				return $old_value;
			}			
		}

		function my_admin_notice() {
			?>
			<div class="updated">
				<p><?php _e( 'Updated!', 'ht-knowledge-base' ); ?></p>
			</div>
			<?php
		}


		/**
		 * Upgrade the meta key values.
		 */
		function maybe_upgrade_meta_fields(){
			HT_Knowledge_Base::ht_kb_upgrade_article_meta_fields( get_the_ID() );
			//return a false so the dummy does not display
			return false;
		}




    } //end class

}//end class exists


//run the module
if(class_exists('HT_Knowledge_Base_Meta_Boxes')){
	$ht_knowledge_base_meta_boxes_init = new HT_Knowledge_Base_Meta_Boxes();
}
