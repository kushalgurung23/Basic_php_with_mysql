<?php
require_once('../../private/initialize.php');

$errors = [];
$username = '';
$password = '';

if(is_post_request()) {

  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Login validation
  if(is_blank($username)) {
    $errors[] = "Username cannot be blank.";
  }
  if(is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }

  // If no error, login
  if(empty($errors)) {
    // Using one variable ensures that msg is the same.
    $login_failure_msg = "Login was unsuccessful.";
    
    $admin = find_admin_by_username($username);
    if($admin) {

      if(password_verify($password, $admin['hashed_password'])) {
        // Password matched
        log_in_admin($admin);
        redirect_to(url_for('/staff/index.php'));
      }
      // Incorrect password
      else {
        $errors[] = $login_failure_msg;
      }
    }
    // No username detected
    else {
      $errors[] = $login_failure_msg;
    }
  }

}

?>

<?php $page_title = 'Log in'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <h1>Log in</h1>

  <?php echo display_errors($errors); ?>

  <form action="login.php" method="post">
    Username:<br />
    <input type="text" name="username" value="<?php echo hsc($username); ?>" /><br />
    Password:<br />
    <input type="password" name="password" value="" /><br />
    <input type="submit" name="submit" value="Submit"  />
  </form>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
