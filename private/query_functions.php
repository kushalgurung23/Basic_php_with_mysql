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

  function find_subject_by_id($id) {
    global $db;
    $sql = "SELECT * FROM subjects ";
    $sql .="WHERE id = '" . $id . "'";

    // result set
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $subject = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    // Returns an assoc array not result set
    return($subject);
  }

  function insert_subject($menu_name, $position, $visible) {

    global $db;
    $sql = "INSERT INTO subjects ";
    $sql .= "(menu_name, position, visible) ";
    $sql .= "VALUES (";
    $sql .= "'" . $menu_name . "', ";
    $sql .="'" . $position . "', ";
    $sql .="'" . $visible . "');";
    $result = mysqli_query($db, $sql);
    // For insert, update and delete, $result is true/false

    if($result) {
      return true;
    }
    else {
      // Insert failed
      mysqli_error($db);
      db_disconnect($db);
    }
  }


  // $subject is a single array of attribute
  function update_subject($subject) {
    global $db;

    $sql = "UPDATE subjects SET ";
    $sql .= "menu_name ='" . $subject['menu_name'] . "', ";
    $sql .= "position ='" . $subject['position'] . "', ";
    $sql .= "visible ='" . $subject['visible'] . "' ";
    $sql .= "WHERE id = '" . $subject['id'] ."' ";
    $sql .= "LIMIT 1;";

    $result = mysqli_query($db, $sql);
    if($result) {
      return true;
    }
    else {
      mysqli_error($db);
      mysqli_close($db);
      exit;
    }
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