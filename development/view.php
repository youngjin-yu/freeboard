<?php
  session_start(); 
  
  $file_dir = $_SERVER['DOCUMENT_ROOT'].'/freeboard/img/';
  
  $num=$_REQUEST["num"];
   
  require_once("../main/MYDB.php");
  $pdo = db_connect();
 
  try{
     $sql = "select * from freeboard_db.development where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
       
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
 	
     $item_num     = $row["num"];
     $item_id      = $row["id"];
     $item_name    = $row["name"];
     $item_nick    = $row["nick"];
     $item_hit     = $row["hit"];
 
     $file_name[0]   = $row["file_name_0"];
     $file_name[1]   = $row["file_name_1"];
     $file_name[2]   = $row["file_name_2"];
      
     $file_type[0]   = $row["file_type_0"];
     $file_type[1]   = $row["file_type_1"];
     $file_type[2]   = $row["file_type_2"];
 
     $file_copied[0] = $row["file_copied_0"];
     $file_copied[1] = $row["file_copied_1"];
     $file_copied[2] = $row["file_copied_2"];
 
     $item_date    = $row["regist_day"];
     $item_date    = substr($item_date,0,10);
     $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
     $item_content = $row["content"];
    $is_html      = $row["is_html"];
      
     if ($is_html!="y"){
	$item_content = str_replace(" ", "&nbsp;", $item_content);
	$item_content = str_replace("\n", "<br>", $item_content);
     }	
// 조회수 1 증가 시킨다
     $new_hit = $item_hit + 1;
     try{
       $pdo->beginTransaction(); 
       $sql = "update freeboard_db.development set hit=? where num=?";  
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
                    <div id="left_menu">?php include "../main/left_menu.php"; ?></div>
                </div> -->
                <div id="col2">
                    <div id="title"><img src="../img/title_development.png"></div>
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
	for ($i=0; $i<3; $i++)
	{
	   if ($file_copied[$i]) 
		{
		$show_name = $file_name[$i];
		$real_name = $file_copied[$i];
		$real_type = $file_type[$i];
		$file_path = $file_dir.$real_name;
		$file_size = filesize($file_path);
    //    real_name : 서버에 저장되어지는 파일 이름, show_name : 실제 사용자가 업로드 할 때 원래 파일 이름
       print "▷ 첨부파일 : $show_name ($file_size Byte) &nbsp;&nbsp;
       <a href='download.php?num=$num&real_name=$real_name&show_name=$show_name&file_type=$real_type'>[다운로드]</a><br>";
		}
	}
 ?>
                        <br>

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