<?php
$members = db::fetchAll("select * from room_peoples r inner join users u on r.user_idx = u.idx where r.room_idx = '$idx'");
?>

<section class="section chat-section">
  <div class="inner-content">
    <div class="drawing-content">
      <p>그림판</p>
      <canvas height="380" width="300" class="drawing"></canvas>
    </div>
    <div class="chat-content">
      <p>채팅</p>
      <div class="chating"></div>
      <input type="text" class="chat-input" placeholder="채팅을 입력해주세요">
    </div>
    <div class="member-content">
      <p>참가인원</p>
      <div class="member-list">
        <?php foreach ($members as $member) { ?>
          <div class="user">
            <span><?= $member->type == 1 ? "관리자" : "일반회원" ?></span>
            <p><?= $member->id ?></p>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</section>

<script>
  const roomIdx = <?= json_encode($idx) ?>;
</script>
<script src="/js/lib.js"></script>
<script src="/js/chat.js"></script>