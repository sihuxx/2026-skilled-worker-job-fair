<?php
$companys = db::fetchAll("select * from companys order by date, start_time")
?>

<section class="section admin-section">
    <div class="interview-content">
        <h3>채용 면접 리스트</h3>
        <div class="interview-grid">
            <?php foreach ($companys as $company) {
                $room = db::fetch("select * from rooms where company_idx = '$company->idx'");
                if ($room) {
                    $room_paints = db::fetchAll("select * from room_paints where room_idx = '$room->idx'");
                    $room_chats = db::fetchAll("select * from room_chats where room_idx = '$room->idx' order by date");
                }
            ?>
                <div class="interview">
                    <img src="/asset/images/<?= $company->image ?>">
                    <div class="interview-info">
                        <span><?= $company->category ?></span>
                        <h3><?= $company->name ?></h3>
                        <p><?= $company->des ?></p>
                        <p><?= $company->date ?></p>
                        <p><?= $company->start_time ?> ~ <?= $company->end_time ?></p>
                        <?php if ($room) { ?>
                            <button onclick="openRoomModal(<?= $room->idx ?>)">대화방 로그</button>
                        <?php } else { ?>
                            <button disabled>대화방 로그</button>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="room-modal modal">
                <div class="room">
                    <div class="modal-head">
                        <button onclick="hideModal('room-modal')">닫기</button>
                    </div>
                    <div class="room-content">
                        <canvas width="380" height="560" class="paints"></canvas>
                        <div class="chats"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="company-list">
        <h3>등록 기업 리스트</h3>
        <div class="company-grid">
            <?php foreach ($companys as $company) { ?>
                <div class="company" onmouseover="overTooltip(this)" data-name="<?= $company->name ?>"><img src="/asset/images/<?= $company->image ?>" alt=""></div>
            <?php } ?>
            <div class="tooltip"></div>
        </div>
    </div>
    <div class="banner-content">
        <h3>배너 관리 리스트</h3>

    </div>
</section>

<script src="/js/lib.js"></script>
<script src="/js/admin.js"></script>