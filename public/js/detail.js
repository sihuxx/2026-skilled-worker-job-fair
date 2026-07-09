const fileInput = $("#fileInput")
const companyImage = $(".company-image")

fileInput.onchange = async e => {
  const file = e.target.files[0];
  const allowed = ["png", "jpg"];
  const ext = file.name.split(".").pop().toLowerCase();
  if (!allowed.includes(ext)) {
    alert("png 또는 jpg 파일만 등록 가능합니다");
    e.target.value = "";
    return;
  }

  const formData = new FormData();
  formData.append("file", fileInput.files[0])

  const res = await fetch(`/updateImage?idx=${companyIdx}`, {
    method: "post",
    body: formData
  })
  const data = await res.json()
  companyImage.src = `/asset/images/${data}`

}
