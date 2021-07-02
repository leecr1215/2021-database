<?php
//DB에 로그인하는 코드
$tns = "
(DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE))
 )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902736';
$password = '1215';

try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}
 ?>
