<?php session_start(); ?>
<meta charset="utf-8">
<?php
   if(!isset($_SESSION["userid"])) {
 ?>
<script>
    alert('로그인 후 이용해 주세요.');
    history.back();
</script>
<?php }
  if(isset($_REQUEST["page"]))
    $page=$_REQUEST["page"];
  else 
    $page=1;
 if(isset($_REQUEST["mode"]))  // 입력방식 구분
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
  
 require_once("../main/MYDB.php");
 $pdo = db_connect();

 if ($mode=="modify"){
   try{
     $pdo->beginTransaction();   
     $sql = "update freeboard_db.politics set subject=?, content=?, is_html=? where num=?";
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $subject, PDO::PARAM_STR);  
     $stmh->bindValue(2, $content, PDO::PARAM_STR);  
     $stmh->bindValue(3, $html_ok, PDO::PARAM_STR);     
     $stmh->bindValue(4, $num, PDO::PARAM_STR); 
     $stmh->execute();
     $pdo->commit(); 
    
     header("Location:http://localhost/freeboard/politics/list.php?page=$page");
    } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
    }                         
                
  } else	{
    if ($html_ok =="y"){
	$is_html = "y";
    }else {
	$is_html = "";
        $content = htmlspecialchars($content);
    }
    
    if ($mode=="response")
    {
     try{
       $sql = "select * from freeboard_db.politics where num = $num"; // 부모 글 가져오기
       $stmh = $pdo->prepare($sql);  
       $stmh->bindValue(1, $num, PDO::PARAM_STR);      
       $stmh->execute();            
      
       $row = $stmh->fetch(PDO::FETCH_ASSOC);  
       $group_num = $row["group_num"];      // group_num, depth, ord 설정
       $depth = $row["depth"] + 1;
       $ord = $row["ord"] + 1;  //ord : 글 순번
	// ord 가 부모글의 ord($row[ord]) 보다 큰 경우엔 ord 값 1 증가 시킴
       $pdo->beginTransaction();
       $sql = "update freeboard_db.politics set ord = ord + 1 where group_num = ? and ord > ?";
       $stmh = $pdo->prepare($sql); 
       $stmh->bindValue(1, $row["group_num"], PDO::PARAM_STR);  
       $stmh->bindValue(2, $row["ord"], PDO::PARAM_STR);  
       $stmh->execute();
       $pdo->commit(); 
             
       $pdo->beginTransaction();  
       $sql = "insert into freeboard_db.politics(group_num, depth, ord, id, name, nick, subject,";
       $sql .= "content, regist_day, hit, is_html) ";
       $sql .= "values(?, ?, ?, ?, ?, ?, ?, ?, now(), 0, ?)";    
       $stmh = $pdo->prepare($sql); 
       $stmh->bindValue(1, $group_num, PDO::PARAM_STR);  
       $stmh->bindValue(2, $depth, PDO::PARAM_STR);  
       $stmh->bindValue(3, $ord, PDO::PARAM_STR);   
       $stmh->bindValue(4, $_SESSION["userid"], PDO::PARAM_STR);  
       $stmh->bindValue(5, $_SESSION["name"], PDO::PARAM_STR);  
       $stmh->bindValue(6, $_SESSION["nick"], PDO::PARAM_STR);  
       $stmh->bindValue(7, $subject, PDO::PARAM_STR);   
       $stmh->bindValue(8, $content, PDO::PARAM_STR);  
       $stmh->bindValue(9, $is_html, PDO::PARAM_STR);               
       $stmh->execute();
       $pdo->commit(); 
           
       header("Location:http://localhost/freeboard/politics/list.php?page=$page");
     } catch (PDOException $Exception) {
        $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     } 
 
    }  else {
        // 신규 글쓰기인 경우 depth와 ord 를 0으로 초기화
      $depth = 0;   // depth와 ord 를 0으로 초기화
      $ord = 0;
       
     try{
        //2020.05.12+(s)
       $group_num=1;
       $pdo->beginTransaction();    // 레코드 삽입(group_num 제외)
       $sql = "insert into freeboard_db.politics(group_num,depth,ord,id,name,nick,subject,content,regist_day,hit,is_html) ";
       $sql .= "values(?,?, ?, ?, ?, ?, ?, ?,now(), 0, ?)";
       $stmh = $pdo->prepare($sql); 
       $stmh->bindValue(1, $group_num, PDO::PARAM_STR);  
       $stmh->bindValue(2, $depth, PDO::PARAM_STR);  
       $stmh->bindValue(3, $ord, PDO::PARAM_STR);        
       $stmh->bindValue(4, $_SESSION["userid"], PDO::PARAM_STR);  
       $stmh->bindValue(5, $_SESSION["name"], PDO::PARAM_STR);  
       $stmh->bindValue(6, $_SESSION["nick"], PDO::PARAM_STR);   
       $stmh->bindValue(7, $subject, PDO::PARAM_STR);  
       $stmh->bindValue(8, $content, PDO::PARAM_STR);  
       $stmh->bindValue(9, $is_html, PDO::PARAM_STR);      
       $stmh->execute();
       //$group_num=$group_num+1;
       //2020.05.12+(e)
    //    추가하는 글이 몇 번째 글인지
       $lastId = $pdo->lastInsertId();   //PHP 7.0 수정됨. Auto_Increment num필드값
       $pdo->commit();
 
       $pdo->beginTransaction();   
       $sql = "update freeboard_db.politics set group_num = ? where num=?";
       $stmh1 = $pdo->prepare($sql); 
       $stmh1->bindValue(1, $lastId, PDO::PARAM_STR);  
       $stmh1->bindValue(2, $lastId, PDO::PARAM_STR);  
       $stmh1->execute();
       $pdo->commit(); 
     
       header("Location:http://localhost/freeboard/politics/list.php?page=$page");
       } catch (PDOException $Exception) {
         $pdo->rollBack();
         print "오류: ".$Exception->getMessage();
       }  
    }
  }
 ?>