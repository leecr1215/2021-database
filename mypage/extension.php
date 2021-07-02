<?php
// 연장
session_start();
include '../db_connect.php';
$isbn = $_POST['ISBN'];

// 현재 isbn의 exttimes, datedue 구하기.
$stmt =  $conn -> prepare("SELECT EXTTIMES, DATERENTED, DATEDUE FROM EBOOK WHERE ISBN = :isbn");
$stmt -> bindParam(":isbn", $isbn);
$stmt -> execute();
$row = $stmt -> fetch(PDO::FETCH_ASSOC);

$extTimes = $row['EXTTIMES'];

// 날짜 yymmdd에서 yyyymmdd로 변환
$stmt =  $conn -> prepare("SELECT TO_CHAR(DATEDUE, 'YYYY/MM/DD') DATEDUE FROM EBOOK WHERE ISBN = :isbn");
$stmt -> bindParam(":isbn", $isbn);
$stmt -> execute();
$row = $stmt -> fetch(PDO::FETCH_ASSOC);
$dateDue = $row['DATEDUE'];

// 이책을 예약한 사람이 있는지 확인
$stmt =  $conn -> prepare("SELECT CNO FROM RESERVE WHERE ISBN = :isbn");
$stmt -> bindParam(":isbn", $isbn);
$stmt -> execute();
$row = $stmt -> fetch(PDO::FETCH_ASSOC);
$check = 1;
if($row['CNO']!=null) { // 예약자 존재
  $check = 0;
}

// exittimes 증가 가능 여부 구하기
if($extTimes < 2 && $check == 1) { // 증가 가능
  $extTimes = $extTimes + 1;
  $dateDue = date("Y/m/d", strtotime("+10 days", strtotime($dateDue)));
  $cno = $_SESSION['user_cno'];

  $stmt =  $conn -> prepare("UPDATE EBOOK SET EXTTIMES = :extTimes, DATEDUE = :dateDue WHERE ISBN = :isbn");
  $stmt -> bindParam(":isbn", $isbn);
  $stmt -> bindParam(":extTimes", $extTimes);
  $stmt -> bindParam(":dateDue", $dateDue);
  $stmt -> execute();
  $row = $stmt -> fetch(PDO::FETCH_ASSOC);

  echo "<script>alert('연장되었습니다!');</script>";
  echo "<script>location.href='mypage_index.php'</script>";
}else{  // 증가 불가능
  if($check == 0 ) {
    echo "<script>alert('예약자가 있습니다. 연장이 불가능합니다.');</script>";
    echo "<script>location.href='mypage_index.php'</script>";
  }
  if($extTimes >= 2) {
    echo "<script>alert('최대 연장횟수입니다. 연장이 불가능합니다.');</script>";
    echo "<script>location.href='mypage_index.php'</script>";
  }
}

 ?>
