<?php
/**
* Voting options - will hook onto a redux framework options array
*/


if(!class_exists('HT_Voting_Options')){

    class HT_Voting_Options{



        /**
         * Constructor
         */
        public function __construct() {
            //filter the option sections
            add_filter('ht_kb_option_sections_1', array($this, 'filter_options_sections_array'));
        }

        /**
        * Filter the options menu
        * @param $sections (Array) The options array to filter
        */
        function filter_options_sections_array($sections){

            $voting_settings_fields = array(
                                            array(
                                                'id'        => 'voting-display',
                                                'type'      => 'switch',
                                                'title'     => __('Enable Voting', 'ht-voting'),
                                                'subtitle'  => __( 'Allow readers to vote', 'ht-voting' ),
                                                'default'   => true,
                                            ),
                                            array(
                                                'id'        => 'anon-voting',
                                                'type'      => 'switch',
                                                'title'     => __('Enable Anonymous', 'ht-voting'),
                                                'subtitle'  => __('Allow users to vote that are not logged in', 'ht-voting'),
                                                'default'   => true,
                                                'required'  => array('voting-display', "=", 1),
                                            ),

                                        );

            $sections[] =  array(
                    'title'     => __('Voting Options', 'ht-voting'),
                    'desc'      => __('Set various options for the voting plugin', 'ht-voting'),
                    'icon'      => 'el-icon-thumbs-up',
                    'fields'    => $voting_settings_fields,
                );


            return $sections;
        }    
        
    }

}

if(class_exists('HT_Voting_Options')){
    $ht_voting_options_init = new HT_Voting_Options();
}
