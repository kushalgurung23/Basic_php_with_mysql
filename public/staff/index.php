<!-- working with file system.
../ = goes one directory back.
Here we aren't using constant because they are set in initialize.php which we haven't called yet.
-->
<?php require_once('../../private/initialize.php'); ?>

<!-- Initializing or setting variable -->
<?php $page_title = 'Staff Menu'; ?>

<?php include(SHARED_PATH . '/staff_header.php'); ?>
<div id="content">
  <div id = "main-menu">
    <h2>Main menu</h2>
    <ul>
    <!-- We aren't using absolute path, we are using relative path so we aren't putting / at beginning -->
      <li><a href="<?php echo url_for('/staff/subjects/index.php');?>   ">Subjects</a></li>
      <li>
        <a href="<?php echo url_for('/staff/pages/index.php');?>">Pages</a>
      </li>
    </ul>
  </div>

</div>
<?php include(SHARED_PATH . '/staff_footer.php'); ?>

