<?php

session_start();
unset($_SESSION["userid"]);
unset($_SESSION["name"]);
unset($_SESSION["nick"]);
unset($_SESSION["level"]);

//쿠키 유지 hour을 현재 hour 까지로 설정함으로써 바로 만료되게 한다
setcookie("userid","",-1,"/");
setcookie("name","",-1,"/");
setcookie("nick","",-1,"/");
setcookie("level","",-1,"/");
setcookie("pw","",-1,"/");

header("Location:http://localhost/freeboard/index.php");
?>