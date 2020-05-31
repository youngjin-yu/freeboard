


<?php
function latest_article($table,$loop,$char_limit)
{
    require_once("./main/MYDB.php");
    $pdo=db_connect();

    try{
        // 해당 테이블에서 num 필드 기준으로 내림차순 정렬 , 최근 글 $loop 개를 가져온다
        $sql="select * from freeboard_db.$table order by num desc limit $loop";
        $stmh=$pdo->query($sql);

        while($row=$stmh->fetch(PDO::FETCH_ASSOC))
        {
            $num=$row["num"];
            //strlen : 문자열 길이
            $len_subject=strlen($row["subject"]);
            $subject=$row["subject"];

            // subject의 길이가 $char_limit을 넘어서면
            if($len_subject>$char_limit){
                // mb_substr : 대상 문자열에서 $char_limit 만큼(바이트 단위 ex>30이면 30바이트) 문자열을 가져온다
                $subject=mb_substr($row["subject"],0,$char_limit,'utf-8');
                $subject=$subject."...";
            }

            $regist_day=substr($row["regist_day"],0,10);

            // 제목을 클릭하면 해당 페이지로 이동한다

            if($table == "humor"){

            $sql="select * from freeboard_db.humordata where num =?";
            $stmh1=$pdo->prepare($sql);
            $stmh1->bindValue(1, $num, PDO::PARAM_STR);
            $stmh1->execute();
            $row=$stmh1->fetch(PDO::FETCH_ASSOC);
            $image=$row["file_copied"];
            $image="./humor/data/".$image;
            ?>
            
            <div class='col1'>
            <img src=<?=$image?> width = "50" height="50" >
            <?php
            echo("
            
                <a href='./$table/view.php?num=$num'>$subject</a>
                </div><div class='col2'>$regist_day</div>
            <div class='clear'></div>
            
            ");
        }else{
            echo("
            <div class='col1'>
                <a href='./$table/view.php?num=$num'>$subject</a>
                </div><div class='col2'>$regist_day</div>
            <div class='clear'></div>
            
            ");
        }
        }
    }catch(PDOException $Exception){
        print "오류: ".$Exception->getMessage();
    }
}
?>