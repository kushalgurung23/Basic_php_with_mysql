<?php
  function url_for($script_path) {
    // adds the leading '/' if not present
    // $script_path[0] = first letter only
    if($script_path[0] != '/') {
      $script_path = "/" . $script_path;
    }
    return WWW_ROOT . $script_path;
  }

  function u($string) {
    return urlencode($string);
  }
  
  function raw_u($string="") {
    return rawurlencode($string);
  }

  function hsc($string) {
    return htmlspecialchars($string);
  }

  function error_404() {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Page Not Found");
    exit();
  }
  
  function error_500() {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    exit();
  }

  function redirect_to($location) {
    header("Location: " . $location); 
    exit();
  }

  // returns either true or false
  function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }

  function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
  }

?>
