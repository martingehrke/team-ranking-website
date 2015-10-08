<?php

$e = 'asdf@asdf.com';

function checkEmail($email) 
{
   if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email)) 
   {
      return FALSE;
   }

   list($Username, $Domain) = split("@",$email);

   if(getmxrr($Domain, $MXHost)) 
   {
      return TRUE;
   }
}

if(checkEmail($e) == FALSE)
{
   echo "E-mail entered is not valid.";
}
else
{
   echo "E-mail entered is valid.";
}



?>
