<?php
session_start();
include '../db_connect.php';

$cno = $_POST['cno'];
$pw = $_POST['password'];

$stmt =  $conn -> prepare("SELECT CNO, NAME, PASSWD, EMAIL FROM CUSTOMER WHERE CNO = :cno");
$stmt -> bindParam(":cno", $cno);
$stmt -> execute();

$row = $stmt -> fetch(PDO::FETCH_ASSOC);

if($row!=null){
  // 비밀번호 맞는지 확인
  if($row['PASSWD'] == $pw) {
    // 세션에 저장
    $_SESSION['user_cno'] = $row['CNO'];
    $_SESSION['user_name'] = $row['NAME'];
    $_SESSION['user_email'] = $row['EMAIL'];
    echo "<script>location.href='../index.php'</script>";
  }else {
    echo "<script>alert('비밀번호가 맞지 않습니다.');</script>";
    echo "<script>location.href='login.php'</script>";
  }
}else{
  echo "<script>alert('회원번호가 맞지 않습니다.');</script>";
  echo "<script>location.href='login.php'</script>";
}



 ?>
