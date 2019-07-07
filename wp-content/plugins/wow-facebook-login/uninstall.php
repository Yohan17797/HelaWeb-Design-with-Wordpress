<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
delete_option( 'wow_fb_login' );