<?php
  function find_all_subjects() {
    // Using global keyword in $db inorder to get access to it since it is outside of our scope access.
    global $db;

    // Space is important after subjects for concatenation.
    $sql = "SELECT * FROM subjects ";
    $sql .="ORDER BY position ASC";
    $result = mysqli_query($db, $sql); 

    // confirms that the query is executed and data are set in the variable.
    confirm_result_set($result);
    return ($result);
  }

  function find_all_pages() {
    global $db;
    $query = "SELECT * FROM pages ";
    $query .="ORDER BY subject_id ASC, position ASC";
    $result = mysqli_query($db, $query);
    confirm_result_set($result);
    return $result;
  }

?>