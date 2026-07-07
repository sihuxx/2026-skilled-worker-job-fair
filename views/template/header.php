<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>제61회 숙련기술인 채용박람회 | 기술로 그리는 내일</title>
  <meta name="description" content="대한민국 산업의 근간인 숙련기술인과 우수 기업을 잇는 제61회 숙련기술인 채용박람회.">
  <link rel="stylesheet" href="/css/style.css">
</head>

<?php
$user = ss();
?>

<body>

  <!-- ============ HEADER ============ -->
  <header class="header">
    <div class="wrap header__inner">
      <input type="checkbox" id="nav-toggle" class="nav-toggle" aria-hidden="true">

      <a href="/" class="logo" aria-label="숙련기술인 채용박람회 홈으로">
        <span class="logo__mark" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M12 2 3 6.5v5C3 17 7 21 12 22c5-1 9-5 9-10.5v-5L12 2Z" stroke="#fff" stroke-width="1.6" stroke-linejoin="round" />
            <path d="m8.5 12 2.3 2.3L15.8 9" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </span>
        <span class="logo__name">
          <span class="logo__sub">SKILLED WORKER</span>
          <strong>숙련기술인 채용박람회</strong>
        </span>
      </a>

      <nav class="nav" aria-label="주요 메뉴">
        <label for="nav-toggle" class="nav__close" aria-label="메뉴 닫기">×</label>
        <ul class="nav__list">
          <li><a href="/">메인</a></li>
          <li><a href="/sub">박람회소개</a></li>
          <li><a href="/recruit">온라인채용면접</a></li>
          <li><a href="#gallery">우수기업</a></li>
          <li><a href="#">채용공고</a></li>
        </ul>
        <div class="nav__auth">
          <a href="#login" class="btn btn--ghost">로그인</a>
          <!-- <a href="#signup" class="btn btn--solid">회원가입</a> -->
        </div>
      </nav>

      <label for="nav-toggle" class="nav-overlay" aria-hidden="true"></label>

      <div class="auth">
        <?php if(!empty($user)) { ?>
          <a href="/logout" class="btn btn--ghost">로그아웃</a>
        <?php } else { ?>
          <a href="#login" class="btn btn--ghost">로그인</a>
        <a href="#signup" class="btn btn--solid">회원가입</a>
        <?php } ?>
        <!-- <label for="nav-toggle" class="nav-toggle-label" aria-label="메뉴 열기"><span></span></label> -->
      </div>
    </div>
  </header>

  <!-- ============ LOGIN MODAL ============ -->
  <div class="modal" id="login" role="dialog" aria-modal="true" aria-label="로그인">
    <form method="POST" action="/login" class="modal__card">
      <div class="modal__head">
        <div class="modal__title">로그인<span>박람회 서비스를 이용하려면 로그인하세요</span></div>
        <a href="#" class="modal__close" aria-label="닫기">×</a>
      </div>
      <div class="field"><label for="li-id">아이디</label><input id="li-id" name="id" type="text" placeholder="아이디를 입력하세요" required></div>
      <div class="field"><label for="li-pw">비밀번호</label><input id="li-pw" name="pw" type="password" placeholder="비밀번호를 입력하세요" required></div>
      <div class="role">
        <input type="radio" name="type" value="1" id="li-admin"><label for="li-admin" required>관리자</label>
        <input type="radio" name="type" value="0" id="li-user" checked><label for="li-user" required>일반회원</label>
      </div>
      <button class="btn btn--solid btn--block">로그인하기</button>
      <!-- <p class="modal__swap">아직 회원이 아니신가요? <a href="#signup">회원가입</a></p> -->
    </form>
  </div>

  <!-- ============ SIGNUP MODAL ============ -->
  <!-- <div class="modal" id="signup" role="dialog" aria-modal="true" aria-label="회원가입">
    <div class="modal__card">
      <div class="modal__head">
        <div class="modal__title">회원가입<span>기술로 그리는 내일, 대한민국을 잇다</span></div>
        <a href="#" class="modal__close" aria-label="닫기">×</a>
      </div>
      <div class="field"><label for="su-id">아이디</label><input id="su-id" type="text" placeholder="사용할 아이디"></div>
      <div class="field"><label for="su-pw">비밀번호</label><input id="su-pw" type="password" placeholder="비밀번호"></div>
      <div class="field"><label for="su-name">이름</label><input id="su-name" type="text" placeholder="이름"></div>
      <div class="role">
        <input type="radio" name="su-role" id="su-admin"><label for="su-admin">관리자</label>
        <input type="radio" name="su-role" id="su-user" checked><label for="su-user">일반회원</label>
      </div>
      <button class="btn btn--solid btn--block">가입하기</button>
      <p class="modal__swap">이미 계정이 있으신가요? <a href="#login">로그인</a></p>
    </div>
  </div> -->