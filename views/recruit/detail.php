<?php
$company = db::fetch("select * from companys where idx = '$idx'");
$notices = db::fetchAll("select * from notices where company_idx = '$idx' order by type desc, date desc");
$today = date("Y-m-d");
$time = date("H:i:s");
$user = ss();
?>

<section class="section detail-section">
  <div class="detail-content">
    <div class="status-content">
      <?php if ($today < $company->date) { ?>
        <button disabled>대기</button>
      <?php } else if ($time >= $company->start_time && $time <= $company->end_time) { ?>
        <?php if ($user->type == 1) { ?>
          <button onclick="openChat(<?= $company->idx ?>)">개설</button>
        <?php } else { ?>
          <button onclick="openChat(<?= $company->idx ?>)">참여</button>
        <?php } ?>
      <?php } else { ?>
        <button disabled>종료</button>
      <?php } ?>
      <button onclick="deleteCompany(<?= $company->idx ?>)">삭제</button>
    </div>
    <div class="company">
      <input type="file" hidden id="fileInput" onchange="fileTypeCheck(this)">
      <label for="fileInput" style="cursor:pointer">
        <img class="company-image" src="/asset/images/<?= $company->image ?>" alt="">
      </label>
      <div class="company-info">
        <span><?= $company->category ?></span>
        <h3><?= $company->name ?></h3>
        <p><?= $company->des ?></p>
        <p>면접 날짜: <?= $company->date ?></p>
        <p>면접 시간: <?= $company->start_time ?> ~ <?= $company->end_time ?></p>
      </div>
    </div>
  </div>
  <div class="notice-content">
    <div class="notice-list">
      <div class="notice-header">
        <button class="notice-add-btn" onclick="openModal('notice-modal')">공지작성</button>
      </div>
      <?php foreach ($notices as $notice) {
        $images = explode(",", $notice->image);
      ?>
        <div class="notice">
          <div class="notice-info">
            <?php if ($notice->image != null) { ?>
              <span onclick="downloadFiles('<?= $notice->image ?>')">
                <img src="/asset/file.png">
              </span>
              <h3 onclick="openModal('noticeModal<?= $notice->idx ?>')"><?= $notice->title ?></h3>
            <?php } ?>
          </div>
          <p><?= $notice->date ?></p>
        </div>
        <div class="notice-detail-modal modal noticeModal<?= $notice->idx ?>">
          <div class="notice-detail">
            <div class="modal__head">
              <a href="#" class="modal__close" onclick="hideModal('noticeModal<?= $notice->idx ?>')" aria-label="닫기">×</a>
            </div>
            <?php if ($notice->type == 1) { ?>
              <span class="type-tag">중요</span>
            <?php } ?>
            <div class="notice-info">
              <h3><?= $notice->title ?></h3>
              <p><?= $notice->date ?></p>
            </div>
            <p><?= $notice->des ?></p>
            <?php foreach ($images as $img) { ?>
              <div class="img-box">
                <img src="/asset/notices/<?= $img ?>">
                <a href="/asset/notices/<?= $img ?>" download="<?= $img ?>"><?= $img ?></a>
              </div>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>

<div class="modal notice-modal">
  <form class="modal__card default-form" action="/addNotice" method="post" enctype="multipart/form-data">
    <div class="modal__head">
      <div class="modal__title">공지 등록<span>공지를 등록하세요</span></div>
      <a href="#" class="modal__close" onclick="hideModal('notice-modal')" aria-label="닫기">×</a>
    </div>
    <input type="hidden" name="idx" value="<?= $idx ?>">
    <label>제목: <input type="text" name="title" placeholder="제목을 입력해주세요" required></label>
    <label>내용: <textarea name="des" placeholder="내용을 입력해주세요" required></textarea></label>
    <label>첨부파일: <input type="file" name="file[]" multiple></label>
    <label>중요공지: <input type="checkbox" value="1" name="type"></label>
    <button>등록</button>
  </form>
</div>

<script>
  const companyIdx = <?= json_encode($company->idx) ?>;
</script>
<script src="/js/lib.js"></script>
<script src="/js/detail.js"></script>