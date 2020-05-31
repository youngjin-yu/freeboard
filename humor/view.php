<?php
 session_start(); 
// 서버 컴퓨터 내에 저장되어있는 파일 경로
 $file_dir = $_SERVER['DOCUMENT_ROOT'].'/freeboard/humor/data/'; 
  
 $num=$_REQUEST["num"];
   
 require_once("../main/MYDB.php");
 $pdo = db_connect();
 
 try{
     $sql = "SELECT humor.num, humor.id, humor.nick, humor.subject, humor.content, humor.regist_day, humor.hit, humor.is_html, humordata.file_name, humordata.file_copied  FROM freeboard_db.humor INNER JOIN freeboard_db.humordata ON humor.num = humordata.num where humor.num = ?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     //2020.05.18+(s)
     $result = array();
     $image_name = array();
     $image_copied = array();
     while($row = $stmh -> fetch()) {

        $result[] = $row;
        
    }

    for($i=0; $i<sizeof($result); $i++){
        $image_name[$i]=$result[$i]["file_name"];
        $image_copied[$i]=$result[$i]["file_copied"];
    }

     //2020.05.18+(e)
     //$row = $stmh->fetch(PDO::FETCH_ASSOC);
 	
     $item_num     = $result[0]["num"];
     $item_id      = $result[0]["id"];
    
     $item_nick    = $result[0]["nick"];
     $item_hit     = $result[0]["hit"];
 
     $item_date    = $result[0]["regist_day"];
     $item_date    = substr($item_date,0,10);
     $item_subject = str_replace(" ", "&nbsp;", $result[0]["subject"]);
     $item_content = $result[0]["content"];
     $is_html      = $result[0]["is_html"];
      
     if ($is_html!="y"){
	$item_content = str_replace(" ", "&nbsp;", $item_content);
     	$item_content = str_replace("\n", "<br>", $item_content);
     }	
 
     $new_hit = $item_hit + 1;
     try{
       $pdo->beginTransaction(); 
       $sql = "update freeboard_db.humor set hit=? where num=?";   // 조회수 증가
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
        <link rel="stylesheet" type="text/css" href="../css/travel.css">
        <script>
            function del(href) {
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
                    <div id="left_menu"> ?php include "../main/left_menu.php"; ?></div>
                </div> -->
                <div id="col2">
                    <div id="title"><img src="../img/title_humor.png"></div>
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
                    <div id="view_content">
                        <?php

	for ($i=0; $i<sizeof($result); $i++)
	{
	   if ($image_copied[$i]) 
            {
                // getimagesize : 인자에 있는 이미지의 크기와 형식을 배열 형태로 반환한다
	     $imageinfo = getimagesize($file_dir.$image_copied[$i]);
         $image_width[$i] = $imageinfo[0];
         $image_height[$i] = $imageinfo[1];
        //  $image_type : 이미지의 형식
	     $image_type[$i]  = $imageinfo[2];
	     $img_name = $image_copied[$i];
	     $img_name = "../humor/data/".$img_name;
 
 	      if ($image_width[$i] > 785)
	          $image_width[$i] = 785;
               
              // image 타입 1은 gif 2는 jpg 3은 png
             if($image_type[$i]==1 || $image_type[$i]==2   
                    || $image_type[$i]==3){
	        print "<img src='$img_name' width='$image_width[$i]'><br><br>";
                    }
            }
        }
  ?>
                        <?= $item_content ?>
                    </div>
                    <div id="view_button">
                        <a href="list.php"><img src="../img/list.png"></a>&nbsp;
                        <?php
    if(isset($_SESSION["userid"])) {
	if($_SESSION["userid"]==$item_id || $_SESSION["userid"]=="admin" ||
           $_SESSION["level"]==1 )
        {
  ?>
                        <a href="write_form.php?mode=modify&num=<?=$num?>"><img src="../img/modify.png"></a>&nbsp;
                        <a href="javascript:del('delete.php?num=<?=$num?>')"><img src="../img/delete.png"></a>&nbsp;
                        <?php  	}
 ?>
                        <a href="write_form.php"><img src="../img/write.png"></a>
                        <?php
	}
  } catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
 ?>
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- end of col2 -->
            </div>
            <!-- end of content -->
        </div>
        <!-- end of wrap -->
    </body>
</html>