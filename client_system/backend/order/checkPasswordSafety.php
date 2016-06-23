<?php

function url_encode($str) {  
   if(is_array($str))
  {  
        foreach($str as $key=>$value)
         {  
           $str[urlencode($key)] = url_encode($value);  
         }  
  } 
  else
   {  
       $str = urlencode($str);  
   }       
    return $str;  
}  


function checkPasswordSafety( $password )
{
 if(!preg_match( "/^[a-zA-Z0-9_]*$/",$password))
      {
      $tips =array("code"=>"4","msg"=>"只支持数字字母下划线","res"=>array("token"=>$_SESSION['token']));
      echo   urldecode(json_encode(url_encode($tips)));
      exit();
       }
 return $password ;
}
?>



