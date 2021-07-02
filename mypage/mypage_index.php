<?php
session_start();
include '../db_connect.php';
// 로그인 상태인지 확인
// 로그인 안되어있으면 로그인하라는 알림창
if(!isset($_SESSION['user_cno'])) {
  echo "<script>alert('로그인 먼저 해주세요');</script>";
  echo "<script>location.href='../login/login.php'</script>";
} else {
$name = $_SESSION['user_name'];
$rent_count = 0;
$reserve_count = 0;

// 대출 권수
$stmt =  $conn -> prepare("SELECT COUNT(*) FROM EBOOK WHERE CNO = :cno");
$cno = $_SESSION['user_cno'];
$stmt -> bindParam(":cno", $cno);
$stmt -> execute();
$row = $stmt -> fetch(PDO::FETCH_ASSOC);
$rent_count = $row['COUNT(*)'];

// 예약 권수
$stmt =  $conn -> prepare("SELECT COUNT(*) FROM RESERVE WHERE CNO = :cno");
$cno = $_SESSION['user_cno'];
$stmt -> bindParam(":cno", $cno);
$stmt -> execute();
$row = $stmt -> fetch(PDO::FETCH_ASSOC);
$reserve_count = $row['COUNT(*)'];

 ?>
<!DOCTYPE html>
<!--mypage-->
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
            crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="mypage_index.css?v=<%=System.currentTimeMillis() %>">
      <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/moonspam/NanumSquare/master/nanumsquare.css">
    <title>MYPAGE</title>
  </head>
  <body>

    <!--nav 시작-->
    <!--사이트 로고 & 카테고리(HOME, MYPAGE, LOGIN, LOGOUT)-->
    <div class="nav">
        <div class="nav-title"><a href="../index.php" ><img src="../logo.PNG"></a></div>
        <div class="nav-item-right">
          <div class="nav-item"><a href="../index.php">SEARCH</a></div>
          <div class="nav-item"><a href="mypage_index.php" id="mypageA">MYPAGE</a></div>
          <div class="nav-item"><a href="../login/login.php">LOG IN</a></div>
          <div class="nav-item"><a href="../login/logout.php" id="logoutBtn">LOG OUT</a></div>
          <div class="nav-item-margin"></div>
      </div>
    </div>
    <!--nav 끝-->

    <!--내용-->
    <div class="container">
      <!--사용자 정보 나타내는 table-->
      <div class="userInfo">
        <table class="table table-bordered text-center userTable">
          <tbody>
            <tr class="name">
              <td class="gray">이름</td>
              <td><?= $name ?></td>
            </tr>
            <tr>
              <td class="gray">대출 권수</td>
              <td><?= $rent_count ?>권</td>
            </tr>
            <tr>
              <td class="gray">예약 권수</td>
              <td><?= $reserve_count ?>권</td>
            </tr>
          </tbody>
        </table>
      </div>
      <br><br>
      <!--대출도서 나타내는 table-->
      <div class="rent">
        <h2>대출도서</h2>
        <table class="rentTable">
            <thead class="gray">
              <tr>
                <th>제목</th>
                <th>대출날짜</th>
                <th>반납날짜</th>
                <th>연장횟수</th>
                <th>반납</th>
                <th>연장</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $stmt = $conn -> prepare("SELECT ISBN, TITLE, PUBLISHER, YEAR, CNO, EXTTIMES, DATERENTED, DATEDUE FROM EBOOK WHERE CNO = :cno ORDER BY TITLE");
              $stmt -> bindParam(":cno", $cno);
              $stmt -> execute();
              while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
              ?>
              <tr>
                <td><a href="../bookview.php?ISBN=<?= $row['ISBN'] ?>"><?= $row['TITLE'] ?></a></td>
                <td><?= $row['DATERENTED'] ?></td>
                <td><?= $row['DATEDUE'] ?></td>
                <td><?= $row['EXTTIMES'] ?></td>
                <form class="form1" method="post" action="return.php">
                  <td><input type="text" name="ISBN" value="<?= $row['ISBN'] ?>" hidden><button type="submit" name="returnBtn">반납</button></td>
                </form>
                <form class="form2" action="extension.php" method="post">
                  <td><input type="text" name="ISBN" value="<?= $row['ISBN'] ?>" hidden><button type="submit" name="extBtn" >연장</button></a></td>
                </form>
              </tr>
              <?php
              }
              ?>
            </tbody>
        </table>
        <br><br>
      </div>

      <hr>
      <br>
      <!--예약도서 나타내는 table-->
      <div class="reserve">
        <h2>예약도서</h2>
        <table class="reserveTable">
          <thead class="gray">
            <tr>
              <th>제목</th>
              <th>예약날짜</th>
              <th>대출</th>
              <th>예약취소</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $conn -> prepare("SELECT E.ISBN, E.TITLE, R.DATETIME FROM EBOOK E, RESERVE R WHERE R.CNO = :cno AND E.ISBN = R.ISBN ORDER BY E.TITLE");
            $stmt -> bindParam(":cno", $cno);
            $stmt -> execute();
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
              <td><a href="../bookview.php?ISBN=<?= $row['ISBN'] ?>"><?= $row['TITLE'] ?></a></td>
              <td><?= $row['DATETIME'] ?></td>
              <form class="form1" method="post" action="../rent.php">
                <td><input type="text" name="ISBN" value="<?= $row['ISBN'] ?>" hidden><button type="submit" name="rentBtn">대출</button></td>
              </form>
              <form class="form2" action="reserveCancel.php" method="post">
                <td><input type="text" name="ISBN" value="<?= $row['ISBN'] ?>" hidden><button type="submit" name="reserveBtn" formation="reserve.php">취소</button></a></td>
              </form>
            </tr>
            <?php
          }}
            ?>
          </tbody>
        </table>
        <br><br>
      </div>
    </div>
</body>
</html>
