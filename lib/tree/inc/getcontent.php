<?php

   require_once("../treemenu.class.php");
   $startTime = TreeMenu::GetFormattedMicrotime();
   $password  = TreeMenu::GetParam("password","get");
   $debug  = TreeMenu::GetParam("debug","get") == "1" ? true : false;
   if($password!="apphpmenu") return;
   $count=substr_count($_SERVER["SCRIPT_NAME"],"/");

   $filename=TreeMenu::GetParam("filename","get");
   $extension=strtolower(substr(strrchr($filename,"."),1));
   if($extension!="jpg"&&$extension!="gif"&&$extension!="bmp"&&$extension!="tif"&&$extension!="png"&&$extension!="jpeg")
   {
	  if($filename[0]!="/") $filename="/".$filename;
	  for($i=0;$i<$count-1;$i++) $filename="/..".$filename;
	  if($filename[0]=="/") $filename=substr($filename,1);
   }
   
   $node=new Node("",0,$filename,"","");

   echo $node->ShowContent($debug)."|||";
   $endTime = TreeMenu::GetFormattedMicrotime();
   echo round((float)$endTime - (float)$startTime, 6);
?>