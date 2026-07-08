const $ = e => document.querySelector(e)
const $$ = e => [...document.querySelectorAll(e)]
const newEl = (e, t) => Object.assign(document.createElement(e), t)

function openModal(name, el) {
    $(`.${name}`).style.visibility = 'visible'
    $(`.${name}`).style.opacity = 1
    if (el && el.dataset.date) {
        $(".date-input").value = el.dataset.date
    }
}
function hideModal(name) {
    $(`.${name}`).style.visibility = 'hidden'
    $(`.${name}`).style.opacity = 0
}

async function openChat(idx) {
    const res = await fetch(`/addRoom?idx=${idx}`, {
        method: "post",
    })
    const data = await res.json()
    joinChat(data.idx)
}
async function joinChat(idx) {
    const res = await fetch(`/joinRoom?idx=${idx}`, {
        method: "post"
    })
    location.href = `/room/${idx}`
}

async function deleteCompany(idx) {
    const ok = confirm("삭제하시겠습니까?");
    if (!ok) return
    const res = await fetch(`/deleteCompany?idx=${idx}`, {
        method: "post"
    })
    location.href = '/recruit'
}

function downloadFiles(image) {
    const files = image.split(",")
    files.forEach(file => {
        newEl("a", { href: `/asset/notices/${file}`, download: file }).click()
    })
}