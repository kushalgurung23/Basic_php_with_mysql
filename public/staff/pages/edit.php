<?php
  require_once('../../../private/initialize.php');

  if(isset($_GET['id'])) {
    $id = $_GET['id'];
  }
  else {
    redirect_to(url_for('staff/pages/index.php'));
  }

  if(is_post_request()) {

    $page = [];

    $page['id'] = $id;
    $page['menu_name'] = $_POST['menu_name'];
    $page['position'] = $_POST['position'];
    $page['visible'] = $_POST['visible'];
    $page['content'] = $_POST['content'];

    $result = update_page($page);
    redirect_to(url_for('/staff/pages/show.php?id=' . $page['id']));
  }
  else {
    $page = find_page_by_id($id);
    $page_set = find_all_pages();
    $page_count = mysqli_num_rows($page_set);
    mysqli_free_result($page_set);
  }

?>

<?php $page_title = 'Edit Page'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/pages/index.php'); ?>">&laquo; Back</a>

  <div class="Page edit">
    <h1>Edit Page</h1>

    <form action="<?php echo url_for('/staff/pages/edit.php?id=' . hsc(u($id))); ?>" method="post">
      <dl>
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu_name" value="<?php echo hsc($page['menu_name']);?>" /></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd>
        <select name="position">
          <?php
          // Suppose $page['position'] == 11. When turn of 11th iteration comes, both page['position'] and $i == 11, so the 11 value in the option will be checked.
            for($i=1; $i <= $page_count; $i++) {
              echo "<option value=\"{$i}\"";
              if($page['position'] == $i) {
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
          <input type="checkbox" name="visible" value="1" <?php if($page['visible'] == '1') {echo "checked";}?>/>
        </dd>
      </dl>
      <dl>
        <dt>Content</dt>
        <dd><input type="text" name="content" value="<?php echo hsc($page['content']);?>" /></dd>
      </dl>
      <div id="operations">
        <input type="submit" value="Edit Page" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>