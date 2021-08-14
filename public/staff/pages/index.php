<?php 
  require_once('../../../private/initialize.php');
?>

<?php $page_title="Pages" ?>

<?php

  // find_all_pages() function is set in query_function.php file.
  $pages_set = find_all_pages();
?>

<?php 
  include(SHARED_PATH . '/staff_header.php');
?>

<div id ="content">
  <div class="pages listing">
    <h1>Pages</h1>
    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/pages/new.php')?>">Create new page</a>
    </div>

    <table class="list">
      <tr>
        <th>ID</th>
        <th>Subject ID</th>
        <th>Name</th>
        <th>Position</th>
        <th>Visible</th>
        <th>Content</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>

        <?php while($page = mysqli_fetch_assoc($pages_set)) { ?>
            <tr>
              <td><?php echo hsc($page['id']);?></td>
              <td><?php echo hsc($page['subject_id']);?></td>
              <td><?php echo hsc($page['menu_name']);?></td>
              <td><?php echo hsc($page['position']);?></td>
              <td><?php echo $page['visible'] == 1 ? 'true' : 'false'; ?></td>
              <td><?php echo hsc($page['content']);?></td>
              <td><a class="action" href="<?php echo url_for('/staff/pages/show.php?id=' . hsc(u($page['id'])))?>">View</a></td>
              <td><a class="action" href="<?php echo url_for('/staff/pages/edit.php?id=' . hsc(u($page['id'])))?>">Edit</a></td>
              <td><a class="action" href="">Delete</a></td>
            </tr>
          <?php } ?>

    </table>

    <?php 
      mysqli_free_result($pages_set);
    ?>
  </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>