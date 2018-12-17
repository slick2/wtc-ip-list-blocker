<?php
/*
Plugin Name:	WTP IP List Blocker
Plugin URI:		http://plugins.webtuners.pro
Description:	Block access to IP's based on list
Version:		1.0.1
Author:			Carey Dayrit
Author URI:		http://www.slick2.me
*/


add_action( 'init', 'limit_access' );

function limit_access(){
     $ip = $_SERVER['REMOTE_ADDR'];
     $response = wp_remote_get( 'http://iplists.webtuners.pro/api/ip/check/?ip='.$ip);

     if(!empty($response['body'])){
        $result = json_decode($response['body'], true);

        if($result['isListed'] == TRUE){
           header('HTTP/1.0 403 Forbidden');
           exit();
        }

    }  
  
 
}
