<?php
// 반납하는 php
session_start();
include '../db_connect.php';

$isbn = $_POST['ISBN'];

// 반납
$stmt =  $conn -> prepare("UPDATE EBOOK SET CNO = :cno, EXTTIMES = :exitTimes, DATERENTED = :dateRented, DATEDUE = :dateDue WHERE ISBN = :isbn");
$stmt -> bindValue(":cno", null);
$stmt -> bindParam(":isbn", $isbn);
$stmt -> bindValue(":exitTimes", null);
$stmt -> bindValue(":dateRented", null);
$stmt -> bindValue(":dateDue", null);
$stmt -> execute();
$row = $stmt -> fetch(PDO::FETCH_ASSOC);

echo "<script>alert('반납되었습니다!');</script>";
echo "<script>location.href='mypage_index.php'</script>";
 ?>
