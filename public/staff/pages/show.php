<?php

  require_once('../../../private/initialize.php');

  require_login();
  
  $id = $_GET['id'] ?? 'no id fetched';

  $page = find_page_by_id($id);

?>

<?php $subject = find_subject_by_id($page['subject_id'])?>

<?php $page_title="Page View" ?>

<?php 
  include(SHARED_PATH . '/staff_header.php');
?>

<div id="content">
  <a class="back-link" href="<?php echo url_for('/staff/subjects/show.php?id=' . hsc(u($subject['id'])));?>">&laquo; Back</a>

  <div class="page show">

    <h1>Page: <?php echo hsc($page['menu_name']); ?></h1>

    <!-- target="_blank" helps to open index.php in new tab -->
    <div class = "actions">
      <a class = "action" href="<?php echo url_for('/index.php?id=' . hsc(u($page['id'])) . '&preview=true'); ?>" target="_blank">Preview</a>
    </div>
 
    <div class="attributes">
      <dl>
        <dt>Menu Name</dt>
        <dd><?php echo hsc($page['menu_name']); ?></dd>
      </dl>
      <dl>
        <dt>Subject Name</dt>
        <dd><?php echo hsc($subject['menu_name']); ?></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd><?php echo hsc($page['position']); ?></dd>
      </dl>
      <dl>
        <dt>Visible</dt>
        <dd><?php echo $page['visible'] == '1' ? 'true' : 'false'; ?></dd>
      </dl>
      <dl>
        <dt>Content</dt>
        <dd><?php echo hsc($page['content']);?></dd>
      </dl>
    </div>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>


<!-- <?php echo htmlspecialchars('<script>alert("Gotcha");</script>');?> -->

<!-- <a class="action" href="show.php?name=<?php echo urlencode('Apple is healthy.');?>&id=100">Apple</a> -->