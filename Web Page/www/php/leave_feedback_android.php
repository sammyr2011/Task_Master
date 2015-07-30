<?php
require_once 'review_class.php';

$review = new review();
$error = $review->getFromPOST($_POST);

if($error == null)
{
  $error['Success']=true;
}

echo json_encode($error);

?>
