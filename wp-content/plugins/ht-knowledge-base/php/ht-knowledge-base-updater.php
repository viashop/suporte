<?php

/**
* Plugin updater
*/

set_site_transient( 'update_plugins', null );

//Hero Themes site url and product name
define( 'HT_STORE_URL', 'https://www.herothemes.com/?nocache' );
define( 'HT_KB_ITEM_NAME', 'Heroic Knowledge Base WordPress Plugin' ); 

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater
    include( dirname(dirname( __FILE__ )) . '/sl-updater/EDD_SL_Plugin_Updater.php' );
}

if (!class_exists('HT_Knowledge_Base_Updater')) {

    class HT_Knowledge_Base_Updater {

        /**
         * Constructor
         */
        function __construct(){
            //init updater
            add_action( 'admin_init', array($this, 'ht_kb_updater' ), 0 );
            //filter the option sections
            add_filter('ht_kb_option_sections_1', array($this, 'ht_kb_license_options_sections_array'), 800);
        }

        /**
        * Create the updater
        */
        function ht_kb_updater() {
            //@todo - don't display options tab if current theme supports ht-kb-theme-managed-updates
            if( ( current_theme_supports('ht_kb_theme_managed_updates') || current_theme_supports('ht-kb-theme-managed-updates') ) ){
                return;
            }

            // retrieve our license key from the DB
            $license_key = trim( get_option( 'ht_kb_license_key' ) );
            // setup the updater
            $edd_updater = new EDD_SL_Plugin_Updater( HT_STORE_URL, HT_KB_MAIN_PLUGIN_FILE, array( 
                    'version'   => HT_KB_VERSION_NUMBER,               // current version number
                    'license'   => $license_key,        // license key (used get_option above to retrieve from DB)
                    'item_name' => HT_KB_ITEM_NAME,    // name of this plugin
                    'author'    => 'Hero Themes'  // author of this plugin
                )
            );
        }


        /**
        * Filter the options menu
        * @param $sections (Array) The options array to filter
        */
        function ht_kb_license_options_sections_array($sections){
            if( ( current_theme_supports('ht_kb_theme_managed_updates') || current_theme_supports('ht-kb-theme-managed-updates') ) ){
                //none supporting theme
                $sections[] =  array(
                    'title'     => __('License and Updates', 'ht-knowledge-base'),
                    'desc'      => __('Updates of this plugin are controlled by the theme, you do not need to do anything on this page' , 'ht-knowledge-base'),
                    'icon'      => 'el-icon-key',
                    'fields'    => array(),
                );
            } else {
                $ht_kb_license_key = get_option( 'ht_kb_license_key' );
                $default_license_status_text = '';
                if(empty($ht_kb_license_key)){
                    $default_license_status_text = __('Please enter your license key below to enable support and updates, this is contained in your download email', 'ht-knowledge-base');
                } else {
                    $default_license_status_text = $ht_kb_license_key;
                    $default_license_status_text = sprintf(__('Unverified, Inactive or Expired - check you license status on your account at <a href="%s" target="_blank">Hero Themes</a>' , 'ht-knowledge-base'), 'http://www.herothemes.com');
                }
                //supporting theme 
                $ht_kb_license_status = get_option('ht_kb_license_status');
                $ht_kb_license_status_form = empty($ht_kb_license_status) ? '' : '(' . $ht_kb_license_status . ') ';

                $ht_kb_license_status_text = ('valid'==$ht_kb_license_status) ? __('Valid and Active', 'ht-knowledge-base') : $ht_kb_license_status_form . $default_license_status_text;

                //counter transients
                $ht_kb_license_function = get_transient( '_ht_kb_license_function' );
                if(isset($ht_kb_license_function)){
                    __('Updating - Refresh page to see license status', 'ht-knowledge-base');
                }

                $ht_kb_license_settings_fields = array(
                                            array(
                                                'id'        => 'ht-kb-license-info',
                                                'type'      => 'info',
                                                'title'     => __('License Status', 'ht-knowledge-base'),
                                                'subtitle'  => __('The status of your license', 'ht-knowledge-base'),
                                                'desc'      => $ht_kb_license_status_text,
                                            ),

                                            array(
                                                'id'        => 'ht-kb-license',
                                                'type'      => 'text',
                                                'title'     => __('License Key', 'ht-knowledge-base'),
                                                'subtitle'  => __('Enter your Heroic Knowledge Base license key', 'ht-knowledge-base'),
                                                'validate_callback' => 'ht_kb_license_key_callback',
                                            ),
                                            

                                        );

                $sections[] =  array(
                    'title'     => __('License and Updates', 'ht-knowledge-base'),
                    'desc'      => __('Enter plugin license details on this page', 'ht-knowledge-base'),
                    'icon'      => 'el-icon-key',
                    'fields'    => $ht_kb_license_settings_fields,
                );

            }

            return $sections;
        }  

        /**
        * Attempt to activate license
        * @param $sections (String) The license key to activate
        */
        public static function activate_license($key=''){
            if(empty($key)){
                return;
            }

            $license_key = $key;

            // data to send in our API request
            $api_params = array( 
                'edd_action'=> 'activate_license', 
                'license'   => $license_key, 
                'item_name' => urlencode( HT_KB_ITEM_NAME ) // the name of our product in EDD
            );
            
            // call custom EDD API
            $response = wp_remote_get( add_query_arg( $api_params, HT_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

            // make sure the response came back okay
            if ( is_wp_error( $response ) ){
                $error = true;
            }
                

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            
            // $license_data->license will be either "valid" or "invalid"
            update_option( 'ht_kb_license_status', $license_data->license );
            //if valid, check if an update is required

            return;

        }

        /**
        * Attempt to deactivate license
        * @param $key (String)  The license key to deactivate
        */
        public static function deactivate_license($key=''){
            if(empty($key)){
                return;
            }

            $license_key = $key;

            // data to send in our API request
            $api_params = array( 
                'edd_action'=> 'deactivate_license', 
                'license'   => $license_key, 
                'item_name' => urlencode( HT_KB_ITEM_NAME ) // the name of our product in EDD
            );

            
            // call custom EDD API
            $response = wp_remote_get( add_query_arg( $api_params, HT_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

            // make sure the response came back okay
            if ( is_wp_error( $response ) )
                return false;

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            
            // $license_data->license will be either "deactivated" or "failed"
            if( $license_data->license == 'deactivated' ){
                delete_option( 'ht_kb_license_status' );
            } else {
                //remove license status, even on failed response
                delete_option( 'ht_kb_license_status' );
            }

            return;    
        }

        /*
        * Check license validity
        * @param $key (String)  The license key to check
        */
        public static function check_license($key='') {
            global $wp_version;

            if(empty($key)){
                return;
            }

            $license_key = $key;
                
            $api_params = array( 
                'edd_action' => 'check_license', 
                'license' => $license_key, 
                'item_name' => urlencode( HT_KB_ITEM_NAME ),
                'url'       => home_url()
            );

            // call custom EDD API
            $response = wp_remote_get( add_query_arg( $api_params, HT_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );


            if ( is_wp_error( $response ) )
                return false;

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );

            if( $license_data->license == 'valid' ) { 
                // this license is still valid, do nothing
            } else {
                // this license is no longer valid, delete status
                delete_option( 'ht_kb_license_status' );
            }
        }

    }//end class 

}//end class exists


if (class_exists('HT_Knowledge_Base_Updater')) {
    $ht_knowledge_base_updater_init  = new HT_Knowledge_Base_Updater();
}

if(!function_exists('ht_kb_license_key_callback')){
    /**
    * Callback for the license key
    * @param $field n/a
    * @param $value The new value
    * @param $existing_value The existing value
    * @return The filtered value
    */
    function ht_kb_license_key_callback($field, $value=' ', $existing_value) {

        $error = false;

        //perform a trim
        $value = trim($value);

        //get the existing license status
        $ht_kb_license_status = get_option('ht_kb_license_status');
        
        if( $value != $existing_value || 'valid' != $ht_kb_license_status ){
            //value has changed so remove license status value or value hasn't changed, but if the license status is still invalid, try to activate again
            delete_option( 'ht_kb_license_status' );
            update_option( 'ht_kb_license_key', $value );
            //activate
            HT_Knowledge_Base_Updater::activate_license($value);
        } else {
            //just check license validity
            HT_Knowledge_Base_Updater::check_license($value);
        }

        $return['value'] = $value;

        if ($error == true) {
            $return['error'] = $field;
        }


        return $return;
    }
}





