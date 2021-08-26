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
    $sql .="WHERE id = '" . db_escape($db, $id) . "'";

    // result set
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $subject = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    // Returns an assoc array not result set
    return($subject);
  }

  function validate_subject($subject) {

    $errors = [];

    if(is_blank($subject['menu_name'])) {
      $errors[] = "Name cannot be empty.";
    }
    // if menu_name's length doesn't come within this limit,
    elseif(!has_length($subject['menu_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Name must be of between 2 and 255 characters.";
    }

    $current_id = $subject['id'] ?? '0';
    if(!has_unique_subject_menu_name($subject['menu_name'], $current_id)) {
      $errors[] = "Menu name must be unique.";
    }

    // Position
    // Type casting $subject['position'] to make sure, we are working with an integer.
    $position_int = (int) $subject['position'];
    if($position_int <=0) {
      $errors[] = "Position must be greated than 0.";
    }
    if($position_int > 999) {
      $errors[] = "Position must be less than 1000.";
    }

    //Visible
    $visible_str = (string) $subject['visible'];
    if(!has_inclusion_of($subject['visible'], ['0', '1'])) {
      $errors[] = "Visible must be true or false.";    
    }

    return $errors;
  }

  // $subject is single array or a whole array
  function insert_subject($subject) {
    global $db;

    $errors = validate_subject($subject);
    //If there is any error
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO subjects ";
    $sql .= "(menu_name, position, visible) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $subject['menu_name']) . "', ";
    $sql .="'" . db_escape($db, $subject['position']) . "', ";
    $sql .="'" . db_escape($db, $subject['visible']) . "');";
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

    $errors = validate_subject($subject);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE subjects SET ";
    $sql .= "menu_name='" . db_escape($db, $subject['menu_name']) . "', ";
    $sql .= "position='" . db_escape($db, $subject['position']) . "', ";
    $sql .= "visible='" . db_escape($db, $subject['visible']) . "' ";
    $sql .= "WHERE id='" . db_escape($db, $subject['id']) . "' ";
    $sql .= "LIMIT 1";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
      return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }

  }

  function delete_subject($id) {
    global $db;

    $sql = "DELETE FROM subjects ";
    $sql .= "WHERE id= '" . db_escape($db, $id) . "' ";
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
    $sql .= "WHERE id = '" . db_escape($db, $id) ."';";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $page = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $page;
  }

  function insert_page($page) {
    global $db;

    $errors = validate_page($page);
    if(!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO pages (menu_name, subject_id, position, visible, content) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $page['menu_name']) . "', ";
    $sql .= "'" . db_escape($db, $page['subject_id']) . "', ";
    $sql .= "'" . db_escape($db, $page['position']) . "', ";
    $sql .= "'" . db_escape($db, $page['visible']) . "', ";
    $sql .= "'" . db_escape($db, $page['content']) . "');";

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

    $errors = validate_page($page);
    if(!empty($errors)) {
      return $errors;
    }
    $sql = "UPDATE pages SET ";
    $sql .= "menu_name = '" . db_escape($db, $page['menu_name']) . "', "; 
    $sql .= "subject_id = '" . db_escape($db, $page['subject_id']) ."', ";
    $sql .= "position = '" . db_escape($db, $page['position']) . "', "; 
    $sql .= "visible = '" . db_escape($db, $page['visible']) . "', "; 
    $sql .= "content = '" . db_escape($db, $page['content']) . "' ";
    $sql .= "WHERE id = '" . db_escape($db, $page['id']) . "' LIMIT 1;"; 

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
    $sql .= "WHERE id = '" . db_escape($db, $id) . "' ";
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

  function validate_page($page) {

    $errors = [];

    if(is_blank($page['menu_name'])) {
      $errors[] = "Name cannot be empty";
    }
    elseif(!has_length($page['menu_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Name must be of between 2 and 255 characters.";
    }

    $current_id = $page['id'] ?? '0';
    if(!has_unique_page_menu_name($page['menu_name'], $current_id)) {
      $errors[] = "Menu name must be unique.";
    }

    $subjectid_int = (int) $page['subject_id'];
    if($subjectid_int <= 0) {
      $errors[] = "Subject ID must be greater than 0.";
    }

    if($subjectid_int > 999) {
      $errors[] = "Subject ID must be less than 1000.";
    }    

    $position_int = (int) $page['position'];
    if($position_int <= 0) {
      $errors[] = "Position must be greater than 0.";
    }

    if($position_int > 999) {
      $errors[] = "Position must be less than 1000.";
    }

    $visible_str = (string) $page['visible'];
    if(!has_inclusion_of($visible_str, ['0', '1'])) {
      $errors[] = "Visible must be either true or false";    
    }

    if(is_blank($page['content'])) {
      $errors[] = "Please enter content of the page";
    }
    elseif(!has_length($page['content'], ['min' => 2])) {
      $errors[] = "Content must contain at least 2 characters.";
    }

    return $errors;
  }

  function find_pages_by_subject_id($subject_id) {
    global $db;
    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE subject_id = '" . db_escape($db, $subject_id) ."' ";
    $sql .= "ORDER BY position ASC;";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

?>