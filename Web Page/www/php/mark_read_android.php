<?php
require_once 'message_class.php';

$out = array();

if(isset($_POST['messageid']))
{
  $message = new message();
  if($message->getFromDB($_POST['messageid'])!=null)
  {
    $out['error']=true;
    $out['MsgNotFound']=true;
  }
  else
  {
    if($message->markRead()==null)
    {
      $out['Success']=true;
    }
    else
    {
      $out['error']=true;
      $out['NotMarkedRead']=true;
    }
  }
}
else
{
  $out['error']=true;
  $out['BadPostInput']=true;
}

echo json_encode($out);

?>
