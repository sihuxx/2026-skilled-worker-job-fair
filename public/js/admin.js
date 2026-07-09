const canvas = $(".paints")
const chatContent = $(".chats")
const ctx = canvas.getContext('2d')
const companyList = await fetch(`/api/allCompany`).then(res => res.json())

const companysEl = $$(".company-list .company")
const dragZoneEl = $(".banner-grid")
const bannerEl = $$(".banner-grid .banner")

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

companysEl.forEach((company, i) => {
  company.setAttribute("draggable", true)
  company.ondragstart = e => {
    e.dataTransfer.setData("idx", company.dataset.idx)
  }
})

document.addEventListener('dragstart', (e) => {
  if (e.target.matches('.draggable-image')) {
    e.dataTransfer.setData('idx', e.target.dataset.idx);
    e.dataTransfer.setData('type', 'from-template');
  }
})

dragZoneEl.ondragover = e => e.preventDefault()
dragZoneEl.ondrop = (e) => {
  const companyIdx = e.dataTransfer.getData("idx")
  const type = e.dataTransfer.getData('type');

  const companyIdxsArr = Object.values(banners) // 지금까지 배너에 추가한 기업들 idx
  const droppedIndex = bannerEl.indexOf(e.target.closest(".banner"))
  const alreadyExists = companyIdxsArr.some(ban => ban == companyIdx);

  // 드롭한 영역의 기업 인덱스와 현재 드롭한 기업이 인덱스가 같으면 리턴
  if (banners[droppedIndex] == companyIdx) return

  // 지금까지 배너에 추가한 기업 인덱스 중 현재 드롭한 기업의 인덱스가 존재하는지 판단 (Boolean)
  if (type === 'from-template' && alreadyExists) { // 만약 배너 grid 내에서 드래그 앤 드롭 했고, 인덱스가 존재하면
    banners = Object.entries(banners).reduce((arr, [dropIdx, comIdx]) => {
      if (comIdx === companyIdx) { // 만약 드롭한 기업 idx가 순회 돈 기업 idx 와 같으면
        return arr // 해당 누적은 건너뜀
      } else { // 드롭한 각 기업 idx가 다르면
        return { ...arr, [dropIdx]: comIdx } // 그대로 반환 (스프레드)
      }
    }, {})
  } else if (alreadyExists) { // 만약 중복값이면
    return; // 종료
  }

  banners[droppedIndex] = companyIdx // 배너들에 현재 드롭한 요소 추가함
  bannerRender(companyIdx)
}

async function bannerRender(idx) {
  bannerEl.forEach((box, i) => {
    const idx = banners[i]
    if (!idx) { box.innerHTML = ""; return; }
    const company = companyList.find(c => c.idx == idx)
    box.innerHTML = `<img class="draggable-image" data-idx="${idx}" draggable="true" src="/asset/images/${company.image}">`
  })
}