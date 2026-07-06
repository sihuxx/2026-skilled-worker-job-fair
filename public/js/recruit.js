const title = $(".current-date")
const content = $(".calendar-content")
const now = new Date()
let current = new Date()
const dates = "일월화수목금토".split("")

async function render() {
  const year = current.getFullYear()
  const month = current.getMonth()
  const companys = await fetch(`/api/companys?year=${year}&month=${month + 1}`).then(res => res.json())

  const byDate = {}
  companys.forEach(c => {
    const d = new Date(c.date).getDate()
    if (!byDate[d]) byDate[d] = []
    byDate[d].push(c)
  })
  const isToday = d => now.toDateString() === new Date(year, month, d).toDateString()
  const weeks = dates.map(d => `<div class="week">${d}</div>`).join("")
  const padding = "<div class='day'></div>".repeat(new Date(year, month, 1).getDay())
  const days = Array.from({ length: new Date(year, month + 1, 0).getDate() }, (_, i) => {
    const d = i + 1
    const events = byDate[d] || []
    const company = events.map(c => {
      return `<div class="company">
      <h3>${c.name}</h3>
      <p>${c.start_time} ~ ${c.end_time}</p>
      <div class="btns">
      <button>상태</button>
      <button>삭제</button>
      </div>
      </div>`
    }).join("")
    return `<div class='day ${isToday(d) ? "active" : ""}'><span>${d}</span> ${company}</div>`
    company.ondragov
  }).join("")
  title.textContent = `${year}년 ${month + 1}월`
  content.innerHTML = weeks + padding + days
}

$(".prev-btn").onclick = () => { current.setMonth(current.getMonth() - 1); render() }
$(".next-btn").onclick = () => { current.setMonth(current.getMonth() + 1); render() }

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