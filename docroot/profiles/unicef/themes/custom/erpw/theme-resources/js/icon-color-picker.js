(function ($, Drupal, drupalSettings) {
    'use strict';
    Drupal.behaviors.icon_color_picker = {
      attach: function (context, settings) {
        $(document).ready(function() { 
          $('.field--name-field-service-type-icon .js-form-type-textfield').css('margin-top', 90);
          $('.fip-box').click(function(){
          });
    
          $('.color_field_widget_box__square').each(function(){
            var bgcolor = $(this).css( "background-color" );
            $(this).css('border-color', bgcolor);
          })

          //add bg on selected icon
          var add_bg= $('body').find('.icons-selector').children('.selector').find('span.selected-icon');
          $('.color_field_widget_box__square').click(function(){
            var color = $(this).css( "background-color" );
            $(add_bg).css('background-color', color);
            console.log(add_bg);
          })
          var activeDiv = $('.color_field_widget_box__square.active').css( "background-color" );
          $(add_bg).css('background-color', activeDiv);
        });
      }
    };
  }(jQuery, Drupal, drupalSettings));