<?php
  session_start(); 
 
  $num=$_REQUEST["num"];
  
  require_once("../main/MYDB.php");
  $pdo = db_connect();
 
  try{
     $sql = "select * from freeboard_db.music where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
    while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
     $item_num     = $row["num"];
     $item_id      = $row["id"];
     $item_name    = $row["name"];
     $item_nick    = $row["nick"];   
     $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
     $item_content = $row["content"];
     $item_date    = $row["regist_day"];
     $item_date    = substr($item_date, 0, 10);   
     $item_hit     = $row["hit"];     
     $is_html      = $row["is_html"];
      
     if ($is_html!="y"){
	$item_content = str_replace(" ", "&nbsp;", $item_content);
	$item_content = str_replace("\n", "<br>", $item_content);
     }	
    //  item_hit은 원래 db에 저장되어있던 value, 조회수 1 증가
      $new_hit = $item_hit + 1;
     try{
       $pdo->beginTransaction(); 
       $sql = "update freeboard_db.music set hit=? where num=?";   // 글 조회수 증가
       $stmh = $pdo->prepare($sql);  
       $stmh->bindValue(1, $new_hit, PDO::PARAM_STR);      
       $stmh->bindValue(2, $num, PDO::PARAM_STR);           
       $stmh->execute();
       $pdo->commit(); 
  } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
  }
 ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/common.css">
        <link rel="stylesheet" type="text/css" href="../css/music.css">
        <script>
            function del(href) {
                // javascript에 있는 confirm 창을 띄운다
                if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
                    document.location.href = href;
                }
            }
        </script>
    </head>
    <body>
        <div id="wrap">
            <div id="header"><?php include "../main/top_login2.php"; ?></div>
            <div id="menu"><?php include "../main/top_menu2.php"; ?></div>
            <div id="content">
                <!-- del 2020.05.12 <div id="col1">
                    <div id="left_menu">?php include "../main/left_menu.php"; ?></div>
                </div> -->
                <div id="col2">
                    <div id="title"><img src="../img/title_music.png"></div>
                    <div id="view_comment">
                        &nbsp;</div>
                    <div id="view_title">
                        <div id="view_title1"><?= $item_subject ?></div>
                        <div id="view_title2"><?= $item_nick ?>
                            | 조회 :
                            <?= $item_hit ?>
                            |
                            <?= $item_date ?>
                        </div>
                    </div>
                    <div id="view_content"><?= $item_content ?></div>
                    <div id="view_button">
                        <a href="list.php"><img src="../img/list.png"></a>&nbsp;
                        <?php
                        // 수정, 삭제 권한은 작성자 또는 관리자만 가능하게 한다
      if(isset($_SESSION["userid"])) {
	if($_SESSION["userid"]==$item_id || $_SESSION["userid"]=="admin"){
 ?>
                        <a href="modify_form.php?num=<?=$num?>"><img src="../img/modify.png"></a>&nbsp;
                        <a href="javascript:del('delete.php?num=<?=$num?>')"><img src="../img/delete.png"></a>&nbsp;
                        <?php  	}
 ?>
                        <a href="write_form.php"><img src="../img/write.png"></a>
                        <?php
	}
     }
 } catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
 }
 ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </body>
</html>