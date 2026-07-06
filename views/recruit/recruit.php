<?php
$companys = db::fetchAll("select * from companys order by date asc");
?>

<section class="main">
    <div class="calendar">
        <div class="calendar-header">
            <button class="prev-btn">이전</button>
            <div class="current-date"></div>
            <button class="next-btn">다음</button>
        </div>
        <div class="calendar-content"></div>
    </div>
    <div class="company-list"></div>
</section>


<script src="/js/lib.js"></script>
<script src="/js/recruit.js" type="module"></script>