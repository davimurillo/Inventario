<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ApPHP TreeMenu :: Code Template</title>
</head>
<body>
<?php
    ## +---------------------------------------------------------------------------+
    ## | 1. Creating & Calling:                                                    |
    ## +---------------------------------------------------------------------------+
    ## *** define a relative (virtual) path to treemenu.class.php file
    // define ("TREEMENU_DIR", "");                  /* Ex.: "treemenu/" */
    ## *** include TreeMenu class
    // require_once(TREEMENU_DIR.'treemenu.class.php');
    ## *** create TreeMenu object
    // $treeMenu = new TreeMenu();

    ## +---------------------------------------------------------------------------+
    ## | 2. General Settings:                                                      |
    ## +---------------------------------------------------------------------------+
    ## *** set unique identifier for TreeMenu
    ## *** (if you want to use several independently configured TreeMenu objects on single page)
    // $treeMenu->SetId(1);
    ##  *** set style for TreeMenu
    ##  *** "default" or "xp" or "vista" or "paneled" or your own style
    // $treeMenu->SetStyle("paneled");
    ## *** set TreeMenu caption
    // $treeMenu->SetCaption("ApPHP TreeMenu v".$treeMenu->Version());
    ## *** show debug info - false|true
    // $treeMenu->Debug(false);
    ## *** set postback method: "get", "post" or "ajax"
    // $treeMenu->SetPostBackMethod("ajax");
    ## *** set variables that used to get access to the page (like: my_page.php?act=34&id=56 etc.)
    /// $treeMenu->SetHttpVars(array("id"));
    ## *** show number of subnodes to the left of every node - false|true
    /// $treeMenu->ShowNumSubNodes(false);
    ## *** use an anchor to navigate to treemenu object after a node is clicked - true|false
    /// $treeMenu->UseAnchor(false);
    ## *** create the anchor automatically - true|false
    /// $treeMenu->CreateAnchorAuto(false);
    ## *** if the CreateAnchorAuto is set to true you have to create the anchor manually
    /// <a name='treemenu'></a>
    ## *** set TreeMenu direction: ltr - left-to-right or rtl - right-to-left
    /// $treeMenu->SetDirection("ltr");

    ## +---------------------------------------------------------------------------+
    ## | 3. Adding nodes:                                                          |
    ## +---------------------------------------------------------------------------+
    ## *** add nodes
    ## arguments:
    ## arg #1 - node's caption
    ## arg #2 - file associated with this node (optional)
    ## arg #3 - icon associated with this node (optional)
    ## Example 1: $treeMenu->AddNode("Title");
    ## Example 2: $treeMenu->AddNode("Title", "text.txt");
    ## Example 3: $treeMenu->AddNode("Title", "text.txt", "icon.gif");

    // $root = $treeMenu->AddNode("Title");
    // $son = $root->AddNode("Son's Title");
    // $second = $root->AddNode("2nd son's Title", "index.html");

    ## *** associate a tab with HTML code snippet:
    /// $son->SetInnerHTML("<b><i><span onclick='alert(\"How do you do?\")'>HTML</span></i> code snippet</b>");
    ## *** open a node in a new window
    /// $second->OpenNewWindow(true);
    ## *** set a tooltip for a node
    /// $son->SetTooltip("Son tab's tooltip");

    ## +---------------------------------------------------------------------------+
    ## | 4. Building tree menu from contents of a folder:                                                          |
    ## +---------------------------------------------------------------------------+
    ## *** build a tree menu from contents of a folder
    /// $treeMenu->BuildFromFolder("styles");
    ## *** use default icons for folder nodes - false|true
    /// $treeMenu->UseDefaultFolderIcons(false);
    ## *** use default icons for file nodes - false|true
    /// $treeMenu->UseDefaultFileIcons(false);

    ## +---------------------------------------------------------------------------+
    ## | 5. Draw TreeMenu:                                                      |
    ## +---------------------------------------------------------------------------+
    // $treeMenu->ShowTree();
    // $treeMenu->ShowContent();
?>
</body>
</html>