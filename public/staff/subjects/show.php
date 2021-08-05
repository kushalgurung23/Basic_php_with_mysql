<?php
require_once('../../../private/initialize.php');


// If value of 'id' is null, $id = 1. { From php > 7.0 }
$id = $_GET['id'] ?? 'No id number';
 
// Ternary conjunction or operator
// If associate array 'id' is set, $id = 'id', else '1'
// latter executes
$id = isset($_GET['id']) ? $_GET['id'] : 'No id number';

echo $id;

?>

<!-- u = urlencode function which is defined in functions.php file -->
<a href="show.php?name=<?php echo u('John Doe'); ?>">Link</a><br />
<a href="show.php?company=<?php echo u('Widgets&More'); ?>">Link</a><br />
<a href="show.php?query=<?php echo u('!#*?'); ?>">Link</a><br />
