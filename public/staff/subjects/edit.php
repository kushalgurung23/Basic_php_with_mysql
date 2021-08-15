<?php

// $test = $_GET['test'] ?? '';
// There should be no white text while modifying the header. Check through curl --head <url> in cmd.
// if($test == '404') {
//   error_404();
// } elseif($test == '500') {
//   error_500();
// }elseif($test == 'redirect') {
//   redirect_to(url_for('/staff/subjects/index.php'));
// }

require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/subjects/index.php'));
}
$id = $_GET['id'] ?? '';

if(is_post_request()) {

  $subject = [];

  $subject['id'] = $id;
  $subject['menu_name'] = $_POST['menu_name'] ?? '';
  $subject['position'] = $_POST['position'] ?? '';
  $subject['visible'] = $_POST['visible'] ?? '';

  $result = update_subject($subject);
  redirect_to(url_for('/staff/subjects/show.php?id=' . $id));

}
else {
  // It will be executed only once when the page is not in post server request method, i.e. in get request method. Once we edit the form, the page will always be in post request.
  $subject = find_subject_by_id($id);
}

?>

<?php $page_title = 'Edit Subject'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

  <div class="subject edit">
    <h1>Edit Subject</h1>

    <form action="<?php echo url_for('/staff/subjects/edit.php?id=' . hsc(u($id))); ?>" method="post">
      <dl>
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu_name" value="<?php echo hsc($subject['menu_name']);?>" /></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd>
          <select name="position">
            <option value="1" <?php if($subject['position'] == '1') {echo "selected";}?>>1</option>
          </select>
        </dd>
      </dl>
      <dl>
        <dt>Visible</dt>
        <dd>
          <input type="hidden" name="visible" value="0" />
          <input type="checkbox" name="visible" value="1" <?php if($subject['visible'] == '1') {echo "checked";}?>/>
        </dd>
      </dl>
      <div id="operations">
        <input type="submit" value="Edit Subject" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>