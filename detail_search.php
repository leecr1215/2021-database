<?php
session_start();
include 'db_connect.php';

// 검색 항목에 대한 변수
$searchKeyword1 = $_GET['searchKeyword1'] ?? '';
$searchKeyword2 = $_GET['searchKeyword2'] ?? '';
$searchKeyword3 = $_GET['searchKeyword3'] ?? '';
$searchPublishStartYear = $_GET['searchPublishStartYear'] ?? '0000';
$searchPublishEndYear = $_GET['searchPublishEndYear'] ?? '9999';
$searchOrder = $_GET['searchOrder'] ?? '';

 ?>

<!DOCTYPE html>
<html lang="ko" >
	<head>
		<meta charset="utf-8">
		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
		<!--CSS-->
		<link rel="stylesheet" type="text/css" href="detail_search.css?v=<%=System.currentTimeMillis() %>">
		<!--font-->
		<link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/moonspam/NanumSquare/master/nanumsquare.css">
		<title>상세검색</title>
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
		  <h2>전자책 자료검색 > 상세 검색</h2>
		  <br><br>
			<div class="searchArea row">
				<form id="searchForm" name="searchForm" method="get">
								<table class="detailSearchTbl table table-bordered">
									<tbody>
										<tr>
											<th class="text-center middle thread-light" scope="row" rowspan="3">검색항목</th>
											<td>
												<div class="fnInputBox">
													<select id="searchCondition1" name="searchCondition1" title="검색 항목 선택" class="form-ele fix">
													<option value="TITLE" selected="selected">서명</option>
													</select>
													<input id="searchKeyword1" name="searchKeyword1" title="검색어 입력" class="form-ele full" type="text" value="<?= $searchKeyword1 ?>">
													<select id="searchOperator1" name="searchOperator1" title="검색조건 선택" class="form-ele fix">
														<option value="INTERSECT">AND</option>
														<option value="UNION">OR</option>
														<option value="MINUS">NOT</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="fnInputBox">
													<select id="searchCondition2" name="searchCondition2" title="검색 항목 선택" class="form-ele fix">
														<option value="AUTHOR" selected="selected">저자</option>
													</select>
													<input id="searchKeyword2" name="searchKeyword2" title="검색어 입력" class="form-ele full" type="text" value="<?= $searchKeyword2 ?>">
													<select id="searchOperator2" name="searchOperator2" title="검색조건 선택" class="form-ele fix">
														<option value="INTERSECT">AND</option>
														<option value="UNION">OR</option>
														<option value="MINUS">NOT</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="fnInputBox">
													<select id="searchCondition3" name="searchCondition3" title="검색 항목 선택" class="form-ele fix">
													<option value="PUBLISHER" selected="selected">출판사</option>
													</select>
													<input id="searchKeyword3" name="searchKeyword3" title="검색어 입력" class="form-ele full" type="text" value="<?= $searchKeyword3 ?>">
													<select id="searchOperator3" name="searchOperator3" title="검색조건 선택" class="form-ele fix">
														<option value="INTERSECT">AND</option>
														<option value="UNION">OR</option>
														<option value="MINUS">NOT</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<th class="text-center" scope="row">발행년도</th>
											<td>
												<input type="text" id="searchPublishStartYear" name="searchPublishStartYear" class="form-ele short" title="발행시작년도 입력" type="text" placeholder="시작 발행년도" value="<?= $searchPublishStartYear ?>"> ~
												<input type="text" id="searchPublishEndYear" name="searchPublishEndYear"  class="form-ele short" title="발행종료년도 입력" type="text" placeholder="끝 발행년도" value="<?= $searchPublishEndYear ?>">
											</td>
										</tr>
										<tr>
											<th class="text-center" scope="row"><label for="searchSort">정렬조건</label></th>
											<td>
												<select name="searchOrder" id="searchOrder" title="정렬순서 선택" class="form-ele auto min">
													<option value="DESC" selected="selected">내림차순</option>
													<option value="ASC">오름차순</option>
												</select>
											</td>
										</tr>
										</tbody>
									</table>
									<br>
									<div class="buttonArea col text-center">
										<input type="reset" name="resetBtn" value="초기화" class="btn btn-secondary">
										<button type="submit" id="searchBtn" class="btn btn-primary">검색</button>
									</div>
						</form>
			</div>
			<br><br>
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

			// AND OR NOT에 대한 변수
			$searchOperator1 = $_GET['searchOperator1'] ?? '';
			$searchOperator2 = $_GET['searchOperator2'] ?? '';
			$searchOperator3 = $_GET['searchOperator3'] ?? '';

			$stmt = $conn -> prepare(
				"SELECT ISBN
				FROM (SELECT * FROM EBOOK WHERE LOWER(TITLE) LIKE '%' || :title || '%'
					{$searchOperator2}
				 SELECT * FROM EBOOK WHERE LOWER(PUBLISHER) LIKE '%' || :publisher || '%'
				  {$searchOperator3}
				 SELECT * FROM EBOOK WHERE TO_CHAR(YEAR, 'YYYY') BETWEEN :startD AND :endD)
				 {$searchOperator3}
				 SELECT ISBN FROM AUTHORS WHERE LOWER(AUTHOR) LIKE '%' || :author || '%'
				 ORDER BY ISBN {$searchOrder}");

			$stmt -> bindParam(":title", $searchKeyword1);
			$stmt -> bindParam(":author", $searchKeyword2);
			$stmt -> bindParam(":publisher", $searchKeyword3);
			$stmt -> bindParam(":startD", $searchPublishStartYear);
			$stmt -> bindParam(":endD", $searchPublishEndYear);
			$stmt -> execute();


			while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {

				$st =  $conn -> prepare("SELECT ISBN, TITLE, PUBLISHER, YEAR FROM EBOOK WHERE ISBN = :isbn2");
				$isbn2 = $row['ISBN'];
				$st -> bindParam(":isbn2", $isbn2);
				$st -> execute();
				$rr = $st -> fetch(PDO::FETCH_ASSOC);
			?>
			<tr>
				<td><a href="bookview.php?ISBN=<?= $rr['ISBN'] ?>"><?= $rr['TITLE'] ?></a></td>
				<td><?= $rr['PUBLISHER'] ?></td>
				<td><?= $rr['YEAR'] ?></td>
				<form class="form1" method="post" action="rent.php">
					<td><input type="text" name="ISBN" value="<?= $rr['ISBN'] ?>" hidden><button type="submit" name="rentBtn">대출</button></td>
				</form>
				<form class="form2" action="reserve.php" method="post">
					<td><input type="text" name="ISBN" value="<?= $rr['ISBN'] ?>" hidden><button type="submit" name="reserveBtn" formation="reserve.php">예약</button></a></td>
				</form>
			</tr>
		<?php
		}
		 ?>

	 </tbody>
 </table>



		</div>
	</body>
</html>
