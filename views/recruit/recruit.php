<?php
$user = ss();
$companys = db::fetchAll("select * from companys order by date asc");
?>

<section class="main">
    <div class="calendar">
        <img src="" class="tooltip">
        <div class="calendar-header">
            <button class="prev-btn">이전</button>
            <div class="current-date"></div>
            <button class="next-btn">다음</button>
        </div>
        <div class="calendar-content"></div>
    </div>
    <div class="company-list"></div>
</section>

<div class="modal company-modal">
    <form class="modal__card" action="/addCompany" method="post" enctype="multipart/form-data">
        <label>기업 이름: <input type="text" name="name" placeholder="기업 이름을 입력해주세요" required></label>
        <label>기업 소개: <textarea name="des" placeholder="기업 소개를 입력해주세요" required></textarea></label>
        <label>기업 이미지: <input type="file" name="file" required></label>
        <label>면접 시작시간: <input type="time" min="07:00" name="start_time" placeholder="면접 시작시간을 입력해주세요" required></label>
        <label>면접 종료시간: <input type="time" name="start_time" placeholder="면접 종료시간을 입력해주세요" required></label>
        <label>채용 분야: <select name="category">
                <option value="IT">IT</option>
                <option value="서비스">서비스</option>
                <option value="제조">제조</option>
            </select></label>
        <button>등록</button>
    </form>
</div>


<script src="/js/lib.js"></script>
<script src="/js/recruit.js"></script>