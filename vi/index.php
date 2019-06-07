<?php
error_reporting(-1);
ini_set('display_errors', 'On');
$basepath = realpath($_SERVER['DOCUMENT_ROOT']);
$template_path = $basepath.'/templates/';
require $basepath.'/inc/template.inc.php';
require $basepath.'/inc/init.vi.inc.php';
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
  <meta charset="UTF-8">
  <title>Trang chủ - Nhịp sinh học</title>
<?php
include template('head.home');
include template('ga');
?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.vi.min.js" integrity="sha256-TnMvef6AxT9fg6mNrTsZCgPDkU4g5EyB+tu66oLAI4U=" crossorigin="anonymous"></script>
  </head>
  <body class="home" data-href="<?php echo base_url(); ?>/vi/">
<?php
include template('header.vi');
?>
    <main class="main">
      <div class="container-fluid hero px-0">
        <div class="container">
          <div class="row mx-auto p-5">
            <div class="hero-box mx-auto w-75 shadow-lg rounded">
              <ul class="nav nav-tabs rounded-top">
                <li class="active rounded-top"><a class="rounded-top" data-toggle="tab" href="#calculate">Tính nhịp sinh học</a></li>
                <li class="rounded-top"><a data-disable-interaction="true" data-step="2" data-intro="Chọn mục này để biết các chu kỳ ngủ của bạn" class="rounded-top" data-toggle="tab" href="#sleep_time">Nhịp sinh học ngủ</a></li>
                <li class="rounded-top"><a href="#ghep-cap">Ghép cặp</a></li>
              </ul>
              <div class="tab-content p-4">
                <section id="calculate" class="tab-pane fade in show active">
                  <form class="form-inline mb-3" id="dob-form" action="/vi/#ket-qua">
                    <label class="sr-only" for="dob">Ngày sinh</label>
                    <input data-toggle="tooltip" data-placement="top" data-original-title="Định dạng: YYYY-MM-DD" data-disable-interaction="true" data-step="1" data-intro="Chọn Ngày sinh theo thứ tự năm, tháng, ngày và ấn `Tính` để tính nhịp sinh học của bạn để tìm hiểu thêm về bản thân" type="text" pattern="\d{4}-\d{2}-\d{2}" class="form-control mb-2 mr-sm-2 col-12 col-xl-5 col-lg-5 col-md-4 col-sm-12" name="dob" id="dob" placeholder="Ngày sinh" required="required" value="<?php echo $dob; ?>" data-date-start-view="decade">
                    <a data-toggle="tooltip" data-placement="top" data-original-title="Ấn để bắt đầu" class="btn btn-warning mb-2 mr-xl-2 mr-lg-2 mr-md-2 col-12 col-xl-2 col-lg-2 col-md-2 col-sm-12" onclick="submitDob()">Tính</a>
                    <a data-toggle="tooltip" data-placement="top" data-original-title="Ấn để biết cách dùng" class="btn btn-info mb-2 col-12 col-xl-4 col-lg-4 col-md-5 col-sm-12" id="how-to-use">Dùng làm sao?</a>
                    <button type="submit" class="d-none"></button>
                  </form>
                  <p class="desc my-0">Đây là <b>Máy tính Nhịp sinh học</b>. Sử dụng công cụ này để tìm hiểu thêm về bản thân bạn. Chọn Ngày sinh Dương lịch của bạn với định dạng <b>YYYY-MM-DD</b> (năm-tháng-ngày) bằng công cụ Chọn ngày. Sau đó, nhấn nút "Tính" để tính toán chỉ số <b>Sức khỏe, Tình cảm, Trí tuệ</b> của bạn. Nếu bạn chỉ quan tâm đến <b>Nhịp sinh học ngủ</b>, bạn có thể bỏ qua mục này.</p>
                </section>
                <section id="sleep_time" class="tab-pane fade">
                  <p>Nếu bạn dự định thức dậy lúc</p>
                  <div class="row p-3">
                    <select id="sleep_time_hour" class="custom-select col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <option>Chọn giờ</option>
                      <option>0</option>
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                      <option>6</option>
                      <option>7</option>
                      <option>8</option>
                      <option>9</option>
                      <option>10</option>
                      <option>11</option>
                      <option>12</option>
                      <option>13</option>
                      <option>14</option>
                      <option>15</option>
                      <option>16</option>
                      <option>17</option>
                      <option>18</option>
                      <option>19</option>
                      <option>20</option>
                      <option>21</option>
                      <option>22</option>
                      <option>23</option>
                      <option>24</option>
                    </select>
                    <select id="sleep_time_minute" class="custom-select col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <option>Chọn phút</option>
                      <option>00</option>
                      <option>05</option>
                      <option>10</option>
                      <option>15</option>
                      <option>20</option>
                      <option>25</option>
                      <option>30</option>
                      <option>35</option>
                      <option>40</option>
                      <option>45</option>
                      <option>50</option>
                      <option>55</option>
                    </select>
                  </div>
                  <div>
                    <p>Bạn nên đi ngủ vào một trong những giờ sau:</p>
                    <span id="sleep_time_results">
                      <ul>
                        <li>N/A</li>
                      </ul>
                    </span>
                  </div>
                  <p>Hoặc nếu bạn muốn ngủ ngay bây giờ</p>
                  <a class="btn btn-warning mb-2" id="sleep_now">Ngủ ngay bây giờ!</a>
                  <div>
                    <p>Bạn nên thức dậy vào một trong những giờ sau:</p>
                    <span id="wake_up_time_results">
                      <ul>
                        <li>N/A</li>
                      </ul>
                    </span>
                  </div>
                </section>
              </div>
            </div>
            <script type="text/javascript">
            function isDate(txtDate) {
              var currVal = txtDate;
              if (currVal === '') {
                return false;
              }
              var rxDatePattern = /^(\d{4})(-)(\d{1,2})(-)(\d{1,2})$/; //Declare Regex
              var dtArray = currVal.match(rxDatePattern); // is format OK?

              if (dtArray === null) {
                return false;
              }
              dtMonth = dtArray[3];
              dtDay= dtArray[5];
              dtYear = dtArray[1];    

              if (dtMonth < 1 || dtMonth > 12) {
                return false;
              } else if (dtDay < 1 || dtDay> 31) {
                return false;
              } else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) {
                return false;
              } else if (dtMonth == 2) {
                var isleap = (dtYear % 4 === 0 && (dtYear % 100 !== 0 || dtYear % 400 === 0));
                if (dtDay> 29 || (dtDay ==29 && !isleap)) {
                  return false;
                }
              }
              return true;
            }
            function countChar(string, character) {
              var charRegex = new RegExp(character, 'g');
              return (string.match(charRegex)||[]).length;
            }
            function maskInput(input, textbox, location, delimiter) {
              //Get the delimiter positons
              var locs = location.split(',');
              var locsLength = locs.length;
              var inputLength = input.length;
              //Iterate until all the delimiters are placed in the textbox
              for (var delimCount = 0; delimCount <= locsLength; ++delimCount) {
                for (var inputCharCount = 0; inputCharCount <= inputLength; ++inputCharCount) {
                  //Check for the actual position of the delimiter
                  if (inputCharCount == locs[delimCount]) {
                    //Confirm that the delimiter is not already present in that position
                    if (input.substring(inputCharCount, inputCharCount + 1) != delimiter) {
                      input = input.substring(0, inputCharCount) + delimiter + input.substring(inputCharCount, input.length);
                    }
                  }
                }
              }
              textbox.val(input);
            }
            function maskField(selector) {
              jQuery(selector).on('keypress', function(e) {
                if (e.keyCode != 8 && e.keyCode != 37 && e.keyCode != 38 && e.keyCode != 39 && e.keyCode != 40 && e.keyCode != 46 && countChar(jQuery(selector).val(), '-') < 2) {
                  maskInput(jQuery(selector).val(),jQuery(selector),'4,7','-');
                }
              }).on('keyup', function(e) {
                if (e.which != 8 && e.which != 37 && e.which != 38 && e.which != 39 && e.which != 40 && e.which != 46 && countChar(jQuery(selector).val(), '-') < 2) {
                  maskInput(jQuery(selector).val(),jQuery(selector),'4,7','-');
                }
              }).on('keydown', function(e) {
                if (e.which != 8 && e.which != 37 && e.which != 38 && e.which != 39 && e.which != 40 && e.which != 46 && countChar(jQuery(selector).val(), '-') < 2) {
                  maskInput(jQuery(selector).val(),jQuery(selector),'4,7','-');
                }
              });
            }
            function disableHyphen(fieldName) {
              jQuery('#'+fieldName).on('keypress', function(e) {
                if (e.which == 45) {
                  return false;
                }
              }).on('keyup', function(e) {
                if (e.which == 189 || e.which == 173 || e.which == 109) {
                  return false;
                }
              }).on('keydown', function(e) {
                if (e.which == 189 || e.which == 173 || e.which == 109) {
                  return false;
                }
              });
            }
            function maskDob() {
              maskField('#dob');
            }
            maskDob();
            disableHyphen('dob');
            function submitDob() {
              if (!isEmpty('#dob')) {
                Cookies.set('BIO:remembered_dob', $('#dob').val());
              }
              $('#dob-form').find('[type="submit"]').trigger('click');
            }
            $('#dob-form').on({
              keypress: function(e) {
                if (e.keyCode == 13 || e.which == 13) {
                  submitDob();
                }
              },
              keyup: function(e) {
                if (e.which == 13) {
                  submitDob();
                }
              },
              keydown: function(e) {
                if (e.which == 13) {
                  submitDob();
                }
              }
            }, '#dob');
            $('#dob').datepicker({
              format: 'yyyy-mm-dd',
              defaultViewDate: '<?php echo ($dob != '') ? date('Y-m-d',strtotime($dob)): '1961-09-26'; ?>',
              endDate: '<?php echo date('Y-m-d'); ?>',
              autoclose: true,
              clearBtn: true,
              immediateUpdates: true,
              todayHighlight: true,
              todayBtn: true,
              language: 'vi',
              orientation: 'bottom'
            }).attr('readonly','readonly');
            $('.nav-tabs li a').each(function(){
              $(this).on('click', function(){
                $('.nav-tabs li').removeClass('active');
                if ($(this).parent('li').hasClass('active')) {
                  $(this).parent('li').removeClass('active');
                } else if (!$(this).parent('li').hasClass('active')) {
                  $(this).parent('li').removeClass('active').addClass('active');
                }
              })
            });
            $('#how-to-use').on('click', function(){
              introJs().setOptions({"nextLabel": "Tiếp theo", "prevLabel": "Trước đó", "skipLabel": "Bỏ qua", "doneLabel": "Hoàn tất", "showProgress": true, "showButtons": true, "showBullets": true, "exitOnOverlayClick": false, "hidePrev": true, "hideNext": true, "disableInteraction": true}).start();
            });
            $(function () {
              if (!Modernizr.touch) {
                $('[data-toggle="tooltip"]').tooltip();
              }
            })
            </script>
            <div id="proverb" class="w-75 mx-auto my-1">
<?php
render_proverb($lang_code);
?>
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid adsense px-0 border-top">
        <div class="container mx-auto px-0">
          <div class="row w-100 mx-auto py-5 px-0">
            <ins class="adsbygoogle mb-4 col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 px-0"
              style="display:block"
              data-ad-client="ca-pub-3585118770961536"
              data-ad-slot="3511497384"
              data-ad-format="auto"
              data-full-width-responsive="true"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
          </div>
        </div>
      </div>
      <div id="ket-qua"></div>
      <div class="container-fluid chart px-0">
        <div class="container mx-auto px-0">
          <div class="row w-100 mx-auto p-5 position-relative" id="ajax-chart">
<?php
echo $chart->output_main_chart();
?>
          </div>
        </div>
      </div>
      <div class="container-fluid info px-0 border-top">
        <div class="container mx-auto px-0">
          <div class="row w-100 mx-auto p-5" id="ajax-info">
<?php
echo $chart->output_info();
?>
          </div>
        </div>
      </div>
      <div id="ghep-cap"></div>
      <div class="container-fluid compat px-0 border-top">
        <div class="container mx-auto px-0">
          <div class="row w-100 mx-auto py-5 px-0">
            <div class="px-5 w-100">
              <h2 class="mb-4">Ghép cặp dựa theo nhịp sinh học</h2>
              <section class="rhythms" data-disable-interaction="true" data-step="11" data-intro="Bạn và đối tác (bạn bè, người yêu, vợ chồng) hợp nhau đến mức nào? Chọn Ngày sinh cho hai người.">
                <article class="rhythm physical" title="Sức khỏe: 100%">
                  <h3>Sức khỏe</h3>
                  <div class="percent">100%</div>
                  <div class="percentage">
                    <div class="percentage-bar"></div>
                  </div>
                </article>
                <article class="rhythm emotional" title="Tình cảm: 100%">
                  <h3>Tình cảm</h3>
                  <div class="percent">100%</div>
                  <div class="percentage">
                    <div class="percentage-bar"></div>
                  </div>
                </article>
                <article class="rhythm intellectual" title="Trí tuệ: 100%">
                  <h3>Trí tuệ</h3>
                  <div class="percent">100%</div>
                  <div class="percentage">
                    <div class="percentage-bar"></div>
                  </div>
                </article>
                <article class="rhythm average" title="Trung bình: 100%">
                  <h3>Trung bình</h3>
                  <div class="percent">100%</div>
                  <div class="percentage">
                    <div class="percentage-bar"></div>
                  </div>
                </article>
              </section>
            </div>
            <form class="dates mx-auto" id="date-input">
              <div class="date" data-disable-interaction="true" data-step="12" data-intro="Chọn ngày sinh của bạn">
                <select name="yyyy" data-original-title="Năm" class="custom-select" data-toggle="tooltip" data-placement="bottom"><option value="">Năm</option></select>
                -
                <select name="mm" data-original-title="Tháng" class="custom-select" data-toggle="tooltip" data-placement="bottom"><option value="">Tháng</option></select>
                -
                <select name="dd" data-original-title="Ngày" class="custom-select" data-toggle="tooltip" data-placement="bottom"><option value="">Ngày</option></select>
              </div>
              <span class="separator">+</span>
              <div class="date" data-disable-interaction="true" data-step="13" data-intro="Chọn ngày sinh của đối tác">
                <select name="yyyy2" data-original-title="Năm" class="custom-select" data-toggle="tooltip" data-placement="bottom"><option value="">Năm</option></select>
                -
                <select name="mm2" data-original-title="Tháng" class="custom-select" data-toggle="tooltip" data-placement="bottom"><option value="">Tháng</option></select>
                -
                <select name="dd2" data-original-title="Ngày" class="custom-select" data-toggle="tooltip" data-placement="bottom"><option value="">Ngày</option></select>
              </div>
            </form>
            <script type="text/javascript">
            function daysInMonth(month, year) {
              return new Date(year, month, 0).getDate();
            }
            function updateDays(year, month, day) {
              $(year + ', ' + month).on('change', function() {
                if ($(year).val().length > 0 && $(month).val().length > 0) {
                  $(day).prop('disabled', false);
                  $(day).find('option').remove();
                  var daysInSelectedMonth = daysInMonth($(month).val(), $(year).val());
                  for (var i = 1; i <= daysInSelectedMonth; i++) {
                    i = ('0' + i).slice(-2);
                    $(day).append($("<option></option>").attr("value", i).text(i));
                  }
                }
              });
            }
            </script>
            <script src="<?php echo $cdn_url; ?>/static/js/compat.js?v=7"></script>
            <script type="text/javascript">
            updateDays('select[name="yyyy"]','select[name="mm"]','select[name="dd"]');
            updateDays('select[name="yyyy2"]','select[name="mm2"]','select[name="dd2"]');
            </script>
          </div>
        </div>
      </div>
<?php
include template('adsense');
?>
    </main>
<?php
include template('footer.vi');
?>
    <script>
    manipulateHome();
    manipulateEnd();
    </script>
  </body>
</html>