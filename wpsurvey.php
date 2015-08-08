<?php
/*
Plugin Name: Popup Surveys & Polls
Plugin URI: http://www.ericsestimate.com
Description: Popup surveys lets you ask visitors questions about your site. Use this feedback to build a better blog or business.
Version: 1.04
Author: dusthazard
Author URI: http://www.ericsestimate.com
License: GPL2

    Copyright 2015  Eric Sloan  (contact : http://www.ericsestimate.com/contact-eric/)

    This software is released under GPL

*/

$versionNumber = "1.04";

include("includes/optionspage.php");
include("config.php");
include("includes/functions.php");

function wps_install() {
	global $wpdb, $wps_db_version, $survey_table_name, $question_table_name, $session_table_name, $response_table_name;
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $survey_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		name text NOT NULL,
		description varchar(128) DEFAULT '' NOT NULL,
		survey_type text NOT NULL,
		behavior varchar(16) DEFAULT '' NOT NULL,
		behavior_url varchar(500) DEFAULT '' NOT NULL,
		website_side varchar(8) DEFAULT 'right' NOT NULL,
		custom_thank_you varchar(256) DEFAULT '' NOT NULL,
		display_seconds mediumint(4) DEFAULT 0 NOT NULL,
		survey_views mediumint(7) DEFAULT 0 NOT NULL,
		status mediumint(1) DEFAULT 0 NOT NULL,
		display_per_visitor mediumint(4) DEFAULT 1 NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;
    CREATE TABLE $question_table_name ( 
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		survey_id mediumint(9) NOT NULL,
		time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		name text NOT NULL,
		step_number mediumint(9) DEFAULT 1 NOT NULL,
		description varchar(128) DEFAULT '' NOT NULL,
		action varchar(12) DEFAULT '' NOT NULL,
		action_value varchar(100) DEFAULT '' NOT NULL,
		question_type text NOT NULL,
		question_views mediumint(7) DEFAULT 0 NOT NULL,
		answer_type text NOT NULL,
		answer_value varchar(500) DEFAULT '' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;
    CREATE TABLE $session_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		survey_id mediumint(9) NOT NULL,
		time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		ip_address varchar(15) DEFAULT '000.000.000.000' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;
    CREATE TABLE $response_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		survey_id mediumint(9) NOT NULL,
		question_id mediumint(9) NOT NULL,
		time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		response varchar(500) DEFAULT '' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'wps_db_version', $wps_db_version );
}

function wps_install_data() {
	global $wpdb;
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	$table_name = $wpdb->prefix . 'liveshoutbox';
	$wpdb->insert( $table_name, array('time' => current_time( 'mysql' ),'name' => $welcome_name,'text' => $welcome_text,) );
}
register_activation_hook( __FILE__, 'wps_install' );

function wps_survey_code() {
  global $wpdb, $survey_table_name, $question_table_name, $session_table_name, $response_table_name, $versionNumber;
  $qry = "SELECT * FROM $survey_table_name WHERE status = 1";
  $surveyArray = $wpdb->get_results( $qry );
  if(count($surveyArray) != 0) {
    /*
    // For controlling URLs
    foreach($surveyArray as $key => $surveyObj) {
      if($surveyObj->behavior != 'all') {
        // unset if not all for now
        unset($surveyArray[$key]);
      }
    }
    */
    if($_COOKIE['wps_survey_done']) {
      $doneSurveys = explode(",",urldecode($_COOKIE['wps_survey_done']));
      foreach($surveyArray as $key => $surveyObj) {
        foreach($doneSurveys as $surveyToRemove) {
          if($surveyToRemove == $surveyObj->id) {
            unset($surveyArray[$key]);
          }
        }
      }
      $surveyArray = array_values($surveyArray);
    }
    
    $totalElements = count($surveyArray) - 1;
    $randomSurvey = rand(0,$totalElements);
    $chosenSurvey = $surveyArray[$randomSurvey];
    
    $visitorViewCount = array_count_values(explode(",",urldecode($_COOKIE['wps_survey_session'])));
    
    if($chosenSurvey->display_per_visitor > $visitorViewCount[$chosenSurvey->id]) {
    
      $qry = "SELECT * FROM $question_table_name WHERE survey_id = " . $chosenSurvey->id . " ORDER BY step_number ASC";
      $questionArray = $wpdb->get_results( $qry );
      
      $thisQuestion = $questionArray[0];
      
      $answerType = $thisQuestion->answer_type;
      $answerValue = unserialize($thisQuestion->answer_value);

      $wpsSurveyId = $chosenSurvey->id;
      $wpsQuestionId = $thisQuestion->id;
      $wpsQuestionText = $thisQuestion->description;
    
      wpsupdateSurveyViews("survey",$wpsSurveyId);
      wpsupdateSurveyViews("question",$wpsQuestionId);
      
      //$promotionArea = "<p class='linkarea'><small>Build your own <a href='' target='_blank'>Wordpress Survey</a></p>";
      $promotionArea = "";
    
 ?>

    <div id='wps_wp_survey_<?php echo $chosenSurvey->id; ?>' class='wps_wp_survey_box wps_wp_survey_v_<?php echo $versionNumber; ?>'>
      <div id="wps_close_wp_survey_box">x</div>
      <h4><?php echo stripslashes($wpsQuestionText); ?></h4>
      <div id='wps_answer_box_<?php echo $wpsQuestionId; ?>' class="wps_wp_question_box">
        <?php
        
          switch ($answerType) {
          
              case "Multiple" : 
                    echo "<div id='wps_survey_type_multiple'>";
                    echo  "<ul class='wps_multiple_choice_list'>";
                    $answerId = 0;
                    foreach ($answerValue as $answerText) {
                           $answerId++;
                           echo "<li id='answer_number_$answerId' class='wps_multiple_choice'>".stripslashes($answerText)."</li>";
                           }
                    echo "</ul>";
                    echo "<div class='wps_submit_area'>$promotionArea<div id='wps_multiple_choice_submit_btn' class='wps_question_submit_btn'>Send</div></div>";
                    echo "</div>";
                    break;
                    
              case "Net Promoter" : 
                    echo "<div id='wps_survey_type_net_promoter'>";
                    echo  "<div class='wps_net_promoter_min'>" . stripslashes($answerValue[1]) . "</div>";
                    echo  "<div class='wps_net_promoter_max'>" . stripslashes($answerValue[2]) . "</div>";
                    echo "<div class='wps_net_promoter_clear'></div>";
                    $i = 0;
                    while ($i < 10) {
                           $i++;
                           echo "<div class='wps_net_promoter_choice'>".$i."</div>";
                           }
                    echo "<div class='wps_submit_area'>$promotionArea<div id='wps_net_promoter_submit_btn' class='wps_question_submit_btn'>Send</div></div>";
                    echo "</div>";
                    break;      
                    
              case "Text Response" : 
                    echo "<div id='wps_survey_type_text_response'>";
                    echo  "<textarea name='wps_text_response'></textarea>";
                    echo "<div class='wps_submit_area'>$promotionArea<div id='wps_text_response_submit_btn' class='wps_question_submit_btn'>Send</div></div>";
                    echo "</div>";
                    break;   
                    
              case "Call To Action" : 
                    echo "<div id='wps_survey_type_cta'>";
                    echo  "<div id='wps_cta_submit_btn' class='wps_question_submit_btn'><a href='".$answerValue[0]."'>".$answerValue[1]."</a></div>";
                    echo  "<div class='wps_submit_area'>$promotionArea</div>";
                    echo "</div>";
                    break;                      
          
            }
        
        ?>
        
      </div>
    </div>
    <script>activateSurvey(<?php echo ($chosenSurvey->display_seconds * 1000); ?>,<?php echo $chosenSurvey->id; ?>);</script>
    
<?php
    }
  }
}
add_action('wp_footer', 'wps_survey_code', 100);

function wpsurvey_scripts() {
	wp_enqueue_style( 'wpsurvey_styles', plugins_url() . '/popup-surveys/includes/style.css' );
	wp_enqueue_script( 'jquery-cookie', plugins_url() . '/popup-surveys/includes/jquery.cookie.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script( 'wpsurvey_scripts', plugins_url() . '/popup-surveys/includes/survey.script.js', array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpsurvey_scripts' );

add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
?>
<script type="text/javascript">var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';</script>
<?php
}