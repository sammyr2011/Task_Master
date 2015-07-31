<?php
require_once 'lister.php';

if(isset($_POST['catid']))
{
  echo json_encode(listTasksByCategory($_POST['catid']));
}
else if(isset($_POST['userid']))
{
  echo json_encode(listTasksByUser($_POST['userid']));
}
else
{
  echo json_encode(listTasksByCategory(0));
}

?>
