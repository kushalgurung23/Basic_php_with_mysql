<?php
  function url_for($script_path) {
    // adds the leading '/' if not present
    // $script_path[0] = first letter only
    if($script_path[0] != '/') {
      $script_path = "/" . $script_path;
    }
    return WWW_ROOT . $script_path;
  }
  
?>
