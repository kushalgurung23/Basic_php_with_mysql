<?php 
  require_once('../../../private/initialize.php');
?>

<?php $page_title="Pages" ?>

<?php
  $pages = [
    ['id'=>11, 'name' => 'Staff area'],
    ['id'=>12, 'name' => 'Subjects'],
    ['id'=>13, 'name' => 'Pages'],
  ];
?>

<?php 
  include(SHARED_PATH . '/staff_header.php');
?>

<div id ="content">
  <div class="pages listing">
    <h1>Pages</h1>
    <div class="actions">
      <a class="action" href="">Create new page</a>
    </div>

    <table class="list">
      <tr>
        <th>ID</th>
        <th>Page</th>
        <th>&nbsp;</th>
      </tr>

        <?php foreach($pages as $page) { ?>
            <tr>
              <td><?php echo hsc($page['id']);?></td>
              <td><?php echo hsc($page['name']);?></td>
              <td><a class="action" href="<?php echo url_for('/staff/pages/show.php?id=' . hsc(u($page['id'])))?>">View</a></td>
            </tr>
          <?php } ?>

    </table>
  </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>