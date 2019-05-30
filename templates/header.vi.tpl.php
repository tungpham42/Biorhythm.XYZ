<header class="site-header shadow-lg fixed-top">
  <div class="container mx-auto">
    <div class="row align-items-center px-2">
      <a class="navbar-brand mr-auto my-0" href="/vi/"><img src="<?php echo $cdn_url; ?>/static/img/app-icons/icon-64.png" class="biorhythm-logo" alt="biorhythm-logo"><span>Nhịp sinh học</span></a>
      <div class="menu-toggle open" title="Trình đơn"></div>
      <nav class="navbar py-0">
        <ul class="nav navbar-nav">
          <li class="pt-4">
            <a class="home" href="/vi/">Trang chủ</a>
          </li>
          <li class="pt-4">
            <a class="about" href="/vi/gioi-thieu/">Giới thiệu</a>
          </li>
          <li class="pt-4">
            <a class="bmi" href="/vi/chi-so-khoi-co-the/">Chỉ số khối cơ thể</a>
          </li>
          <li class="pt-4">
            <a class="contact" href="/vi/lien-he/">Liên hệ</a>
          </li>
          <li class="pt-4">
            <a target="_blank" href="https://biorhythm.xyz/blog/">Blog</a>
          </li>
<?php
include 'lang.php';
?>
        </ul>
      </nav>
    </div>
  </div>
</header>
<script type="text/javascript">
$('.menu-toggle').on('click', function(){
  if ($(this).hasClass('open')) {
    $(this).removeClass('open').removeClass('close').addClass('close');
  } else if ($(this).hasClass('close')) {
    $(this).removeClass('close').removeClass('open').addClass('open');
  }
});
</script>