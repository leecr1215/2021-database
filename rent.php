<?php
include 'db_connect.php';
session_start();

$isbn = $_POST['ISBN'];

// 로그인 상태인지 확인
// 로그인 안되어있으면 로그인하라는 알림창
if(!isset($_SESSION['user_cno'])) {
  echo "<script>alert('로그인 먼저 해주세요');</script>";
  echo "<script>location.href='./login/login.php'</script>";
} else {
  // 로그인 된 상태
  // 1. db에서 이 책 대출한 사람 있는지 확인
  $stmt =  $conn -> prepare("SELECT CNO FROM EBOOK WHERE ISBN = :isbn");
  $stmt -> bindParam(":isbn", $isbn);
  $stmt -> execute();
  $rent_check = $stmt -> fetch(PDO::FETCH_ASSOC);

  //2. 이 회원이 빌릴 수 있는지 확인
  $stmt =  $conn -> prepare("SELECT COUNT(*) FROM EBOOK WHERE CNO = :cno");
  $cno = $_SESSION['user_cno'];
  $stmt -> bindParam(":cno", $cno);
  $stmt -> execute();
  $row = $stmt -> fetch(PDO::FETCH_ASSOC);
  $count = $row['COUNT(*)'];


  if($rent_check['CNO']==null && $count < 3 ) { // 빌릴 수 있음.
    // 현재 회원이 해당 책 대출했다고 ebook table 업데이트

    $exitTimes = 0; // 연장 횟수
    $dateRented = date("Y/m/d");  // 현재 시각이 빌린 시간
    $dateDue = date("Y/m/d", strtotime(date("Y/m/d")."+10days"));

    $stmt =  $conn -> prepare("UPDATE EBOOK SET CNO = :cno, EXTTIMES = :exitTimes, DATERENTED = :dateRented, DATEDUE = :dateDue WHERE ISBN = :isbn");
    $stmt -> bindParam(":cno", $cno);
    $stmt -> bindParam(":isbn", $isbn);
    $stmt -> bindParam(":exitTimes", $exitTimes);
    $stmt -> bindParam(":dateRented", $dateRented);
    $stmt -> bindParam(":dateDue", $dateDue);
    $stmt -> execute();
    $row = $stmt -> fetch(PDO::FETCH_ASSOC);

    // 현재 이책 예약중이면 예약에서 제거
    $stmt =  $conn -> prepare("DELETE FROM RESERVE WHERE ISBN = :isbn AND CNO = :cno");
    $stmt -> bindParam(":isbn", $isbn);
    $stmt -> bindParam(":cno", $cno);
    $stmt -> execute();
    $row = $stmt -> fetch(PDO::FETCH_ASSOC);

    echo "<script>alert('대출되었습니다!');</script>";
    echo "<script>location.href='index.php'</script>";

  } else { // 빌릴 수 없음
    echo "<script>alert('책이 대출 중이거나 회원님의 대출 권수가 3권입니다.');</script>";
    echo "<script>location.href='index.php'</script>";
  }
}
 ?>
