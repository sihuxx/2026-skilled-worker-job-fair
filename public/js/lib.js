const $ = e => document.querySelector(e)
const $$ = e => [...document.querySelectorAll(e)]
const newEl = (e,t) => Object.assign(document.createElement(e), t)
function openModal(name) {
    $(`.${name}`).style.visibility = 'visible'
    $(`.${name}`).style.opacity = 1
}
function hideModal(name) {
    $(`.${name}`).style.visibility = 'hidden'
    $(`.${name}`).style.opacity = 0
}