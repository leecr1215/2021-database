<?php
session_start();

// db연결
$tns = "
(DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE))
 )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902736';
$password = '1215';
$cno = $_GET['cno'] ?? '';
$pw = $_GET['pw'] ?? '';
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}
?>

<!DOCTYPE>
<!--로그인 html-->
<html>
	<head>
		<title>로그인</title>
		<meta charset="utf-8">
		 <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
		<link rel="stylesheet" type="text/css" href="login.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/moonspam/NanumSquare/master/nanumsquare.css">
</head>
 <body>
   <?php
   //현재 로그인 되어있는지 확인.

   if(isset($_SESSION['user_name'])) {
     $login_check = TRUE;
   }else{
     $login_check = FALSE;
   }

   // 로그인 되어있는 상태면 알림띄우고 그대로
   if($login_check) {
     echo "<script>alert('이미 로그인 하셨습니다');</script>";
     echo "<script>location.href='../index.php'</script>";
   } else{

   ?>
<div class="body">
  <div class="logo">
    <a href="../index.php"><img src="loginLogo.PNG" alt="로고 사진" id="logoImg"></a>
  </div>
  <br>
  <form class="row" method="post" action="login_result.php">
    <input type="text" name="cno" id="cno" placeholder="회원번호"  required><br><br>
    <input type="password" name="password" id="password" placeholder="비밀번호" required><br><br>
    <button type="submit" name="loginBtn" id="loginBtn">로그인</button>
  </form>

		 <br><br><br>
		 <div class="box">
			 <p>아직도 회원이 아니신가요?</p>
			 <p><a href="./signup/signup.php">회원가입하러 가기</a></p>
		 </div>
</div>
</body>
<?php
}
?>
</html>
