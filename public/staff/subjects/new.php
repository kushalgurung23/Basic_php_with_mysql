<?php

require_once('../../../private/initialize.php');

require_login();

$test = $_GET['test'] ?? '';
// There should be no white text while modifying the header. Check through curl --head <url> in cmd.
if($test == '404') {
  error_404();
} elseif($test == '500') {
  error_500();
}elseif($test == 'redirect') {
  redirect_to(url_for('/staff/subjects/index.php'));
}

if(is_post_request()) {       
  // Handle form values sent by new.php

  $subject = [];
  $subject['menu_name'] = $_POST['menu_name'] ?? '';
  $subject['position'] = $_POST['position'] ?? '';
  $subject['visible'] = $_POST['visible'] ?? '';

  $result = insert_subject($subject);
  if($result === true) {
    $new_id = mysqli_insert_id($db);
    $_SESSION['message'] = 'Subject was created successfully.';
    redirect_to(url_for('/staff/subjects/show.php?id=' . u($new_id)));
  } else {
    $errors = $result;
  }
}
else {
  // Display the blank form
}

$subject_set = find_all_subjects();
// New subject will automatically have new position number.
$subject_count = mysqli_num_rows($subject_set) + 1; 
mysqli_free_result($subject_set);

$subject = [];
$subject['position'] = $subject_count;

?>

<?php $page_title = 'Create Subject'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back</a>

  <div class="subject new">
    <h1>Create Subject</h1>

    <?php echo display_errors($errors);?>

    <form action="<?php echo url_for('/staff/subjects/new.php')?>" method="post">
      <dl>
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu_name" value="" /></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd>
          <select name="position">
            <?php
              for($i=1; $i <= $subject_count; $i++) {
                echo "<option value=\"{$i}\"";
                if($subject["position"] == $i) {
                  echo " selected";
                }
                echo ">{$i}</option>";
              }
            ?>
          </select>
        </dd>
      </dl>
      <dl>
        <dt>Visible</dt>
        <dd>
          <input type="hidden" name="visible" value="0" />
          <input type="checkbox" name="visible" value="1" />
        </dd>
      </dl>
      <div id="operations">
        <input type="submit" value="Create Subject" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>