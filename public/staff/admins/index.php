<!-- working with file system.
../ = goes one directory back.
-->
<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<!-- Initializing or setting variable -->
<?php $page_title = 'Admin'; ?>

<?php

  $admin_set = find_all_admins();
 
?>

<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div class="Admin listing">
    <h1>Admin</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/admins/new.php');?>">Create New Admin</a>
    </div>

  	<table class="list">
  	  <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Username</th>
  	    <th>&nbsp;</th>
  	    <th>&nbsp;</th>
        <th>&nbsp;</th>
  	  </tr>

      <?php while($admin = mysqli_fetch_assoc($admin_set)) { ?>
        
        <tr>
          <td><?php echo hsc($admin['id']); ?></td>
          <td><?php echo hsc($admin['first_name']); ?></td>
          <td><?php echo hsc($admin['last_name']); ?></td>
          <td><?php echo hsc($admin['email']); ?></td>
          <td><?php echo hsc($admin['username']); ?></td>
          <td><a class="action" href="<?php echo url_for('/staff/admins/show.php?id=' . hsc(u($admin['id']))); ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/admins/edit.php?id=' . hsc(u($admin['id'])));?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/admins/delete.php?id=' . hsc(u($admin['id'])));?>">Delete</a></td>
    	  </tr>
      <?php } ?>
  	</table>

    <!-- Releasing the stored object after it's use for memory management. -->
    <?php
      mysqli_free_result($admin_set);
    ?>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>

