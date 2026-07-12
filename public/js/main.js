const banners = await fetch("/api/mainBanners").then(res => res.json())

$(".banner__row").innerHTML = banners.map(b => `
      <div class="brand">
    <img src="/asset/images/${b.image}">
    <div class="brand__over">
      <span>${b.name}</span>
      <a href="/recruit/${b.company_idx}" class="brand__more">더보기 →</a>
    </div>
  </div>
`).join("")

if(banners.length > 5) {
    const pages = Math.ceil(banners.length / 5)
    let page = 0
    setInterval(() => {
page = (page + 1) % pages
$(".banner__row").style.transform = `translateX(-${page * 100}%)`
    }, 3000)
}