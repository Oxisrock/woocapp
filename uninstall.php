<?php

if(! defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

$wpdb->query("DELETE FROM wp_proyects");