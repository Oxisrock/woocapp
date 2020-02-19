<?php
 
$post_data = file_get_contents("php://input");
 
if ( empty( $post_data ) ) {
    http_response_code( 400 );
    die;
}
 
$post_data = json_decode( $post_data );
 
if( empty( $post_data->key_id ) ) {
    http_response_code( 400 );
    die;
}
 
require_once( $_SERVER['DOCUMENT_ROOT'] . '/inc/setup.php' );
 
$user_id = (int) filter_var( $post_data->user_id, FILTER_SANITIZE_NUMBER_INT );
$consumer_key = filter_var( $post_data->consumer_key, FILTER_SANITIZE_STRING );
$consumer_secret = filter_var( $post_data->consumer_secret, FILTER_SANITIZE_STRING );
 
$insert_user_keys = iconic_db_insert_user_keys( $user_id, $consumer_key, $consumer_secret );
 
if ( ! $insert_user_keys ) {
    http_response_code( 500 );
    die;
}