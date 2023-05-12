/**
 * @file script.js
 */
(function (Drupal, drupalSettings, $, once) {
  'use strict';

  Drupal.behaviors.userwayEmbed = {
    attach: function(context, settings) {
      if (settings.userway_embed !== undefined
        && settings.userway_embed.custom_trigger !== ""
        && settings.userway_embed.custom_trigger_selector !== ""
      ) {
        once('userwayEmbed', settings.userway_embed.custom_trigger_selector, context).forEach(function(element) {
          if (settings.userway_embed.custom_trigger.indexOf('#') !== -1) {
            $(element).attr('id', settings.userway_embed.custom_trigger.replace('#', ''));
          } else {
            $(element).addClass(settings.userway_embed.custom_trigger.replace('.', ''));
          }

          let href = $(element).attr('href');
          if (typeof href !== 'undefined' && href !== false) {
            $(element).attr('href', 'javascript:void(0);');
          }
        });
      }
    }
  };

}(Drupal, drupalSettings, jQuery, once));