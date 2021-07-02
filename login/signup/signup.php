<?php
session_start();
?>
<!DOCTYPE html>
<!--회원가입 html -->
<html lang="ko">

<head>
  <meta charset="utf-8">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <title>회원가입</title>
  <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/moonspam/NanumSquare/master/nanumsquare.css">
  <link rel="stylesheet" href="signup.css">
</head>

<body>
  <div class="body">
    <div class="up">
      <p id="signupP">회원가입</p>
      <hr>
      <br>
      <form class="row" action="signup_result.php" method="post">
        <label for="name" id="nameLabel">이름: </label><input type="text" name="name" id="name" placeholder="이름" required><br><br>
        <label for="cno" id="cnoLabel">회원번호: </label><input type="text" name="cno" id="cno" placeholder="회원번호" required><br><br>
        <label for="password" id="pwLabel">비밀번호: </label><input type="password" name="password" id="password" placeholder="비밀번호" required><br><br>
        <label for="email" id="emailLabel">이메일: </label><input type="email" name="email" id="email" placeholder="이메일" required><br><br>
        <br><br>
        <button type="submit" name="button" id="okBtn" class="btn">확인</button>
        <button type="button" name="button" id="cancelBtn" class="btn">취소</button>
      </form>
    </div>

  </div>
  <script type="text/javascript" src="signup.js?v=<%=System.currentTimeMillis() %>"></script>
</body>

</html>
