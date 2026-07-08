$("#companyImage").onchange = e => {
  const file = e.target.files[0];
  const allowed = ["png", "jpg"];
  const ext = file.name.split(".").pop().toLowerCase();
  if (!allowed.includes(ext)) {
    alert("png 또는 jpg 파일만 등록 가능합니다");
    e.target.value = "";
    return;
  }
}