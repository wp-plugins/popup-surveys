function activateSurvey(wps_delay_time,surveyId) {

  if($.cookie('wps_survey_session')) {
  
    var wpsCookieData = [];
    var wpsRawCookieData = unescape(jQuery.cookie('wps_survey_session'));
    wpsCookieData = wpsRawCookieData.split(',');
    
    wpsCookieData.push(surveyId);
    
    //console.log(wpsCookieData);
  
  } else {
    
    var wpsCookieData = [];
    wpsCookieData = [surveyId];
  
  }
  
  //alert(wpsCookieData);
  
  wpsCookieData = escape(wpsCookieData.join(','));
  
  $.cookie('wps_survey_session',wpsCookieData, {expires:7, path: '/'});
  jQuery(".wps_wp_survey_box").delay(wps_delay_time).animate({bottom: "+=500"}, 1000);
  
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
        if($.cookie('wps_survey_done'))
            previousDoneCookie = $.cookie('wps_survey_done') + ",";
        $.cookie("wps_survey_done",previousDoneCookie + surveyId);
        $(".wps_wp_survey_box h4").html("Thank You For Your Feedback!");
        $(".wps_wp_question_box").html('');
        $(".wps_wp_survey_box").delay(2000).fadeOut(1000);
        
      }).fail(function() {
        
        //alert(result);
        
      });
  
}

jQuery(document).ready(function($) {

  $("#wps_close_wp_survey_box").on("click",function() {
  
        $(".wps_wp_survey_box").fadeOut(200);
        var surveyId = $(this).parent().attr('id');
        surveyId = surveyId.substring(14);
  
        var previousDoneCookie = ""
        if($.cookie('wps_survey_done'))
            previousDoneCookie = $.cookie('wps_survey_done') + ",";
        $.cookie("wps_survey_done",previousDoneCookie + surveyId);
  
  });

  $(".wps_question_submit_btn").on("click",function() {
  
    //find the type
    var wpsSurveyType = $(this).parent().parent().attr('id');
        wpsSurveyType = wpsSurveyType.substring(16);   
         
    var wpsSurveyId = $(".wps_wp_survey_box").attr('id');
        wpsSurveyId = wpsSurveyId.substring(14);
        
    var wpsQuestionId = $(".wps_wp_question_box").attr('id');
        wpsQuestionId = wpsQuestionId.substring(15);
        
    var wpsQuestionResponse = "";
    
    if(wpsSurveyType == "multiple") {
      wpsQuestionResponse = $('.wps_multiple_choice_selected').attr('id');
      wpsQuestionResponse = wpsQuestionResponse.substring(14);      
    } else if(wpsSurveyType == "net_promoter") {
      wpsQuestionResponse = $('.wps_net_promoter_selected').html(); 
    } else if(wpsSurveyType == "text_response") {
      wpsQuestionResponse = $('#wps_survey_type_text_response textarea').val();
    } else if(wpsSurveyType == "cta") {
    
    } else {
    
    }
  
    wpsSaveResponse(wpsSurveyId,wpsQuestionId,wpsQuestionResponse);
  
  });

  $(".wps_multiple_choice").on('click', function() {
    wpsSelectMultipleChoice($(this).attr('id'));
  });
  
  $(".wps_net_promoter_choice").on('click',function() {
    jQuery(".wps_net_promoter_choice").removeClass("wps_net_promoter_selected");
    jQuery(this).addClass("wps_net_promoter_selected");
  });

});