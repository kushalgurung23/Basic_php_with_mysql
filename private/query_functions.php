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

  // $subject is single array or a whole array
  function insert_subject($subject) {

    global $db;
    $sql = "INSERT INTO subjects ";
    $sql .= "(menu_name, position, visible) ";
    $sql .= "VALUES (";
    $sql .= "'" . $subject['menu_name'] . "', ";
    $sql .="'" . $subject['position'] . "', ";
    $sql .="'" . $subject['visible'] . "');";
    $result = mysqli_query($db, $sql);
    // For insert, update and delete, $result is true/false

    if($result) {
      return true;
    }
    else {
      // Insert failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
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
      echo mysqli_error($db);
      mysqli_close($db);
      exit;
    }
  }

  function delete_subject($id) {
    global $db;

    $sql = "DELETE FROM subjects ";
    $sql .= "WHERE id= '" . $id . "' ";
    $sql .= "LIMIT 1;";

    // We get either true/false
    $result = mysqli_query($db, $sql);
    if($result) {
      return true;
    }
    else {
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function find_subject_id() {
    global $db;
    $sql = "SELECT id FROM subjects;";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    //$all_subject_id = mysqli_fetch_assoc($result);
    //mysqli_free_result($result);
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

  function find_page_by_id($id) {
    global $db;
    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE id = '" .$id ."';";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $page = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $page;
  }

  function insert_page($page) {
    global $db;

    $sql = "INSERT INTO pages (menu_name, subject_id, position, visible, content) ";
    $sql .= "VALUES (";
    $sql .= "'" . $page['menu_name'] . "', ";
    $sql .= "'" . $page['subject_id'] . "', ";
    $sql .= "'" . $page['position'] . "', ";
    $sql .= "'" . $page['visible'] . "', ";
    $sql .= "'" . $page['content'] . "');";

    $result = mysqli_query($db, $sql);
    if($result) {
      return true;
    }
    else {
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function update_page($page) {
    global $db;
    $sql = "UPDATE pages SET ";
    $sql .= "menu_name = '" . $page['menu_name'] . "', "; 
    $sql .= "subject_id = '" . $page['subject_id'] ."', ";
    $sql .= "position = '" . $page['position'] . "', "; 
    $sql .= "visible = '" . $page['visible'] . "', "; 
    $sql .= "content = '" . $page['content'] . "' ";
    $sql .= "WHERE id = '" . $page['id'] . "' LIMIT 1;"; 

    $result_set = mysqli_query($db, $sql);
    if($result_set) {
      return true;
    }
    else {
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function delete_page($id) {
    global $db;

    $sql = "DELETE FROM pages ";
    $sql .= "WHERE id = '" . $id . "' ";
    $sql .= "LIMIT 1;";
    $result = mysqli_query($db, $sql);
    
    if($result) {
      return true;
    }
    else {
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

?>