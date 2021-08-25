<?php

require_once('../../../private/initialize.php');

if(is_post_request()) {

  $page = [];

  $page['menu_name'] = $_POST['menu_name'];
  $page['subject_id'] = $_POST['subject_id'];
  $page['position'] = $_POST['position'];
  $page['visible'] = $_POST['visible'];
  $page['content'] = $_POST['content'];

  $page_result = insert_page($page);

  if($page_result === true) {
    $page_id = mysqli_insert_id($db);
  redirect_to(url_for('/staff/pages/show.php?id=' . hsc(u($page_id))));
  }
  else {
    $errors = $page_result;
  }
  
}   

else {
  // Display empty create new page form.
}

$page_set = find_all_pages();
$page_count = mysqli_num_rows($page_set) + 1;
mysqli_free_result($page_set);

$page = [];
$page['subject_id'] = '';
$page['position'] = $page_count;

?>

<?php $page_title = 'Create Page'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/pages/index.php'); ?>">&laquo; Back</a>

  <div class="page new">
    <h1>Create Page</h1>

    <?php echo display_errors($errors);?>

    <form action="<?php echo url_for('/staff/pages/new.php')?>" method="post">
      <dl>
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu_name" value="" /></dd>
      </dl>
      <dl>
        <dt>Subject</dt>
        <dd>
          <select name="subject_id">
          <?php
            $subject_set = find_all_subjects();
            while($subject = mysqli_fetch_assoc($subject_set)) {
              echo "<option value=\"" . hsc($subject['id']) . "\"";
              if($page["subject_id"] == $subject['id']) {
                echo " selected";
              }
              echo ">" . hsc($subject['menu_name']) . "</option>";
            }
            mysqli_free_result($subject_set);
          ?>
          </select>
        </dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd>
          <select name="position">
            <?php
            // Example - if position 11 of our database is equal to 11th iteration, i.e. 11 == 11, then 11 will be selected as new position.
            // Hint of below code: <option value = "11" selected> 11 </option>
              for($i=1; $i <= $page_count; $i++) {
                echo "<option value=\"{$i}\"";
                if($page["position"] == $i) {
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
      <dl>
        <dt>Content</dt>
        <dd>
          <textarea name="content" cols="60" rows="10" value=""></textarea>
        </dd>
      </dl>
      <div id="operations">
        <input type="submit" value="Create Page" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>