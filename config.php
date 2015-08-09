<?php
global $wpdb;
global $wps_db_version;
$wps_db_version = '1.2';
global $survey_table_name, $question_table_name, $session_table_name, $response_table_name;
$survey_table_name = $wpdb->prefix . 'wpSurveyTable';
$question_table_name = $wpdb->prefix . 'wpSurveyQuestionTable';
$session_table_name = $wpdb->prefix . 'wpSurveySessionTable';
$response_table_name = $wpdb->prefix . 'wpSurveyResponseTable';