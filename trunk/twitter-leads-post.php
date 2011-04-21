<?php
require_once('load-wp.php');
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
session_start();

$message = $_POST['twitter_leads_message'];
$phone = $_POST['twitter_leads_phone'];
$email = $_POST['twitter_leads_email'];
process_message($message, $phone, $email);
send_email($email);
$_SESSION['quickleads'] = "success";
$location = $_SERVER['HTTP_REFERER'];
header("Location: $location");

function process_message($message, $phone, $email) {
  $full_message = $message . " - " . $phone . " " . $email;
  // 130 chars gives room for e.g. 1/2 text
  $tweet_length = 130;
	$current_tweet = 1;
	$total_length = strlen($full_message);
	$total_tweets = ceil($total_length / $tweet_length);
	$index = 0;
		
  while ($current_tweet <= $total_tweets) {
    send_tweet("$current_tweet/$total_tweets " . substr($full_message, $index, $index + $tweet_length));
    $index += $tweet_length;
		$current_tweet++;
  }
}

function send_email($email) {
  mail($email, get_option('twitter_leads_email_subject'), get_option('twitter_leads_email_body'), "From: <" . get_option('twitter_leads_email_from') . ">\nReply-To: WeKnowUrban <" . get_option('twitter_leads_email_from') . ">\nX-Mailer: chfeedback.php 2.01" );
  
}
function send_tweet($tweet) {  
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, get_option('twitter_leads_oauth_token'), get_option('twitter_leads_oauth_token_secret'));
  $connection->post('statuses/update', array('status' => $tweet));
}
?>