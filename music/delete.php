<?php
    session_start();
    $num=$_REQUEST["num"];
        
    require_once("../main/MYDB.php");
    $pdo = db_connect();
        
     try{
  
       //2020.05.16+(s) 글쓴이가 로그인한 아이디와 일치하는지 체크한 후 삭제할 수 있도록 수정
       $sql= "select * from freeboard_db.music where num = ?";
       $stmh1=$pdo->prepare($sql);
       $stmh1->bindValue(1,$num,PDO::PARAM_STR);
       $stmh1->execute();
       
       while($row = $stmh1->fetch(PDO::FETCH_ASSOC)) {
        $item_nick=$row["nick"];
       }
       if($item_nick==$_SESSION["nick"]){
        $pdo->beginTransaction();
        $sql = "delete from freeboard_db.music where num = ?";   
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1,$num,PDO::PARAM_STR);
        $stmh->execute();   
        $pdo->commit();
                 
         header("Location:http://localhost/freeboard/music/list.php");
       }else{
         //글쓴이가 로그인한 아이디와 일치하지 않으면 경고 창을 띄운다
         ?>
         <script>
           alert("삭제 권한이 없습니다!");
           history.back();
         </script>
         <?php
       }
       //2020.05.16+(e)
       
       } catch (Exception $ex) {
                $pdo->rollBack();
                print "오류: ".$Exception->getMessage();
       }
  ?>
