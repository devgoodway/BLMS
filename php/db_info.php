<?php
$conn = mysqli_connect("localhost", "<YOUR_DB_ID>", "<YOUR_DB_PW>");
mysqli_select_db($conn, "<YOUR_DB_TABLE>");
if (!$conn) {
  echo "Error: Unable to connect to MySQL." . PHP_EOL;
  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
  exit;}
$root_url = '<YOUR_ROOT_URL>';
?>
