<?php
session_start();
$tns = "
  (DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE))
  )
    ";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902736';
$password = '1215';
$isbn = $_GET['ISBN'];
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
  }
$stmt = $conn -> prepare("SELECT TITLE, PUBLISHER, YEAR FROM EBOOK WHERE ISBN = ? ");
$stmt -> execute(array($isbn));
$title = '';
$publisher = '';
$year = '';
$author = '';
if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    $title = $row['TITLE'];
    $publisher = $row['PUBLISHER'];
    $year = $row['YEAR'];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
  <!--CSS-->
  <link rel="stylesheet" type="text/css" href="bookview.css?v=<%=System.currentTimeMillis() %>">
  <!--font-->
  <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/moonspam/NanumSquare/master/nanumsquare.css">
  <title>Book VIEW</title>
</head>
<body>
  <!--nav 시작-->
  <!--사이트 로고 & 카테고리(SEARCH, MYPAGE, LOGIN, LOGOUT)-->
  <div class="nav">
      <div class="nav-title"><a href="index.php" ><img src="logo.PNG"></a></div>
      <div class="nav-item-right">
        <div class="nav-item"><a href="index.php">SEARCH</a></div>
        <div class="nav-item"><a href="./mypage/mypage_index.php">MYPAGE</a></div>
        <div class="nav-item"><a href="./login/login.php">LOG IN</a></div>
        <div class="nav-item"><a href="./login/logout.php" id="logoutBtn">LOG OUT</a></div>
        <div class="nav-item-margin"></div>
    </div>
  </div>
  <!--nav 끝-->

<div class="container">
  <br><br><br>
  <h2>전자책 자료검색 > 도서 상세 화면</h2>
  <br><br>
  <table class="table table-bordered text-center">
    <tbody>
      <tr>
        <td class="gray">제목</td>
        <td><?= $title ?></td>
      </tr>
      <tr>
        <td class="gray">출판사</td>
        <td><?= $publisher ?></td>
      </tr>
      <tr>
        <td class="gray">출판년도</td>
        <td><?= $year ?></td>
      </tr>
      <?php
        $stmt = $conn -> prepare("SELECT AUTHOR FROM AUTHORS WHERE ISBN = :isbn ORDER BY AUTHOR");
        $stmt -> bindParam(":isbn", $isbn);
        $stmt -> execute();
        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
          $author = $author . $row['AUTHOR'] . ", ";
        }
        $author = substr($author, 0, -2);
        ?>
      <tr>
        <td class="gray">저자</td>
        <td><?= $author ?></td>
      </tr>
    </tbody>
  </table>
<?php
}
?>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <a href="index.php" class="btn btn-primary">도서목록</a>
    </div>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</html>
