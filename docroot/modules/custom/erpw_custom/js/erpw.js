(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.catapult_img_preview = {
    attach: function (context, settings) {
      //Home page guideline link add attr
      $('.field--name-field-upload-gbv-referrals-guide .file--application-pdf a').attr('target', '_blank');
      // Redirect user to Language selector screen.
      let langCookieSelector = getCookie('userLanguageSelection');
      if (langCookieSelector !== "TRUE" && window.location.pathname !== "/select-language") {
       window.location.href = "/select-language";
      }

      /**
       * Get cookie value.
       */
      function getCookie(name) {
        function escape(s) { return s.replace(/([.*+?\^$(){}|\[\]\/\\])/g, '\\$1'); }
        var match = document.cookie.match(RegExp('(?:^|;\\s*)' + escape(name) + '=([^;]*)'));
        return match ? match[1] : null;
      }

      jQuery( ".help-text" ).hover(
        function() {
          jQuery( this ).append( jQuery( '<span class="password-help-text">Password should contain one number,  one lowercase letter, one uppercase letter and one special symbol (min Length 8 Character)</span>' ) );
        }, function() {
          jQuery( this ).find( "span" ).last().remove();
        }
      );
      $(document).ready(function() {
        $(".page-node-type-service-type .ui-icon-closethick").on("click", function(event){
          event.preventDefault();
          window.location.href = drupalSettings.erpw_custom.manage_service_type_page;
        });
      });
      $(".ui-icon-closethick").on("click", function(event){
        event.preventDefault();
        window.location.href = "/";
      });
      $(".signin-ok").on("click", function(event){
        event.preventDefault();
        $(".ui-icon-closethick").click();
      });
      $(".ok-btn").click(function(){
        $("span.ui-icon-closethick").click();
      });
      // set Localstorage and remove Localstorage when browser close.
      if(sessionStorage.getItem('signinPopup')){
        $('.sign-in-popup, .overlay').hide();
      }
      $(".path-frontpage, .skip, .sign-in").on('click', function(){
        window.sessionStorage.signinPopup = "true";
      });
      $(window).on("unload", function(){
        // Clear the session storage
        window.signinPopup.clear()
      });

      //Add select 2 for multiselect
      $('.form-select.add_multiple').attr('multiple','multiple');
      $('.form-select').select2();

      //Home page country field
      if ($('.user-location').text().length > 0) {
        $('.user-location').addClass('location-theme');
      }
      /** Sign UP form country check Start **/
      $("#sign-up .form-item-level-0 > label").text(Drupal.t("Country"));
      if ($("#sign-up #edit-level-0").val() == '') {
        $("#sign-up #edit-back").hide();
        $("#sign-up .signup-next").hide();
      }
      else {
        $('#sign-up #intro-text').hide();
      }
      $("#sign-up #edit-level-0").on("change", function() {
        if ($(this).val() != '') {
          $('#sign-up #intro-text').hide();
          $("#sign-up #edit-back").show();
          $("#sign-up .signup-next").show();
        }
        else {
          $('#sign-up #intro-text').show();
          $("#sign-up #edit-back").hide();
          $("#sign-up .signup-next").hide();
        }
      });
       /** Sign UP form country check End **/

       //Add condition for border under view header
       var icon_button = $('.button-with-icon').length;
       if ($(icon_button).length > 0) {
          $('.view-header').css('border-bottom', '2px solid #f3c1bf87');
       } else {
          $('.view-header').css('border-bottom', 0);
       }
    }
  };

  // Removing nid from autocomplete of user forms.
  Drupal.behaviors.user_location_autocomplete = {
    attach: function(context) {
      // Remove TID's onload.
      Drupal.user_location_autocomplete.remove_tid();
      // Remove TID's onchange.
      jQuery('body').find('.form-autocomplete').on('autocompleteclose', function() {
        Drupal.user_location_autocomplete.remove_tid();
      });
    }
  };

  Drupal.user_location_autocomplete = {
    remove_tid: function () {
      let field_autocomplete = jQuery('body').find('.form-autocomplete');
      field_autocomplete.each(function (event, node) {
        let str = $(this).val();
        let val = str.replace(/("|')/g, "");
        let match = val.match(/\s\(.*?\)/g);
        if (match) {
          $(this).data('real-value', val);
          $(this).val(val.replace(/\s\(.*?\)/g, '' ));
        }
      });
    }
  };

}(jQuery, Drupal, drupalSettings));