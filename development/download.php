<?php session_start(); ?>
<?php
	if(!isset($_SESSION["userid"])) {
	print("
	 <script>
	     window.alert('로그인 후 이용해 주세요.');
	     history.go(-1);
	  </script>
		");
	 exit;
	}
         
 $num=$_REQUEST["num"];
 $real_name=$_REQUEST["real_name"];
 $show_name=$_REQUEST["show_name"];
 $file_type=$_REQUEST["file_type"];
 
 $upload_dir = $_SERVER['DOCUMENT_ROOT'].'/freeboard/img/';   //물리적 저장위치   
 
 $file_path = $upload_dir.$real_name;
     
    if(file_exists($file_path) )
    { 
      // 파일을 여는 방식 rb
	$fp = fopen($file_path,"rb"); 

        if( $file_type ) 
           { 
		Header("Content-type: application/x-msdownload"); 
                Header("Content-Length: ".filesize($file_path));     
                Header("Content-Disposition: attachment; filename=$show_name");   
                Header("Content-Transfer-Encoding: binary"); 
		Header("Content-Description: File Transfer"); 
                header("Expires: 0"); 
            } 
            else 
            { 
                if(preg_match("/(MSIE 5.0|MSIE 5.1|MSIE 5.5|MSIE 6.0)/i", $HTTP_USER_AGENT)) 
                { 
                    Header("Content-type: application/octet-stream"); 
                    Header("Content-Length: ".filesize($file_path)); 
                   Header("Content-Disposition: attachment; filename=$show_name");   
                   Header("Content-Transfer-Encoding: binary");   
                   Header("Expires: 0");   
                 } 
                else 
                 { 
                   Header("Content-type: file/unknown");     
                   Header("Content-Length: ".filesize($file_path)); 
                   Header("Content-Disposition: attachment; filename=$show_name"); 
                   Header("Content-Description: PHP3 Generated Data"); 
                   Header("Expires: 0"); 
                 } 
             } 
 
		if(!fpassthru($fp)) 
		fclose($fp); 
	}
 ?>