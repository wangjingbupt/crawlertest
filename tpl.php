<?php
error_reporting(0);
ini_set('display_errors',0);
define('PATH','/home/ganji/template_test/css_selector');

$filePath = urldecode($_GET['filePath']);


//$filePath = str_replace('/home/ganji','../..',$filePath);
//$filePath = '/home/ganji/template_test/css_selector/baixing.tar.bz2';
//$fp = fopen($filePath)
if(file_exists($filePath))
{
  $html = head();
  if($_GET['edit'] == 1)
  {
    $file = file_get_contents($filePath);
    $innerHtml = htmlentities($file,ENT_NOQUOTES,'UTF-8');
    $html .=<<<HTML
      <div style='margin-top:10px;'>
<script>
function BodyOnLoad() 
{ 
  var textarea= document.getElementById("tpl"); 
  textarea.style.height=textarea.scrollHeight; 
} 
</script>
      <form action="/tpl.php?update=1&filePath=$filePath" target="right" method="POST">
        <textarea style="width:100%;word-wrap:normal;font-size:11px;overflow-x:scrolli;overflow-y:visible"  onkeydown='return catchTab(this,event)' name='tpl' id='tpl'>$innerHtml</textarea>
        <button type="submit" class="btn btn-primary">Submit</button>
         <a href="/tpl.php?filePath=$filePath" target="right" ><button class="btn btn-primary" type="button">Back</button></a>
      </form>
<script>
BodyOnLoad();
</script>
      </div>
HTML;
  }
  else if($_GET['update']==1)
  {
    echo $filePath;
    $fp = fopen($filePath,'w');
    $tpl = $_POST['tpl'];
    $sign = fwrite($fp,$tpl);
    fclose();
    if($sign)
    {
      $html .=<<<HTML
      <div class="alert alert-success" style="margin-top:10px;"><button type="button" class="close" data-dismiss="alert">x</button> 修改成功！！</div>
HTML;
    }
    else
    {
      $html .=<<<HTML
      <div class="alert alert-error" style="margin-top:10px;"><button type="button" class="close" data-dismiss="alert">x</button> 修改失败！！</div>
HTML;
    }
    $html .= tplHTML($filePath);
    
  }
  else
  {
    $html .= tplHTML($filePath);
  }

    $html .='</body></html>';
    echo $html;
}
else
{
  $html = head();
  if($_GET['new'] == 1)
  {
    $html .= newTpl();
  }else if($_GET['add'] ==1){

    $tempName = trim($_POST['templateName']);
    $tpl = $_POST['tpl'];
    if($tempName =='' ||  $tpl == '')
    {
      $html .=<<<HTML
      <div class="alert alert-error" style="margin-top:10px;"><button type="button" class="close" data-dismiss="alert">x</button>参数不能为空!</div>
HTML;
      $html .= newTpl();
      $html .='</body></html>';
      echo $html;
      exit;

    }
    $tplName = explode('.',$tempName,2);
    if(count($tplName) <2)
    {
      $filePath = PATH."/".$tempName.".txt";
    }
    else{
      $fileDir = PATH."/".$tplName[0];
      if(!is_dir($fileDir))
      {
        mkdir($fileDir,0777);
      }
      $filePath = PATH."/".$tplName[0]."/".$tempName.".txt";
    }

    if(file_exists($filePath))
    {
      $html .=<<<HTML
              <div class="alert alert-error" style="margin-top:10px;"><button type="button" class="close" data-dismiss="alert">x</button>模板已存在!不能添加!</div>
HTML;
      $html .= newTpl();
      $html .='</body></html>';
      echo $html;
      exit;
    }
    $fp = fopen($filePath,'w');
    echo $filePath;
    //$fp = fopen('/tmp/tpl.txt','w');
    $sign = fwrite($fp,$tpl);
    fclose($fp);
    if($sign)
    {
      $html .=<<<HTML
      <div class="alert alert-success" style="margin-top:10px;"><button type="button" class="close" data-dismiss="alert">x</button> 添加成功！！</div>
HTML;
      $html .= tplHTML($filePath);
    }
    else
    {
      $html .=<<<HTML
      <div class="alert alert-error" style="margin-top:10px;"><button type="button" class="close" data-dismiss="alert">x</button> 添加失败！！</div>
HTML;
    }

  }
  $html .='</body></html>';
  echo $html;
}

function tplHTML($filePath)
{

    $html .="<div>";
    $fp = fopen($filePath,'r');
    $html .= '<pre class="prettyprint linenums" style="margin-bottom: 9px;margin-top:20px;">';
    while(($line = fgets($fp,4096)))
    {
      $html .=htmlentities($line,ENT_NOQUOTES,'UTF-8');

    }
    $html .= '</pre><p><a href="/tpl.php?edit=1&filePath='.urlencode($filePath).'" target="right"><button class="btn btn-primary" type="button">编辑模板</button></a></p></div>';
    return $html;
}

function newTpl(){
  
    $html =<<<HTML
      <div style='margin-top:10px;height:800px;'>
      <form action="/tpl.php?add=1&filePath=$filePath" target="right" method="POST">
        <label>Template Name</label>
        <input type="text" name='templateName' placeholder="e.g. 58.dog,58.ershou_1"/>
        <label>Template</label>
        <textarea style="width:100%;height:80%;word-wrap:normal;font-size:11px;overflow-x:scrolli;overflow-y:visible"  onkeydown="return catchTab(this,event)" name='tpl' id='tpl'></textarea>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      </div>
HTML;
    return $html;

}

function head(){
  $html =<<<HTML
<html>
<head>
    <meta charset="utf-8">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="js/google-code-prettify/prettify.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>
    <script src="js/jquery.js"></script>
    <script src="js/1.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
    <script src="js/bootstrap-affix.js"></script>
    <script src="js/application.js"></script>
<body>
HTML;
  return $html;
}

?>
