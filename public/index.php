<!-- working with file system.
../ = goes one directory back.
Here we aren't using constant because they are set in initialize.php which we haven't called yet.
-->
<?php require_once('../private/initialize.php'); ?>

<?php
  if(isset($_GET['id'])) {
    $page_id = $_GET['id'];
    $page = find_page_by_id($page_id);
    // If no value is returned
    if(!$page) {
      redirect_to(url_for('/index.php'));
    }
     $subject_id = $page['subject_id'];
  }
  else {
    // nothing selected; show the home page
  }
?>

<?php include(SHARED_PATH . '/public_header.php'); ?>
<div id="main">

  <?php include(SHARED_PATH . '/public_navigation.php'); ?>

  <div id = "page">
   <?php
    if(isset($page)) {
      // show page from db
      // TODO add html escaping
      echo hsc(db_escape($db, $page['content']));
    }
    else {

    }
   ?>
  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>

