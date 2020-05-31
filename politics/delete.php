<?php   
   session_start();
   $num=$_REQUEST["num"];
   $page=$_REQUEST["page"];
         
   require_once("../main/MYDB.php");
   $pdo = db_connect();
 
   // politics 게시판은 첨부파일이 없으므로 삭제해도 된다
   // $upload_dir = $_SERVER['DOCUMENT_ROOT'].'/freeboard/img/';   //물리적 저장위치   
 
   // travel 디렉토리에 delete는 첨부파일 지우는 것도 포함되므로 삭제하였음
   try{
     $sql = "select * from freeboard_db.politics where num = ? ";
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1,$num,PDO::PARAM_STR); 
     $stmh->execute();
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
     if($row["nick"]==$_SESSION["nick"]){

     $pdo->beginTransaction();
     $sql = "delete from freeboard_db.politics where num = ?";  
     $stmh1 = $pdo->prepare($sql);
     $stmh1->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh1->execute();   
     $pdo->commit();
 
     header("Location:http://localhost/freeboard/politics/list.php?page=$page");
    }else{
      ?>
<script>
    alert("작성자만 삭제 가능합니다!");
    history.back();
</script>
<?php
    }
    
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
  
?>