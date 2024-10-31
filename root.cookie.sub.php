<?php
/*
Plugin Name: root Cookie Path (subdomains)
Plugin URI: http://www.linickx.com/archives/466/root-cookie-for-wp26root-cookie-for-wp26
Description: Changes the cookie default path to / (i.e. the whole domain.com not just domain.com/blog), and changes the domain to allow for sub-domains to use the cookie (www.domain.com to .domain.com)
Author: Vizion Interactive, Inc. based on code by Nick [LINICKX] Bettison
Version: 1.3
Author URI: http://www.vizioninteractive.com/
*/

# OK, so we rock up and setup a constant....
define('ROOT_COOKIE', '/' );

# Then we paste the WP functions from /wp-includes/pluggable.php
# ...
# and to finish we replace COOKIEPATH, PLUGINS_COOKIE_PATH  and ADMIN_COOKIE_PATH with ROOT_COOKIE, job done!

if ( !function_exists('wp_set_auth_cookie') ) :
/**
 * Sets the authentication cookies based User ID.
 *
 * The $remember parameter increases the time that the cookie will be kept. The
 * default the cookie is kept without remembering is two days. When $remember is
 * set, the cookies will be kept for 14 days or two weeks.
 *
 * @since 2.5
 *
 * @param int $user_id User ID
 * @param bool $remember Whether to remember the user or not
 */
function wp_set_auth_cookie($user_id, $remember = false, $secure = '') {
	if ( $remember ) {
		$expiration = $expire = time() + 1209600;
	} else {
		$expiration = time() + 172800;
		$expire = 0;
	}

	if ( '' === $secure )
		$secure = is_ssl() ? true : false;

	if ( $secure ) {
		$auth_cookie_name = SECURE_AUTH_COOKIE;
		$scheme = 'secure_auth';
	} else {
		$auth_cookie_name = AUTH_COOKIE;
		$scheme = 'auth';
	}

	$auth_cookie = wp_generate_auth_cookie($user_id, $expiration, $scheme);
	$logged_in_cookie = wp_generate_auth_cookie($user_id, $expiration, 'logged_in');

	do_action('set_auth_cookie', $auth_cookie, $expire, $expiration, $user_id, $scheme);
	do_action('set_logged_in_cookie', $logged_in_cookie, $expire, $expiration, $user_id, 'logged_in');
	
	$info = get_bloginfo('url');
	$info = parse_url($info);
	$info = $info['host'];
	$exp = explode('.',$info);
	if(count($exp)==3){$domain = '.'.$exp[1].'.'.$exp[2];}
	elseif(count($exp)==2){$domain = '.'.$info;}
	elseif(3<count($exp)){$exp = array_reverse($exp); $domain = '.'.$exp[1].'.'.$exp[0];}
	else{$domain = COOKIE_DOMAIN;}

	setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $domain, $secure);
	setcookie($auth_cookie_name, $auth_cookie, $expire, ROOT_COOKIE, $domain, $secure);
	setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, ROOT_COOKIE, $domain);
	if ( ROOT_COOKIE != SITECOOKIEPATH )
		setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, SITECOOKIEPATH, $domain);
}
endif;

if ( !function_exists('wp_clear_auth_cookie') ) :
/**
 * Removes all of the cookies associated with authentication.
 *
 * @since 2.5
 */
function wp_clear_auth_cookie() {
	
	$info = get_bloginfo('url');
	$info = parse_url($info);
	$info = $info['host'];
	$exp = explode('.',$info);
	if(count($exp)==3){$domain = '.'.$exp[1].'.'.$exp[2];}
	elseif(count($exp)==2){$domain = '.'.$info;}
	elseif(3<count($exp)){$exp = array_reverse($exp); $domain = '.'.$exp[1].'.'.$exp[0];}
	else{$domain = COOKIE_DOMAIN;}

	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);

	// Old cookies
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);

	// Even older cookies
	setcookie(USER_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(PASS_COOKIE, ' ', time() - 31536000, ROOT_COOKIE, $domain);
	setcookie(USER_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);
	setcookie(PASS_COOKIE, ' ', time() - 31536000, SITECOOKIEPATH, $domain);
}
endif;

?>