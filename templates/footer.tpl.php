<footer>
  <div class="container">
    <div class="row p-5">
      <div class="col-12 col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
        <p>Contact email</p>
        <a class="w-100" href="mailto:tung.42@gmail.com">tung.42@gmail.com</a>        
        <p class="mt-3"><i class="fal fa-copyright"></i> Copyright <a target="_blank" href="https://tungpham42.info/">Tung Pham</a></p>
      </div>
      <div class="col-12 col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
        <ul class="list-unstyled">
          <li>
            <a class="home" href="/"><i class="fal fa-home-alt"></i> Home</a>
          </li>
          <li>
            <a class="about" href="/about-us/"><i class="fal fa-info-square"></i> About Us</a>
          </li>
          <li>
            <a class="bmi" href="/body-mass-index/"><i class="fal fa-weight"></i> Body Mass Index</a>
          </li>
          <li>
          <li>
            <a class="contact" href="/contact-us/"><i class="fal fa-at"></i> Contact Us</a>
          </li>
          <li>
<?php
include 'lang.php';
?>
          </li>
          <li>
            <a target="_blank" href="https://jooble.org/">US jobs</a>
          </li>
          <li>
            <a target="_blank" class="buy" href="https://www.codester.com/items/23917/yet-another-biorhythm-calculator-php?ref=tungpham"><i class="fad fa-shopping-cart"></i> BUY</a>
          </li>
        </ul>
      </div>
      <div class="col-12 col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
        <p>Find us on social media</p>
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
        <h5 class="modal-title">AdBlock detected</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Sure, ad-blocking software does a great job at blocking ads, but it also blocks some useful and important features of our website. For the best possible site experience please take a moment to disable your AdBlocker.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
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