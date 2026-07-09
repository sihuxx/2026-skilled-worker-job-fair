const canvas = $(".paints")
const chatContent = $(".chats")
const ctx = canvas.getContext('2d')

const companys = $$(".company-list .company")
const dragZone = $(".banner-grid")
const bannersObject = $$(".banner-grid .banner")
let banners = {}

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


companys.forEach((company, i) => {
  company.setAttribute("draggable", true)
  company.ondragstart = e => {
    e.dataTransfer.setData("idx", company.dataset.idx)

  }
})
dragZone.ondragover = e => e.preventDefault()
dragZone.ondrop = (e) => {
  const idx = e.dataTransfer.getData("idx")

  const bannerValues = Object.values(banners)
  const droppedIndex = bannersObject.indexOf(e.target)

  const type = e.dataTransfer.getData('type');
  const alreadyExists = bannerValues.some(ban => ban == idx);

  if (type === 'from-template' && alreadyExists) {
    banners = Object.entries(banners).reduce((arr, [droppedIndex, companyIdx]) => {
      if (companyIdx === idx) {
        return arr
      } else {
        return {...arr, droppedIndex: companyIdx}
      }
    }, {})
  } else if (alreadyExists) {
    return;
  }

  

  banners[droppedIndex] = idx

  bannerRender(droppedIndex, idx)
}
bannersObject.forEach((banner, i) => {
  banner.setAttribute("draggable", true)
  banner.ondragstart = e => {
     const idx = banner.querySelector('img').dataset.idx
    e.dataTransfer.setData("idx", idx)
  }
})

async function bannerRender(droppedIndex, idx) {
  const res = await fetch(`/api/companyImage/${idx}`).then(res => res.json())
  $('.banner-grid').innerHTML = reduce()
  bannersObject[droppedIndex].innerHTML = `<img class="draggable-image" data-idx="${idx}" draggable="true" src="/asset/images/${res.image}">`
}

// const variable = Boolean({});
// // truthy

// // falsy 

document.addEventListener('dragstart', (e) => {
  if (e.target.matches('.draggable-image')) {
    e.dataTransfer.setData('idx', e.target.dataset.idx);
    e.dataTransfer.setData('type', 'from-template');
  }
})