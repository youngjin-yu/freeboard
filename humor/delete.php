<?php   
   session_start();
   $num=$_REQUEST["num"];
         
   require_once("../main/MYDB.php");
   $pdo = db_connect();
 
   $upload_dir = $_SERVER['DOCUMENT_ROOT'].'/freeboard/humor/data/';   //물리적 저장위치   
 
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
      $sql = "delete from freeboard_db.humor where num = ?";  
      $stmh1 = $pdo->prepare($sql);
      $stmh1->bindValue(1,$num,PDO::PARAM_STR);      
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
  
      header("Location:http://localhost/freeboard/humor/list.php");
                          
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
