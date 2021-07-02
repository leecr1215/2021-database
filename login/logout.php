<?php
//logout 하는 php
session_start();
session_destroy();  // 세션 한번에 삭제
echo "<script>alert('로그아웃 되었습니다.');</script>";
echo "<script>location.href='../index.php'</script>";
 ?>
