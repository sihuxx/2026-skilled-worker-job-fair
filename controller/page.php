<?php

get("/", function () {
  views("main");
});
get("/sub", function () {
  views("sub");
});
get("/recruit", function () {
  views("recruit/recruit");
});
post("/login", function () {
  extract($_POST);
  $user = db::fetch("select * from users where id = '$id'");
  if ($user) {
    if ($pw == $user->pw && $type == $user->type) {
      if ($user->token == null) {
        $token = bin2hex(random_bytes(32));
        db::exec("update users set token = '$token' where id = '$id'");
        $_SESSION["ss"] = $user;
        if ($user->last_login == null) {
          db::exec("update users set last_login = now() where id = '$id'");
          move("/", "최초 로그인입니다");
        } else {
          db::exec("update users set last_login = now() where id = '$id'");
          move("/", "$user->last_login");
        }
      } else {
        back(".");
      }
    } else {
      back("회원 정보가 일치하지 않습니다");
    }
  }
});
get("/logout", function () {
  $user = ss();
  db::exec("update users set token = null where idx = '$user->idx'");
  session_destroy();
  move("/", "로그아웃 성공");
});
get("/api/companys", function () {
  extract($_GET);
  $companys = db::fetchAll("select * from companys where year(date) = $year and month(date) = $month order by date asc");
  echo json_encode($companys);
});
get("/api/user", function () {
  $user = ss();
  echo json_encode($user);
});
post("/addCompany", function () {
  extract($_POST);
  $file = $_FILES["file"];
  $path = "/asset/companys/" . $file["name"];
  if (db::fetch("select * from companys where date = '$date' and end_time > '$start_time' and start_time < '$end_time'")) {
    back("다른 면접 일정과 겹칩니다.");
  } else {
    if (move_uploaded_file($file["tmp_name"], ".$path")) {
      db::exec("insert into companys (name, des, image, date, start_time, end_time, category) values ('$name', '$des', '$path', '$date', '$start_time', '$end_time', '$category')");
    }
  }
});
