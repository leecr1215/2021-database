<?php
session_start();
include '../db_connect.php';

$isbn = $_POST['ISBN'];
$cno = $_SESSION['user_cno'];

// 현재 cno와 isbn을 이용해 RESERVE 테이블에서 삭제
$stmt =  $conn -> prepare("DELETE FROM RESERVE WHERE ISBN = :isbn AND CNO = :cno");
$stmt -> bindParam(":isbn", $isbn);
$stmt -> bindParam(":cno", $cno);
$stmt -> execute();
$row = $stmt -> fetch(PDO::FETCH_ASSOC);

echo "<script>alert('예약이 삭제되었습니다.');</script>";
echo "<script>location.href='mypage_index.php'</script>";

 ?>
