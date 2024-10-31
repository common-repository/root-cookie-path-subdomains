=== root Cookie Path (subdomains) ===
Contributors: sc0ttkclark, Nick  Bettison - LINICKX
Tags: login, logout, authentication, cookie, root, subdomain, path
Requires at least: 2.6
Tested up to: 2.7
Stable tag: 1.3

Changes the cookie default path to / (i.e. the whole domain.com not just domain.com/blog), and changes the domain to allow for sub-domains to use the cookie (www.domain.com to .domain.com)

== Description ==

If you want to integrate the wordpress authentication magic into another script within your domain you may come across authentication issues because your code cannot read the wordpress cookie.

By default the wordpress cookie exactly matches the URL of your installation, this plugin removes any subfolders from the cookie so that your whole domain has access to it. Modified from the original "root Cookie Path" plugin, you can now extend this same cookie functionality into your sub-domains.

For Example if you have wordpress installed in http://one.mydomain.com/wordpress any php stored in http://two.mydomain.com/mymagiccode cannot see the cookie due to browser security. This plugin changes the path to http://*.mydomain.com so that any php code on your site can access the cookie, so in our above example http://*.mydomain.com/mymagiccode/test.php can now check the cookie to see if you have logged into wordpress. This however will not work if both sites do not share the same user / password combination.

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP plugin page)
1. Activate this plugin

OR you can just install it with WordPress by going to Plugins >> Add New >> and type root Cookie Path (subdomains)