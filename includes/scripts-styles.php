<style type="text/css">

      .wps_wp_survey_box {background-color: #333; border-radius: 6px 6px 0 0; padding: 20px; color: white; min-width: 280px; right: 10%; text-align: center; max-width: 380px;}
       .wps_wp_survey_box h3,
       .wps_wp_survey_box h2,
       .wps_wp_survey_box h4,
       .wps_wp_survey_box p,
       .wps_wp_survey_box li {color: inherit; text-align: inherit;}
       .wps_wp_survey_box ul,
       body .wps_wp_survey_box li {list-style-type: none!important; margin: 0 0 0.8rem; padding: 0; vertical-align: top;}
       body .wps_wp_survey_box li {border-radius: 12px; background-color: #777; padding: 4px 12px; cursor: pointer; font-size: 13px; text-align: left;}
        body .wps_wp_survey_box li:hover {background-color: #999;}
        body .wps_wp_survey_box textarea {height: 90px; margin: 0 0 1rem; width: 100%;}
       body .wps_wp_survey_box input {vertical-align: middle; margin-right: 6px;}
       
       .wps
       
       body #wps_survey_type_net_promoter {max-width: 340px; margin: 0 auto;}
        #wps_survey_type_net_promoter .wps_net_promoter_min {float: left;}
        #wps_survey_type_net_promoter .wps_net_promoter_max {float: right;}
       
       body .wps_wp_survey_box .wps_net_promoter_choice {display: inline-block; padding: 6px; background-color: #777; border-radius: 2px; margin: 18px 6px; cursor: pointer;}
       body .wps_wp_survey_box .wps_net_promoter_choice:hover,
       body .wps_wp_survey_box .wps_net_promoter_choice_selected {background-color: #999;}
       body .wps_wp_survey_box .wps_net_promoter_clear {clear:both; display: block; height: 0;}
       
       body .wps_wp_survey_box .wps_multiple_choice_selected {background-color: #999;}
       
       body .wps_wp_survey_box .wps_submit_area {height: 40px;display: block; padding: 0 20px;}
       
       body .wps_wp_survey_box .wps_question_submit_btn {color: white; border-radius: 4px; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2); font-size: 16px; padding: 6px 12px; display: inline-block; cursor: pointer; background: rgb(28, 184, 65); float: right;}

      .fauxlink {
        color: #0073aa;
        text-decoration: underline;
        cursor: pointer;
      }
      
      .fauxlink:active,
      .fauxlink:hover {
        color: #00a0d2;
      }
    
      .pluginBtn {color: white; border-radius: 4px; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2); font-size: 16px; padding: 6px; display: inline-block; cursor: pointer; text-decoration: none;}
       .pluginBtn:hover {color: white;}
      .greenBtn {background: rgb(28, 184, 65);}
       .greenBtn:hover {background: rgb(63, 190, 106);}
      .maroonBtn {background: rgb(202, 60, 60);}
      .orangeBtn {background: rgb(223, 117, 20);}
      .lightBlueBtn {background: rgb(66, 184, 221);}        
      .blueGreyBtn {background-color: #a2acc5;}
    
      textarea {width: 500px; height: 200px;}
      #ct_options_form {max-width: 800px;}
      
      .survey_title {margin: 0 0 1rem;}
        .survey_title input {width: 600px; height: 60px; font-size: 20px; padding: 0 10px;}
      
      .survey_steps {margin: 0 0 1rem;}
       .survey_step {background-color: #efefef; border-radius: 5px; padding: 12px; display: inline-block; width: 120px; height: 80px; margin-right: 12px; text-align: center; vertical-align: top;}
        #add_survey_step {cursor: pointer;}
          #add_survey_step:hover {background-color: #e0e0e0;}
          #add_survey_step div {font-size: 120px; color: #ccc; line-height: 68px;}
       
      .wrap {width: 100%; position: relative; margin: 20px 0; } 
      
      #tab_content {border: 1px solid #ccc; padding: 40px; background-color: white; display: block; vertical-align: top; margin-right: 20px;}
       #tab_content h2 {margin: 0 0 20px; font-size: 28px;}
      
      #tab_main_content {max-width: 750px; vertical-align: top; display: inline-block; margin-right: 10%;}
      #tab_sidebar {width: 280px; border: 1px solid #ccc; background-color: #efefef; vertical-align: top; border-top: 16px solid #444; display: inline-block; float: right;}
        #tab_sidebar div {padding: 30px 40px;}
       
      .survey_create_body {display: block; position: relative;}
        .survey_create_body div {vertical-align: top;}
        .survey_create_options {display: inline-block; width: 60%; margin-right: 3%;}
        .survey_preview {display: inline-block; width: 34%;}
        
       .survey_create_option {padding: 20px; border: 1px solid #ccc; margin-bottom: 1rem;}
        .survey_create_option h4 {font-size: 18px;}
        .create_answer {background-color: #efefef; padding: 12px; margin: 0 0 1rem;}
        
       .survey_list_item {border: 1px solid #ccc; padding: 10px 20px; margin: 0 0 1rem;}
        
       .survey_inactive {background-color: #efefef;}
       .survey_active {background-color: #d3e9cb; border: 4px solid #8cbf79;}
       
       .create_question_text textarea {width: 100%; height: 60px;}
       
       .show_results_area {display: block; position: relative;}
        .show_results_area p {clear: both;}
        .show_results_area strong {width: 28px; float: left; margin-top: 4px;}
        .show_results_area .wps_results_response_number { float: left; margin-top: 4px; width: 1.4rem;}
        .show_results_area .wps_results_response_graph {display: block; overflow: hidden;}
        .show_results_area span.wps_results_response_bar {background-color: #85c16f; padding: 4px; display: block;}
       
       
    
    </style>
    
    <script>
    
      jQuery(document).ready(function($) {
      
        <?php
        
        if(isset($_GET['wpsSurveyId']) && count($questionArray) != 0) {
        
        ?>
        
        jQuery("#wps_answer_type_selection option").each(function() {
        
          if(jQuery(this).val() == "<?php echo $selectedQuestion->answer_type; ?>")
                jQuery(this).attr("selected","selected");
        
        });
        
        jQuery("#behavior_display option").each(function() {
        
          if(jQuery(this).val() == "<?php echo $selectedSurvey->behavior; ?>")
                jQuery(this).attr("selected","selected");
        
        });        
        
        <?php } ?>
              
        function addSurveyStep() {
          
          var lastStep = jQuery(".survey_step").last().attr("id");
          lastStep = parseInt(lastStep.substring(12));
          nextStep = lastStep++;
          newStep = "<div id=\"survey_step_"+nextStep+"\" class=\"survey_step\">" + 
                    "<p><strong>Step Two</strong></p>" + 
                    "<p>Description</p>" + 
                    "</div>";
          jQuery(".survey_step").last().before(newStep);
        
        }
        
        function addAnswer() {
        
          var lastAnswer = jQuery(".create_answer").last().attr("id");
          lastAnswer = parseInt(lastAnswer.substring(7));
          nextAnswer = lastAnswer+1;
          newAnswer = "<div id=\"answer_"+nextAnswer+"\" class=\"create_answer\">" + 
                      "<p><input name=\"answer_"+nextAnswer+"\" class=\"text\" placeholder=\"New Answer...\" type=\"text\" /></p>" +
                      "</div>";
          jQuery(".create_answer").last().after(newAnswer);
        
        }
        
        // external ajax call for forms
        function externalCall(formId) {
        
          var formData = "action=create_new_survey";
          formData = formData + "&" + jQuery("#"+formId).serialize();
          formData = formData + "&wp_root=<?php echo ABSPATH; ?>";
        
          jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: formData
          }).done(function(result) {
            
            window.location = "/wp-admin/admin.php?page=wp-survey";
            
          }).fail(function() {
            
            alert("There was an error saving this survey.");
            
          });
        
        }
        
        function answerTypeChoice(thisChoice) {
          if(thisChoice == "Multiple") {
            jQuery("#add_new_answer").show();
            jQuery('.create_answer').show();
            jQuery('.create_answers').show();
          }        
          if(thisChoice == "Net Promoter") {
            jQuery("#add_new_answer").hide();
            jQuery('.create_answer').show();
            jQuery('.create_answers').show();
            jQuery(".create_answers").find('.create_answer').slice(2).hide();
          }
          if(thisChoice == "Text Response") {
            jQuery("#add_new_answer").hide();
            jQuery('.create_answers').hide();
            jQuery('.create_answer').hide();
          }
          if(thisChoice == "CTA") {
            jQuery("#add_new_answer").hide();
            jQuery('.create_answers').hide();
            jQuery('.create_answer').hide();
          }  
        }
        
        // submit forms using Ajax
        jQuery('form').submit(function(event) {
        
          event.preventDefault();
          
          var thisFormId = jQuery(this).attr('id');
          externalCall(thisFormId);
        
        });
  
        jQuery("#add_survey_step").click(function() {
        
          addSurveyStep();
        
        });
        
        jQuery("#add_new_answer").click(function() {
        
          addAnswer(); 
        
        });
        
        jQuery('#wps_answer_type_selection').on('change', function() {answerTypeChoice(jQuery(this).val());});
        answerTypeChoice(jQuery('#wps_answer_type_selection').val());
        
        $(".show_results_btn").on("click",function() {
        
          var thisSurveyId = $(this).attr('id');
          thisSurveyId = thisSurveyId.substring(12);
          
          $("#show_results_"+thisSurveyId).show();
          $("#show_results_"+thisSurveyId).html("<h4>Loading</h4>");
          
          var thisResultsData = "action=get_survey_results";
              thisResultsData = thisResultsData + "&surveyId="+thisSurveyId;
          
          jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: thisResultsData
          }).done(function(result) {
            
            $("#show_results_"+thisSurveyId).html(result);
            
          }).fail(function() {
            
            alert("Error retrieving survey");
            
          });
        
        });
        
        function wpsRenderPreview (questionType) {
        
          var wps_multiple_template = "<div id='wps_survey_type_multiple'>" + 
                                      "<ul class='wps_multiple_choice_list'>" +
                                      "</ul>" +
                                      "<div class='wps_submit_area'><div id='wps_multiple_choice_submit_btn' class='wps_question_submit_btn'>Send</div></div>" + 
                                      "</div>";
                                      
          var wps_net_promoter_template = "<div id='wps_survey_type_net_promoter'>" + 
                                          "<div class='wps_net_promoter_min'></div>" +
                                          "<div class='wps_net_promoter_max'></div>" +
                                          "<div class='wps_net_promoter_clear'></div>" +
                                          "<div class='wps_net_promoter_choice'>1</div>" +
                                          "<div class='wps_net_promoter_choice'>2</div>" +
                                          "<div class='wps_net_promoter_choice'>3</div>" +
                                          "<div class='wps_net_promoter_choice'>4</div>" +
                                          "<div class='wps_net_promoter_choice'>5</div>" +
                                          "<div class='wps_net_promoter_choice'>6</div>" +
                                          "<div class='wps_net_promoter_choice'>7</div>" +
                                          "<div class='wps_net_promoter_choice'>8</div>" +
                                          "<div class='wps_net_promoter_choice'>9</div>" +
                                          "<div class='wps_net_promoter_choice'>10</div>" +
                                          "<div class='wps_submit_area'><div id='wps_net_promoter_submit_btn' class='wps_question_submit_btn'>Send</div></div>" +
                                          "</div>";
                                          
          var wps_text_response_template = "<div id='wps_survey_type_text_response'>" +
                                            "<textarea name='wps_text_response'></textarea>" +
                                            "<div class='wps_submit_area'><div id='wps_text_response_submit_btn' class='wps_question_submit_btn'>Send</div></div>" +
                                            "</div>";        
        
          if(questionType == "Multiple") {
            $(".wps_wp_question_box").html(wps_multiple_template);
            $(".create_answer input").each(function() {
            
              var thisId = $(this).attr("name");
              thisId = thisId.substring(7);
              $(".wps_multiple_choice_list").append("<li id='answer_number_"+thisId+"' class='wps_multiple_choice'>"+$(this).val()+"</li>");
            
            });
          }
            
          if(questionType == "Net Promoter") {
            $(".wps_wp_question_box").html(wps_net_promoter_template);
            $(".wps_net_promoter_min").html($("#answer_1 input").val());
            $(".wps_net_promoter_max").html($("#answer_2 input").val());
          }
            
          if(questionType == "Text Response")
            $(".wps_wp_question_box").html(wps_text_response_template);
            
          $(".wps_question_submit_btn").html($("#cta_1 input").val());
          
          $(".wps_wp_survey_box h4").html($(".create_question_text textarea").val());
        
        }
        
        wpsRenderPreview($("#wps_answer_type_selection").val());
        
        $("#new_survey").on('change','input,select,textarea',function() {
        
          wpsRenderPreview($("#wps_answer_type_selection").val());
        
        });

        
      });
    
    </script>