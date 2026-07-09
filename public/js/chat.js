const input = $(".chat-input")
const chating = $(".chating")
const memberList = $(".member-list")
const canvas = $("canvas")
const ctx = canvas.getContext('2d')
let painting = false
let lastPaint = 0
let x, y

input.onkeydown = e => {
  if (e.key !== 'Enter') return
  chatAdd(input.value)
}

async function chatAdd(message) {
  const formData = new FormData()
  formData.append('message', input.value)
  formData.append('room_idx', roomIdx)

  const res = await fetch(`/addChat`, {
    method: "post",
    body: formData
  })
  input.value = ""
}

async function paintAdd(startX, startY, endX, endY) {
  const formData = new FormData()
  formData.append("room_idx", roomIdx)
  formData.append("start_x", startX);
  formData.append("start_y", startY);
  formData.append("end_x", endX);
  formData.append("end_y", endY);

  const res = await fetch(`/addPaint`, {
    method: "post",
    body: formData
  })
}

setInterval(async () => {
  const chats = await fetch(`/api/chats?idx=${roomIdx}`).then(res => res.json())
  chating.innerHTML = chats.map(chat => {
    return `<div class="chat">
          <div class="chat-info">
            <p>${chat.user_id}</p>: <p>${chat.message}</p>
          </div>
          <p class="chat-date">${chat.date}</p>
        </div>`
  }).join("")
}, 30)

setInterval(async () => {
  const paints = await fetch(`/api/paints?idx=${roomIdx}`).then(res => res.json())
  const newPaint = paints.slice(lastPaint)

  newPaint.forEach((p, i) => {
    ctx.beginPath()
    ctx.moveTo(p.start_x, p.start_y)
    ctx.lineTo(p.end_x, p.end_y)
    ctx.stroke()
  })

  lastPaint = paints.length
}, 30)

setInterval(async () => {
  const res = await fetch(`/api/rooms?idx=${roomIdx}`).then(res => res.json())

  if (res.is_end == 1) {
    location.href = `/recruit/${res.company_idx}`
  }
}, 30)

setInterval(async () => {
  const users = await fetch(`/api/roomUsers?idx=${roomIdx

    
  }`).then(res => res.json())
  memberList.innerHTML = users.map(user => {
    return `<div class="user">
            <span>${user.type == 1 ? '관리자' : '일반회원'}</span>
            <p>${user.id}</p>
          </div>`
  }).join("")
}, 30)

canvas.onmousedown = e => {
  painting = true;
  [x, y] = [e.offsetX, e.offsetY];
  ctx.beginPath()
}
canvas.onmouseup = () => painting = false
canvas.onmouseleave = () => painting = false
canvas.onmousemove = e => {
  if (painting) {
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();
    paintAdd(x, y, e.offsetX, e.offsetY);
    [x, y] = [e.offsetX, e.offsetY]
  }
  else ctx.moveTo(x, y);
}