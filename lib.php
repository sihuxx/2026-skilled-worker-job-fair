<?php

function views($page, $data = [])
{
  extract($data);
  require_once "../views/template/header.php";
  require_once "../views/$page.php";
  require_once "../views/template/footer.php";
}
function move($page, $msg = false)
{
  if ($msg) echo "<script>alert('$msg')</script>";
  echo "<script>location.href = '$page'</script>";
}
function back($msg = false)
{
  if ($msg) echo "<script>alert('$msg')</script>";
  echo "<script>history.back()</script>";
}
function ss() {
  return $_SESSION["ss"] ?? false;
}