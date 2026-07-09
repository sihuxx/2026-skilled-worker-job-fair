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
get("/admin", function() {
  views("admin");
});
get("/api/companys", function () {
  extract($_GET);
  $companys = db::fetchAll("select * from companys where year(date) = $year and month(date) = $month order by date asc");
  echo json_encode($companys);
});
get('/api/companyImage/{idx}', function($idx) {
  $image = db::fetch("select * from companys where idx = '$idx'");
  echo json_encode($image);
});
get("/api/user", function () {
  $user = ss();
  echo json_encode($user);
});
get("/api/chats", function () {
  extract($_GET);
  $chats = db::fetchAll("select u.id as user_id, r.* from room_chats r inner join users u on r.user_idx = u.idx where r.room_idx = '$idx' order by r.date asc");
  echo json_encode($chats);
});
get("/api/paints", function () {
  extract($_GET);
  $paints = db::fetchAll("select * from room_paints where room_idx = '$idx'");
  echo json_encode($paints);
});
get("/api/rooms", function () {
  extract($_GET);
  $room = db::fetch("select * from rooms where idx = '$idx'");
  echo json_encode($room);
});
get("/api/roomUsers", function () {
  extract($_GET);
  $users =  db::fetchAll("select * from room_peoples r inner join users u on r.user_idx = u.idx where r.room_idx = '$idx'");
  echo json_encode($users);
});
get("/api/companyRooms", function () {
  extract($_POST);
  $rooms = db::fetch("select * from rooms where company_idx = '$idx'");
  echo json_encode($rooms);
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
  $user = ss();
  $room = db::fetch("select * from rooms where company_idx = '$idx'");
  if (!$room) {
    if ($user->type == 1) {
      db::exec("insert into rooms (company_idx) values ('$idx')");
      $room = db::fetch("select * from rooms where company_idx = '$idx' order by idx desc limit 1");
      echo json_encode($room);
    } /* else {
      back("아직 방이 개설되지 않았습니다");
    } */
  }
  if ($room->is_end == 1) {
    if ($user->type == 1) {
      db::exec("update rooms set is_end = 0 where idx = '$room->idx'");
      echo json_encode($room);
    } /* else {
      back("아직 방이 개설되지 않았습니다");
    } */
  } else {
    echo json_encode($room);
  }
});
post("/joinRoom", function () {
  extract($_GET);
  $user = ss();
  if (!db::fetch("select * from room_peoples where user_idx = '$user->idx' and room_idx = '$idx'")) {
    db::exec("insert into room_peoples (room_idx, user_idx) values ('$idx', '$user->idx')");
  }
});
post("/addChat", function () {
  extract($_POST);
  $user = ss();
  if ($user) {
    db::exec("insert into room_chats(user_idx, room_idx, message) values ('{$user->idx}', '$room_idx', '$message')");
  } else {
    back("로그인 한 유저만 이용 가능한 기능입니다");
  }
});
post("/addPaint", function () {
  extract($_POST);
  $user = ss();
  if ($user->type == 0) back("관리자만 그림판 이용이 가능합니다");
  db::exec("insert into room_paints (room_idx, start_x, start_y, end_x, end_y) values ('$room_idx', '$start_x', '$start_y', '$end_x', '$end_y')");
});
get("/endRoom/{idx}", function ($idx) {
  $room = db::fetch("select * from rooms where idx = '$idx'");
  db::exec("update rooms set is_end = 1 where idx = '$idx'");
  move("/recruit/$room->company_idx");
});
get("/exitRoom/{idx}", function ($idx) {
  $room = db::fetch("select * from rooms where idx = '$idx'");
  $user = ss();
  db::exec("delete from room_peoples where room_idx = '$idx' and user_idx = '$user->idx'");
  move("/recruit/$room->company_idx");
});
post("/updateImage", function() {
  extract($_GET);
  $file = $_FILES["file"];
  $image = $file["name"];
  if(move_uploaded_file($file["tmp_name"], "./asset/images/$image")) {
    db::exec("update companys set image = '$image' where idx = '$idx'");
    echo json_encode($image);
  }
});