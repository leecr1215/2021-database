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
  // 이 회원이 빌릴 수 있는지 확인 -> 현재 예약한 책이 최대 2권
  $stmt =  $conn -> prepare("SELECT COUNT(*) FROM RESERVE WHERE CNO = :cno");
  $cno = $_SESSION['user_cno'];
  $stmt -> bindParam(":cno", $cno);
  $stmt -> execute();
  $row = $stmt -> fetch(PDO::FETCH_ASSOC);
  $reserve_count = $row['COUNT(*)'];

  // 지금 이책 예약중인지 확인
  $stmt =  $conn -> prepare("SELECT ISBN FROM RESERVE WHERE CNO = :cno");
  $cno = $_SESSION['user_cno'];
  $stmt -> bindParam(":cno", $cno);
  $stmt -> execute();
  $ok = 1;
  while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    if($row['ISBN']==$isbn){  // 이미 이책 예약중
      $ok = 0;
    }
  }



  if($reserve_count < 3 && $ok == 1) { // 빌릴 수 있음.
    // 현재 회원이 해당 책 대출했다고 ebook table 업데이트

    $dateTime = date("Y/m/d");  // 예약 시간

    $stmt =  $conn -> prepare("INSERT INTO RESERVE VALUES (:isbn, :cno, :dTime)");
    $stmt -> bindParam(":cno", $cno);
    $stmt -> bindParam(":isbn", $isbn);
    $stmt -> bindParam(":dTime", $dateTime);
    $stmt -> execute();
    $row = $stmt -> fetch(PDO::FETCH_ASSOC);

    echo "<script>alert('예약되었습니다!');</script>";
    echo "<script>location.href='index.php'</script>";
  } else { // 빌릴 수 없음
    echo "<script>alert('회원님의 현재 예약 권수가 3권입니다.');</script>";
    echo "<script>location.href='index.php'</script>";
  }
}


 ?>
