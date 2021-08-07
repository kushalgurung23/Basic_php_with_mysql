<?php

  require_once('../../../private/initialize.php');

  $id = $_GET['id'] ?? 'no id fetched';
?>

<?php $page_title="Page View" ?>

<?php 
  include(SHARED_PATH . '/staff_header.php');
?>

<div id="content">
  <a class="back-link" href="<?php echo url_for('/staff/pages/index.php');?>">&laquo; Back</a>
  <br>
  <p>page id: <?php echo hsc($id);?></p>
</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>


<!-- <?php echo htmlspecialchars('<script>alert("Gotcha");</script>');?> -->

<!-- <a class="action" href="show.php?name=<?php echo urlencode('Apple is healthy.');?>&id=100">Apple</a> -->