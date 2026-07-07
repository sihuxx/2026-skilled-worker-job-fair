const title = $(".current-date")
const content = $(".calendar-content")
const list = $(".company-list")
const now = new Date()
let current = new Date()
const dates = "일월화수목금토".split("")

async function render() {

  const year = current.getFullYear()
  const month = current.getMonth()
  const user = await fetch("/api/user").then(res => res.json())
  const companys = await fetch(`/api/companys?year=${year}&month=${month + 1}`).then(res => res.json())

  const byDate = {}
  companys.forEach(c => {
    const d = new Date(c.date).getDate()
    if (!byDate[d]) byDate[d] = []
    byDate[d].push(c)
  })

  list.innerHTML = companys.map(c => {
    return `<div class="company-content">
            <img src="/asset/images/${c.image}">
            <div class="company-info">
                <span>${c.category}</span>
                <h3>${c.name}</h3>
                <p>${c.des}</p>
                <p>${c.date} ${c.start_time} ~ ${c.end_time}</p>
            </div>
        </div>`
  }).join('')
  const isToday = d => now.toDateString() === new Date(year, month, d).toDateString()
  const weeks = dates.map(d => `<div class="week">${d}</div>`).join("")
  const padding = "<div class='day'></div>".repeat(new Date(year, month, 1).getDay())
  const days = Array.from({ length: new Date(year, month + 1, 0).getDate() }, (_, i) => {
    const d = i + 1
    const events = byDate[d] || []
    const company = events.map(c => {
      return `<div class="company">
      <h3 onmouseover="overTooltip(this)" onclick="location.href='/recruit/${c.idx}'" onmouseleave="hideTooltip(this)" data-image="${c.image}">${c.name}</h3>
      <p>${c.start_time} ~ ${c.end_time}</p>
      <div class="btns">
      ${getButton(c)}
      <button onclick="deleteCompany(${c.idx})">삭제</button>
      </div>
      </div>`
    }).join("")
    return `<div class='day ${isToday(d) ? "active" : ""}'> <span>${d}</span> ${user.type == '1' ? `<button class='add-btn' onclick="openModal('company-modal', this)" data-date='${year}-${month + 1}-${d}'>등록</button>` : ''} ${company}</div>`
    company.ondragov
  }).join("")
  title.textContent = `${year}년 ${month + 1}월`
  content.innerHTML = weeks + padding + days
}

$(".prev-btn").onclick = () => { current.setMonth(current.getMonth() - 1); render() }
$(".next-btn").onclick = () => { current.setMonth(current.getMonth() + 1); render() }


async function submitCompany() {
  const formData = new FormData()
  formData.append("name", $("[name='name']").value)
  formData.append("des", $("[name='des']").value)
  formData.append("date", $("[name='date']").value)
  formData.append("start_time", $("[name='start_time']").value)
  formData.append("end_time", $("[name='end_time']").value)
  formData.append("category", $("[name='category']").value)
  formData.append("file", $("[name='file']").files[0])

  const res = await fetch("/addCompany", {
    method: "post",
    body: formData
  })
  hideModal('company-modal')
  render()
}

async function deleteCompany(idx) {
  const ok = confirm("삭제하시겠습니까?");
  if (!ok) return
  const res = await fetch(`/deleteCompany?idx=${idx}`, {
    method: "post"
  })
  render()
}

function getButton(el) {
  const now = new Date()
  const start = new Date(`${el.date}T${el.start_time}`)
  const end = new Date(`${el.date}T${el.end_time}`)
  if (now < start) {
    return `<button class='status-btn' disabled>대기</button>`
  } else if (now >= start && now <= end) {
    return `<button class='status-btn' onclick="openChat(${el.idx})">개설</button>`
  } else {
    return `<button class='status-btn' disabled>종료</button>`
  }
}

function overTooltip(e) {
  const tooltip = $(".tooltip")
  tooltip.style.display = 'block'
  const y = e.offsetY
  const x = e.offsetX
  tooltip.src = "/asset/images/" + e.dataset.image
  tooltip.style.left = e.offsetLeft + e.offsetWidth + "px"
  tooltip.style.top = e.offsetTop + "px"
}

function hideTooltip(e) {
  const tooltip = $(".tooltip")
  tooltip.style.display = 'none'
}

$("[name='end_time']").onchange = checkTime
$("[name='start_time']").onchange = checkTime

function checkTime() {
  const start = $("[name='start_time']").value
  const end = $("[name='end_time']").value
  if (end && start && end <= start) {
    alert("종료시간은 시작시간 이후로만 등록할 수 있습니다.")
    $("[name='end_time']").value = ""
  }
}

function openChat(idx) {
  location.href = `/chat/${idx}`
}

$("[name='file']").onchange = e => {
  const file = e.target.files[0]
  const allowed = ["png", "jpg"]
  const ext = file.name.split(".").pop().toLowerCase()
  if (!allowed.includes(ext)) {
    alert("png 또는 jpg 파일만 등록 가능합니다")
    e.target.value = ""
    return
  }
}

render()

// async function data() {
//   const data = await fetch("/asset/json/job_fair.json").then(res => res.json())

//   const esc = str => String(str).replace(/'/g, "''")

//   const sql = data.map(row => `
// INSERT INTO companys (idx, name, des, image, date, start_time, end_time, category)
// VALUES (${row.idx}, '${esc(row.company_name)}', '${esc(row.description)}', '${esc(row.company_image)}', '${row.event_date}', '${row.start_time}', '${row.end_time}', '${row.category}');
// `).join("")

//   console.log(sql)
// }