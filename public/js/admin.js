const canvas = $(".paints")
const chatContent = $(".chats")
const ctx = canvas.getContext('2d')

async function openRoomModal(roomIdx) {
  openModal('room-modal')
  const paints = await fetch(`/api/paints?idx=${roomIdx}`).then(res => res.json())
  const chats = await fetch(`/api/chats?idx=${roomIdx}`).then(res => res.json())

  ctx.clearRect(0, 0, canvas.width, canvas.height)

  paints.forEach((p, i) => {
    ctx.beginPath()
    ctx.moveTo(p.start_x, p.start_y)
    ctx.lineTo(p.end_x, p.end_y)
    ctx.stroke()
  })

  chatContent.innerHTML = chats.map(chat => {
    return `<div class="chat">
          <div class="chat-info">
            <p>${chat.user_id}</p>: <p>${chat.message}</p>
          </div>
          <p class="chat-date">${chat.date}</p>
        </div>`
  }).join("")
}