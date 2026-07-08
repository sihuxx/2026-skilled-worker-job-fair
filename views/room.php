<?php
$user = ss();
$members = db::fetchAll("select * from room_peoples r inner join users u on r.user_idx = u.idx where r.room_idx = '$idx'");
?>

<section class="section chat-section">
  <div class="room-header">
    <?php if ($user->type == 1) { ?>
      <a class="room-end-btn" href="/endRoom/<?= $idx ?>">대화방 종료</a>
    <?php } else { ?>
      <a class="room-end-btn" href="/exitRoom/<?= $idx ?>">퇴장</a>
    <?php } ?>
  </div>
  <div class="inner-content">
    <div class="drawing-content">
      <p>그림판</p>
      <canvas height="600" width="450" class="drawing"></canvas>
    </div>
    <div class="chat-content">
      <p>채팅</p>
      <div class="chating"></div>
      <input type="text" class="chat-input" placeholder="채팅을 입력해주세요">
    </div>
    <div class="member-content">
      <p>참가인원</p>
      <div class="member-list"></div>
    </div>
  </div>
</section>

<script>
  const roomIdx = <?= json_encode($idx) ?>;
</script>
<script src="/js/lib.js"></script>
<script src="/js/chat.js"></script>