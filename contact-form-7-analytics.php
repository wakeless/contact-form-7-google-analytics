<?php
/*
Plugin Name: Contact Form 7 Google Analytics
Plugin URI:
Description: Enable Google Analytics Event for contact form 7
Author: Michael Gall
Version: 0.1
*/

add_action( 'init', 'WPCF7_GoogleAnalytics::init');


class WPCF7_GoogleAnalytics {
  static function init() {
    add_filter("wpcf7_ajax_json_echo", "WPCF7_GoogleAnalytics::json_change", 10, 2);
    add_filter("wpcf7_display_message", "WPCF7_GoogleAnalytics::display_message", 10, 2);
  }

  static function display_message($message, $status) {
    if($status == "mail_sent_ok") {
      return $message . "<script type='text/javascript'>".self::js_page_event("submit")."</script>";
    } else {
      return $message;
    }
  }

  static function json_change($item, $result) {
    $items['onSentOk'][] = self::js_page_event("submit");
    return $items;
  }

  static function js_page_event($pageName) {
    return "(function() {
      var eventLocation = document.location.pathname + '/$pageName';

      if(window['_gaq']) {
        _gaq.push(['_trackPageview', eventLocation]);
      } else if(window['pageTracker']) {
        pageTracker._trackPageview(eventLocation);
      } else {
        //do nothing
      }
    })();";
  }
}

