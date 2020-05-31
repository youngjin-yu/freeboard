<?php
  session_start(); 
  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";
          
  require_once("../main/MYDB.php");
  $pdo = db_connect();

  if ($mode=="modify"){
    try{
        $sql = "select * from freeboard_db.humor where num = ?";
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute();              
      
        //print "검색결과가 없습니다.<br>";
        $num=1;
       
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"];
        $item_subject = $row["subject"];
        $item_content = $row["content"];
      //   $item_file_0 = $row["file_name_0"];
      //   $item_file_1 = $row["file_name_1"];
      //   $item_file_2 = $row["file_name_2"];
      //   $copied_file_0 = $row["file_copied_0"];
      //   $copied_file_1 = $row["file_copied_1"];
      //   $copied_file_2 = $row["file_copied_2"];
        
       }catch (PDOException $Exception) {
         print "오류: ".$Exception->getMessage();
       } 
}else{
    try{
      $sql = "select * from freeboard_db.humor order by num desc limit 1";
      $stmh = $pdo->prepare($sql);  
      $stmh->execute();
      $count = $stmh->rowCount();              
    if($count<1){  
      $num=1;
     }else{
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
      $num = $row["num"];
    
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
    }
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/common.css">
        <link rel="stylesheet" type="text/css" href="../css/travel.css">
        
    </head>

    <body>
        <div id="wrap">
            <div id="header">
                <?php include "../main/top_login2.php"; ?>
            </div>
            <div id="menu">
                <?php include "../main/top_menu2.php"; ?>
            </div>
            <div id="content">
                <!-- del 2020.05.12 <div id="col1">
                    <div id="left_menu">
                        ?php include "../main/left_menu.php";?>
                    </div>
                </div> -->
                <div id="col2">
                    <div id="title"><img src="../img/title_humor.png"></div>
                    <div class="clear"></div>
                    <div id="write_form_title">
                        <img src="../img/write_form_title.gif">
                    </div>
                    <div class="clear"></div>
                    <?php
    if($mode=="modify"){
        // 글 수정 부분 - insert.php 로 이동
  ?>
                    <form
                        name="board_form"
                        method="post"
                        action="insert.php?mode=modify&num=<?=$num?>"
                        enctype="multipart/form-data">
                    <?php  } else {
                        //신규 글 작성 - insert.php 로 이동
  ?>
                        <form
                                action="insert.php?num=<?=$num?>"
                                method="post"
                                name="board_form"
                                enctype="multipart/form-data"
                                >
                                    
                                    <!-- <input type="hidden" name="nickname" value="testNickName">
                                    <input type="hidden" name="time" value="20200518"> -->
                        
                        <!-- <form
                            name="board_form"
                            method="post"
                            action="insert.php"
                            enctype="multipart/form-data"
                            > -->
                            <?php
	}
  ?>
                            <div id="write_form">
                                <div class="write_line"></div>
                                <div id="write_row1">
                                    <div class="col1">
                                        별명
                                    </div>
                                    <div class="col2"><?=$_SESSION["nick"]?></div>
                                    <div class="col3"><input type="checkbox" name="html_ok" value="y">
                                        HTML 쓰기</div>
                                </div>
                                <div class="write_line"></div>
                                <div id="write_row2">
                                    <div class="col1">
                                        제목
                                    </div>
                                    <div class="col2"><input
                                        type="text"
                                        name="subject"
                                        <?php if($mode=="modify"){ ?>
                                        <?php }?>
                                        required="required" value="<?=$item_subject?>" ></div>
                                </div>
                                <div class="write_line"></div>
                                <div id="write_row3">
                                    <div class="col1">
                                        내용
                                    </div>
                                    <div class="col2">
                                        <textarea rows="15" cols="79" name="content" required="required"><?php if($mode=="modify") print $item_content?></textarea>
                                    </div>
                                </div>
                                <div class="write_line"></div>
                                <!-- <div id="write_row4">
                                    <div class="col1">
                                        이미지파일1
                                    </div>
                                    upfile[] : 동일한 이름인데 여러 개 저장하는 경우
                                    <div class="col2"><input type="file" name="upfile[]"></div>
                                </div>
                                <div class="clear"></div> -->
                                <?php 	if ($mode=="modify" && $item_file_0)
	         {
  ?>
                                <!-- <div class="delete_ok">
                                    ?=$item_file_0?
                                    파일이 등록되어 있습니다.
                                    <input type="checkbox" name="del_file[]" value="0">
                                    삭제</div><div class="delete_ok">?=$item_file_2?
                                    파일이 등록되어 있습니다.
                                    <input type="checkbox" name="del_file[]" value="2">
                                    삭제</div>
                                <div class="clear"></div>
                                <div class="clear"></div> -->
                                <?php  	} ?>
                                <!-- <div class="write_line"></div>
                                <div id="write_row5">
                                    <div class="col1">
                                        이미지파일2
                                    </div>
                                    <div class="col2"><input type="file" name="upfile[]"></div>
                                </div> -->
                                <?php 	if ($mode=="modify" && $item_file_1)
	{
  ?>
                                <!-- <div class="delete_ok">?=$item_file_1?>
                                    파일이 등록되어 있습니다.
                                    <input type="checkbox" name="del_file[]" value="1">
                                    삭제</div>
                                <div class="clear"></div> -->
                                <?php  	} ?>
                                <!-- <div class="write_line"></div>
                                <div class="clear"></div>
                                <div id="write_row6">
                                    <div class="col1">
                                        이미지파일3
                                    </div>
                                    <div class="col2"><input type="file" name="upfile[]"></div>
                                </div> -->
                                <?php 	if ($mode=="modify" && $item_file_2)
	{
  ?>
                                <!-- <div class="delete_ok">?=$item_file_2?
                                    파일이 등록되어 있습니다.
                                    <input type="checkbox" name="del_file[]" value="2">
                                    삭제</div>
                                <div class="clear"></div> -->
                                <?php  	} ?>
                                <div class="write_line"></div>
                                <div class="clear"></div>
                                
                                <input type="file" name="upfile[]" multiple="multiple" >

                            </div>
                            
                            <div id="write_button"><input type="image" src="../img/ok.png" >&nbsp;
                                <a href="list.php"><img src="../img/list.png"></a>
                            </div>   
                        </form>
                        <!-- 2020.05.19+(s) -->
                        <!-- <iframe name="test" style="width: 760px; height: 200px;"></iframe> -->
                                <!-- 2020.05.19+(e) -->
                              

                    </div>
                </div>
            </div>
        </body>
    </html>