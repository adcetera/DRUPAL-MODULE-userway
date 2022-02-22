/**
 * @file script.js
 */
(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.adcAccessibe = {
    attach: function(context, settings) {
      var $acsbLinks = $('a[href="#showacsb"]');
      $acsbLinks.each(function(i, link) {
        $(link).attr('data-acsb-custom-trigger', 'true');
        $(link).attr('href', 'javascript:void(0);');
      });
    }
  };

}(Drupal, jQuery));