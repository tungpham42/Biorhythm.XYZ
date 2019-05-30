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
  <title>Giới thiệu - Nhịp sinh học</title>
<?php
include template('head.about');
include template('ga');
?>
  </head>
  <body class="about" data-href="<?php echo base_url(); ?>/vi/gioi-thieu/">
<?php
include template('header.vi');
?>
    <main class="main">
      <div class="container p-5">
        <h2 class="h1-responsivefooter text-center my-4">Giới thiệu</h2>
        <div class="row">
          <p>Nhịp sinh học (tiếng Anh: biorhythm) là một chu trình giả thiết về tình trạng khỏe mạnh hay năng lực sinh lý, cảm xúc, hoặc trí thông minh.</p>
          <p>Cụ thể hơn, lấy một ví dụ, người ta cho rằng có thời điểm một người rất dễ mắc bệnh, còn có lúc khác thì không. Các thời điểm này cứ lặp đi lặp lại rất nhiều lần và có quy luật. Quy luật đó gọi là nhịp sinh học. Và chúng sẽ dao động đều trong khoảng -100% đến 100% trong đồ thị nhịp sinh học (số càng lớn thì càng mạnh).</p>
          <p>Cũng bởi vì vậy nên có rất nhiều lý thuyết cũng như nhiều loại nhịp sinh học khác nhau. Không có gì đảm bảo những loại nhịp sinh học này là chính xác, bởi vì bản thân con người luôn chịu nhiều tác động từ môi trường, và đời sống xã hội. Tuy nhiên, rất nhiều nhà khoa học công nhận 3 loại nhịp sinh học cơ bản là: Sức khỏe (Physical), Tình cảm (Emotional) và Trí tuệ (Intellectual).</p>
          <h3>Các nhịp sinh học cơ bản</h3>
          <p>Biểu đồ nhịp sinh học với 3 đường cơ bản và một đường bổ sung, với trục ngang chỉ thời gian, chính giữa là ngày hiện tại.</p>
          <p>Lý thuyết cổ điển của nhịp sinh học gắn liền với Hermann Swoboda ở đầu thế kỷ 20, ông được cho là người đưa ra chu trình 23 ngày cho nhịp sức khỏe và 28 ngày cho nhịp tình cảm.</p>
          <p>Năm 1920, Alfred Teltschercho rằng chu trình của trí thông minh là 33 ngày.</p>
          <p>Chúng ta sẽ thấy một chuỗi số thú vị: 23-28-33, và số tiếp theo là 38 được cho là chu trình của trực giác.</p>
          <h3>Công thức tính toán</h3>
          <p class="w-100">Do có chu trình đều và lặp lại, với mốc thời gian là ngày sinh, hoàn toàn dễ hiểu với các hàm số sau:</p>
          <ul>
            <li>Sức khỏe: sin(2π t/23)</li>
            <li>Tình cảm: sin(2π t/28)</li>
            <li>Trí tuệ: sin(2π t/33)</li>
            <li>Trực giác: sin(2π t/38)</li>
            <li>Thẩm mỹ: sin(2π t/43)</li>
            <li>Nhận thức: sin(2π t/48)</li>
            <li>Tinh thần: sin(2π t/53)</li>
          </ul>
          <p class="w-100">Với t là thời gian tính từ khi người đó được sinh ra.</p>
          <h3 class="w-100">Ứng dụng</h3>
          <p>Đây là ứng dụng tính toán nhịp sinh học của cơ thể bạn về các mặt:</p>
          <ul>
            <li>Sức khỏe: thể lực, sức mạnh, sự phối hợp các cơ quan trong cơ thể và nó theo dõi tình trạng thể chất và sức khỏe.</li>
            <li>Tình cảm: sự sáng tạo, nhạy cảm, tâm trạng và nhận thức.</li>
            <li>Trí tuệ: sự tỉnh táo, phân tích hoạt động, phân tích vấn đề, bộ nhớ, tiếp nhận thông tin.</li>
            <li>Trực giác: khả năng nhận biết, cảm nhận.</li>
            <li>Thẩm mỹ: sự nhạy cảm về mặt thẩm mỹ, thẩm mỹ của bản thân.</li>
            <li>Nhận thức: thể hiện khả năng cảm nhận được cá tính riêng.</li>
            <li>Tinh thần: vấn đề tâm linh, quan niệm và các hiện tượng thần bí.</li>
          </ul>
          <p>Thực tế đã chứng minh khi chỉ số thấp:</p>
          <ul>
            <li>Đối với chu kỳ tình cảm, thường buồn bực vô cớ.</li>
            <li>Đối với chu kỳ trí tuệ, đó là ngày đãng trí, khả năng tư duy kém.</li>
            <li>Đặc biệt đối với chu kỳ sức khỏe, đó là ngày thường xảy ra tai nạn lao động.</li>
            <li>Đối với hai chu kỳ, số ngày chuyển tiếp trùng nhau chỉ xảy ra một lần trong một năm.</li>
            <li>Ngày trùng hợp đó của ba chu kỳ là ngày xấu nhất, có thể coi là ngày "vận hạn" của mỗi người.</li>
          </ul>
          <p>Việc nghiên cứu về nhịp sinh học thu hút sự quan tâm của rất nhiều nhà khoa học trên thế giới, họ đã nghiên cứu trong nhiều năm.</p>
          <p>Ở các nước phát triển như Nhật, Mỹ… Nhịp sinh học được áp dụng nhiều trong việc sử dụng con người, các phi công, kỹ sư trong hàng không, vũ trụ sẽ được nghỉ ngơi vào thời gian họ có nhịp sinh học nhỏ hơn 50%. Các nhà khoa học cũng tìm ra mối liên hệ giữa nhịp sinh học với tai nạn lao động, và các hiện tượng xảy ra trong đời sống của con người.</p>
          <p>Ngày nay, môn khoa học này đã phát triển và nhịp sinh học là khái niệm được dùng để chỉ khuynh hướng riêng biệt của mỗi người theo từng ngày. Nhịp sinh học đo xu hướng này dựa vào ngày sinh của mỗi người. Nhịp sinh học tính toán các chu kỳ ứng với các khía cạnh khác nhau của con người. Có 3 chu kỳ sinh học cơ bản: sức khỏe, tình cảm và trí tuệ và 4 chu kỳ phụ: trực giác, thẩm mỹ, tinh thần, nhận thức. Chúng hoạt động theo mô hình: bắt đầu bằng 50% vào lúc sinh của bạn và tăng dần, Sau khi đạt đến mức tối đa 100%, chu kỳ giảm xuống tới 0%. Sau đó, chiều hướng sẽ thay đổi và chu kỳ lại chuyển động về phía trên.</p>
          <p>Các ứng dụng về Nhịp sinh học có rất nhiều, trong đó cụ thể nhất là việc phòng tránh tai nạn lao động. Một nghiên cứu của tiến sỹ Toán – Lý người Nga Serik Mazhkenov tiến hành tại một công ty thăm dò dầu khí cho thấy 70% số vụ tai nạn lao động xảy ra với nhân công trùng với những ngày mà một trong 3 nhịp sinh học chính của người đó tiệm cận với mức 0. Và từ đó đưa ra phương hướng giải quyết để giảm tới 29% vụ tai nạn thường gặp.</p>
          <p>Một nghiên cứu ở Nhật Bản trên công ty giao thông Ohmi Railway cũng đã lập các biểu đồ sinh học cho các tài xế lái xe của công ty để họ có sự cảnh giác và phòng tránh. Kết quả tai nạn của các tài xế đã giảm 50% từ năm 1969 đến 1970 tại Tokyo.</p>
          <p>Với ứng dụng hàng ngày, bạn nên xem qua các đường sinh học của mình để có những biện pháp đối phó phù hợp. Ví như nếu nhịp sinh học về cảm xúc ở mức thấp thì bạn rất dễ bị bực bội, cáu kỉnh. Khi đó, nên tìm những không gian thoáng đãng, vui tươi hay trò chuyện với những người vui vẻ. Hoặc nếu Nhịp sinh học Sức khỏe giảm sút, bạn chớ nên làm những việc nặng nhọc hay đòi hỏi thời gian làm kéo dài.</p>
        </div>
      </div>
<?php
include template('adsense');
?>
    </main>
<?php
include template('footer.vi');
?>
  </body>
</html>