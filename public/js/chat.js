const input = $(".chat-input")
const chating = $(".chating")
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