<?php

require_once 'task_list.php';

if(isset($_POST['catid']))
{
  $incatid = $_POST['catid'];
}
else
{
  $incatid = 0;
}

echo json_encode(listByCategory($incatid));

?>
