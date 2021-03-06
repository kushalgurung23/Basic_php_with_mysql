<?php
if(!isset($page_title)) {$page_title = 'Staff Area';}
?>
<!doctype html>

<html lang="en">
  <head>
    <title>KGB - <?php echo hsc($page_title);?> </title>
    <meta charset="utf-8">
    <!-- working with url -->
    <link rel="stylesheet" media="all" href= "<?php echo url_for('/stylesheets/staff.css'); ?>"/>
  </head>

  <body>
    <header>
      <h1>KGB Staff Area</h1>
    </header>

    <navigation>
      <ul>
        <li>User: <?php echo $_SESSION['username'] ?? ''; ?></li>
        <li>
          <a href="<?php echo url_for('/staff/index.php');?>">Menu</a>
        </li>
        <li>
          <a href="<?php echo url_for('/staff/logout.php');?>">Logout</a>
        </li>
      </ul>
    </navigation>

    <?php echo display_session_message(); ?>
    