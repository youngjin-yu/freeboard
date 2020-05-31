  <?php   
   session_start();
   $num=$_REQUEST["num"];
         
   require_once("../main/MYDB.php");
   $pdo = db_connect();
 
   $upload_dir = $_SERVER['DOCUMENT_ROOT'].'/freeboard/img/';   //물리적 저장위치   
 
   try{
     $sql = "select * from freeboard_db.development where num = ? ";
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1,$num,PDO::PARAM_STR); 
     $stmh->execute();
     $count = $stmh->rowCount();              
 
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
     if($row["nick"]==$_SESSION["nick"]){
     $copied_name[0] = $row["file_copied_0"];
     $copied_name[1] = $row["file_copied_1"];
     $copied_name[2] = $row["file_copied_2"];
      
     for ($i=0; $i<3; $i++)
     { 
         if ($copied_name[$i])
         {
       $image_name = $upload_dir.$copied_name[$i];
      //  unlink : 서버에 저장되어있는 파일을 삭제한다 (서버에 저장되어 있는 물리적 경로에 있는 파일을 삭제)
	     unlink($image_name);
	  }
     }


    //  데이터베이스에서 삭제하는 부분
   try{
    $pdo->beginTransaction();
    $sql = "delete from freeboard_db.development where num = ?";  
    $stmh1 = $pdo->prepare($sql);
    $stmh1->bindValue(1,$num,PDO::PARAM_STR);      
    $stmh1->execute();   
    $pdo->commit();

    header("Location:http://localhost/freeboard/development/list.php");
                        
    } catch (Exception $ex) {
       $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
  }

    }else{
      ?>
      <script>
           alert("작성자만 삭제 가능합니다!");
           history.back();
      </script>
      <?php
    }
   }catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
   }
 
  ?>
