=== Contextual Advertisement Plugin ===
Contributors: mc2pl
Tags: ads, ad, wordpress ad, wordpress advertisement, wordpress reklama, reklama na stronie, reklama na blogu, saas
Requires at least: 4.4
Tested up to: 5.1.1
Stable tag: 5.1.1
License: LGPLv3 or later
Requires PHP: 5.4
License URL: http://www.gnu.org/licenses/lgpl-3.0.html

== Description ==

This plugin allows context360 advertisement platform to manage contextual text links on wordpress website.

== Installation ==

== External tools ==

1. We use Google reCaptcha V3 technology to stop robots from random registrations (a form of attack).
   Accessing endpoint `https://www.recaptcha.net/recaptcha/api.js` serves that purpose.
2. We use our API endpoint, hosted on `context360.net` domain.
3. We use Chart.js is open source and available under the MIT license.

== External HTML source code ==

This plugin injects HTML source code on end-user's page. That source code is injected in `head` and `body` part
(posts content, to be clear). The origin of those data is http://api.contexter.net . When registration is successfully
completed we deliver this source code when user enters `/wp-admin/admin.php?page=my-submenu-zaawansowane` admin page
and is stored in Wordpress database.

Later our visitors will have those HTML sections injected in their HTML source code in order to attach
ads to their pages/posts.

== Frequently Asked Questions ==

1) What is the main WWW address for platform:

https://www.context360.net/

== Upgrade Notice ==

No upgrades yet.

== Screenshots ==

1. This screen shot description corresponds registration
/context360/assets/images/registration.png

2. This screen shot description corresponds placements
/context360/assets/images/placements.png

. This screen shot description corresponds statistics
/context360/assets/images/statistics.png

== Changelog ==

= 1.2.0 =

Initial submission to WordPress.org for approval
