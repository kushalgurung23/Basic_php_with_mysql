<?php 
  require_once('../../../private/initialize.php');
?>

<?php $page_title="Pages" ?>

<?php
  $pages = [
    ['id'=>11, 'name' => 'Staff area', 'position' => '1', 'visible' => '0'],
    ['id'=>12, 'name' => 'Subjects', 'position' => '1', 'visible' => '0'],
    ['id'=>13, 'name' => 'Subjects', 'position' => '1', 'visible' => '1'],
  ];
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
        <th>Name</th>
        <th>Position</th>
        <th>Visible</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>

        <?php foreach($pages as $page) { ?>
            <tr>
              <td><?php echo hsc($page['id']);?></td>
              <td><?php echo hsc($page['name']);?></td>
              <td><?php echo hsc($page['position']);?></td>
              <td><?php echo $page['visible'] == 1 ? 'true' : 'false'; ?></td>
              <td><a class="action" href="<?php echo url_for('/staff/pages/show.php?id=' . hsc(u($page['id'])))?>">View</a></td>
              <td><a class="action" href="<?php echo url_for('/staff/pages/edit.php?id=' . hsc(u($page['id'])))?>">Edit</a></td>
              <td><a class="action" href="">Delete</a></td>
            </tr>
          <?php } ?>

    </table>
  </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>