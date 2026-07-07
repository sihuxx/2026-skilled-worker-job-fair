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