<?php
require_once('../../../private/initialize.php');

require_login();

// If value of 'id' is null, $id = 1. { From php > 7.0 }
$id = $_GET['id'] ?? 'No id number';
 
// Ternary conjunction or operator
// If associate array 'id' is set, $id = 'id', else '1'
// latter executes
$id = isset($_GET['id']) ? $_GET['id'] : 'No id number';

//htmlspecialchars to avoid execution of harmful user inputs like username </div>kushal
//echo hsc($id);

$admin = find_admin_by_id($id);

?>

<?php $page_title="Admin View" ?>

<?php 
  include(SHARED_PATH . '/staff_header.php');
?>

<div id="content">
  <a class="back-link" href="<?php echo url_for('/staff/admins/index.php');?>">&laquo; Back</a>

  <div class="admin show">

    <h1>Admin: <?php echo hsc($admin['username']); ?></h1>
 
    <div class="attributes">
    <dl>
        <dt>First Name</dt>
        <dd><?php echo hsc($admin['first_name']);?></dd>
      </dl>
      <dl>
        <dt>Last Name</dt>
        <dd><?php echo hsc($admin['last_name']);?></dd>
      </dl>
      <dl>
        <dt>Email</dt>
        <dd><?php echo hsc($admin['email']);?></dd>
      </dl>
      <dl>
        <dt>Username</dt>
        <dd><?php echo hsc($admin['username']);?></dd>
      </dl>
    </div>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
