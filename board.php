<?php
$conn = mysqli_connect( //사용자 입장
  'localhost', 
  'user',
  'password1234',
  'template'); 

  if ($_SERVER['REQUEST_METHOD'] === 'POST') { // 만약 서버 입장에 메시지를 전송하면
    if (isset($_POST['title']) && isset($_POST['contents'])) { //isset으로 변수가 준비되었는지 확인. post로 title과 contents가 전송됨. 만약 준비가 되었음이 확인된다면 밑의 전체 코드 실행
      $title = mysqli_real_escape_string($conn, $_POST['title']); //mysql과 연결할 때 string을 escape 상태로 만듬
      $contents = mysqli_real_escape_string($conn, $_POST['contents']);
    
      if (trim($title) !== '' && trim($contents) !== '') {// 만약 title 혹은 contents에 공백이 있다면 공백 문자 자르기. 공백 " " 을 !== 로 삭제해버림. 
        $sql = "INSERT INTO board (title, contents, created) VALUES ('$title', '$contents', NOW())"; //보드에 title, contents, created를 삽입. 값은 mysql에 insert할 값들
        $result = mysqli_query($conn, $sql);//쿼리와 거시기 함
    
        if ($result === false) {//만약 결과가 잘못되면 에러 출력
          echo '에러';
          error_log(mysqli_error($conn));
        } else {
          header('Location: board.php'); //에러 없으면 board.php 파일을 열고 탈출
          exit();
        }
      } else {
        echo '입력값이 공백입니다.'; //만약 텍스트가 없다면 공백이라고 알려주기
      }
    }
  }

$sql = "SELECT * FROM board";
$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="index.css">
    <title>Board</title>
  </head>
  <body>
    <h1>게시판</h1>
   
    <h3>게시글을 작성하세요.</h3>
    <form action="board.php" method="POST">
      <p><input type="text" name="title" placeholder="제목."></p>
      <p><input type="text" name="contents" placeholder="내용."></p>      
      <p><input type="submit"></p>
    </form>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="image" accept="image/*" />
      <button type="submit">Upload</button>
    </form>

    <h2>글 목록</h2>
    <?php
    while($row = mysqli_fetch_array($result)) {
      echo '<h3>'.$row['title'].'</h3>';
      echo $row['created'].'<br>';
      echo $row['contents'].'<br><br>';
    }
    ?>


  </body>
</html>
