<?php
    require_once("../treemenu.class.php");
    $password  = TreeMenu::GetParam("password","request");
    if($password!="apphpmenu") return;
    $output = "";
    echo $style;
    
    $directory=TreeMenu::GetParam("directory","get");
    $style=TreeMenu::GetParam("style","get");
    $folderIcons=TreeMenu::GetParam("folderIcons","get");
    $fileIcons=TreeMenu::GetParam("fileIcons","get");
    $parentId=TreeMenu::GetParam("parentId","get");
    $path=TreeMenu::GetParam("path","get");
    $direction=TreeMenu::GetParam("direction","get");
    $count=substr_count($_SERVER["SCRIPT_NAME"],"/");
    $treeMenu=new TreeMenu();
    if($folderIcons == 1) $treeMenu->UseDefaultFolderIcons(true);
    if($fileIcons == 1) $treeMenu->UseDefaultFileIcons(true);
    $treeMenu->SetPostbackMethod("ajax");
    $treeMenu->SetStyle($style);
    $treeMenu->SetId($parentId);
    $treeMenu->SetDirection($direction);
    if($directory[0]!="/") $directory="/".$directory;
    for($i=0;$i<$count-1;$i++) $directory="/..".$directory;
    if($directory[0]=="/") $directory=substr($directory,1);
    $directory=TreeMenu::SimplifyPath($directory);
    $treeMenu->SetSecondaryPath($path);
    $treeMenu->BuildFromFolder($directory,false);
    $output.= $treeMenu->GetNumChildren();
    $output.= $treeMenu->ShowNodes();
    echo $output;
?>