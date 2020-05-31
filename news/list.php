<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/common.css">

        <style type="text/css"></style>

    </head>

    <body>
        <div id="wrap">
            <div id="header">
                <!-- 상대 경로 지정 -->
                <?php include "../main/top_login2.php"; ?>
            </div>
            <!-- end of header -->
            <p></p>
            <div id="menu">
                <!-- 상대 경로 지정 -->
                <?php include "../main/top_menu2.php"; ?>
            </div>
            <!-- end of menu -->
            <p></p>
            
            <?php
ini_set("allow_url_fopen",1);

include "./simplehtmldom_1_9_1/simple_html_dom.php";

$data=file_get_html("https://www.daum.net");

$subject= $data->find("ul.list_txt");

foreach($subject as $b){
    
    ?>
    <pre>
    <?php echo $b;?>
    </pre>
    
    <?php
    
}
?>
        </div>
        <!-- end of wrap -->
    </body>
</html>