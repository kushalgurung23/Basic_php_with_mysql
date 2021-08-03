<!-- working with file system.
../ = goes one directory back.
Here we aren't using constant because they are set in initialize.php which we haven't called yet.
-->
<?php require_once('../../private/initialize.php'); ?>

<!-- Initializing or setting variable -->
<?php $page_title = 'Staff Menu'; ?>

<?php include(SHARED_PATH . '/staff_header.php'); ?>
<div id="content">

</div>
<?php include(SHARED_PATH . '/staff_footer.php'); ?>

