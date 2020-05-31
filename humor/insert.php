<?php session_start(); 
 require_once("../main/MYDB.php");
 $pdo = db_connect();
 $conn = new mysqli("localhost","keepgoing","dudWLS11!!","freeboard_db");?>
<meta charset="utf-8">
<?php
  if(!isset($_SESSION["userid"])) {
 ?>
<script>
    alert('로그인 후 이용해 주세요.');
    history.back();
</script>
<?php
  }
 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";
       
 if(isset($_REQUEST["html_ok"]))  //checkbox는 체크해야 변수명 전달됨.
    $html_ok=$_REQUEST["html_ok"];
  else
    $html_ok="";
   
  $subject=$_REQUEST["subject"];
  $content=$_REQUEST["content"];
   // _FILES : 슈퍼 글로벌 변수
  $files = $_FILES["upfile"];    //첨부파일	
  $count = count($files["name"]); 
  $root = $_SERVER['DOCUMENT_ROOT']; 
  // 2020.05.10 수정 //서버 컴퓨터 내에 물리적 저장위치, upload 폴더 경로
  $upload_dir = $root.'/freeboard/humor/data/';   //물리적 저장위치    
 
 //수정인 경우   
 if ($mode=="modify"){
   
   try{
      $sql = "select * from freeboard_db.humordata where num = ? ";
      $stmh = $pdo->prepare($sql); 
      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
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
  
      if($result[0]["nick"]==$_SESSION["nick"]){
     //  $copied_name[0] = $row["file_copied_0"];
     //  $copied_name[1] = $row["file_copied_1"];
     //  $copied_name[2] = $row["file_copied_2"];
       
      for ($i=0; $i<sizeof($result); $i++){ 
          if ($result[$i]["file_copied"]){
           $image_name = $upload_dir.$result[$i]["file_copied"];
           //  unlink : 서버에 저장되어있는 파일을 삭제한다
            unlink($image_name);
              }
           }
 
      try{
       $pdo->beginTransaction();
       $sql = "update freeboard_db.humor set subject = ?, content =?, is_html =? where num = ?";  
       $stmh1 = $pdo->prepare($sql);
       $stmh1->bindValue(1,$subject,PDO::PARAM_STR);    
       $stmh1->bindValue(2,$content,PDO::PARAM_STR);
       $stmh1->bindValue(3,$html_ok,PDO::PARAM_STR);        
       $stmh1->bindValue(4,$num,PDO::PARAM_STR);      
       $stmh1->execute();   
       $pdo->commit();
 
       try{
         $pdo->beginTransaction();
         $sql = "delete from freeboard_db.humordata where num = ?";  
         $stmh2 = $pdo->prepare($sql);
         $stmh2->bindValue(1,$num,PDO::PARAM_STR);      
         $stmh2->execute();   
         $pdo->commit();
       }catch (Exception $ex) {
         $pdo->rollBack();
         print "오류: ".$Exception->getMessage();
       }
   
       
                           
       } catch (Exception $ex) {
          $pdo->rollBack();
          print "오류: ".$Exception->getMessage();
     }

          
 
     for ($i=0; $i<$count; $i++)
     {
        $upfile_name[$i]     = $files["name"][$i];         //파일 이름(확장자명까지 포함)
        $upfile_tmp_name[$i] = $files["tmp_name"][$i];   //서버에 저장되는 파일 이름
        $upfile_type[$i]     = $files["type"][$i]; //파일 형식
        $upfile_size[$i]     = $files["size"][$i]; //파일 크기
        $upfile_error[$i]    = $files["error"][$i];//제대로 전송되어졌는지 여부
        $file = explode(".", $upfile_name[$i]);//explode: 자바에서 split와 비슷한 기능을 한다
        $file_name = $file[0];
        $file_ext  = $file[1];
   
      //   에러가 없다면->제대로 전송하였다면
        if (!$upfile_error[$i])
        {
         //   연,월,일,시,분,초 value
      $new_file_name = date("Y_m_d_H_i_s");
      $new_file_name = $new_file_name."_".$i;
      $copied_file_name[$i] = $new_file_name.".".$file_ext;    
      //upload_dir : 서버 컴퓨터 내에 물리적 저장 경로
      $uploaded_file[$i] = $upload_dir.$copied_file_name[$i];
   
      // 파일 업로드 크기가 200MB를 초과한다면
      if( $upfile_size[$i]  > 200000000 ) {
   
      print("
       <script>
         alert('업로드 파일 크기가 지정된 용량(200MB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
         history.back();
       </script>
      ");
      exit;
      }
            if ( ($upfile_type[$i] != "image/gif") && ($upfile_type[$i] != "image/jpeg"))
      {
      print(" <script>
                alert('JPG와 GIF 이미지 파일만 업로드 가능합니다!');
                history.back();
              </script>");
      exit;
      }
      // move_uploaded_file : 함수의 한 종류로 첫번째 인자를 두번째 인자의 경로로 이동시키는 기능
      
      if(move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i])) { //$_FILES["upfile"]["tmp_name"][$i]
         $sql = "INSERT INTO freeboard_db.humordata(num,nick,file_name,file_copied)
         VALUES ('$num','".$_SESSION["nick"]."','".$upfile_name[$i]."','$copied_file_name[$i]')";
                  $res = $conn->query($sql);
                 } else {
                     print("
                        <script>
                             parent.alert('업로드를 성공하지 못했습니다.');
                            history.back();
                        </script>
                    ");
                    exit;
                 }
   
                 
                 
         }
     }

     }else{
       ?>
       <script>
            alert("작성자만 수정 가능합니다!");
            history.back();
       </script>
       <?php
     }
    }catch (PDOException $Exception) {
         print "오류: ".$Exception->getMessage();
    }
                           
       
 } else	{
   // modify가 아닌 경우 : insert인 경우를 의미(신규 글쓰기인 경우)
      //2020.05.19+(s)
  try{
   $sql = "select * from freeboard_db.humor order by num desc limit 1";
   $stmh = $pdo->prepare($sql); 
 
   $stmh->execute();
   $rowcount = $stmh->rowCount();   
   $row = $stmh->fetch(PDO::FETCH_ASSOC);    

   if(!isset($row["num"])){
      $num_update=1;
   }else{
   $num_update=$row["num"]+1;
   }
   if($rowcount==0 || $row["num"]==$num){  
      if ($html_ok =="y"){
         $is_html = "y";
         }else {
        $is_html = "";
        // htmlspecialchars() 함수는 $string의 내용 중에서 HTML 특수문자를 HTML 코드로 변경한다. 
             $content = htmlspecialchars($content);
         }
        try{
          $pdo->beginTransaction();
          //  regist_day : 등록일, hit : 조회수, is_html : html인지 여부
          $sql = "insert into freeboard_db.humor(id, name, nick, subject, content, regist_day, hit, is_html)";
          //이미지 파일 이름, 서버에 저장할 파일 이름   
          $sql .= "values(?, ?, ?, ?, ?, now(), 0, ?)";
          $stmh = $pdo->prepare($sql); 
          $stmh->bindValue(1, $_SESSION["userid"], PDO::PARAM_STR);  
          $stmh->bindValue(2, $_SESSION["name"], PDO::PARAM_STR);  
          $stmh->bindValue(3, $_SESSION["nick"], PDO::PARAM_STR);   
          $stmh->bindValue(4, $subject, PDO::PARAM_STR);  
          $stmh->bindValue(5, $content, PDO::PARAM_STR);  
          $stmh->bindValue(6, $is_html, PDO::PARAM_STR);    
                 
          $stmh->execute();
          $pdo->commit(); 
     
          
          
          
        //   try{
        //    $pdo->beginTransaction();
        //    $sql = "insert into (num, )";
        //    $stmh1 = $pdo->prepare($sql);
        //    $stmh1->bindValue(1,$num,PDO::PARAM_STR);      
        //    $stmh1->execute();   
        //    $pdo->commit();
                               
        //    } catch (Exception $ex) {
        //       $pdo->rollBack();
        //       print "오류: ".$Exception->getMessage();
        //  }
     
     
          } catch (PDOException $Exception) {
               $pdo->rollBack();
            print "오류: ".$Exception->getMessage();
          }   
   
   }

   for ($i=0; $i<$count; $i++)
  {
     $upfile_name[$i]     = $files["name"][$i];         //파일 이름(확장자명까지 포함)
     $upfile_tmp_name[$i] = $files["tmp_name"][$i];   //서버에 저장되는 파일 이름
     $upfile_type[$i]     = $files["type"][$i]; //파일 형식
     $upfile_size[$i]     = $files["size"][$i]; //파일 크기
     $upfile_error[$i]    = $files["error"][$i];//제대로 전송되어졌는지 여부
     $file = explode(".", $upfile_name[$i]);//explode: 자바에서 split와 비슷한 기능을 한다
     $file_name = $file[0];
     $file_ext  = $file[1];

   //   에러가 없다면->제대로 전송하였다면
     if (!$upfile_error[$i])
     {
      //   연,월,일,시,분,초 value
	$new_file_name = date("Y_m_d_H_i_s");
	$new_file_name = $new_file_name."_".$i;
   $copied_file_name[$i] = $new_file_name.".".$file_ext;    
   //upload_dir : 서버 컴퓨터 내에 물리적 저장 경로
	$uploaded_file[$i] = $upload_dir.$copied_file_name[$i];

   // 파일 업로드 크기가 200MB를 초과한다면
	if( $upfile_size[$i]  > 200000000 ) {

	print("
	 <script>
	   alert('업로드 파일 크기가 지정된 용량(200MB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
	   history.back();
	 </script>
	");
	exit;
	}
      	if ( ($upfile_type[$i] != "image/gif") && ($upfile_type[$i] != "image/jpeg"))
	{
	print(" <script>
	          alert('JPG와 GIF 이미지 파일만 업로드 가능합니다!');
	          history.back();
	        </script>");
	exit;
   }
   // move_uploaded_file : 함수의 한 종류로 첫번째 인자를 두번째 인자의 경로로 이동시키는 기능
   
   if(move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i])) { //$_FILES["upfile"]["tmp_name"][$i]
      $sql = "INSERT INTO freeboard_db.humordata(num,nick,file_name,file_copied)
      VALUES ('$num_update','".$_SESSION["nick"]."','".$upfile_name[$i]."','$copied_file_name[$i]')";
               $res = $conn->query($sql);
              } else {
                  print("
                     <script>
                          parent.alert('업로드를 성공하지 못했습니다.');
                         history.back();
                     </script>
                 ");
                 exit;
              }

              
              
      }
  }


  
  }catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
  }
  //2020.05.19+(e)
   }
   
   header("Location:http://localhost/freeboard/humor/list.php");
 ?>