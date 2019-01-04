<?php
/*
Plugin Name:	WTP IP List Blocker
Plugin URI:		http://plugins.webtuners.pro
Description:	Block access to IP's based on list
Version:		1.0.4
Author:			Carey Dayrit
Author URI:		http://www.slick2.me
*/


add_action( 'init', 'limit_access' );

function limit_access(){
    $options = get_option('wtp_ipblock_options');
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $response = wp_remote_get( 'https://check.youmake.net/api/ip/check/?ip='.$ip.'&key='.$options['api_key'],  array( 'timeout' => 30)) );

    if(!empty($response['body'])){
        $result = json_decode($response['body'], true);

        if($result['isListed'] == TRUE){
           header('HTTP/1.0 403 Forbidden');
           exit();
        }
    }    
 
}
/*
function wtp_ipblock_create_options(){
    add_option('wtp_ipblock_options', array(
        'api_key'=> ''
    );
        
}
*/
add_action('admin_menu', 'wtp_ipblock_add_page');

function wtp_ipblock_add_page(){
    add_options_page('WTP IP Block', 'IP Block', 'manage_options', 'wtp_ipblock', 'wtp_ipblock_options_page');
}

function wtp_ipblock_options_page(){
    ?>
    <div class="wrap">
        <?php screen_icon();?>
        <form action="options.php" method="post" class="form-table">

                        <?php settings_fields('wtp_ipblock_options');?>
                        <?php do_settings_sections('wtp_ipblock'); ?>
            <p class="submit">
                    
                        <input name="Submit" type="submit" value="Save Changes" class='button-primary' />
            </p>
        </form>
    </div>
    <?php
}


add_action('admin_init', 'wtp_ipblock_admin_init');

function wtp_ipblock_admin_init(){
    register_setting('wtp_ipblock_options', 'wtp_ipblock_options', 'wtp_ipblock_validate_options');
    add_settings_section('wtp_ipblock_main', 'IP Block API Settings', 'wtp_ipblock_section_text', 'wtp_ipblock');
    add_settings_field('wtp_ipblock_text_string', "<label for='api_key'>Enter API Key:</label>", 'wtp_ipblock_setting_input', 'wtp_ipblock', 'wtp_ipblock_main');
}

function wtp_ipblock_section_text(){
    //echo '<p>Enter your settings here.</p>';
}

function wtp_ipblock_setting_input(){
    $options = get_option('wtp_ipblock_options');
    $api_key = $options['api_key'];
    echo "<input id='api_key' name='wtp_ipblock_options[api_key]' type='text' value='$api_key' class='regular-text' />";
}

function wtp_ipblock_validate_options($input){
    $valid = array();
    $valid['api_key'] = preg_replace('/[^a-zA-Z0-9]/', '', $input['api_key']);
    
    return $valid;
}


