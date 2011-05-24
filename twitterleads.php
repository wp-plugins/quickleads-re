<?php
/*
Plugin Name: QuickLeads RE
Plugin URI: http://www.weknowurban.com
Description: Allows visitors to tweet from your Twitter account.
Version: 1.0
Author: We Know Urban
Author URI: http://www.weknowurban.com
License: GPL2
*/
require_once('config.php');

// Add the option page to the settings menu
function twitter_leads_menu() {
	add_options_page('QuickLeads RE Options', 'QuickLeads RE', 'manage_options', 'twitter-leads-menu', 'twitter_leads_settings_page');
	add_action('admin_init', 'register_settings');
}

function register_settings() {
  register_setting('twitter_leads_settings_group', 'twitter_leads_email_body');
  register_setting('twitter_leads_settings_group', 'twitter_leads_email_subject');
  register_setting('twitter_leads_settings_group', 'twitter_leads_email_from');
}

function set_anchor_text() {
  $locations = array("Phoenix", "Scottsdale", "Tempe", "Phoenix");
  $dwellings = array("central corridor", "midtown", "downtown", "condo", "condominium", "loft", "highrise");
  $subject = array("Realtor", "Know-it-all", "Guy", "Agent", "Real Estate Agent");
  
  $anchor_text = $locations[array_rand($locations)] . " " . $dwellings[array_rand($dwellings)] . " " . $subject[array_rand($subject)];
  add_option('twitter_leads_anchor_text', $anchor_text);
  
}

// Create the option page for Twitter account information
add_action('admin_menu', 'twitter_leads_menu');

if (get_option('twitter_leads_anchor_text') == null)
  set_anchor_text();
function twitter_leads_settings_page() {
?>
<div class="wrap">
<h2>Quick Leads RE</h2>
<p>People shopping for real estate on the web often email or call multiple agents until they get hold of someone.  The first agent to respond to the inquiry almost always “wins” the business. Quick Leads RE shoots your leads to you via text message within seconds, giving you a huge advantage over your competition. Real estate teams and small brokerages can use Quick Leads RE to send incoming leads to multiple agents within the team or brokerage at the same time which results in an extremely fast response time. Management can see all incoming leads on one simple web page to monitor response times and lead to closing conversion. Quick Leads RE uses Twitter to handle text message distribution and lead monitoring for a very stable, reliable and easy to use system.</p>
<p><em>NOTE:  If you currently receive text messages from an existing Twitter page and you are unwilling to deactivate that feature from that page then Quick Leads RE is NOT for you.  Receiving text message leads involves linking a new Twitter Lead page with your cell phone.  Twitter does NOT allow more than one Twitter page to link to one cell number.</em>
<h3>Get Started</h3>
  <ol>
    <li>Follow the Twitter setup instructions below</li>
    <li>Authorize your Twitter account</li>
    <li>Fill out the autoresponder email</li>
  </ol>
  <?php
  if (get_option('twitter_leads_oauth_token') && get_option('twitter_leads_oauth_token_secret')) { ?>
    <p>You are successfully authorized with Twitter. 
    <a href="<?= TWITTER_LEADS_PATH ?>deauthorize.php">Disconnect Twitter Account</a></p>
  <?php } else { ?>
    <a href="<?= TWITTER_LEADS_PATH ?>authorize.php">Authorize Twitter Account</a>
  <?php } ?>
  <br/>
  <h3>Email Autoresponder Options</h3>
  <form method="post" action="options.php">
    <?php settings_fields( 'twitter_leads_settings_group' ); ?>
    
    <label>Email Body:</label>
    <p><textarea style="height: 200px; width:250px;" name="twitter_leads_email_body"><?php echo get_option('twitter_leads_email_body'); ?></textarea></p>
    
    <label>Email Subject:</label><br />
    <p><input style="width:250px" type="text" name="twitter_leads_email_subject" value="<?php echo get_option('twitter_leads_email_subject'); ?>" /></p>
    
    <label>Email From Address:</label><br />
    <p><input style="width:250px" type="text" name="twitter_leads_email_from" value="<?php echo get_option('twitter_leads_email_from'); ?>" /></p>
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </form>
  <br/>
  <h3>Video Instructions</h3>
  <h4>Part 1</h4>
  <iframe width="560" height="349" src="http://www.youtube.com/embed/IhDj2qzz7ac" frameborder="0" allowfullscreen></iframe>
  
  <h4>Part 2</h4>
  <iframe width="560" height="349" src="http://www.youtube.com/embed/h_ELexLKEh4" frameborder="0" allowfullscreen></iframe>
  
  <h4>Part 3</h4>
  <iframe width="560" height="349" src="http://www.youtube.com/embed/GHQxNd5cAGU" frameborder="0" allowfullscreen></iframe>
  
  <h3>Written Instructions</h3>
  <ol>
    <li>Create a new Twitter account that will be used to house and distribute all your leads.  We call this the Leads Page.  Go to <a href="http://www.twitter.com">Twitter.com</a> and click the “Sign Up” button on the right side of the page.</li>
    <li>Fill out the new Leads Page.  Create a “Username” that is short but descriptive.  After you click “Create My Account” at the bottom of the page you will receive an email with a confirmation that the account has been created.</li>
    <li>Go to edit your profile</li>
    <li>Be sure to add an email address here AND check the box</li>
    
    <li>Twitter will now check to see if the phone number you entered is already linked to receive text messages from another Twitter page.  If so, then you will receive instructions on how to break that link.  The instructions will be to text the word “STOP” to 40404.  You will receive a confirmation that it has stopped.  Once that is done, reenter the cell phone number into the page/step above and hit start again.  Twitter will again check to see if the phone number you entered is still linked to receive text messages from another Twitter page.  If not, then you will be instructed to text the word “GO” to 40404.  Once you receive confirmation then you really are..... good to go!  :-)  </li>
    
    <li>Although Twitter typically recognizes the cell phone link right away we have seen it take a couple hours.  To check it simply click the “Home” link at the top of the page.  There type a simple tweet like “test” in the “What's happening box” and click the “Tweet” button.  The word “Tweet” will appear on the home page and you should receive a message on your cell phone something like this – “Your Username: Test”.  Once this happens you are now ready to receive leads from your WordPress blog to your cell phone. </li>
  </ol>
</div>
<?php }

class Twitter_Leads_Widget extends WP_Widget {
  function Twitter_Leads_Widget() {
    $widget_ops = array('classname' => 'widget_twitter_leads', 'description' => 'Form for public tweets');
    $control_ops = array('id_base' => 'twitter-leads-widget');
    $this->WP_Widget('twitter-leads-widget', 'QuickLeads RE', $widget_ops, $control_ops);
  }
  
  function widget($args, $instance) {
    extract($args); 
    if (get_option('twitter_leads_oauth_token') && get_option('twitter_leads_oauth_token_secret') && get_option('twitter_leads_anchor_text') && get_option('twitter_leads_email_from') && get_option('twitter_leads_email_body') && get_option('twitter_leads_email_subject')) { ?>
      <div style="border:solid 2px #bcd; padding: 10px; margin-bottom: 10px; background: #def">
        <?php echo($_SESSION['quickleads']); ?>
        <?php if ($_SESSION['quickleads'] == 'success') { 
          $_SESSION['quickleads'] = '';
          ?>
          <div style="text-align:center; color: #000;"><em>Message successfully sent</em></div>
        <?php } ?>
        <form method="post" action="<?= TWITTER_LEADS_PATH ?>twitter-leads-post.php" id="twitter_leads_form" name="twitter_leads_form">
          <p style="font-weight:bold; margin-bottom: 5px; color: #000">Reach me NOW!</p>
          <textarea style="width: 95%; height: 100px; margin-bottom: 15px;" name="twitter_leads_message" id="twitter_leads_message" ></textarea>
          <p style="font-weight:bold; margin-bottom: 5px; color: #000">Email:</p>
          <input type="text" style="width: 95%; margin-bottom: 10px" name="twitter_leads_email" id="twitter_leads_email" />
          <p style="font-weight:bold; margin-bottom: 5px; color: #000">Phone Number:</p>
          <input type="text" style="width: 95%; margin-bottom: 10px" name="twitter_leads_phone" id="twitter_leads_phone" />
          <div style="text-align: center; margin-bottom: 5px;"><input type="submit" value="Send message"/></div>
          <div style="text-align: center;">Powered by: <a href="http://www.weknowurban.com"><?php echo(get_option('twitter_leads_anchor_text')) ?></a></div>
        </form>
      </div>
      <?php
    }
  }
}
function register_twitter_leads_widget() {
  register_widget('Twitter_Leads_Widget');
}
add_action('widgets_init', 'register_twitter_leads_widget');
?>