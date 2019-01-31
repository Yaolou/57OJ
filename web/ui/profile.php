<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("content-type:application/javascript");
// include("modal.php");
if(isset($_SERVER['HTTP_REFERER'])) $dir=basename(dirname($_SERVER['HTTP_REFERER']));
else $dir="";

if($dir=="57bbs") $path_fix="../";
else $path_fix="";

require_once("../include/db_info.inc.php");

if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}else{
  require_once("../lang/en.php");
}

function checkmail(){
  global $OJ_NAME;

  $sql="SELECT count(1) FROM `mail` WHERE new_mail=1 AND `to_user`=?";
  $result=pdo_query($sql,$_SESSION[$OJ_NAME.'_'.'user_id']);

  if(!$result) return false;

  $row=$result[0];
  $retmsg="<span id=red>(".$row[0].")</span>";
		
  return $retmsg;
}

$profile='';

if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
  $sid=$_SESSION[$OJ_NAME.'_'.'user_id'];

  $profile.= "<li><a href='".$path_fix."userinfo.php?user=$sid'><span id=red>个人主页</span></a></li>&nbsp;<li><a href=".$path_fix."modifypage.php>修改信息</a></li>";

  if((isset($OJ_EXAM_CONTEST_ID)&&$OJ_EXAM_CONTEST_ID>0)||
     (isset($OJ_ON_SITE_CONTEST_ID)&&$OJ_ON_SITE_CONTEST_ID>0)||
     (isset($OJ_MAIL)&&!$OJ_MAIL)){
  }
  else{
    $mail=checkmail();
    if($mail) $profile.= "&nbsp;<li><a class='glyphicon glyphicon-envelope' href=".$path_fix."mail.php>$mail</a></li>";
  }

  $profile.="&nbsp;<li><a href='".$path_fix."status.php?user_id=$sid'><span id=red>$MSG_MY_SUBMISSIONS</span></a></li>";
  $profile.="&nbsp;<li><a href='".$path_fix."contest.php?my'><span id=red>$MSG_MY_CONTESTS</span></a></li>";

  $profile.= "&nbsp;<li><a href=".$path_fix."logout.php>$MSG_LOGOUT</a></li>&nbsp;";

}else{
	$profile.= "<li role='presentation'>";
	$profile.= "<a data-toggle='modal' data-target='#login_modal'>登录</a></li>";
	$profile.= "<li role='presentation'>";
	$profile.= "<a href='lostpassword.php'>$MSG_LOST_PASSWORD</a>";
	$profile.= "</li>";
  $profile.= "<li><a data-toggle='modal' data-target='#myModal'>$MSG_REGISTER</a></li>";

}

if(isset($_SESSION[$OJ_NAME.'_'.'balloon'])){
  $profile.= "<li><a href='".$path_fix."balloon.php'>$MSG_BALLOON</a></li>&nbsp;";
}

if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){
  $profile.= "<li><a href=".$path_fix."admin/>$MSG_ADMIN</a></li>&nbsp;";
}

//$profile.="</ul></li>";
?>
document.write("<?php echo ( $profile);?>");
document.getElementById("profile").innerHTML="<?php echo  isset($sid)?$sid:"登录"  ?>";
