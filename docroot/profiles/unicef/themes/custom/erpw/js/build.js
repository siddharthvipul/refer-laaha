!function(c,e){"use strict";e.behaviors.form_select={attach:function(e,o){0==c("select option:selected").val()&&c("select").css("color","#E0D7D7"),c("select").on("change",function(){return 0==c(this).children("option:selected").val()?c(this).css("color","#E0D7D7"):c(this).css("color","#444")}),c(".signup-next").click(function(){c("input").each(function(){""==c(this).val()&&c(this).css("border","2px solid red")}),c(".form-select").change(function(){0==c(this).children("option:selected").val()&&c(this).css("border","2px solid green")})})}}}(jQuery,Drupal,drupalSettings),function(c,e){"use strict";e.behaviors.icon_color_picker={attach:function(e,o){c(document).ready(function(){c(".fip-box").click(function(){c(this).parents("form").find(".selector").show(),c(".field--name-field-service-type-icon .js-form-type-textfield").css("margin-top",90)}),c(".color_field_widget_box__square").each(function(){var e=c(this).css("background-color");c(this).css("border-color",e)}),c(".color_field_widget_box__square").click(function(){var e=c(this).css("background-color"),o=c(this).parents("body").find(".icons-selector").children(".selector").find("span.selected-icon");c(o).css("background-color",e)}),c(".messages--error").length&&c(".fip-box").hasClass("current-icon")&&(c("body").find(".selector").show(),c(".field--name-field-service-type-icon .js-form-type-textfield").css("margin-top",90))})}}}(jQuery,Drupal,drupalSettings),function(c,e){"use strict";e.behaviors.menu_list={attach:function(e,o){c(".menu-icon").click(function(){c("#block-erpw-main-menu").append("<span class='close-popup'></span>"),c("#block-erpw-main-menu").show(),c(".overlay").show(),c(".close-popup").click(function(e){e.preventDefault(),c(".overlay, #block-erpw-main-menu").hide(),c(".close-popup").remove()})})}}}(jQuery,Drupal,drupalSettings),function(c,e){"use strict";e.behaviors.popup={attach:function(e,o){c(".sign-in-popup").length&&(c(".sign-in-popup").show(),c(".overlay").show(),c(".close-popup, .skip").click(function(e){e.preventDefault(),c(".sign-in-popup, .overlay").hide()})),c(".overlay").on("click",function(){c(".sign-in-popup").hide(),c("#block-erpw-main-menu").hide(),c(this).hide(),c(".close-popup").remove()}).appendTo(c(document.body))}}}(jQuery,Drupal,drupalSettings);