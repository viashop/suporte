<?php

/**
* Include and setup custom metaboxes and fields.
*/

if (!class_exists('HT_Voting_Meta_Boxes')) {

    class HT_Voting_Meta_Boxes {

    	//Constructor
    	public function __construct() {
    		add_filter( 'cmb_meta_boxes', array( $this, 'ht_kb_voting_meta_boxes') );
    		add_action( 'init', array( $this, 'cmb_initialize_cmb_meta_boxes'), 9999 );
    	 }

    	 /**
		 * Register meta boxes
		 * @uses the meta-boxes module
		 * @param (Array) $meta_boxes The exisiting metaboxes
		 * @param (Array) Filtered metaboxes
		 */
		function ht_kb_voting_meta_boxes( array $meta_boxes ) {

			$prefix = '_ht_voting_';


			// meta box definition
			$meta_boxes[] = array(
				// Meta box id, UNIQUE per meta box. Optional since 4.1.5
				'id' => 'kb_meta_voting',
				// Meta box title - Will appear at the drag and drop handle bar. Required.
				'title' => __( 'Voting Options', 'ht-knowledge-base' ),
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
				
					//voting enable
					array(
						'name' => __( 'Disable Voting', 'ht-knowledge-base' ),
						'description' => __( 'Disable voting on this article', 'ht-knowledge-base' ),
						'id'   => $prefix .'voting_disabled',
						'type' => 'checkbox',
						'show_on_cb' => array( $this, 'cmb_only_show_for_voting_enabled' ),
						
					),
					//voting reset
					array(
						'name' => __( 'Reset Voting', 'ht-knowledge-base' ),
						'description' => __( 'Check this box to reset all votes for this article on update', 'ht-knowledge-base' ),
						'id'   => $prefix .'voting_reset',
						'type' => 'checkbox',
						'default' => false,
						'sanitization_cb' => array( $this, 'santize_reset_field' ), // custom sanitization callback parameter
						'show_on_cb' => array( $this, 'cmb_only_show_for_votes' ),
					),	
					//voting reset confirmation
					array(
						'name' => __( 'No Votes', 'ht-knowledge-base' ),
						'description' => __( 'There are currently no votes or votes have been reset', 'ht-knowledge-base' ),
						'id'   => $prefix .'voting_reset_confirm',
						'type' => 'checkbox',
						'default' => true,
						'sanitization_cb' => array( $this, 'santize_reset_confirm_field' ),
						'show_on_cb' => array( $this, 'cmb_only_show_for_no_votes' ),
					),	
					//usefulness
					array(
						'name' => __( 'Usefulness', 'ht-knowledge-base' ),
						'description' => __( 'Set the usefulness for this article (editing may cause inconsistencies with voting)', 'ht-knowledge-base' ),
						'id'   => '_ht_kb_usefulness',
						'type' => 'text',
						// custom sanitization callback parameter
						//none
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


		function cmb_only_show_for_voting_enabled(){
			global $ht_knowledge_base_options;

			return ( isset($ht_knowledge_base_options) && array_key_exists('voting-display', $ht_knowledge_base_options) && $ht_knowledge_base_options['voting-display'] );
		}

		function cmb_only_show_for_votes(){
			return ($this->cmb_only_show_for_voting_enabled() && $this->cmb_only_show_for_no_votes()==false);
		}

		function cmb_only_show_for_no_votes(){
			//return false if no voting
			if($this->cmb_only_show_for_voting_enabled() == false)
				return false;


			$votes = get_post_meta( get_the_ID(), HT_VOTING_KEY, true );
			return empty($votes);

		}

		function santize_reset_field($new_value, $args, $field){
			if($new_value=='on'){
				//clear votes
				delete_post_meta( get_the_ID(), HT_VOTING_KEY );
				delete_post_meta( get_the_ID(), HT_USEFULNESS_KEY );
				update_post_meta( get_the_ID(), HT_USEFULNESS_KEY, 0 );
			}
			return false;			
		}

		function santize_reset_confirm_field($new_value, $args, $field){
			return true;			
		}


		//upgrade 1.3 -> 1.4
		//transfer _ht_knowledge_base_ prefixed options to _ht_voting_
		/**
		 * Upgrade the meta key values.
		 */
		function maybe_upgrade_meta_fields(){
			HT_Voting::ht_voting_upgrade_post_meta_fields( get_the_ID() );
			//return a false so the dummy does not display
			return false;
		}


		function get_voting_enabled_option(){
			global $ht_knowledge_base_options;
			if($ht_knowledge_base_options && array_key_exists('voting-display', $ht_knowledge_base_options)){
				return $ht_knowledge_base_options['voting-display'];
			} else {
				return false;
			}
			
		}

		




    } //end class

}//end class exists


//run the module
if(class_exists('HT_Voting_Meta_Boxes')){
	$ht_voting_meta_boxes_init = new HT_Voting_Meta_Boxes();
}
