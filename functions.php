<?php
/*
Plugin Name: Skule Network
Plugin URI: http://sws.skule.ca/
Description: Plugin included by all Skule Network blogs.
Version: 0.15
Author: Rafal Dittwald
Author URI: http://sws.skule.ca/
License: MIT
*/
add_action('wp_print_styles', 'add_my_stylesheet');

function add_my_stylesheet() {
	$myStyleUrl = WP_PLUGIN_URL . '/skule_network/style.css';
        $myStyleFile = WP_PLUGIN_DIR . '/skule_network/style.css';
        if ( file_exists($myStyleFile) ) {
            wp_register_style('skule_network_css', $myStyleUrl);
            wp_enqueue_style( 'skule_network_css');
	}
}

remove_action( 'bp_adminbar_menus', 'bp_adminbar_login_menu', 2 );
add_action( 'bp_adminbar_menus', 'my_bp_adminbar_login_menu', 2 );

function my_bp_adminbar_login_menu() {
	global $bp;

	if ( is_user_logged_in() )
		return false;

	echo '<li class="bp-login no-arrow"><a href="' . $bp->root_domain . '/wp-login.php?redirect_to=' . urlencode( "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ) . '">' . __( 'Log In', 'buddypress' ) . '</a></li>';

	// Show "Sign Up" link if user registrations are allowed
	if ( bp_get_signup_allowed() ) {
		echo '<li class="bp-signup no-arrow"><a href="' . bp_get_signup_page(false) . '">' . __( 'Sign Up', 'buddypress' ) . '</a></li>';
	}
}

remove_action( 'bp_adminbar_menus', 'bp_adminbar_blogs_menu', 6);
remove_action( 'bp_adminbar_menus', 'bp_adminbar_account_menu', 4 );
remove_action( 'bp_adminbar_menus', 'bp_adminbar_notifications_menu', 8 );
//remove_action( 'bp_adminbar_menus', 'bp_adminbar_authors_menu', 12 );
remove_action( 'bp_adminbar_menus', 'bp_adminbar_random_menu', 100 );

add_action ('bp_adminbar_menus', 'bp_adminbar_my_skule_menu', 10);


function bp_adminbar_my_skule_menu() {
	global $bp;
	if ( !is_user_logged_in() )
		return false;

	if ( !$blogs = wp_cache_get( 'bp_blogs_of_user_' . $bp->loggedin_user->id . '_inc_hidden', 'bp' ) ) {
		$blogs = bp_blogs_get_blogs_for_user( $bp->loggedin_user->id, true );
		wp_cache_set( 'bp_blogs_of_user_' . $bp->loggedin_user->id . '_inc_hidden', $blogs, 'bp' );
	}

	echo '<li id="bp-adminbar-my-skule-menu"><a href="' . bp_loggedin_user_domain() . '">';

	echo __( 'My Skule', 'buddypress' ) . '</a>';
	echo '<ul>';

if ( is_array( $blogs['blogs'] ) && (int)$blogs['count'] ) {
		$counter = 0;
		foreach ( (array)$blogs['blogs'] as $blog ) {
			$alt = ( 0 == $counter % 2 ) ? ' class="alt"' : '';

			echo '<li' . $alt . '>';
			echo '<a href="' . esc_attr( $blog->siteurl ) . '">' . esc_html( $blog->name ) . '</a>';

			echo '<ul>';
			echo '<li class="alt"><a href="' . esc_attr( $blog->siteurl ) . 'wp-admin/">' . __( 'Dashboard', 'buddypress' ) . '</a></li>';
			echo '<li><a href="' . esc_attr( $blog->siteurl ) . 'wp-admin/post-new.php">' . __( 'New Post', 'buddypress' ) . '</a></li>';
			echo '<li class="alt"><a href="' . esc_attr( $blog->siteurl ) . 'wp-admin/edit.php">' . __( 'Manage Posts', 'buddypress' ) . '</a></li>';
			echo '<li><a href="' . esc_attr( $blog->siteurl ) . 'wp-admin/edit-comments.php">' . __( 'Manage Comments', 'buddypress' ) . '</a></li>';
			echo '</ul>';

			echo '</li>';
			$counter++;
		}
	}

	$alt = ( 0 == $counter % 2 ) ? ' class="alt"' : '';

	echo '<li' . $alt . '><a id="bp-admin-account" href="' . bp_loggedin_user_domain() . '">' . __( 'My Profile', 'buddypress' ) . '</a></li>';
	echo '<li' . $alt . '><a id="bp-admin-logout" class="logout" href="' . wp_logout_url(site_url()) . '">' . __( 'Log Out', 'buddypress' ) . '</a></li>';
	echo '</ul>';
	echo '</li>';
}



add_filter('upload_mimes', 'custom_upload_mimes');

function custom_upload_mimes ( $existing_mimes=array() ) {
 
$existing_mimes['doc'] = 'application/msword';
$existing_mimes['xls'] = 'application/msexcel';
$existing_mimes['ppt'] = 'application/mspowerpoint';
$existing_mimes['pdf'] = 'application/pdf';
$existing_mimes['mp3'] = 'audio/x-mpeg';
$existing_mimes['mov'] = 'video/quicktime';
$existing_mimes['wav'] = 'audio/x-wav';
$existing_mimes['avi'] = 'video/msvideo';
$existing_mimes['mpeg'] = 'video/mpeg';
$existing_mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
$existing_mimes['xlsx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
$existing_mimes['pptx'] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
$existing_mimies['bmp'] = 'image/bmp';
  
return $existing_mimes;
 
}

?>
