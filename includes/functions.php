<?php

// Ajax functions to deal with forms

add_action('wp_ajax_create_new_survey', 'create_new_survey_callback');

function create_new_survey_callback() {

  global $wpdb, $wps_db_version, $survey_table_name, $question_table_name, $session_table_name, $response_table_name;
  
  // Setup the vars
  $surveyName = $_POST['new_survey_name'];
  //$surveyDescription = $_POST['new_survey_description'];
  //$surveyType = $_POST['new_survey_type'];
  $questionText = $_POST['question_text'];
  $answerType = $_POST['answers_type'];
  $answers = array();
  $behavior = $_POST['behavior'];
  $urls = array();
  $displaySeconds = $_POST['display_seconds'];
  $displayPerVisitor = $_POST['display_per_visitor'];
  
  $create_or_update = isset($_POST['surveyId']) ? "update" : "create";
  
  // turn answers and URLs into an array
  foreach($_POST as $field => $value) {
    if(substr($field, 0, 7) == "answer_") {
      $answers[substr($field,7)] = $value;
    }
    if(substr($field, 0, 4) == "url_") {
      $urls[substr($field,4)] = $value;
    }
  } 
  
  $answers = serialize($answers);
  $urls = serialize($urls);
  
  if($create_or_update == "create") {
  
    // crete new survey
    $wpdb->insert(
        $survey_table_name,
        array(
            'name'              =>  $surveyName,
            'behavior'          =>  $behavior,
            'behavior_url'      =>  $urls,
            'display_seconds'   =>  $displaySeconds,
            'display_per_visitor' =>  $displayPerVisitor
        )
    );
    
    $survey_id = $wpdb->insert_id;
    
    $wpdb->insert(
        $question_table_name,
        array(
            'survey_id'       =>  $survey_id,
            'description'     =>  $questionText,
            'answer_type'     =>  $answerType,
            'answer_value'    =>  $answers
        )
    );
    
  } else {
  
    // crete new survey
    $wpdb->update(
        $survey_table_name,
        array(
            'name'              =>  $surveyName,
            'behavior'          =>  $behavior,
            'behavior_url'      =>  $urls,
            'display_seconds'   =>  $displaySeconds,
            'display_per_visitor' =>  $displayPerVisitor
        ),
        array( 'id' => $_POST['surveyId'] )
    );
    
    $wpdb->update(
        $question_table_name,
        array(
            'description'   => $questionText,
            'answer_type'   => $answerType,
            'answer_value'  => $answers
        ),
        array( 'survey_id' => $_POST['surveyId'], 'step_number' => 1 )
    );
  
  }
  
  die();
  
}

add_action('wp_ajax_save_survey_response', 'save_survey_response_callback');

function save_survey_response_callback() {

  global $wpdb, $wps_db_version, $survey_table_name, $question_table_name, $session_table_name, $response_table_name;
  
  $surveyId = $_POST['surveyId'];
  $questionId = $_POST['questionId'];
  $questionResponse = $_POST['questionResponse'];
  
  if(isset($surveyId)) {
  
    // crete new survey
    $wpdb->insert(
        $response_table_name,
        array(
            'survey_id'       =>  $surveyId,
            'question_id'     =>  $questionId,
            'response'        =>  $questionResponse
        )
    );
    
  }
  
  die();

}

add_action('wp_ajax_get_survey_results', 'get_survey_results_callback');

function get_survey_results_callback() {

  global $wpdb, $wps_db_version, $survey_table_name, $question_table_name, $session_table_name, $response_table_name;

  $surveyId = $_POST['surveyId'];
  
  $qry = "SELECT * FROM $question_table_name WHERE survey_id = " . $surveyId . " ORDER BY step_number ASC";
  $questionArray = $wpdb->get_results($qry);
  $thisQuestion = $questionArray[0];
  $answerArray = unserialize($thisQuestion->answer_value);
  
  echo "<h4>" . $thisQuestion->description . "</h4>";
  
  if($thisQuestion->answer_type == "Multiple") {
  
    foreach($answerArray as $key => $thisAnswer) {
      
      $qry = "SELECT * FROM $response_table_name WHERE survey_id = $surveyId AND response = $key";
      $responseArray = $wpdb->get_results($qry);
      $responseCount = count($responseArray);
      
      echo "<p><strong>$thisAnswer:</strong> $responseCount";
      
    }
  
  } elseif($thisQuestion->answer_type == "Net Promoter") {
    
    $i = 1;
    $responseTotal = 0;
    while($i <= 10) {
    
      $qry = "SELECT * FROM $response_table_name WHERE survey_id = $surveyId AND response = $i";
      $responseArray = $wpdb->get_results($qry);
      $responseCount[$i] = count($responseArray);
      $responseTotal += $responseCount[$i];      
      $i++;
    
    }
    
    foreach($responseCount as $key => $responseNumber) {
    
      $percentage = $responseNumber / $responseTotal;
      $percentage = $percentage * 100;
    
      if($percentage > 0) {
      
        echo "<p><span class='wps_results_response_key'><strong>$key:</strong></span><span class='wps_results_response_number'>$responseNumber</span><span class='wps_results_response_graph'><span class='wps_results_response_bar' style='width:$percentage%'>($percentage%)</span></span></p>";
        
      } else {
      
        echo "<p><span class='wps_results_response_key'><strong>$key:</strong></span><span class='wps_results_response_number'>$responseNumber</span><span class='wps_results_response_graph'><span class='wps_results_response_bar' style='width:$percentage%'>&nbsp;</span></span></p>";
        
      }
    
    }
    
    
  } elseif($thisQuestion->answer_type == "Text Response") {
    
    $qry = "SELECT * FROM $response_table_name WHERE survey_id = $surveyId";
    $responseArray = $wpdb->get_results($qry);
    $responseCount = count($responseArray);
    
    echo "<p><strong>Total Responses:</strong> $responseCount</p>";
    echo "<ol>";
    
    foreach($responseArray as $thisResponse) {
    
      echo "<li>" . stripslashes($thisResponse->response) . "</li>";
    
    }
    
    echo "</ol>";
    
  }

  die();

}

function wpsupdateSurveyViews($updateObject,$objectId) {

  global $wpdb, $wps_db_version, $survey_table_name, $question_table_name, $session_table_name, $response_table_name;
  
  if($updateObject == "survey") {
    $updateTable = $survey_table_name;
    $updateColumn = "survey_views";
  } elseif ($updateObject == "question") {
    $updateTable = $question_table_name;
    $updateColumn = "question_views";
  }

  $wpdb->query(
        $wpdb->prepare(
                "
                UPDATE $updateTable
                  SET $updateColumn = $updateColumn+1
                  WHERE id = %d
                ",
                $objectId
        )  
  );  

}

function wpsnumberOfViews () {

  

}