<?php

require_once('../../../private/initialize.php');

$test = $_GET['test'] ?? '';
// There should be no white text while modifying the header. Check through curl --head <url> in cmd.
if($test == '404') {
  error_404();
} elseif($test == '500') {
  error_500();
}elseif($test == 'redirect') {
  redirect_to(url_for('/staff/subjects/index.php'));
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

    <form action="<?php echo url_for('/staff/subjects/create.php')?>" method="post">
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