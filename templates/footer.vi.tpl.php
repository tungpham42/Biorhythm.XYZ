<footer>
  <div class="container">
    <div class="row p-5">
      <div class="col-12 col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
        <p>Email liên hệ</p>
        <a class="w-100" href="mailto:tung.42@gmail.com">tung.42@gmail.com</a>
        <p class="mt-3"><i class="fal fa-copyright"></i> Bản quyền <a target="_blank" href="https://tungpham42.info/">Phạm Tùng</a></p>
      </div>
      <div class="col-12 col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
        <ul class="list-unstyled">
          <li>
            <a class="home" href="/vi/"><i class="fal fa-home-alt"></i> Trang chủ</a>
          </li>
          <li>
            <a class="about" href="/vi/gioi-thieu/"><i class="fal fa-info-square"></i> Giới thiệu</a>
          </li>
          <li>
            <a class="bmi" href="/vi/chi-so-khoi-co-the/"><i class="fal fa-weight"></i> Chỉ số khối cơ thể</a>
          </li>
          <li>
            <a class="contact" href="/vi/lien-he/"><i class="fal fa-at"></i> Liên hệ</a>
          </li>
          <li>
<?php
include 'lang.php';
?>
          </li>
          <li>
            <a target="_blank" class="buy" href="https://www.codester.com/items/23917/yet-another-biorhythm-calculator-php?ref=tungpham"><i class="fad fa-shopping-cart"></i> MUA</a>
          </li>          
          <li>
            <a target="_blank" href="https://jooble.org/">US jobs</a>
          </li>
        </ul>
      </div>
      <div class="col-12 col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
        <p>Tìm chúng tôi trên mạng xã hội</p>
        <a class="w-100 mr-2 display-4" target="_blank" href="https://www.facebook.com/NhipSinhHocBiorhythm/"><i class="fab fa-facebook-square rounded"></i></a>
        <a class="w-100 mr-2 display-4" target="_blank" href="https://twitter.com/BiorhythmChart/"><i class="fab fa-twitter-square"></i></a>
      </div>
    </div>
  </div>
</footer>
<div class="modal fade" id="AdBlockModal" tabindex="-1" role="dialog" aria-label="AdBlock" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title">Phát hiện AdBlock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Đương nhiên, phần mềm chặn quảng cáo thực hiện công việc tuyệt vời trong việc chặn quảng cáo, nhưng nó cũng chặn một số tính năng hữu ích và quan trọng của trang web của chúng tôi. Để có trải nghiệm trang web tốt nhất có thể, vui lòng dành chút thời gian để tắt AdBlocker của bạn.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<script>
$('.menu-toggle').on('click', function(){
  if ($(this).hasClass('open')) {
    $(this).removeClass('open').removeClass('close').addClass('close');
  } else if ($(this).hasClass('close')) {
    $(this).removeClass('close').removeClass('open').addClass('open');
  }
});
// Function called if AdBlock is not detected
function adBlockNotDetected() {
//	alert('AdBlock is not enabled');
}
// Function called if AdBlock is detected
function adBlockDetected() {
	$('#AdBlockModal').modal();
}

// Recommended audit because AdBlock lock the file 'blockadblock.js' 
// If the file is not called, the variable does not exist 'blockAdBlock'
// This means that AdBlock is present
if(typeof FuckAdBlock === 'undefined') {
	adBlockDetected();
} else {
	fuckAdBlock.onDetected(adBlockDetected);
	fuckAdBlock.onNotDetected(adBlockNotDetected);
	// and|or
	fuckAdBlock.on(true, adBlockDetected);
	fuckAdBlock.on(false, adBlockNotDetected);
	// and|or
	fuckAdBlock.on(true, adBlockDetected).onNotDetected(adBlockNotDetected);
}

// Change the options
//blockAdBlock.setOption('checkOnLoad', false);
// and|or
//blockAdBlock.setOption({
//	debug: true,
//	checkOnLoad: false,
//	resetOnEnd: false
//});
</script>
<?php
include template('to-top');
?>