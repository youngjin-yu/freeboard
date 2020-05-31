<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="./css/common.css">
    
    <style type="text/css">
         
    </style>
    <script>
        function previewFile() {
            var preview = document.querySelector('img');
            var file    = document.querySelector('input[type=file]').files[0];
            var reader  = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
            }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
}
    </script>


</head>

<body>
    <div id="wrap">
        <div id="header">
            <!-- 상대 경로 지정 -->
            <?php include "./main/top_login1.php"; ?>
        </div> <!-- end of header -->
        <p></p>
        <div id="menu">
            <!-- 상대 경로 지정 -->
            <?php include "./main/top_menu1.php"; ?>
        </div> <!-- end of menu -->

        <div id="content">
            <div id="main_img"><img src="./img/main_img.jpg"></div>
            <?php include "./main/func.php"; ?>
            <div id="latest">
                <div id="latest1">
                    <div id="title_latest1"><img src="./img/title_travel.png"></div>
                    <div class="latest_box">
                        <?php latest_article("travel",5,30);?>

                    </div>
                </div>
                <div id="latest2">
                    <div id="title_latest2"><img src="./img/title_economy.png"></div>
                    <div class="latest_box">
                        <?php latest_article("economy",5,30);?>
                    </div>
                </div>

                <div id="latest3">
                    <div id="title_latest3"><img src="./img/title_politics.png"></div>
                    <div class="latest_box">
                        
                        <?php latest_article("politics",5,30);?>
                    </div>
                </div>

                <div id="latest4">
                    <div id="title_latest4"><img src="./img/title_humor.png"></div>
                    <div class="latest_box">
                        <?php latest_article("humor",5,30);?>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- end of wrap -->
</body>
</html>