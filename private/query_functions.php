<?php
  function find_all_subjects($options=[]) {
    // Using global keyword in $db inorder to get access to it since it is outside of our scope access.
    global $db;

    $visible = $options['visible'] ?? false;

    // Space is important after subjects for concatenation.
    $sql = "SELECT * FROM subjects ";
    if($visible) {
      $sql .= "WHERE visible = true ";
    }
    $sql .="ORDER BY position ASC";
    $result = mysqli_query($db, $sql); 

    // confirms that the query is executed and data are set in the variable.
    confirm_result_set($result);
    return ($result);
  }

  function find_subject_by_id($id, $options = []) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM subjects ";
    $sql .="WHERE id = '" . db_escape($db, $id) . "' ";
    if($visible) {
      $sql .= "AND visible = true;";
    }
    
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

    shift_subject_positions(0, $subject['position']);

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

    $old_subject = find_subject_by_id($subject['id']);
    $old_position = $old_subject['position'];
    shift_subject_positions($old_position, $subject['position'], $subject['id']);

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

    $old_subject = find_subject_by_id($id);
    $old_position = $old_subject['position'];
    shift_subject_positions($old_position, 0, $id);

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

  function shift_subject_positions($start_pos, $end_pos, $current_id=0) {
    global $db;

    if($start_pos == $end_pos) { return; }

    $sql = "UPDATE subjects ";
    if($start_pos == 0) {
      // new item, +1 to items greater than $end_pos
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    } elseif($end_pos == 0) {
      // delete item, -1 from items greater than $start_pos
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    } elseif($start_pos < $end_pos) {
      // move later, -1 from items between (including $end_pos)
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
      $sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
    } elseif($start_pos > $end_pos) {
      // move earlier, +1 to items between (including $end_pos)
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
      $sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
    }
    // Exclude the current_id in the SQL WHERE clause
    $sql .= "AND id != '" . db_escape($db, $current_id) . "' ";

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

  // Page query section
  function find_all_pages() {
    global $db;
    $query = "SELECT * FROM pages ";
    $query .="ORDER BY subject_id ASC, position ASC";
    $result = mysqli_query($db, $query);
    confirm_result_set($result);
    return $result;
  }

  function find_page_by_id($id, $options = []) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE id = '" . db_escape($db, $id) ."' ";
    if($visible) {
      $sql .= "AND visible = true;";
    }

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

    shift_page_positions(0, $page['position'], $page['subject_id']);

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

    $old_page = find_page_by_id($page['id']);
    $old_position = $old_page['position'];
    shift_page_positions($old_position, $page['position'], $page['subject_id']);

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

    $old_page = find_page_by_id($id);
    $old_position = $old_page['position'];
    shift_page_positions($old_position, 0, $old_page['subject_id'], $id);

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

  function find_pages_by_subject_id($subject_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE subject_id = '" . db_escape($db, $subject_id) ."' ";
    if($visible) {
      $sql .= "AND visible = true ";
    }
    $sql .= "ORDER BY position ASC;";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    return $result;
  }

  function count_pages_by_subject_id($subject_id, $options=[]) {
    global $db;

    $visible = $options['visible'] ?? false;

    $sql = "SELECT COUNT(id) FROM pages ";
    $sql .= "WHERE subject_id = '" . db_escape($db, $subject_id) ."' ";
    if($visible) {
      $sql .= "AND visible = true ";
    }
    $sql .= "ORDER BY position ASC;";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

    // mysqli_fetch_row() returns a single row (standard array)
    $row = mysqli_fetch_row($result);
    mysqli_free_result($result);
    $count = $row[0];
    return $count;
  }

  function shift_page_positions($start_pos, $end_pos, $subject_id, $current_id=0) {
    global $db;

    if($start_pos == $end_pos) { return; }

    $sql = "UPDATE pages ";
    if($start_pos == 0) {
      // new item, +1 to items greater than $end_pos
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    } elseif($end_pos == 0) {
      // delete item, -1 from items greater than $start_pos
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    } elseif($start_pos < $end_pos) {
      // move later, -1 from items between (including $end_pos)
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
      $sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
    } elseif($start_pos > $end_pos) {
      // move earlier, +1 to items between (including $end_pos)
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
      $sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
    }
    // Exclude the current_id in the SQL WHERE clause
    $sql .= "AND id != '" . db_escape($db, $current_id) . "' ";
    $sql .= "AND subject_id = '" . db_escape($db, $subject_id) . "'";

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

  // Admin query section

  function find_all_admins() {
    global $db;
    $query = "SELECT * FROM admins ";
    $query .="ORDER BY last_name ASC, first_name ASC;";
    $result = mysqli_query($db, $query);
    confirm_result_set($result);
    return $result;
  }

  function find_admin_by_id($id) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE id = '" . db_escape($db, $id) ."' ";
    $sql .= "LIMIT 1;";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $admin;
  }

  function insert_admin($admin) {
    global $db;

    $errors = validate_admin($admin);
    if(!empty($errors)) {
      return $errors;
    }

    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT, ['cost' => 10]);

    $sql = "INSERT INTO admins (first_name, last_name, email, username, hashed_password) ";
    $sql .= "VALUES (";
    $sql .= "'" . db_escape($db, $admin['first_name']) . "', ";
    $sql .= "'" . db_escape($db, $admin['last_name']) . "', ";
    $sql .= "'" . db_escape($db, $admin['email']) . "', ";
    $sql .= "'" . db_escape($db, $admin['username']) . "', ";
    $sql .= "'" . db_escape($db, $hashed_password) . "');";

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

  function update_admin($admin) {
    global $db;

    // if password is also sent, $password_sent will be true; because is_blank will return false.
    $password_sent = !is_blank($admin['password']);

    $errors = validate_admin($admin, ['password_required' => $password_sent]);
    if(!empty($errors)) {
      return $errors;
    }

    $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT, ['cost' => 10]);

    $sql = "UPDATE admins SET ";
    $sql .= "first_name = '" . db_escape($db, $admin['first_name']) . "', "; 
    $sql .= "last_name = '" . db_escape($db, $admin['last_name']) ."', ";
    $sql .= "email = '" . db_escape($db, $admin['email']) . "', "; 
    if($password_sent) {
      $sql .= "hashed_password = '" . db_escape($db, $hashed_password) . "', ";
    }
    $sql .= "username = '" . db_escape($db, $admin['username']) . "' "; 
    
    $sql .= "WHERE id = '" . db_escape($db, $admin['id']) . "' LIMIT 1;"; 

    $result_set = mysqli_query($db, $sql);
    if($result_set) {
      // Updating username session value
      $_SESSION['username'] = $admin['username'];
      return true;
    }
    else {
      echo mysqli_error($db);
      db_disconnect($db);
      exit;
    }
  }

  function delete_admin($id) {
    global $db;

    $sql = "DELETE FROM admins ";
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

  function validate_admin($admin, $options = []) {

    $password_required = $options['password_required'] ?? true;

    $errors = [];
    $current_id = $admin['id'] ?? '0';

    if(is_blank($admin['first_name'])) {
      $errors[] = "First name cannot be empty";
    }
    elseif(!has_length($admin['first_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "First name must be of between 2 and 255 characters.";
    }

    if(is_blank($admin['last_name'])) {
      $errors[] = "Last name cannot be empty";
    }
    elseif(!has_length($admin['last_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Last name must be of between 2 and 255 characters.";
    }

    if(is_blank($admin['email'])) {
      $errors[] = "Email cannot be empty";
    }
    elseif(!has_length($admin['email'], ['max' => 255])) {
      $errors[] = "Email address must be less than 255 characters.";
    }
    elseif(!has_valid_email_format($admin['email'])) {
      $errors[] = "Email address must be in the valid format.";
    }

    if(is_blank($admin['username'])) {
      $errors[] = "Username cannot be empty";
    }
    elseif(!has_length($admin['username'], ['min' => 8, 'max' => 255])) {
      $errors[] = "Username must be of between 8 and 255 characters.";
    }
    elseif(!has_unique_admin_username($admin['username'], $current_id)) {
      $errors[] = "Username must be unique.";
    }

    // If password was sent
    if($password_required) {
      if(is_blank($admin['password'])) {
        $errors[] = "Password cannot be empty";
      }
      elseif(!has_length($admin['password'], ['min' => 12])) {
        $errors[] = "Password must be more than 12 characters.";
      }
      elseif(!preg_match('/[A-Z]/', $admin['password'])) {
        $errors[] = "Password must must contain at least 1 uppercase letter.";
      }
      elseif(!preg_match('/[a-z]/', $admin['password'])) {
        $errors[] = "Password must must contain at least 1 lowercase letter.";
      }
      elseif(!preg_match('/[0-9]/', $admin['password'])) {
        $errors[] = "Password must must contain at least 1 number.";
      }
      // \s = white space
      elseif(!preg_match('/[^A-Za-z0-9\s]/', $admin['password'])) {
        $errors[] = "Password must must contain at least 1 symbol.";
      }
  
      if(is_blank($admin['confirm_password'])) {
        $errors[] = "Confirm password cannot be empty";
      }
      elseif($admin['password'] !== $admin['confirm_password']) {
        $errors[] = "Password and confirm password must match.";
      }
    }
    
    return $errors;
  }

  function find_admin_by_username($username) {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE username = '" . db_escape($db, $username) ."' ";
    $sql .= "LIMIT 1;";

    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $admin = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $admin;
  }

?>