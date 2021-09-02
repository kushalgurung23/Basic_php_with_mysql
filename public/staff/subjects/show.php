<?php
require_once('../../../private/initialize.php');

require_login();

// If value of 'id' is null, $id = 1. { From php > 7.0 }
$id = $_GET['id'] ?? 'No id number';
$pages_set = find_pages_by_subject_id($id);
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

  <hr />
    <div class="pages listing">
      <h2>Pages</h2>
      <div class="actions">
        <a class="action" href="<?php echo url_for('/staff/pages/new.php?subject_id=' . hsc(u($subject['id']))); ?>">Create new page</a>
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

          <?php while($page = mysqli_fetch_assoc($pages_set)) { ?>
            <?php $subject = find_subject_by_id($page['subject_id']);?>
              <tr>
                <td><?php echo hsc($page['id']);?></td>
                <td><?php echo hsc($page['menu_name']);?></td>
                <td><?php echo hsc($page['position']);?></td>
                <td><?php echo $page['visible'] == 1 ? 'true' : 'false'; ?></td>
                <td><a class="action" href="<?php echo url_for('/staff/pages/show.php?id=' . hsc(u($page['id'])))?>">View</a></td>
                <td><a class="action" href="<?php echo url_for('/staff/pages/edit.php?id=' . hsc(u($page['id'])))?>">Edit</a></td>
                <td><a class="action" href="<?php echo url_for('/staff/pages/delete.php?id=' . hsc(u($page['id']))); ?>">Delete</a></td>
              </tr>
            <?php } ?>

      </table>

      <?php 
        mysqli_free_result($pages_set);
      ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
