!function(c,o){"use strict";o.behaviors.form_select={attach:function(o,e){0==c("select option:selected").val()&&c("select").css("color","#E0D7D7"),c("select").on("change",function(){return 0==c(this).children("option:selected").val()?c(this).css("color","#E0D7D7"):c(this).css("color","#444")}),c(".form-submit").click(function(){c("input").each(function(){""==c(this).val()&&c(this).css("border","2px solid red")}),c(".form-select").change(function(){0==c(this).children("option:selected").val()&&c(this).css("border","2px solid green")})})}}}(jQuery,Drupal,drupalSettings),function(c,o){"use strict";o.behaviors.icon_color_picker={attach:function(o,e){c(document).ready(function(){c(".field--name-field-service-type-icon .js-form-type-textfield").css("margin-top",90),c(".fip-box").click(function(){}),c(".color_field_widget_box__square").each(function(){var o=c(this).css("background-color");c(this).css("border-color",o)});var e=c("body").find(".icons-selector").children(".selector").find("span.selected-icon");c(".color_field_widget_box__square").click(function(){var o=c(this).css("background-color");c(e).css("background-color",o),console.log(e)});var o=c(".color_field_widget_box__square.active").css("background-color");c(e).css("background-color",o)})}}}(jQuery,Drupal,drupalSettings),function(c,o){"use strict";o.behaviors.menu_list={attach:function(o,e){c(".menu-icon").click(function(){c("#block-erpw-main-menu").append("<span class='close-popup'></span>"),c("#block-erpw-main-menu").show(),c(".overlay").show(),c(".close-popup").click(function(o){o.preventDefault(),c(".overlay, #block-erpw-main-menu").hide(),c(".close-popup").remove()})})}}}(jQuery,Drupal,drupalSettings),function(c,o){"use strict";o.behaviors.popup={attach:function(o,e){c(".sign-in-popup").length&&(c(".sign-in-popup").show(),c(".overlay").show(),c(".close-popup, .skip").click(function(o){o.preventDefault(),c(".sign-in-popup, .overlay").hide()})),c(".overlay").on("click",function(){c(".sign-in-popup").hide(),c("#block-erpw-main-menu").hide(),c(this).hide(),c(".close-popup").remove()}).appendTo(c(document.body))}}}(jQuery,Drupal,drupalSettings);