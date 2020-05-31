  <?php
    // num : 글 목록 번호
    $num=$_REQUEST["num"];
    // ripple_num : 댓글 목록 번호
    $ripple_num=$_REQUEST["ripple_num"];    

    require_once("../main/MYDB.php");
    $pdo = db_connect();
        
     try{
       $pdo->beginTransaction();
       $sql = "delete from freeboard_db.economy_ripple where num = ?";  //db만 수정 
       $stmh = $pdo->prepare($sql);
       $stmh->bindValue(1,$ripple_num,PDO::PARAM_STR);
       $stmh->execute();   
       $pdo->commit();
        // num : 글 목록 번호 
       header("Location:http://localhost/freeboard/economy/view.php?num=$num");
       } catch (Exception $ex) {
                $pdo->rollBack();
                print "오류: ".$Exception->getMessage();
       }
  ?>
