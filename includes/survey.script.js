function activateSurvey(wps_delay_time,surveyId) {

  setTimeout(function() {
  
      if(jQuery.cookie('wps_survey_session')) {
      
        var wpsCookieData = [];
        var wpsRawCookieData = unescape(jQuery.cookie('wps_survey_session'));
        wpsCookieData = wpsRawCookieData.split(',');
        
        wpsCookieData.push(surveyId);
      
      } else {
        
        var wpsCookieData = [];
        wpsCookieData = [surveyId];
      
      }
      
      wpsCookieData = escape(wpsCookieData.join(','));
      
      jQuery.cookie('wps_survey_session',wpsCookieData, {expires:7, path: '/'});
  
      jQuery(".wps_wp_survey_box").animate({bottom: "+=500"}, 1000);
  
  }, wps_delay_time);
  
}

function wpsSelectMultipleChoice(thisId) {
  jQuery(".wps_multiple_choice").removeClass("wps_multiple_choice_selected");
  jQuery("#"+thisId).addClass("wps_multiple_choice_selected");
}

function wpsSaveResponse(surveyId,questionId,questionResponse,ipAddress) {

    var submitData = "action=save_survey_response" + 
                     "&surveyId=" + surveyId +
                     "&questionId=" + questionId +
                     "&questionResponse=" + questionResponse;
  
    jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: submitData
      }).done(function(result) {
        
        var previousDoneCookie = ""
        if(jQuery.cookie('wps_survey_done'))
            previousDoneCookie = jQuery.cookie('wps_survey_done') + ",";
        jQuery.cookie("wps_survey_done",previousDoneCookie + surveyId);
        jQuery(".wps_wp_survey_box h4").html(result);
        jQuery(".wps_wp_question_box").html('');
        jQuery(".wps_wp_survey_box").delay(2000).fadeOut(1000);
        
      }).fail(function() {
        
        //alert(result);
        
      });
  
}

jQuery(document).ready(function($) {

  jQuery("#wps_close_wp_survey_box").on("click",function() {
  
        jQuery(".wps_wp_survey_box").fadeOut(200);
        var surveyId = jQuery(this).parent().attr('id');
        surveyId = surveyId.substring(14);
  
        var previousDoneCookie = ""
        if(jQuery.cookie('wps_survey_done'))
            previousDoneCookie = jQuery.cookie('wps_survey_done') + ",";
        jQuery.cookie("wps_survey_done",previousDoneCookie + surveyId);
  
  });

  jQuery(".wps_question_submit_btn").on("click",function() {
  
    //find the type
    var wpsSurveyType = jQuery(this).parent().parent().attr('id');
        wpsSurveyType = wpsSurveyType.substring(16);   
         
    var wpsSurveyId = jQuery(".wps_wp_survey_box").attr('id');
        wpsSurveyId = wpsSurveyId.substring(14);
        
    var wpsQuestionId = jQuery(".wps_wp_question_box").attr('id');
        wpsQuestionId = wpsQuestionId.substring(15);
        
    var wpsQuestionResponse = "";
    
    if(wpsSurveyType == "multiple") {
      wpsQuestionResponse = jQuery('.wps_multiple_choice_selected').attr('id');
      wpsQuestionResponse = wpsQuestionResponse.substring(14);      
    } else if(wpsSurveyType == "net_promoter") {
      wpsQuestionResponse = jQuery('.wps_net_promoter_selected').html(); 
    } else if(wpsSurveyType == "text_response") {
      wpsQuestionResponse = jQuery('#wps_survey_type_text_response textarea').val();
    } else if(wpsSurveyType == "cta") {
    
    } else {
    
    }
    
    if(wpsQuestionResponse != "") {
        wpsSaveResponse(wpsSurveyId,wpsQuestionId,wpsQuestionResponse);
    }
  
  });

  jQuery(".wps_multiple_choice").on('click', function() {
    wpsSelectMultipleChoice(jQuery(this).attr('id'));
    jQuery(".wps_question_submit_btn").addClass("wps_save_btn_activated");
  });
  
  jQuery(".wps_net_promoter_choice").on('click',function() {
    jQuery(".wps_net_promoter_choice").removeClass("wps_net_promoter_selected");
    jQuery(this).addClass("wps_net_promoter_selected");
    jQuery(".wps_question_submit_btn").addClass("wps_save_btn_activated");
  });
  
  jQuery("#wps_survey_type_text_response textarea").on("change keyup paste", function() {
    if(jQuery(this).val() != "") {
      jQuery(".wps_question_submit_btn").addClass("wps_save_btn_activated");
    } else {
      jQuery(".wps_question_submit_btn").removeClass("wps_save_btn_activated");
    }
  });

});