<?php
require_once('../../../private/initialize.php');


// If value of 'id' is null, $id = 1. { From php > 7.0 }
$id = $_GET['id'] ?? 'No id number';
 
// Ternary conjunction or operator
// If associate array 'id' is set, $id = 'id', else '1'
// latter executes
$id = isset($_GET['id']) ? $_GET['id'] : 'No id number';

//htmlspecialchars to avoid execution of harmful user inputs like username </div>kushal
//echo hsc($id);

$subject = find_subject_by_id($id);

?>

<?php $page_title="Subject View" ?>

<?php 
  include(SHARED_PATH . '/staff_header.php');
?>

<div id="content">
  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php');?>">&laquo; Back</a>

  <div class="subject show">

    <h1>Subject: <?php echo hsc($subject['menu_name']); ?></h1>
 
    <div class="attributes">
      <dl>
        <dt>Menu Name</dt>
        <dd><?php echo hsc($subject['menu_name']); ?></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd><?php echo hsc($subject['position']); ?></dd>
      </dl>
      <dl>
        <dt>Visible</dt>
        <dd><?php echo $subject['visible'] == '1' ? 'true' : 'false'; ?></dd>
      </dl>
    </div>

  </div>
  <br/>

  <div class="check parameters">
    <h2>Links to check url parameter</h2>
    <!-- u = urlencode function which is defined in functions.php file -->
    <a href="show.php?name=<?php echo hsc(u('John Doe')); ?>">John Doe</a><br />
    <a href="show.php?company=<?php echo hsc(u('Widgets&More')); ?>">Widgets&More</a><br />
    <a href="show.php?query=<?php echo hsc(u('!#*?')); ?>">!#*?</a><br />
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
