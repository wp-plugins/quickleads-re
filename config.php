<?php
define('TWITTER_LEADS_PATH', get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/');
define('OPTIONS_PAGE', get_option('siteurl') . '/wp-admin/options-general.php?page=twitter-leads-menu');
define('CONSUMER_KEY', 'aFvnBCs9aquCW6e4yIBCFQ');
define('CONSUMER_SECRET', '8XiBwNj7Bt4RO16Gld5h7w6Cr9Otjacfr2iSOgT6kQ');
define('OAUTH_CALLBACK', TWITTER_LEADS_PATH . 'callback.php');
?>