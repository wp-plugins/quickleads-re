<?php
session_start();
require_once('load-wp.php');
require_once('config.php');

delete_option('twitter_leads_oauth_token');
delete_option('twitter_leads_oauth_token_secret');
delete_option('twitter_leads_anchor_text');
header('Location: ' . OPTIONS_PAGE); 
