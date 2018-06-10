<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP TreeMenu Advanced version 2.1.2 (14.07.2011)                         #
##  Developed by:  ApPHP <info@apphp.com>                                      #
##  License:       GNU GPL v.2                                                 #
##  Site:          http://www.apphp.com/php-tree-menu/                         #
##  Copyright:     ApPHP TreeMenu (c) 2010 - 2011. All rights reserved.        #
##                                                                             #
##  Additional modules (embedded):                                             #
##  -- jQuery v1.4.2 (JavaScript Library)                                      #
##                                                                             #
################################################################################

class TreeMenu
{
    // PUBLIC                                     PRIVATE
    // -------                                    --------
    // constructor                                ShowPlusMinus
    // AddNode                                    ShowIcon
    // AddNodeWithInnerHTML                       ShowNode
    // AddNodeAction                              LoadFiles
    // ShowTree
    // ShowNodes                                  STATIC
    // ShowContent                                --------
    // BuildFromFolder                            Simplify
    // BuildNodeFromFolder                        CheckInput
    // SetHttpVars
    // UseDefaultFolderIcons
    // UseDefaultFileIcons
    // ShowNumSubNodes

    // IsRefreshSelectedNodesAllowed
    // AllowRefreshSelectedNodes
    // SetPostBackMethod
    // SetId
    // GetId
    // SetCaption
    // GetPath
    // SetStyle
    // GetStyle
    // GetSecondaryPath
    // SetSecondaryPath
    // GetNumChildren
    // Debug
    // GetDebug
    // Version

    // ShowDebugInfo
    // SetDirection
    // UseAnchor
    // CreateAnchorAuto

    // STATIC
    // --------
    // GetFormattedMicrotime
    // SimplifyPath
    // GetParam

    //--- PRIVATE DATA MEMBERS --------------------------------------------------
    private $nodes;
    private $numChildren;
    private $caption;
    private $id;
    private $isDebug;
    private $postBackMethod;
    private $style;
    private $path;
    private $width;
    private $height;
    private $httpVars;
    private $useDefaultFolderIcons;
    private $useDefaultFileIcons;
    private $nodesWithInnerHTML;
    private $showNumSubNodes=false;
    private $secondaryPath;
    private $refreshSelectedNodesAllowed=true;
    private $anchor="treemenu";
    private $useAnchor=false;
    private $createAnchorAuto=true;
    private $direction="ltr";


    //--- CONSTANTS -------------------------------------------------------------
    const version="2.1.2";
    const defaultEmptyMessage="No nodes defined";

    //--- PRIVATE STATIC DATA MEMBERS -------------------------------------------
    private static $bad_chars="><|?*:,\"";

    /**
    *	Creates a tree menu
    */
    public function __construct()
    {    	$this->nodes=array();
    	$this->numChildren=0;
    	$this->caption="";
    	$this->id=1;
    	$this->isDebug=false;
    	$this->postBackMethod="ajax";
    	$this->style="default";
    	if(defined("TREEMENU_DIR")) $this->path = TREEMENU_DIR;
        else $this->path = "";
        $this->secondaryPath=$this->path;
    	$this->width="auto";
    	$this->height="auto";
    	$this->httpVars=array();
    	$this->nodesWithInnerHTML=array();
    }

    /**
	*	Adds a new child node to this menu (calculates new node's id and calls function AddNodeAction)
	      @param $caption - node's caption
	      @param $file - file associated with this node
	      @param $icon - icon associated with this node
	      @param $isFolder - is true when this node corresponds to a folder
	*/
    public function AddNode($caption,$file="",$icon="undefined",$isFolder=false)
    {
         $id=$this->GetId()."_".++$this->numChildren;

         return $this->AddNodeAction($caption,$id,$file,$icon,$isFolder);
    }

    /**
	*   Adds a node to list of nodes with inner HTML content
	     @param $node
	*/
    public function AddNodeWithInnerHTML($node)
    {
    	if($node->HasInnerHTML())
       	   $this->nodesWithInnerHTML[count($this->nodesWithInnerHTML)]=$node;
    }

    /**
	*	Adds a new child node to this menu
	      @param $caption - node's caption
	      @param $id - node's id
	      @param $file - file associated with this node
	      @param $icon - icon associated with this node
	      @param $isFolder - is true when this node corresponds to a folder
	*/
    public function AddNodeAction($caption,$id,$file,$icon,$isFolder)
    {
       if(preg_match("/^[a-z|A-Z|0-9|_]+$/",$id)==0)
          return;
       if($pos=strrpos($file,"?"))
          $filename=substr($file,0,$pos);
       else $filename=$file;
       if(strpbrk($filename,self::$bad_chars))
          $file="";
       if(strpbrk($icon,self::$bad_chars))
          $icon="";
       $this->nodes[$id]=new Node($caption,$id,$file,$this,$icon,$isFolder);
       return $this->nodes[$id];
    }

    /**
	*	Shows tree structure on the screen
	    (is used when this is an independent menu)
	*/
    public function ShowTree()
    {
       $output = "";
       if($this->useAnchor && $this->createAnchorAuto)
            $output.= "<a name='".$this->anchor."'></a>";
       if($this->caption!="")
            echo "<span id='caption' class='tmCaption'>".$this->caption."</span><br /><br />";
       if($this->numChildren==0)
       {
            echo self::defaultEmptyMessage;
            return;
       }
       if($this->isDebug)
          $startTime = self::GetFormattedMicrotime();
       $output.= $this->LoadFiles();
       if($this->postBackMethod!="ajax")
       {
          $params="";
          if($this->postBackMethod=="post")
          {
             foreach($this->httpVars as $key)
                if(self::GetParam($key, "get")!= "")
                   $params .= "&amp;".$key."=".self::GetParam($key, "get");
             if($params!="")
             {
                $params = "?".substr($params, 5);
             }
          }
          //if(self::CheckInput($_SERVER["SCRIPT_NAME"]))
          //{
              if($this->useAnchor) $action=$_SERVER["SCRIPT_NAME"].$params."#".$this->anchor;
              else $action=$_SERVER["SCRIPT_NAME"].$params;
          //}
          $output.= "\n<form name='frmnodes".$this->GetId()."' id='frmnodes".$this->GetId()."' action='".$action."' method='".$this->postBackMethod."'>";
       }
       $output.= "<div class='nodes'>";
       if($this->postBackMethod!="ajax")
       {
          if($this->postBackMethod=="get")
             foreach($this->httpVars as $key)
                if(self::GetParam($key, "get")!= "")
                   $output.= "<input type='hidden' id='".$key."' name='".$key."' value='".self::GetParam($key, "get")."' />";
       }
       else
       {
       	  $output.= "<input type='hidden' id='debug".$this->GetId()."' value='".$this->GetDebug()."' />";
       	  $output.= "<input type='hidden' id='folderIcons".$this->GetId()."' value='".$this->useDefaultFolderIcons."' />";
       	  $output.= "<input type='hidden' id='fileIcons".$this->GetId()."' value='".$this->useDefaultFileIcons."' />";
       	  $output.= "<input type='hidden' id='showNumFiles".$this->GetId()."' value='".$this->showNumSubNodes."' />";
       }
       $output.= "<input type='hidden' id='path".$this->GetId()."' value='".$this->GetPath()."' />";
       $output.= "<input type='hidden' id='refresh".$this->GetId()."' value='".$this->refreshSelectedNodesAllowed."' />";
       $output.= "<input type='hidden' id='style".$this->GetId()."' name='style' value='".$this->GetStyle()."' />";
       $output.= "<input type='hidden' id='direction".$this->GetId()."' name='direction' value='".$this->direction."' />";
       if(self::GetParam("nodeid".$this->GetId(),"request") != "")
          $selectedNodeId = self::GetParam("nodeid".$this->GetId(),"request");
       else $selectedNodeId = "";
       $paneled = ($this->style == "paneled");
       $output.= "<input type='hidden' id='nodeid".$this->GetId()."' name='nodeid".$this->GetId()."' value='".$selectedNodeId."' />";
       if(!$paneled)
          $output.= "<ul class='tmTree'>";
       else $output.= "<table cellpadding='0' cellspacing='0' style='width:100%;'>";
       for($i=1;$i<=$this->numChildren;$i++)
       {
          if($paneled)
             $output.= "<tr class='tmRow'><td><ul class='tmTree'>";
          $output.= $this->ShowNode($this->id."_".$i,$i==$this->numChildren,true,false);
          if($paneled)
             $output.= "</ul></td></tr>";
       }
       if(!$paneled)
          $output.= "</ul>";
       else $output.= "</table>";

       $output.= "</div>";
       if($this->postBackMethod != "ajax")
          $output.= "</form>";

       $output.= "\n<!-- END This script was generated by treemenu.class.php v.".self::version."(http://www.apphp.com/php-tree-menu/index.php) END -->\n";
       if($this->isDebug)
       		$output.= $this->ShowDebugInformation($startTime);
       echo $output;    }

    /**
    *   Shows tree structure on the screen
        (is used when this menu is a part of some bigger menu)
    */
    public function ShowNodes()
    {
       $output = "";
       $output.= "\n<ul class='tmSubTree'>";
       for($i=1;$i<=$this->numChildren;$i++)
       {
          $output.= $this->ShowNode($this->id."_".$i,$i==$this->numChildren,false);
       }
       $output.= "\n</ul>\n";
       return $output;   	}

    /**
	*	Shows the loaded content
	*/
    public function ShowContent()
    {
       $output = "";
       $output.= "\n<div class='tmContainer' id='container".$this->GetId()."'>";
       if(self::GetParam("nodeid".$this->GetId(),"request") != "")
       {
          $selectedNodeId = self::GetParam("nodeid".$this->GetId(),"request");
          if(isset($this->nodes[$selectedNodeId]))
             $selectedNode = $this->nodes[$selectedNodeId];
          else $selectedNode = null;
       }
       else $selectedNode = null;

       foreach($this->nodesWithInnerHTML as $nodeWithInnerHTML)
       {
           if($selectedNode!=null && $nodeWithInnerHTML==$selectedNode)
              $output.= "\n<div id='code".$nodeWithInnerHTML->GetId()."'>".$nodeWithInnerHTML->GetInnerHTML()."</div>";
           else// if($this->postBackMethod=="ajax")
              $output.= "\n<div id='code".$nodeWithInnerHTML->GetId()."' style='display:none'>".$nodeWithInnerHTML->GetInnerHTML()."</div>";
       }
       $output.= "<div id='innercontainer".$this->GetId()."'>";

       if($this->postBackMethod!="ajax" && $selectedNode !=null)
          $output.= $selectedNode->ShowContent($this->GetDebug());
       $output.= "</div>";
       $output.= "\n</div>";
       echo $output;
    }

    /**
    *   Shows plus or minus sign to the left of the text
	       @param $symbol - symbol to display (plus or minus)
	       @param $node - the symbol's node
	       @param $isIndependent - is true when this is a stand-alone menu
    */
    private function ShowPlusMinus($symbol,$node,$isIndependent)
    {
        $output = "";
        $output.= "<img id='imge".$node->GetId()."' class='tmImage' alt='' name='tmImage'";
        if(!$isIndependent)
           $output.= " onmouseover='__tmHighlight(this)' onmouseout='__tmLowlight(this)' ";
        if($this->direction == "rtl")
	        $output.= " src='".$this->secondaryPath."styles/".$this->style."/images-right/".$symbol.".gif'";
        else $output.= " src='".$this->secondaryPath."styles/".$this->style."/images/".$symbol.".gif'";
        	if($node->GetFolder()!="")
        	{
        		//if(!self::CheckInput($_SERVER["SCRIPT_NAME"])) $pathToFile = "";
        		/*else*/ $pathToFile=substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
           		$output.= " onclick=\"__tmSwitch('".$node->GetId()."','".self::SimplifyPath($pathToFile.$node->GetFolder())."')\" />";
           	}
           	else $output.= " onclick=\"__tmSwitch('".$node->GetId()."','')\" />";
        return $output;    }

    /**
    *   Shows node's icon
	       @param $node
    */
    private function ShowIcon($node)
    {    	$output = "";
    	if($node->GetIcon()!="undefined" && $node->GetIcon()!="")
        {
            $output.= "<img id='pic".$node->GetId()."' class='tmIcon' src='".$node->GetIcon()."' alt='' />";
        }
        return $output;    }

    /**
	*	Shows a node
	      @param $id - node's id
	      @param $last - whether the node is the last one on this level
	      @param $isIndependent - is true when this is an independent menu
	      @param $isStandAlone - is true when this is a stand-alone menu
	*/
    private function ShowNode($id,$last,$isIndependent=true,$isStandAlone=false)
    {
        $output = "";
        $node=$this->nodes[$id];
        if($this->useDefaultFolderIcons && $node->IsFolder() && ($node->GetIcon()==""||$node->GetIcon()=="undefined"))
           $node->ChooseIcon();
        if(!$node->IsFolder() && $node->GetIcon()=="" && $this->useDefaultFileIcons)
           $node->ChooseIcon();
        // defining if this node is selected
        if(self::GetParam("nodeid".$this->GetId(),"request") == $node->GetId())
        {
           if($this->refreshSelectedNodesAllowed)
               $class = "tmSelected";
           else $class = "tmSelectedPass";
        }
        else $class = "tmRegular";
        if(!$isStandAlone)
        {
        	if ($last)
        		$output.= "<li class='tmLast'>";
	        else if($node->GetId()==$this->id."_1" && $isIndependent)
    	    	$output.= "<li class='tmFirst'>";
        	else
        		$output.= "<li class='tmSingle'>";
        }
       //if(!self::CheckInput($_SERVER["SCRIPT_NAME"])) $pathToFile = "";
       /*else*/ $pathToFile=substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
       $symbol = "";
        // if this node has subnodes
        if($node->getNumChildren()>0 || $node->Getfolder()!="")
        {
        	// if this node is expanded
        	if(self::GetParam("node".$node->GetId(), "request") == 'e' || $class=="tmSelected" || $class=="tmSelectedPass")
        	{                $liclass = "tmExpanded";
                if($this->style == "paneled" && $node->GetLevel() == 1)
                    $symbol = "minus-first";
                else $symbol = "minus";
                $value = "e";
            }
            // if this node is collapsed
            else
            {                $liclass="tmCollapsed";
                if($this->style=="paneled" && $node->GetLevel() == 1)
                    $symbol="plus-first";
                else $symbol="plus";
                $value="c";            }
        	$output.= "<div class='".$liclass."' id='tm".$node->GetId()."'>";
        	// displaying plus or minus sign to the left of the text
            if($this->direction != "rtl") $output.= $this->ShowPlusMinus($symbol,$node,$isIndependent);


        }
        // if node has no subnodes
        else
            $output.= "<div class='tmNoChildren' id='tm".$node->GetId()."'>";


        $output.= "<span id='span".$node->GetId()."' class='tmNode' ";
        if($node->GetTooltip()!="")
           $output.= " title='".$node->GetTooltip()."' ";
        if(!$isIndependent)
           $output.= "onmouseover='__tmHighlight(this)' onmouseout='__tmLowlight(this)'";

        if($node->HasInnerHTML())
        {
	        $output.= "onclick=\"__tmPostBackAjax('".$node->GetId()."','".$node->GetFile()."','code')\">";
        }
        else if($node->IsOpenNewWindow())
        {
            if($this->postBackMethod == "ajax")
	            $output.= " onclick=\"document.location.href='".$node->GetFile()."'\">";
            else $output.= " onclick=\"__tmPostBackNewWindow('".$node->GetId()."','".$this->id."','".$node->GetFile()."')\">";
        }
        else if($this->postBackMethod == "ajax")
        {
           if($node->GetFolder()=="")
           {
              if($node->IsPicture())
                 $output.= "onclick=\"__tmPostBackAjax('".$node->GetId()."','".$node->GetFile()."','file')\">";
              else $output.= "onclick=\"__tmPostBackAjax('".$node->GetId()."','".self::SimplifyPath($pathToFile.$node->GetFile())."','file')\">";
           }
           else $output.= "onclick=\"__tmPostBackAjax('".$node->GetId()."','".self::SimplifyPath($pathToFile.$node->GetFolder())."','filesystem')\">";
        }
        else $output.= "onclick=\"__tmPostBack('".$node->GetId()."')\">";

        //displaying this node's icon
        if ($this->direction != "rtl") $output.= $this->ShowIcon($node);
        // displaying this node's text
        $output.= "<span id='text".$node->GetId()."' class='".$class."'>".$node->GetCaption();
        if($this->showNumSubNodes && $node->GetNumChildren()>0)
        	$output.= "&nbsp(".$node->GetNumChildren().")";
        $output.= "</span>";
        if ($this->direction == "rtl") $output.= $this->ShowIcon($node);
        $output.= "</span>";
        if($node->getNumChildren()>0 || $node->Getfolder()!="")
        {
           if($this->direction == "rtl")
	           $output.= $this->ShowPlusMinus($symbol,$node,$isIndependent);
           // hidden field which stores this node's state ('c' for collapsed and 'e' for expanded)
        	$output.= "<input type='hidden' id='node".$node->GetId()."' name='node".$node->GetId()."' value='".$value."' />";
        }
        //displaying this node's subnodes if there are any
        if($node->getNumChildren()>0)
        {
        	$output.= "<br />";
        	if($last)
        	   $output.= "<ul class='tmSubTree-last'>";
         	else $output.= "<ul class='tmSubTree'>";
        	for($i=1;$i<=$node->getNumChildren();$i++)
               $output.= $this->ShowNode($node->GetId()."_".$i,$i==$node->getNumChildren());
            $output.= "</ul>";
        }
           $output.= "</div>";
           if(!$isStandAlone)
        $output.= "</li>";

        return $output;    }

    /**
	*	Builds a tree menu from contents of a folder
	       @param $folder - relative path to the folder
	       @param $isIndependent - is true when this is an independent menu
	*/
    public function BuildFromFolder($folder,$isIndependent=true)
	{
		$this->BuildNodeFromFolder($this,$folder,$isIndependent);
    }

    /**
	*	Builds a tree menu from contents of a folder
	      @param $root - the node
	      @param $folder - relative path to the folder
	      @param $isIndependent - is true when this is an independent menu
	*/
    public function BuildNodeFromFolder($root,$folder,$isIndependent)
	{
        $folder=self::SimplifyPath($folder);
        $dirs=array();
        $files=array();
        if (is_dir($folder)) {
           if ($dir = opendir($folder)) {
           	   while (false !== ($file = readdir($dir))){
           	   	  if($file != "." && $file != ".." && is_dir($folder."/".$file))
           	   	     $dirs[count($dirs)]=$file;
           	   	  else if($file != "." && $file != "..")
           	   	     $files[count($files)]=$file;
               }
               closedir($dir);
    	    }
        }
        natcasesort($dirs);
        natcasesort($files);

        foreach($dirs as $dir)
        {
            $node=$root->AddNode($dir,"","",true);
            if($this->postBackMethod!="ajax")
               $this->BuildNodeFromFolder($node,$folder."/".$dir,$isIndependent);
            else $node->SetFolder($folder."/".$dir);
        }
        foreach($files as $file)
        {
        	$node=$root->AddNode($file,$folder."/".$file,"",false);
            if($node->IsPicture()&&!$isIndependent)
            	$node->SetFile($this->secondaryPath."inc/".$node->GetFile());
        }
    }

    /**
	*	Loads CSS and JS files
	*/
	private function LoadFiles()
	{		$output = "";
	    if(!file_exists($this->path."styles/".$this->style."/style.css")) $this->style="default";
        if($this->direction == "rtl")
        {
	    	$output.= "<link href='".$this->path."styles/".$this->style."/style-right.css' rel='stylesheet' type='text/css' />\n";
	        $output.= "<link href='".$this->path."styles/common-right.css' rel='stylesheet' type='text/css' />\n";
	   	}
	   	else
	   	{	   		$output.= "<link href='".$this->path."styles/".$this->style."/style.css' rel='stylesheet' type='text/css' />\n";
	   		$output.= "<link href='".$this->path."styles/common.css' rel='stylesheet' type='text/css' />\n";
	    }
       	if(file_exists($this->path."js/script.js"))
       		$output.= "<script type='text/javascript' defer='defer' src='".$this->path."js/script.js'></script>\n";
       	$output.= "\n<!--[if IE]>";
       	$output.= "<link href='".$this->path."styles/commonIE.css' rel='stylesheet' type='text/css' />";
       	if (file_exists($this->path."styles/".$this->style."/styleIE.css"))
        	$output.= "<link href='".$this->path."styles/".$this->style."/styleIE.css' rel='stylesheet' type='text/css' />\n";
       	$output.= "<![endif]-->\n";
       	$output.= "<script src='".$this->path."js/jquery-1.4.2.min.js' type='text/javascript'></script>";
       	return $output;
    }

    /**
    *	Sets variables that used to get access to the page (like: my_page.php?act=34&id=56 etc.)
           @param $vars
	*/
    public function SetHttpVars($vars)
    {
    	$this->httpVars = $vars;
    }

    /**
	*	Sets whether or not default icons are used for folder nodes
			@param $use - false|true
	*/
	public function UseDefaultFolderIcons($use = false)
	{
		if($use === true || strtolower($use) == "true") $this->useDefaultFolderIcons = true;
		if($use === false || strtolower($use) == "false") $this->useDefaultFolderIcons = false;
	}

    /**
	*	Sets whether or not default icons are used for file nodes
			@param $use - false|true
	*/
	public function UseDefaultFileIcons($use = false)
	{
		if($use === true || strtolower($use) == "true") $this->useDefaultFileIcons = true;
		if($use === false || strtolower($use) == "false") $this->useDefaultFileIcons = false;
	}

    /**
	*	Sets if number of sub-nodes is displayed in brackets to the left of every node
			@param $show - false|true
	*/
	public function ShowNumSubNodes($show = false)
	{
		if($show === true || strtolower($show) == "true") $this->showNumSubNodes = true;
	}

    /**
    *	Returns if refreshing selected Nodes is enabled
    */
    public function IsRefreshSelectedNodesAllowed()
    {
        return $this->refreshSelectedNodesAllowed;
    }

    /**
    *	Allows or prohibits to refresh selected Nodes
    *		@param $allow - true or false
    */
    public function AllowRefreshSelectedNodes($allow = false)
    {
        if($allow === true || strtolower($allow) == "true") $this->refreshSelectedNodesAllowed = true;
        if($allow === false || strtolower($allow) == "false") $this->refreshSelectedNodesAllowed = false;
    }
    
    /**
	*	Sets postback method
	*		@param $postback_method
	*/
	public function SetPostBackMethod($postback_method = "post")
	{
		if(strtolower($postback_method) == "get") $this->postBackMethod = "get";
		else if(strtolower($postback_method) == "ajax") $this->postBackMethod = "ajax";
		else $this->postBackMethod = "post";
	}

    /*
    * Sets menu's unique identifier
    *      @param $id
    */
    public function SetId($id)
    {
        $this->id=$id;
    }

    /**
	*	Returns menu's unique identifier
	*/
    public function GetId()
    {
       return $this->id;
    }

    /**
	*	Sets menu's caption
	*     @param $caption - new caption
	*/
    public function SetCaption($caption)
    {
    	$this->caption=$caption;
    }

    /**
	*	Returns menu's path
	*/
    public function GetPath()
    {
    	return $this->path;
    }

    /**
	*	Sets style
	*     @param $style - new style
	*/
    public function SetStyle($style)
    {
    	//if(file_exists($this->path."styles/".$style."/style.css"))
        $this->style=$style;
    }

    /**
	*	Returns menu's style
	*/
    public function GetStyle()
    {
    	return $this->style;
    }

    /**
	*	Returns menu's style
	*/
    public function GetSecondaryPath()
    {
    	return $this->secondaryPath;
    }

    /**
	*	Sets menu's secondary path
	*/
    public function SetSecondaryPath($secondaryPath)
    {
    	$this->secondaryPath=$secondaryPath;
    }

    /**
	*	Returns number of nodes on the first level
	*/
	public function GetNumChildren()
    {
       return $this->numChildren;
    }

    /**
    *	Sets debug mode
    *		@param $mode
	*/
	public function Debug($mode = false)
	{
		if($mode === true || strtolower($mode) == "true") $this->isDebug = true;
	}

    /**
    *	Gets debug mode
	*/
	public function GetDebug()
	{
		return $this->isDebug;
	}
    
    /**
	*	Returns TreeMenu's version
	*/
    public function Version()
    {
       return self::version;
    }

    /**
    *	Gets formatted microtime
	*/
    public static function GetFormattedMicrotime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
	*	Shows debug information
	*      @param $startTime - time when the script started
	*/
    public function ShowDebugInformation($startTime)
    {
       $output = "";
       $endTime = self::GetFormattedMicrotime();
	   $output.= "<div id='tmDebug' style='margin: 10px auto; text-align:left; color:#000096;'>";

	   $output.= "Debug Info: (Total running time: <span id='tmTime".$this->id."'>".round((float)$endTime - (float)$startTime, 6)."</span> sec.) <br />========<br />";
	   $output.= "<br />GET: <br />--------<br />";
	   $output.= "<pre>";
	   $output.= print_r($_GET, true);
	   $output.= "</pre><br />";
	   $output.= "POST: <br />--------<br />";
	   $output.= "<pre>";
	   $output.= print_r($_POST, true);
	   $output.= "</pre><br />";

	   $output.= "NODES: <br />--------<br />";
	   $output.= "<pre><table style='color:#000096;'>";
       foreach($this->nodes as $node)
       {
          $output.= "<tr>";
          $output.= "<td>".$node->GetId()."</td>";
          $output.= "<td>".$node->GetCaption()."</td>";
          $output.= "</tr>";
       }
       $output.= "</table></pre>";
       $output.= "</div>";
       return $output;    }

    /**
	*	Simplifies dirs like /./dir ordir/../../dir/ and returns a simplified pathname
	*    	@param $path - path to be simplified
	*/
    public static function SimplifyPath($path)
    {
       if(strlen($path)>0 && $path[0]=="/")
          return "/".self::SimplifyPath(substr($path,1));
       if($path=="")
          return $path;
       $dirs = explode('/',$path);
       for($i=0; $i<count($dirs);$i++)
       {
          if($dirs[$i]=="." || $dirs[$i]=="")
          {
             array_splice($dirs,$i,1);
             $i--;
          }
          if($dirs[$i]=="..")
          {
            $c = count($dirs);
            $dirs=self::Simplify($dirs, $i);
            $i-= $c-count($dirs);
          }
       }
       return implode('/',$dirs);
    }
    
    /**
	*	Sets menu's direction (left-to-right or right-to-left)
	*		@param $dir
	*/
    public function SetDirection($dir = "ltr")
    {
    	$this->direction = (strtolower($dir) == "rtl") ? "rtl" : "ltr";
    }
    
    /**
	*	Auxiliary function
	*       @param $dirs
	*       @param $idx
	*/
    private static function Simplify($dirs, $idx)
    {
       if($idx==0) return $dirs;
       if($dirs[$idx-1]=="..") self::Simplify($dirs, $idx-1);
       else  array_splice($dirs,$idx-1,2);
       return $dirs;
    }

    /**
	*	Checks input string for suspicious code
	*       @param $input - input string
	*	   @param $level - security level
	*/
    private static function CheckInput($input, $level = "medium")
    {
    	if($input == "") return true;
    	$error = 0;
    	$bad_string = array("%20union%20", " /*", "*/ union /*", "+union+", "load_file", "outfile", "document.cookie", "onmouse", "<script", "<iframe", "<applet", "<meta", "<style", "<form", "<img", "<body", "<link", "_GLOBALS", "_REQUEST", "_GET", "_POST", "include_path", "prefix", "http://", "https://", "ftp://", "smb://" );
    	foreach($bad_string as $string_value)
    	{
    		if(strstr($input, $string_value)) $error = 1;
    	}
    	if((preg_match("/<[^>]*script*\"?[^>]*>/i", $input)) ||
        (preg_match("/<[^>]*object*\"?[^>]*>/i", $input)) ||
        (preg_match("/<[^>]*iframe*\"?[^>]*>/i", $input)) ||
        (preg_match("/<[^>]*applet*\"?[^>]*>/i", $input)) ||
        (preg_match("/<[^>]*meta*\"?[^>]*>/i", $input)) ||
        (preg_match("/<[^>]*style*\"?[^>]*>/i", $input)) ||
        (preg_match("/<[^>]*form*\"?[^>]*>/i", $input)) ||
        (preg_match("/<[^>]*img*\"?[^>]*>/i", $input)) ||
        (preg_match("/<[^>]*onmouseover*\"?[^>]*>/i", $input)) ||
        (preg_match("/<[^>]*body*\"?[^>]*>/i", $input)) ||
        (preg_match("/\([^>]*\"?[^)]*\)/i", $input)) ||
        (preg_match("/ftp:\/\//i", $input)) ||
        (preg_match("/https:\/\//i", $input)) ||
        (preg_match("/http:\/\//i", $input)) )
        {
        	$error = 1;
        }
        $ss = $_SERVER['HTTP_USER_AGENT'];
        if ((preg_match("/libwww/i",$ss)) ||
        (preg_match("/^lwp/i",$ss))  ||
        (preg_match("/^Jigsaw/i",$ss)) ||
        (preg_match("/^Wget/i",$ss)) ||
        (preg_match("/^Indy\ Library/i",$ss)) )
    	{
        	$error = 1;
    	}
    	if($error){
    		return false;
   		}
    	return true;
	}
	
    /**
    *	Gets a GET- or POST- parameter
    *      @param $key - parameter's key
    *	    @param $postbackMethod - postback method
    */
    public static function GetParam($key, $postbackMethod)
    {
        if(strtolower($postbackMethod) == "get")
            return isset($_GET[$key]) && self::CheckInput($_GET[$key]) ? $_GET[$key] : "";
        if(strtolower($postbackMethod) == "post")
            return isset($_POST[$key]) && self::CheckInput($_POST[$key]) ? $_POST[$key] : "";
        return isset($_REQUEST[$key]) && self::CheckInput($_REQUEST[$key]) ? $_REQUEST[$key] : "";
    }
    
    /**
    *	Use an anchor to navigate to treemenu object after a node is clicked
    *		@param $use - true or false
    */
    public function UseAnchor($use = false)
    {
        if($use === true || strtolower($use) == "true") $this->useAnchor = true;
        if($use === false || strtolower($use) == "false") $this->useAnchor = false;
    }

    /**
    *	Create the anchor automatically
    *		@param $create - true or false
    */
    public function CreateAnchorAuto($create = false)
    {
        if($create === true || strtolower($create) == "true") $this->createAnchorAuto = true;
        if($create === false || strtolower($create) == "false") $this->createAnchorAuto = false;
    }
}

/**
*	Class Node
*      represents a separate node
*      last date modified: 10.07.2011
*
*/
class Node
{
    // PUBLIC
    // -------
    // constructor
    // AddNode
    // BuildFromFolder
    // ShowContent
    // GetCaption
    // GetLevel
    // GetFile
    // SetFile
    // GetIcon
    // SetIcon
    // GetTooltip
    // SetTooltip
    // GetId
    // GetNumChildren
    // IsPicture
    // IsHTML
    // IsPHP
    // IsText
    // ChooseIcon
    // SetFolder
    // GetFolder
    // SetInnerHTML
    // HasInnerHTML
    // GetInnerHTML
    // OpenNewWindow
    // IsOpenNewWindow

    //--- PRIVATE DATA MEMBERS --------------------------------------------------
    private $caption;
    private $selected=false;
    private $id;
    private $parent;
    private $numChildren=0;
    private $file;
    private $icon;
    private $level;
    private $tooltip;
    private $innerHTML;
    private $openNewWindow;
    private $folder;
    public $isFolder=false;

    //--- PRIVATE STATIC DATA MEMBERS -------------------------------------------
    private static $bad_chars="><|?*:,\"";

    /**
    *	Creates a new node
	*        @param $caption - node's caption
    *        @param $id - node's id
    *        @param $file - name of the file associated with this node
    *        @param $parent - menu which contains this node
    *        @param $icon - icon associated with this node
    *       @param $isFolder - is true when this node corresponds to a folder
	*/
    function __construct($caption,$id,$file,$parent,$icon,$isFolder=false)
	{
    	if(preg_match("/^[a-z|A-Z|0-9|_]/",$id)==0)
        	$id=0;
        $this->id=$id;
        $this->caption=$caption;
        $this->parent=$parent;

        $this->level=substr_count($id,"_");
        if($pos=strrpos($file,"?"))
           $filename=substr($file,0,$pos);
        else $filename=$file;
        if(strpbrk($filename,self::$bad_chars))
           $this->file="";
        else $this->file=$file;
        $this->isFolder=$isFolder;
        if(strpbrk($icon,self::$bad_chars))
           $this->icon="";
        else $this->icon=$icon;
	}

    /**
	*	Adds a new child node to this node
	*      @param $caption - node's caption
	*      @param $file - file associated with this node
	*      @param $icon - icon associated with this node
	*      @param $isFolder - is true when this node corresponds to a folder
	*/
    public function AddNode($caption,$file="",$icon="undefined",$isFolder=false)
    {

        if(!is_a($this->parent,"TreeMenu"))
        {
           echo "<font color='#FF0000'>Error: node ".$this->caption." has no valid parent object</font>";
           return;
        }
        $id=$this->GetId()."_".++$this->numChildren;
        return $this->parent->AddNodeAction($caption,$id,$file,$icon,$isFolder);
    }


    /**
	*	Builds a tree menu from contents of a folder
	*      @param $folder - relative path to the folder
	*/
    public function BuildFromFolder($folder)
    {    	if(!is_a($this->parent,"TreeMenu"))
        {
           echo "<font color='#FF0000'>Error: node ".$this->caption." has no valid parent object</font>";
           return;
        }
        $this->parent->BuildNodeFromFolder($this,$folder,true);    }

    /**
	*	Shows contents of the file which is associated with this node
	*/
    public function ShowContent($debug)
    {
        $output = "<br />\n\n";
        if($pos=strrpos($this->file,"?"))
        {
            $get_parameters=substr($this->file,$pos+1);
            $this->file=substr($this->file,0,$pos);
            $get_parameters_array = explode("&", $get_parameters);
            foreach($get_parameters_array as $get_parameter)
            {
            	$key_value=explode("=",$get_parameter);
            	$_GET[$key_value[0]]=$key_value[1];
            }
        }
        if($this->IsPicture())
        {
       	    $output.= "<img id='tmTree_image' src='".$this->file."' alt='' />";
   	    }
    	else
    	{
    	    if($this->file=="")
         	{
         		if(!$this->IsFolder())
         			$output.= "No content associated with this node";
         	}
    	    else if(!file_exists($this->file)){    	    	if($debug)
	                $output.= "<div style='color:#000096;'>File '".$this->file."' not found</div>";
            }
            else if($this->IsPHP()){
                flush();
                ob_start();
                include_once($this->file);
                $contents = ob_get_clean();
                $output.= $contents;
            }
            else if($this->IsHTML()){
                $str = file_get_contents($this->file);
                if(preg_match("/<head.*?>(.+?)<\/head>/si",$str,$head) != 0){
                    if(preg_match_all("/<script.*?>(.*?)<\/script>/si",$head[1],$scripts) != 0)
                        foreach($scripts[0] as $script){
                            $output .= $script;
                        }
                    if(preg_match_all("/<style.*?>(.*?)<\/style>/si",$head[1],$styles) != 0){
                        foreach($styles[0] as $style){
                            $output .= $style;
                        }
                    }
                }
                if(preg_match("/<body.*?>(.+?)<\/body>/si",$str,$body) != 0){
                    $output.= print_r($body[1], true);
                }else{
                    $output.= print_r($str, true);
                }
            }
            else if($this->IsText()){
                $output.= htmlspecialchars(file_get_contents($this->file), ENT_QUOTES);
            }        }
        return $output;
    }

    /**
    *	Returns the node's caption
    */
    public function GetCaption()
    {
    	 return $this->caption;
    }

    /**
    *	Returns the node's level
	*/
    public function GetLevel()
    {
    	return $this->level;
    }

    /**
	*	Returns the node's file
	*/
    public function GetFile()
    {
    	return $this->file;
    }

    /**
	*	Sets the node's file
	*/
    public function SetFile($file)
    {
        $this->file=$file;    }

    /**
	*	Returns the node's icon
	*/
    public function GetIcon()
    {
       return $this->icon;
    }

    /**
    *	Sets node's icon
	*      @param $icon
	*/
    public function SetIcon($icon)
    {
		$this->icon=$icon;
    }

    /**
    *	Returns the node's tooltip
    */
    public function GetTooltip()
    {
        return $this->tooltip;
    }

    /**
    *	Sets node's tooltip
    *       @param $tooltip
    */
    public function SetTooltip($tooltip)
    {
        $this->tooltip= htmlspecialchars($tooltip, ENT_QUOTES);
    }

    /**
	*	Returns the node's id
	*/
    public function GetId()
    {
		return $this->id;
    }

    /**
	*	Returns amount of subnodes associated with this node
	*/
    public function GetNumChildren()
    {
        return $this->numChildren;
    }

    /**
    *	Checks if file associated with this node is a graphic file
	*/
 	public function IsPicture()
  	{
        $extension=strtolower(substr(strrchr($this->file,"."),1));
        if($extension=="jpg"||$extension=="gif"||$extension=="bmp"||$extension=="tif"||$extension=="png"||$extension=="jpeg")
           return true;
        else return false;
    }

    /**
	*	Checks if file associated with this node is a hypertext file
	*/
    public function IsHTML()
    {
        if($pos=strrpos($this->file,"?"))
           $filename=substr($this->file,0,$pos);
        else $filename=$this->file;
        $extension=strtolower(substr(strrchr($filename,"."),1));
        if($extension=="htm"||$extension=="xml"||$extension=="html")
           return true;
        else return false;
    }

    /**
	*	Checks if file associated with this node is a PHP file
	*/
    public function IsPHP()
    {
        if($pos=strrpos($this->file,"?"))
           $filename=substr($this->file,0,$pos);
        else $filename=$this->file;
        $extension=strtolower(substr(strrchr($filename,"."),1));
        if($extension=="php")
           return true;
        else return false;
    }

    /**
	*	Checks if file associated with this node is a text file
	*/
    public function IsText()
    {
        $extension=strtolower(substr(strrchr($this->file,"."),1));
        if($extension=="txt")
           return true;
        else return false;
    }

    /**
    * Chooses icon according to file's type if it wasn't defined by user
    */
    public function ChooseIcon()
    {
    	$this->icon=$this->parent->GetSecondaryPath()."styles/".$this->parent->GetStyle()."/images/";
        if($this->IsFolder())
        {
            if(TreeMenu::GetParam("node".$this->GetId(),"request") == 'e')
               $this->icon = $this->icon."folderopened";
            else $this->icon = $this->icon."folder";
        }
        else if($this->IsPicture())
           $this->icon = $this->icon."picture";
        else if($this->IsText())
           $this->icon = $this->icon."text";
        else if($this->IsHTML())
           $this->icon = $this->icon."html";
        else $this->icon=$this->icon."file";
        if($this->parent->GetStyle() == "default" || ($this->parent->GetStyle() == "paneled" && $this->IsFolder()))
           $this->icon = $this->icon.".gif";
        else $this->icon = $this->icon.".jpg";
    }

    /**
	*	Sets folder which this node corresponds to
	*   	@param $folder
	*/
    public function SetFolder($folder)
    {    	$this->folder = $folder;    }

    /**
	*	Returns folder which this node corresponds to
	*/
    public function GetFolder()
    {
        return $this->folder;    }

    /**
	*	Sets this node's inner HTML content
	*      @param $innerHTML
	*
	*/
    public function SetInnerHTML($innerHTML)
    {
    	$this->innerHTML=$innerHTML;
    	if($innerHTML!="")
    	   $this->parent->AddNodeWithInnerHTML($this);
    }

    /**
	*	Returns true if this node has inner HTML content (false otherwise)
	*/
    public function HasInnerHTML()
    {
       return $this->innerHTML!="";
    }

    /**
	*	Gets this node's inner HTML content
	*/
    public function GetInnerHTML()
    {
       return $this->innerHTML;
    }

    /**
	*	Sets if this node must be opened in a new window
	*       @param $open
	*/
    public function OpenNewWindow($open = false)
	{
		if($open === true || strtolower($open) == "true")
		   $this->openNewWindow = true;
	}

    /**
	*	Returns if this node must be opened in a new window
	*/
    public function IsOpenNewWindow()
    {
        return $this->openNewWindow;
    }

    /**
    *  Returns true if this node corresponds to a folder or has sub-nodes
    */
    public function IsFolder()
    {       return $this->numChildren > 0 || $this->isFolder;    }
}
?>