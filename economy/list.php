<?php
   session_start();
 ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/common.css">
        <link rel="stylesheet" type="text/css" href="../css/travel.css">
    </head>

<?php
 
 
  require_once("../main/MYDB.php");
  $pdo = db_connect();
 
//mode라는 변수가 어떤 value를 가지고 있는지 여부에 따라
  if(isset($_REQUEST["mode"])) //검색해서 list.php를 호출한 경우
     $mode=$_REQUEST["mode"];
  else 
     $mode="";

  if(isset($_REQUEST["search"]))   // search 쿼리스트링 값 할당 체크
    $search=$_REQUEST["search"];
  else 
    $search="";

  if(isset($_REQUEST["find"]))
    $find=$_REQUEST["find"];
  else
    $find="";
 
  if($mode=="search"){
    if(!$search){
  ?>
    <script>
        alert('검색할 단어를 입력해 주세요!');
        history.back();
    </script>
<?php
     }
   $sql="select * from freeboard_db.economy where $find like '%$search%' order by num desc";
  } else {
   $sql="select * from freeboard_db.economy order by num desc";
  }
  try{  
    $stmh = $pdo->query($sql); 
    $count=$stmh->rowCount(); 
  ?>
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
                        ?php include "../main/left_menu.php"; ?>
                    </div>
                </div> -->
                <!-- end of col1 -->

                <div id="col2">
                    <!-- 2020.05.10 사진 변경 할 것 -->
                    <div id="title"><img src="../img/title_economy.png"></div>
                    <form name="board_form" method="post" action="list.php?mode=search">
                        <div id="list_search">
                            <div id="list_search1">▷ 총
                                <?= $count ?>
                                개의 게시물이 있습니다.</div>
                            <div id="list_search2"><img src="../img/select_search.gif"></div>
                            <div id="list_search3">
                                <!-- $_REQUEST"find" 변수를 의미한다 -->
                                <select name="find">
                                    <option value='subject'>제목</option>
                                    <option value='content'>내용</option>
                                    <option value='nick'>닉네임</option>
                                    <option value='name'>이름</option>
                                </select>
                            </div>
                            <!-- $_REQUEST"search" 변수를 의미한다 -->
                            <div id="list_search4"><input type="text" name="search"></div>
                            <div id="list_search5"><input type="image" src="../img/list_search_button.gif"></div>
                        </div>
                        <!-- end of list_search -->
                    </form>
                    <div class="clear"></div>
                    <div id="list_top_title">
                        <ul>
                            <li id="list_title1"><img src="../img/list_title1.gif"></li>
                            <li id="list_title2"><img src="../img/list_title2.gif"></li>
                            <li id="list_title3"><img src="../img/list_title3.gif"></li>
                            <li id="list_title4"><img src="../img/list_title4.gif"></li>
                            <li id="list_title5"><img src="../img/list_title5.gif"></li>
                        </ul>
                    </div>
                    <!-- end of list_top_title -->
                    <div id="list_content">
                        <?php  // 글 목록 출력
  $i=$count;  // 글 삭제해도 연속 번호 보이는 목록번호 출력용 // i : 레코드 개수
  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
    // economy 테이블에서 num 은 일련번호를 의미
    $item_num=$row["num"];
    $item_id=$row["id"];
    $item_name=$row["name"];
    $item_nick=$row["nick"];
    $item_hit=$row["hit"];
    $item_date=$row["regist_day"];
    $item_date=substr($item_date, 0, 10);
    // 빈 공백은 &nbsp로 바꾼다는 의미
    $item_subject=str_replace(" ", "&nbsp;", $row["subject"]);

    $sql="select * from freeboard_db.economy_ripple where parent=$item_num";
    $stmh1 = $pdo->query($sql); 
    $num_ripple=$stmh1->rowCount(); 
    

  ?>
                        <div id="list_item">
                            <!-- $item_num : 글번호를 의미 -->
                            <div id="list_item1"><?= $i ?></div>
                            <div id="list_item2">
                                <a href="view.php?num=<?=$item_num?>"><?= $item_subject ?></a>
                                <?php
                                // html에서 댓글 개수를 표시하는 부분, 댓글이 있으면 표시한다
    if($num_ripple)
     print "[<font color=red><b>$num_ripple</b></font>]";
  ?>

                            </div>
                            <div id="list_item3"><?= $item_nick ?></div>
                            <div id="list_item4"><?= $item_date ?></div>
                            <div id="list_item5"><?= $item_hit ?></div>
                        </div>
                        <!– end of list_item -->

                        <?php
                        // 글 목록 번호를 1씩 decrease 한다
                        $i--;
    }
   } catch (PDOException $Exception) {
     print "오류: ".$Exception->getMessage();
   }  
  ?>

                        <div id="write_button">
                            <a href="list.php"><img src="../img/list.png"></a>&nbsp;
                            <?php
                            // userid라는 세션 변수가 value를 가지고 있다면(로그인을 하였다면) 글쓰기 버튼이 보이도록 한다
   if(isset($_SESSION["userid"]))
   {
  ?>
                            <a href="write_form.php"><img src="../img/write.png"></a>
                            <?php
   }
  ?>
                        </div>
                    </div>
                </div>
                <!-- end of col2 -->
            </div>
            <!-- end of content -->
        </div>
        <!-- end of wrap -->
    </body>
</html>