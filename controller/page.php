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
get("/recruit/{idx}", function ($idx) {
  views("recruit/detail", ["idx" => $idx]);
});
get("/room/{idx}", function ($idx) {
  views("room", ["idx" => $idx]);
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
  $fileName = $file["name"];
  $path = "/asset/images/" . $file["name"];
  if (db::fetch("select * from companys where date = '$date' and end_time > '$start_time' and start_time < '$end_time'")) {
    back("다른 면접 일정과 겹칩니다.");
  } else {
    if (move_uploaded_file($file["tmp_name"], ".$path")) {
      db::exec("insert into companys (name, des, image, date, start_time, end_time, category) values ('$name', '$des', '$fileName', '$date', '$start_time', '$end_time', '$category')");
    }
  }
});
post("/deleteCompany", function () {
  extract($_GET);
  db::exec("delete from companys where idx = '$idx'");
});
post('/addNotice', function () {
  extract($_POST);
  $type = $type ?? 0;
  $images = [];
  $file = $_FILES["file"];
  foreach ($file["tmp_name"] as $i => $tmp) {
    if (!$tmp) continue;
    $path = "/asset/notices/" . $file["name"][$i];
    move_uploaded_file($tmp, ".$path");
    $images[] = $file["name"][$i];
  }
  $image = implode(",", $images);

  if ($image) {
    db::exec("insert into notices (title, des, image, type, company_idx) values ('$title', '$des', '$image', '$type', '$idx')");
    move("/recruit/$idx", "공지사항 추가 성공");
  } else {
    db::exec("insert into notices (title, des, type, company_idx) values ('$title', '$des', '$type', '$idx')");
    move("/recruit/$idx", "공지사항 추가 성공");
  }
});
post("/addRoom", function () {
  extract($_GET);
  $room = db::fetch("select * from rooms where company_idx = '$idx'");
  if ($room) {
    echo json_encode($room);
  } else {
    db::exec("insert into rooms (company_idx) values ('$idx')");
    echo json_encode($room);
  }
});
