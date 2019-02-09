<?php
require 'Main.php';

$do = new Main;
if ($do->login('tests.php')){
  // echo 'DONE';
}else{
  // echo 'Something wrong';
}
?>
