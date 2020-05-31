<?php
session_start();
?>
 <div id="logo"><a href="../index.php">issueInside</a></div>
    <div id="moto">이슈가 모여 있는 곳!</div>
    <div id="top_login">
		<?php
		
		//2020.05.18+(s) 쿠키 - 자동 로그인 추가 부분
		if(isset($_COOKIE["userid"]) && isset($_COOKIE["name"]) && isset($_COOKIE["nick"]) && isset($_COOKIE["level"]) && isset($_COOKIE["pw"])){

        		// id, password 가 member 테이블에 존재하는 경우는 로그인한다
        		$_SESSION["userid"]=($_COOKIE["userid"]);
        		$_SESSION["name"]=($_COOKIE["name"]);
        		$_SESSION["nick"]=($_COOKIE["nick"]);
        		$_SESSION["level"]=($_COOKIE["level"]);
    
    
		}
		//2020.05.18+(e)

		if (!isset($_SESSION["userid"])) {
		?>
    		<a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
    	<?php
		} else {
		?>
    		<?= $_SESSION["nick"] ?> | <a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?= $_SESSION["userid"] ?>">정보수정</a>
    	<?php
		}
		?>
	</div>
	<script>
    var toDoWhenClosing = function () {
        $.ajax({type: "POST", url: "/var/www/html/freeboard/login/logout.php", async: false});
        return;
    };
    window.addEventListener("beforeunload", function (e) {
        if (closing_window) {
            toDoWhenClosing();
        }
    });
</script>