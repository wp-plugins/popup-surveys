<?php

$surveyTable = 'wp_wpSurveyTable';

// add the options page to the menu
add_action('admin_menu', 'wpsurvey_menu');

function wpsurvey_menu() {
	add_menu_page('Popup Surveys Menu', 'Popup Surveys', 'manage_options' , 'wp-survey', 'build_survey_page');
	add_submenu_page('wp-survey', 'New Popup Survey', 'New Survey', 'manage_options', 'wp-survey-new', 'build_new_survey_page' );
	//add_submenu_page('wp-survey', 'WP Survey Options', 'Options', 'manage_options', 'wp-survey-options', 'build_survey_options' );
	add_submenu_page('wp-survey', 'Edit WP Survey', null, 'manage_options', 'wp-survey-edit', 'edit_survey' );
}

// build the options page
function build_survey_page() { 
  global $wpdb, $survey_table_name, $question_table_name, $session_table_name, $response_table_name; 
  if(isset($_GET['activateSurveyId']) || isset($_GET['deactivateSurveyId'])) {
    $surveyToChange = isset($_GET['activateSurveyId']) ? $_GET['activateSurveyId'] : $_GET['deactivateSurveyId'];
    $activateToggle = isset($_GET['activateSurveyId']) ? 1 : 0;
    $wpdb->update(
          $survey_table_name,
          array('status' => $activateToggle),
          array('id' => $surveyToChange)
    );
  }

?>

    <?php include("scripts-styles.php"); ?>
    <div class="wrap">
      <div id="tab_content">
        <div id="tab1_content" class="tab_content">
          <div id="tab_main_content">
            <h2>Wordpress Popup Surveys</h2>
            <p>Your surveys are listed below. Please note that this is the very first version of this plugin. If you see any bugs or would like additional features, please <a href="http://www.ericsestimate.com/contact-eric/">contact me here</a>.</p>
            <div id="list_all_surveys">
            <?php
              // find list of states in DB
              $qry = "SELECT * FROM $survey_table_name ORDER BY status DESC";
              $surveyArray = $wpdb->get_results( $qry );
              foreach($surveyArray as $surveyItem) {
                $qry = "SELECT * FROM $response_table_name WHERE survey_id = " . $surveyItem->id;
                $surveyResponses = $wpdb->get_results($qry);
                $responseCount = count($surveyResponses);
                $wpsStatusClass = ($surveyItem->status == 0) ? "survey_inactive" : "survey_active";
                $activateDeactivateLink = ($surveyItem->status == 0) ? "<a href=\"?page=wp-survey&activateSurveyId=" . $surveyItem->id . "\">Activate Survey</a>" : "<a href=\"?page=wp-survey&deactivateSurveyId=" . $surveyItem->id . "\">Deactivate Survey</a>";
              ?>
                <div id="survey_list_item_<?php echo $surveyItem->id; ?>" class='survey_list_item <?php echo $wpsStatusClass; ?>'>
                  <h3><?php echo $surveyItem->name; ?></h3>
                  <div class=''>
                    <p>Survey Views: <?php echo $surveyItem->survey_views; ?><br />
                       Survey Responses: <?php echo $responseCount; ?></p>
                    <p><a href="?page=wp-survey-new&wpsSurveyId=<?php echo $surveyItem->id; ?>">Edit Survey</a> | <span id="see_results_<?php echo $surveyItem->id; ?>" class="fauxlink show_results_btn">Show Results</span> | <?php echo $activateDeactivateLink; ?></p>
                    <div id="show_results_<?php echo $surveyItem->id; ?>" class="show_results_area" style="display: none;"></div>
                  </div>
                </div>
              <?php  }   ?>
            </div>
          </div> <!-- tab_main_content -->
          <div id="tab_sidebar">
            <h4>Sidebar</h4>
          </div>
        </div>
      </div> <!-- tab_content -->
    </div> <!-- wrap -->
    <div id="footerlinks">
        <p>WP Surveys Powered By <a target="_blank" href="http://www.lprockstar.com">Landing Page Rockstar</a></p>
    </div>
<?php }
function build_survey_options() {

  include("scripts-styles.php"); ?>

  <div class="wrap">
    <div id="tab_content">
        <div id="tab2_content" class="tab_content">
            <h2>Options</h2>
            <p>Options for the plugin.</p>
        </div>
    </div>
  </div>
  <div id="footerlinks"><p>WP Surveys Powered By <a target="_blank" href="http://www.lprockstar.com">Landing Page Rockstar</a></p></div>

<?php }

function build_new_survey_page() {
  $updateInput = "";
  if(isset($_GET['wpsSurveyId'])) {
    global $wpdb, $survey_table_name, $question_table_name, $session_table_name, $response_table_name; 
    $updateInput = "<input type='hidden' name='update' value='1' />";
    $questionKey = isset($_GET['wpsQuestionStep']) ? $_GET['wpsQuestionStep'] : 1;
    $questionKey--;
    $qry = "SELECT * FROM $survey_table_name WHERE id = " . $_GET['wpsSurveyId'];
    $surveyArray = $wpdb->get_results( $qry );
    if(count($surveyArray) == 0) {
      $error = "Error: Survey not Found \n";
    } else {
      $selectedSurvey = $surveyArray[0];
      $qry = "SELECT * FROM $question_table_name WHERE survey_id = " . $selectedSurvey->id . " ORDER BY step_number ASC";
      $questionArray = $wpdb->get_results($qry);
      if(count($questionArray) == 0) {
        $error = "Error: Question not found \n";
      } else {
        // create the object
        $selectedQuestion = $questionArray[$questionKey];
      }
    }
  }
?>

  <?php include("scripts-styles.php"); ?>
  <div class="wrap">
    <div id="tab_content">
        <div id="tab3_content" class="tab_content">
          <?php if(isset($error)) echo "<div class='error alert'> $error </div>"; ?>
          <form id="new_survey">
            <?php echo $updateInput; ?>
            <?php if(isset($selectedSurvey)) : ?>
              <input type="hidden" name="surveyId" value="<?php echo $selectedSurvey->id; ?>" />
            <?php endif; ?>
            <div class="create_container">
              <?php if(isset($selectedSurvey)) : ?>
                <h2>Edit Survey</h2>
              <?php else : ?>
                <h2>Create New Survey</h2>
              <?php endif; ?>
              <div class="survey_title"><input name="new_survey_name" type="text" class="text" placeholder="Survey Name..." value="<?php echo isset($selectedSurvey) ? stripslashes($selectedSurvey->name) : ""; ?>" /></div>
              <div class="survey_steps" style="display: none;">
                <div id="survey_step_1" class="survey_step">
                  <p><strong>Step One</strong></p>
                  <p>Description</p>
                </div>
                <div id="add_survey_step" class="survey_step">
                  <div>+</div>
                </div>
              </div> <!-- survey_steps -->
              <div class="survey_create_body">
                <div class="survey_create_options">
                  <div class="survey_create_option create_question_text">
                    <h4>Question Text</h4>
                    <p><textarea name="question_text" class="text textarea" placeholder="Enter Question Text..."><?php echo isset($selectedQuestion) ? stripslashes($selectedQuestion->description) : ""; ?></textarea>
                  </div>
                  <div class="survey_create_option create_answer_type">
                    <h4>Answer Type</h4>
                    <p>
                    <select id="wps_answer_type_selection" name="answers_type">
                      <option value="Multiple">Multiple Choice</option>                    
                      <option value="Net Promoter">Net Promoter Score</option>
                      <option value="Text Response">Text Response</option>
                      <!-- <option value="CTA">Call To Action</option> -->
                    </select>
                    </p>
                  </div>
                  <div class="survey_create_option create_answers">
                    <h4>Answers</h4>
                    <div id="wps_multipel_choice_create_answers">
                    <?php
                      if(isset($selectedQuestion)) :
                        $answersArray = unserialize($selectedQuestion->answer_value);
                        $i = 1;
                        foreach($answersArray as $answerText) :
                    ?>
                      <div id="answer_<?php echo $i; ?>" class="create_answer">
                        <p><input name="answer_<?php echo $i; ?>" class="text" value="<?php echo stripslashes($answerText); ?>" type="text" /></p>
                      </div>
                    <?php
                          $i++;
                        endforeach;
                      else :
                    ?>
                      <div id="answer_1" class="create_answer">
                        <p><input name="answer_1" class="text" placeholder="Don't Agree" type="text" /></p>
                      </div>
                      <div id="answer_2" class="create_answer">
                        <p><input name="answer_2" class="text" placeholder="Totally Agree" type="text" /></p>
                      </div>
                    <?php endif; ?>
                    </div>
                    <div id="add_new_answer" class="blueGreyBtn pluginBtn">Add Another Answer</div>
                  </div>
                  <div class="survey_create_option">
                    <h4>Call to action text</h4>
                    <div id="wps_cta_create_answers">
                      <div id="cta_1" class="create_call_to_action">
                        <p><input name="cta_1" class="text" value="Send" type="text" /></p>
                        <p><select name="wps_cta_action"><option value="thankyou">Thank You Message</option></select></p>
                      </div>
                    </div>                      
                  </div>
                  <div class="survey_create_option create_behavior">
                    <h4>Behavior</h4>
                    <?php /* fix this later
                    
                    <p>
                      <select id="behavior_display" name="behavior">
                        <option value="all">Display on all pages</option>
                        <option value="specific">Display on certain pages</option>
                      </select>
                    </p>
                    
                    <div class="certain_pages">
                      <p><input name="url_1" type="text" placeholder="Enter URL..." /> <span class="certain_pages_controls"><span class="certain_pages_more">+</span></span></p>
                    </div>
                    
                    */ ?>
                    <div class="delay_survey">
                      <p>Display Survey After <input name="display_seconds" type="text" value="<?php echo isset($selectedSurvey) ? $selectedSurvey->display_seconds : 0; ?>" /> Seconds</p>
                    </div>   
                    <div class="display_per_visitor">
                      <p>Display up to <input name="display_per_visitor" type="text" value="<?php echo isset($selectedSurvey) ? $selectedSurvey->display_per_visitor : 3; ?>" /> times per visitor</p>
                    </div>                                                         
                  </div>
                </div> <!-- survey_create_body -->
                <div class="survey_preview">
                  <h3>Preview</h3>
                  <div id='wps_wp_survey_preview' class='wps_wp_survey_box'>
                    <div id="wps_close_wp_survey_box"></div>
                    <h4>Question Text</h4>
                    <div id='wps_answer_box_preview' class="wps_wp_question_box">
                    </div>
                  </div>
                </div> <!-- survey_preview -->
                <input class="greenBtn pluginBtn" value="Save Survey" type='submit' />
              </div> <!-- create_container -->
            </form> <!-- end new survey form -->
          </div> <!-- tab3_content -->
       </div>
    </div>
    <div id="footerlinks"><p>WP Surveys Powered By <a target="_blank" href="http://www.lprockstar.com">Landing Page Rockstar</a></p></div>
          
<?php }
function edit_survey() {
  include("scripts-styles.php"); ?>
  <div class="wrap">
    <div id="tab_content">
      <h3>Nothing to show yet</h3>
      <p><?php echo $_GET['testvar']; ?></p>
    </div>
  </div>
<?php 

}