const canvas = $(".paints")
const chatContent = $(".chats")
const ctx = canvas.getContext('2d')
const companyList = await fetch(`/api/allCompany`).then(res => res.json())

const $companys = $$(".company-list .company")
const $dragZone = $(".banner-grid")
const $banner = $$(".banner-grid .banner")

let banners = Array(15).fill(null)

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

$companys.forEach((company, i) => {
  company.setAttribute("draggable", true)
  company.ondragstart = e => {
    e.dataTransfer.setData("idx", company.dataset.idx)
    e.dataTransfer.setData('type', 'from-list');
  }
})

document.addEventListener('dragstart', (e) => {
  if (e.target.matches('.draggable-image')) {
    e.dataTransfer.setData('idx', e.target.dataset.idx);
    e.dataTransfer.setData('type', 'from-banner');
  }
})

$dragZone.ondragover = e => e.preventDefault()

$banner.forEach((box, dropIndex) => {
  box.ondragover = e => e.preventDefault()

  box.ondrop = e => {
    e.preventDefault()
    e.stopPropagation()

    const idx = e.dataTransfer.getData("idx") 
    const type = e.dataTransfer.getData("type")
    if (!idx) return

    const from = banners.findIndex(b => b == idx)
    if (from === dropIndex) return

    if (type === "from-list" && from !== -1) {
      alert("중복된 기업입니다")
      return
    }

    if (from !== -1) {
      banners[from] = banners[dropIndex]
      banners[dropIndex] = idx
    } else {
      if (banners[dropIndex] != null) {
        banners.splice(dropIndex, 0, idx)
        banners = banners.slice(0, 15)
      } else {
        banners[dropIndex] = idx
      }
    }

    bannerRender()
  }
})

async function bannerRender(idx) {
  $banner.forEach((box, i) => {
    const companyIdx = banners[i]
    if (!companyIdx) { box.innerHTML = ""; return; }
    const company = companyList.find(c => c.idx == companyIdx)
    box.innerHTML = `
    <img class="draggable-image" data-idx="${companyIdx}" draggable="true" src="/asset/images/${company.image}">
    <p>${company.name}</p>    
    <button class="delete-btn"  data-idx="${companyIdx}">삭제</button>
    `
    box.querySelector(".delete-btn").onclick = () => {
      banners[i] = null
      bannerRender()
    }
  })
}

$(".save-btn").onclick = async => {
  const formData = new FormData()
  banners.filter(b => b != null).forEach(idx => {
    formData.append("banners[]", idx)
  })
  await fetch("/saveBanner", {
    method: "post",
    body: formData
  })
}