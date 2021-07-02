<?php
include '../../db_connect.php';

// signup.php에서 받은 값 저장
$cno = $_POST['cno'];
$name = $_POST['name'];
$pw = $_POST['password'];
$email = $_POST['email'];

$stmt =  $conn -> prepare("SELECT CNO, NAME, EMAIL FROM CUSTOMER WHERE CNO = :cno");
$stmt -> bindParam(":cno", $cno);
$stmt -> execute();
$row = $stmt -> fetch(PDO::FETCH_ASSOC);

// 중복 cno 있는지 검사
// 이미 있으면 다시 회원가입 페이지로
if($row != null){
  echo "<script>alert('이미 존재하는 회원번호가 있습니다.');</script>";
  echo "<script>location.href='signup.php'</script>";
}

// 올바른 이메일 주소인지 검사
$checkEmailAdress = filter_var($email, FILTER_VALIDATE_EMAIL);
if($checkEmailAdress != true) {
  echo "<script>alert('올바른 이메일 주소 형식이 아닙니다.');</script>";
  echo "<script>location.href='signup.php'</script>";
}

// db에 넣기
$stmt = $conn -> prepare("INSERT INTO CUSTOMER VALUES (:cno, :name, :pw, :email)");
$stmt -> bindParam(":cno", $cno);
$stmt -> bindParam(":name", $name);
$stmt -> bindParam(":pw", $pw);
$stmt -> bindParam(":email", $email);
$stmt -> execute();

// 회원가입 성공 메시지 후 login 페이지로 돌아감.
echo "<script>alert('회원가입 성공! 환영합니다.');</script>";
echo "<script>location.href='../login.php'</script>";
 ?>
