<header class="site-header shadow-lg fixed-top">
  <div class="container mx-auto">
    <div class="row align-items-center px-2">
      <a class="navbar-brand mr-auto my-0" href="/"><img src="<?php echo $cdn_url; ?>/static/img/app-icons/icon-64.png" class="biorhythm-logo" alt="biorhythm-logo"><span>Biorhythm</span></a>
      <div class="menu-toggle open" title="Menu"></div>
      <nav class="navbar py-0">
        <ul class="nav navbar-nav">
          <li class="pt-4">
            <a class="home" href="/">Home</a>
          </li>
          <li class="pt-4">
            <a class="about" href="/about-us/">About Us</a>
          </li>
          <li class="pt-4">
            <a class="bmi" href="/body-mass-index/">Body Mass Index</a>
          </li>
          <li class="pt-4">
            <a class="contact" href="/contact-us/">Contact Us</a>
          </li>
          <li class="pt-4">
            <a target="_blank" href="/blog/">Blog</a>
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