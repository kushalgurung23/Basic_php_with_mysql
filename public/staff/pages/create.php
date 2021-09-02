<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

  $page = [];

  $page['menu_name'] = $_POST['menu_name'];
  $page['subject_id'] = $_POST['subject_id'];
  $page['position'] = $_POST['position'];
  $page['visible'] = $_POST['visible'];
  $page['content'] = $_POST['content'];

  $page_result = insert_page($page);
  $page_id = mysqli_insert_id($db);
  redirect_to(url_for('/staff/pages/show.php?id=' . hsc(u($page_id))));

}

else {
  redirect_to(url_for('/staff/pages/new.php'));
}

?>
