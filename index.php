<!--처음 메인 페이지-->
<?php
session_start();  // session 열기

$tns = "
(DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE))
 )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902736';
$password = '1215';
$searchWord = $_GET['searchWord'] ?? '';
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
      <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
              <!-- Bootstrap CSS -->
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
                      crossorigin="anonymous">
              <link rel="stylesheet" type="text/css" href="index.css?v=<%=System.currentTimeMillis() %>">
              <!--font-->
              <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/moonspam/NanumSquare/master/nanumsquare.css">

                          <title>chem's 서재</title>
</head>
<body>
  <?php
  session_start();
  ?>
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
  <br>
  <h2 class="text">전자책 자료검색</h2>
  <br>
  <form class="row">
    <div class="col-10">
      <label for="searchWord" class="visually-hidden">Search Word</label>
      <input type="text" class="form-control" id="searchWord" name="searchWord" placeholder="검색어 입력" value="<?= $searchWord ?>">
    </div>
    <div class="col-auto text-end">
      <button type="submit" class="btn btn-primary mb-3" id="searchBtn">검색</button>
      <button type="button"  onclick="location.href='detail_search.php'" class="btn btn-primary mb-3" >상세검색</button>
    </div>
  </form>
  <table class="table table-bordered text-center ">
    <thead>
      <tr class="th">
        <th>제목</th>
        <th>출판사</th>
        <th>출판일</th>
        <th>대출</th>
        <th>예약</th>
      </tr>
    </thead>
    <tbody>
<?php
$stmt = $conn -> prepare("SELECT ISBN, TITLE, PUBLISHER, YEAR FROM EBOOK WHERE LOWER(TITLE) LIKE '%' || :searchWord || '%' ORDER BY TITLE");
$stmt -> execute(array($searchWord));
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
?>
      <tr>
        <td><a href="bookview.php?ISBN=<?= $row['ISBN'] ?>"><?= $row['TITLE'] ?></a></td>
        <td><?= $row['PUBLISHER'] ?></td>
        <td><?= $row['YEAR'] ?></td>
        <form class="form1" method="post" action="rent.php">
          <td><input type="text" name="ISBN" value="<?= $row['ISBN'] ?>" hidden><button type="submit" name="rentBtn">대출</button></td>
        </form>
        <form class="form2" action="reserve.php" method="post">
          <td><input type="text" name="ISBN" value="<?= $row['ISBN'] ?>" hidden><button type="submit" name="reserveBtn" formation="reserve.php">예약</button></a></td>
        </form>
      </tr>
<?php
}
?>
    </tbody>
  </table>
  <br>
</div>
</body></html>

<!--로고에 있는 그림 출처-->
<!--
<div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
-->
