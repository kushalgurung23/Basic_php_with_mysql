<?php
  // Default values to prevent errors when no subjects/pages are selected from left navigation menu.
  $page_id = $page_id ?? '';
  $subject_id = $subject_id ?? '';
  $visible = $visible ?? true;
?>

<navigation>
  <?php $nav_subjects = find_all_subjects(['visible' => $visible]); ?>
  <ul class="subjects">
    <?php while($nav_subject = mysqli_fetch_assoc($nav_subjects)) { ?>
      <!-- If subject is not visible, go to next loop without executing anything below continue keyword. -->
      <?php //if(!$nav_subject['visible']) {continue;} ?>
      <li class="<?php if($nav_subject['id'] == $subject_id) {echo 'selected'; }?>">
        <a href="<?php echo url_for('index.php?subject_id=' . hsc(u($nav_subject['id']))); ?>">
          <?php echo hsc($nav_subject['menu_name']); ?>
        </a>

        <?php if($nav_subject['id'] == $subject_id) {?>
          <?php $nav_pages = find_pages_by_subject_id($nav_subject['id'], ['visible' => $visible]); ?>
            <ul class="pages">
              <?php while($nav_page = mysqli_fetch_assoc($nav_pages)) { ?>
                <!-- If page is not visible, go to next loop without executing anything below continue keyword. -->
                <?php //if(!$nav_page['visible']) {continue;} ?>
                <!-- $page_id is in index.php which is the same file -->
                <li class="<?php if($nav_page['id'] == $page_id) {echo 'selected'; }?>">
                  <a href="<?php echo url_for('index.php?id=' . hsc(u($nav_page['id']))); ?>">
                    <?php echo hsc($nav_page['menu_name']); ?>
                  </a>
                  
                </li>
              <?php } // while $nav_pages ?>
            </ul>
          <?php mysqli_free_result($nav_pages); ?>
        <?php } ?>

      </li>
    <?php } // while $nav_subjects ?>
  </ul>
  <?php mysqli_free_result($nav_subjects); ?>
</navigation>
