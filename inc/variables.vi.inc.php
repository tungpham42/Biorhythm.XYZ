<?php
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('max_execution_time', 0);
set_time_limit(0);
require realpath($_SERVER['DOCUMENT_ROOT']).'/inc/prep.inc.php';
$p = isset($_GET['p']) ? prevent_xss($_GET['p']): 'home';
$q = isset($_GET['q']) ? prevent_xss($_GET['q']): "";
$dob = isset($_GET['dob']) ? prevent_xss($_GET['dob']): (isset($_COOKIE['BIO:remembered_dob']) ? $_COOKIE['BIO:remembered_dob']: date('Y-m-d'));
$embed = isset($_GET['embed']) ? prevent_xss($_GET['embed']): 0;
//$lang_code = init_lang_code();
$lang_code = 'vi';
$keywords = "biorhythm,biorhythms,biorhythm.xyz";
$og_title = "Đây là Máy tính Nhịp sinh học";
$og_desc = "Đây là Máy tính Nhịp sinh học. Sử dụng công cụ này để tìm hiểu thêm về bản thân bạn. Chọn Ngày sinh Dương lịch của bạn với định dạng YYYY-MM-DD (năm-tháng-ngày) bằng công cụ Chọn ngày. Sau đó, nhấn nút `Tính` để tính toán chỉ số Sức khỏe, Tình cảm, Trí tuệ của bạn. Nếu bạn chỉ quan tâm đến Nhịp sinh học ngủ, bạn có thể bỏ qua mục này.";
$article_tag = "nhịp sinh học";
$bmi_title = "Đây là Máy tính Chỉ số khối cơ thể";
$bmi_desc = "Đây là Máy tính Chỉ số khối cơ thể. Sử dụng công cụ này để biết Cân nặng và Chiều cao lý tưởng cũng như Lời khuyên.";
$time_zone = 7;
$show_ad = false;
$show_donate = false;
$show_sponsor = false;
$show_addthis = false;
$show_sumome = false;
$hotjar = false;
$clicktale = false;
$smartlook = false;
$credential_id = 3; //change this to 4 in DEMO
//$cdn_url = 'https://nhipsinhhoc.cdn.vccloud.vn';
//$cdn_url = "https://cdn_local.nhipsinhhoc.vn";
//$cdn_url = "https://cdn.biorhythm.xyz";
//$cdn_url = "https://static-bio.vncdn.vn";
$cdn_url = "";
//$cdn_url = "https://filecuatui.com";
//$cdn_url = "https://taptincuatui.com";
//$cdn_url = 'https://biorhythm.cdn.vccloud.vn';
$number = calculate_life_path($dob);
if (isset($_GET['dob']) && isset($_GET['diff']) && isset($_GET['is_secondary']) && isset($_GET['dt_change']) && isset($_GET['partner_dob']) && isset($_GET['lang_code'])) {
	$chart = new Chart($_GET['dob'],$_GET['diff'],$_GET['is_secondary'],$_GET['dt_change'],$_GET['partner_dob'],$_GET['lang_code']);
} else {
	$date = (isset($_GET['date']) && $_GET['date'] != "") ? $_GET['date']: date('Y-m-d');
	$chart = new Chart($dob,0,0,$date,$dob,$lang_code);
}
if (isset($_GET['ad'])) {
	setcookie('BIO:show_ad',$_GET['ad']);
}
if (isset($_COOKIE['BIO:member'])) {
//	$show_ad = false;
	$chart->set_registered(true);
} else if (!isset($_COOKIE['BIO:member'])) {
//	$show_ad = true;
	$chart->set_registered(false);
}
$email_credentials = array(
	'username' => 'admin@nhipsinhhoc.vn',
	'password' => '@DM!Nv0d0i'
);
$faroo_key = 'kc5BZXhbMCj0@lx0TEVOiHNvSok_';
$clickbank = '<a href="https://tungpham42.15manifest.hop.clickbank.net"><img src="https://maxcdn.15minutemanifestation.com/affiliates/images/300x250.jpg"></a>';
$input_interfaces = array(
	'search' => array(
		'vi' => 'Tìm kiếm',
		'en' => 'Search',
		'ru' => 'Искать',
		'es' => 'Buscar',
		'zh' => '寻求',
		'ja' => '探す'
	),
	'fullname' => array(
		'vi' => 'Họ tên',
		'en' => 'Full name',
		'ru' => 'Полное имя',
		'es' => 'Nombre',
		'zh' => '全名',
		'ja' => 'フルネーム'
	),
	'dob' => array(
		'vi' => 'Ngày sinh',
		'en' => 'Date of birth',
		'ru' => 'Дата рождения',
		'es' => 'Fecha de nacimiento',
		'zh' => '出生日期',
		'ja' => '生まれた日'
	),
	'dt_change' => array(
		'vi' => 'Đổi ngày',
		'en' => 'Change date',
		'ru' => 'Изменение даты',
		'es' => 'Cambiar fecha',
		'zh' => '更改日期',
		'ja' => '日付の変更'
	),
	'partner_dob' => array(
		'vi' => 'Đối tác',
		'en' => 'Partner',
		'ru' => 'Напарник',
		'es' => 'Compañero',
		'zh' => '伙伴',
		'ja' => 'パートナー'
	),
	'email' => array(
		'vi' => 'Thư điện tử',
		'en' => 'Email',
		'ru' => 'Электронная почта',
		'es' => 'Correo electrónico',
		'zh' => '电子邮件',
		'ja' => '電子メール'
	),
	'password' => array(
		'vi' => 'Mật khẩu',
		'en' => 'Password',
		'ru' => 'Пароль',
		'es' => 'Contraseña',
		'zh' => '密码',
		'ja' => 'パスワード'
	),
	'repeat_password' => array(
		'vi' => 'Lặp lại',
		'en' => 'Repeat',
		'ru' => 'Повторять',
		'es' => 'Repetición',
		'zh' => '重复',
		'ja' => 'リピート'
	)
);
$button_interfaces = array(
	'name_toggle' => array(
		'vi' => 'Họ tên',
		'en' => 'Full name',
		'ru' => 'Полное имя',
		'es' => 'Nombre',
		'zh' => '全名',
		'ja' => 'フルネーム'
	),
	'home_page' => array(
		'vi' => 'Trang chủ',
		'en' => 'Home',
		'ru' => 'Дом',
		'es' => 'Casa',
		'zh' => '主页',
		'ja' => 'ホーム'
	),
	'dob_erase' => array(
		'vi' => 'Xóa',
		'en' => 'Erase',
		'ru' => 'Стирать',
		'es' => 'Borrar',
		'zh' => '抹去',
		'ja' => '消す'
	),
	'dob_submit' => array(
		'vi' => 'Chạy',
		'en' => 'Run',
		'ru' => 'Идти',
		'es' => 'Correr',
		'zh' => '运行',
		'ja' => '走る'
	),
	'dob_list' => array(
		'vi' => 'Danh sách',
		'en' => 'List',
		'ru' => 'Список',
		'es' => 'Lista',
		'zh' => '名单',
		'ja' => 'リスト'
	),
	'dob_create' => array(
		'vi' => 'Tạo',
		'en' => 'Create',
		'ru' => 'Создать',
		'es' => 'Crear',
		'zh' => '创建',
		'ja' => '作る'
	),
	'dob_edit' => array(
		'vi' => 'Sửa',
		'en' => 'Edit',
		'ru' => 'Редактировать',
		'es' => 'Editar',
		'zh' => '编辑',
		'ja' => '編集'
	),
	'dob_remove' => array(
		'vi' => 'Xóa bỏ',
		'en' => 'Remove',
		'ru' => 'Удалить',
		'es' => 'Quitar',
		'zh' => '拆除',
		'ja' => '除く'
	),
	'today' => array(
		'vi' => ' Hôm nay',
		'en' => ' Today',
		'ru' => ' Сегодня',
		'es' => ' Hoy',
		'zh' => ' 今天',
		'ja' => ' 今日'
	),
	'prev' => array(
		'vi' => ' Trước',
		'en' => ' Back',
		'ru' => ' Назад',
		'es' => ' Atrás',
		'zh' => ' 回去',
		'ja' => ' 戻る'
	),
	'next' => array(
		'vi' => 'Sau ',
		'en' => 'Forward ',
		'ru' => 'Вперед ',
		'es' => 'Enviar ',
		'zh' => '前进 ',
		'ja' => '前進する '
	),
	'intro' => array(
		'vi' => 'Giới thiệu',
		'en' => 'Introduction',
		'ru' => 'Введение',
		'es' => 'Introducción',
		'zh' => '介绍',
		'ja' => 'はじめに'
	),
	'blog' => array(
		'vi' => 'Blog',
		'en' => 'Blog',
		'ru' => 'блог',
		'es' => 'Blog',
		'zh' => '博客',
		'ja' => 'ブログ'
	),
	'forum' => array(
		'vi' => 'Diễn đàn',
		'en' => 'Forum',
		'ru' => 'Форум',
		'es' => 'Forum',
		'zh' => '论坛',
		'ja' => 'フォーラム'
	),
	'bmi' => array(
		'vi' => 'BMI',
		'en' => 'BMI',
		'ru' => 'ИМТ',
		'es' => 'IMC',
		'zh' => '身高體重指數',
		'ja' => 'ボディマス指数'
	),
	'lunar' => array(
		'vi' => 'Bói',
		'en' => 'Lunar calendar',
		'ru' => 'Лунный календарь',
		'es' => 'Calendario lunar',
		'zh' => '阴历',
		'ja' => '太陰暦'
	),
	'game' => array(
		'vi' => 'Game',
		'en' => 'Game',
		'ru' => 'Игра',
		'es' => 'Juego',
		'zh' => '游戏',
		'ja' => 'ゲーム'
	),
	'survey' => array(
		'vi' => 'Góp ý',
		'en' => 'Survey',
		'ru' => 'Обзор',
		'es' => 'Estudio',
		'zh' => '调查',
		'ja' => '調査'
	),
	'apps' => array(
		'vi' => 'App',
		'en' => 'Applications',
		'ru' => 'Приложений',
		'es' => 'Aplicaciones',
		'zh' => '应用',
		'ja' => 'アプリ'
	),
	'donate' => array(
		'vi' => 'ĐÓNG GÓP',
		'en' => 'DONATE',
		'ru' => 'ДАРИТЬ',
		'es' => 'DONAR',
		'zh' => '捐赠',
		'ja' => '寄付する'
	),
	'donate_reason' => array(
		'vi' => 'nếu bạn thấy trang có ích',
		'en' => 'if you find it useful',
		'ru' => 'коли вас считать это полезным',
		'es' => 'si usted lo encuentra útil',
		'zh' => '如果您发现它有用',
		'ja' => 'あなたはそれが役立つかどう'
	),
	'sponsor' => array(
		'vi' => 'TÀI TRỢ',
		'en' => 'SPONSOR',
		'ru' => 'СПОНСОР',
		'es' => 'PATROCINIO',
		'zh' => '贊助',
		'ja' => 'スポンサー'
	),
	'sponsor_reason' => array(
		'vi' => 'để đặt quảng cáo',
		'en' => 'to put banner',
		'ru' => 'положить баннер',
		'es' => 'poner bandera',
		'zh' => '把旗帜',
		'ja' => 'バナーを置くために'
	),
	'install_chrome' => array(
		'vi' => 'Thêm vào Chrome',
		'en' => 'Add to Chrome',
		'ru' => 'Добавить в Chrome',
		'es' => 'Añadir a Chrome',
		'zh' => '添加到Chrome浏览器',
		'ja' => 'クロームに追加'
	),
	'install_firefox' => array(
		'vi' => 'Cài ứng dụng Firefox',
		'en' => 'Install Firefox app',
		'ru' => 'Установите Firefox приложение',
		'es' => 'Instalar Firefox aplicación',
		'zh' => '安装Firefox的应用程序',
		'ja' => 'Firefoxのアプリをインストール'
	),
	'register' => array(
		'vi' => 'Đăng ký',
		'en' => 'Register',
		'ru' => 'Регистр',
		'es' => 'Registrarse',
		'zh' => '注册',
		'ja' => '登録'
	),
	'try_it' => array(
		'vi' => 'Đăng ký',
		'en' => 'Try it',
		'ru' => 'Регистр',
		'es' => 'Registrarse',
		'zh' => '注册',
		'ja' => '登録'
	),
	'update' => array(
		'vi' => 'Cập nhật',
		'en' => 'Update',
		'ru' => 'Обновлять',
		'es' => 'Actualizar',
		'zh' => '更新',
		'ja' => 'アップデート'
	),
	'login' => array(
		'vi' => 'Đăng nhập',
		'en' => 'Log In',
		'ru' => 'Войти',
		'es' => 'Iniciar Sesión',
		'zh' => '登录',
		'ja' => 'ログイン'
	),
	'logout' => array(
		'vi' => 'Đăng xuất',
		'en' => 'Log Out',
		'ru' => 'Выйти',
		'es' => 'Cerrar Sesión',
		'zh' => '登出',
		'ja' => 'ログアウト'
	),
	'change_pass' => array(
		'vi' => 'Đổi mật khẩu',
		'en' => 'Change password',
		'ru' => 'Изменить пароль',
		'es' => 'Cambiar la contraseña',
		'zh' => '更改密码',
		'ja' => 'パスワードを変更する'
	),
	'edit' => array(
		'vi' => 'Sửa',
		'en' => 'Edit',
		'ru' => 'Редактировать',
		'es' => 'Editar',
		'zh' => '编辑',
		'ja' => '編集'
	),
	'profile' => array(
		'vi' => 'Hồ sơ',
		'en' => 'Profile',
		'ru' => 'Профиль',
		'es' => 'Perfil',
		'zh' => '轮廓',
		'ja' => 'プロフィール'
	),
	'sleep_now' => array(
		'vi' => 'Ngủ ngay bây giờ!',
		'en' => 'Sleep now!',
		'ru' => 'Засыпай!',
		'es' => '¡Duerme ahora!',
		'zh' => '现在睡觉！',
		'ja' => '今スリープ！'
	),
	'upgrade' => array(
		'vi' => 'Nâng cấp',
		'en' => 'Upgrade',
		'ru' => 'Обновить',
		'es' => 'Mejorar',
		'zh' => '升级',
		'ja' => 'アップグレード'
	),
	'submit' => array(
		'vi' => 'Gửi',
		'en' => 'Submit',
		'ru' => 'Отправить',
		'es' => 'Enviar',
		'zh' => '提交',
		'ja' => '提出します'
	),
	'contact' => array(
		'vi' => 'Liên hệ',
		'en' => 'Contact',
		'ru' => 'Связаться',
		'es' => 'Contactar',
		'zh' => '联系',
		'ja' => '接触'
	)
);
$span_interfaces = array(
	'me' => array(
		'vi' => 'Tôi',
		'en' => 'Me',
		'ru' => 'Меня',
		'es' => 'Yo',
		'zh' => '我',
		'ja' => '私に'
	),
	'author' => array(
		'vi' => 'Tác giả',
		'en' => 'Author',
		'ru' => 'Автор',
		'es' => 'Autor',
		'zh' => '作者',
		'ja' => '著者'
	),
	'author_intro' => array(
		'vi' => '<h2>Giới thiệu</h2><p>Tôi là Phạm Tùng, hiện đang là lập trình viên PHP.</p>',
		'en' => '<h2>Introduction</h2><p>I am Tung Pham, currently a PHP programmer.</p>',
		'ru' => '<h2>Введение</h2><p>Я Тунг Фам, в настоящее время программист PHP.</p>',
		'es' => '<h2>Introducción</h2><p>Soy Tung Pham, actualmente programador de PHP.</p>',
		'zh' => '<h2>介绍</h2><p>我是目前的PHP程序员范松。</p>',
		'ja' => '<h2>前書き</h2><p>私は現在、PHPプログラマーである范松です。</p>'
	),
	'author_projects' => array(
		'vi' => '<h2>Các dự án</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">bấm vào đây</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">bấm vào đây</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">bấm vào đây</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">bấm vào đây</a></li><li>Cung Rao . NET -> <a href="https://cungrao.net\/">bấm vào đây</a></li></ol>',
		'en' => '<h2>Projects</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">click here</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">click here</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">click here</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">click here</a></li><li>Cung Rao . NET -> <a href="https://cungrao.net\/">click here</a></li></ol>',
		'ru' => '<h2>Проектов</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">кликните сюда</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">кликните сюда</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">кликните сюда</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">кликните сюда</a></li><li>Cung Rao . NET -> <a href="https://cungrao.net\/">кликните сюда</a></li></ol>',
		'es' => '<h2>Proyectos</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">haga clic aquí</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">haga clic aquí</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">haga clic aquí</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">haga clic aquí</a></li><li>Cung Rao . NET -> <a href="https://cungrao.net\/">haga clic aquí</a></li></ol>',
		'zh' => '<h2>项目</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">点击这里</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">点击这里</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">点击这里</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">点击这里</a></li><li>Cung Rao . NET -> <a href="https://cungrao.net\/">点击这里</a></li></ol>',
		'ja' => '<h2>プロジェクト</h2><ol><li>Nhip Sinh Hoc . VN -> <a href="\/">ここをクリック</a></li><li>Nhip Sinh Hoc Blog -> <a href="\/blog\/">ここをクリック</a></li><li>Nhip Sinh Hoc Wiki -> <a href="\/wiki\/">ここをクリック</a></li><li>Nhip Sinh Hoc Quiz -> <a href="\/quiz\/">ここをクリック</a></li><li>Cung Rao . NET -> <a href="https://cungrao.net\/">ここをクリック</a></li></ol>'
	),
	'list_user_same_birthday_links' => array(
		'vi' => 'Những người trùng ngày sinh với tôi',
		'en' => 'People with same birthday with me',
		'ru' => 'Люди с таким же рождения со мной',
		'es' => 'Las personas con misma fecha de cumpleaños conmigo',
		'zh' => '与人同一天生日与我',
		'ja' => '私と同じ誕生日を持つ人々'
	),
	'list_user_birthday_links' => array(
		'vi' => 'Sinh nhật người nổi tiếng hôm nay',
		'en' => 'Celebrities birthdays today',
		'ru' => 'Знаменитости дня рождения',
		'es' => 'Celebridades cumpleaños hoy',
		'zh' => '名人今天生日',
		'ja' => '今日は有名人誕生日'
	),
	'list_user_links' => array(
		'vi' => 'Ngày sinh người nổi tiếng',
		'en' => 'Celebrities birth dates',
		'ru' => 'Знаменитости даты рождения',
		'es' => 'Celebridades fecha de nacimiento',
		'zh' => '人的出生日期',
		'ja' => '有名人誕生日'
	),
	'list_persons' => array(
		'vi' => 'Danh sách ngày sinh của tôi',
		'en' => 'My birthdates list',
		'ru' => 'Мой список дат рождения',
		'es' => 'Mi lista de fechas de nacimiento',
		'zh' => '我的出生日期列表',
		'ja' => '私の誕生日一覧'
	),
	'no_persons' => array(
		'vi' => 'Tạo ngày sinh đầu tiên nào',
		'en' => 'Create first birthdate now',
		'ru' => 'Создать первую дату рождения в настоящее время',
		'es' => 'Crea primera fecha de nacimiento ahora',
		'zh' => '现在创建第一个生日',
		'ja' => '今最初の誕生日を作成します'
	),
	'copyright' => array(
		'vi' => 'bản quyền thuộc',
		'en' => 'copyright',
		'ru' => 'авторское право',
		'es' => 'derechos del autor',
		'zh' => '著作權',
		'ja' => 'コピーライト'
	),
	'pham_tung' => array(
		'vi' => 'Phạm Tùng',
		'en' => 'Tung Pham',
		'ru' => 'Тунг Фам',
		'es' => 'Tung Pham',
		'zh' => '范松',
		'ja' => '范松'
	),
	'email' => array(
		'vi' => 'Thư điện tử',
		'en' => 'Email',
		'ru' => 'Электронная почта',
		'es' => 'Correo electrónico',
		'zh' => '电子邮件',
		'ja' => '電子メール'
	),
	'blog' => array(
		'vi' => 'Blog',
		'en' => 'Blog',
		'ru' => 'блог',
		'es' => 'Blog',
		'zh' => '博客',
		'ja' => 'ブログ'
	),
	'forum' => array(
		'vi' => 'Diễn đàn',
		'en' => 'Forum',
		'ru' => 'Форум',
		'es' => 'Forum',
		'zh' => '论坛',
		'ja' => 'フォーラム'
	),
	'sleep_time' => array(
		'vi' => 'Nhịp sinh học ngủ',
		'en' => 'Sleep rhythm',
		'ru' => 'Ритм сна',
		'es' => 'Ritmo del sueño',
		'zh' => '睡眠节律',
		'ja' => '睡眠リズム'
	),
	'hour' => array(
		'vi' => 'Chọn giờ',
		'en' => 'Select hour',
		'ru' => 'Час',
		'es' => 'Hora',
		'zh' => '钟头',
		'ja' => 'アワー'
	),
	'minute' => array(
		'vi' => 'Chọn phút',
		'en' => 'Select minute',
		'ru' => 'Минут',
		'es' => 'Minuto',
		'zh' => '分钟',
		'ja' => '一刻'
	),
	'sleep_time_head' => array(
		'vi' => 'Nếu bạn dự định thức dậy lúc',
		'en' => 'If you plan to get up at',
		'ru' => 'Если вы встаете на',
		'es' => 'Si te levantas a',
		'zh' => '如果你起床',
		'ja' => 'あなたは時に起きる場合'
	),
	'wake_up_time_head' => array(
		'vi' => 'Hoặc nếu bạn muốn ngủ ngay bây giờ',
		'en' => 'Or if you want to sleep right now',
		'ru' => 'Или, если вы ложитесь спать прямо сейчас',
		'es' => 'O si usted va a dormir en este momento',
		'zh' => '或者，如果你去睡觉，现在',
		'ja' => 'あるいは場合は、あなたは今すぐに眠りにつきます'
	),
	'sleep_time_results' => array(
		'vi' => 'Bạn nên đi ngủ vào một trong những giờ sau:',
		'en' => 'You should try to fall asleep at one of the following times:',
		'ru' => 'Вы должны попытаться заснуть в одном из следующих времен:',
		'es' => 'Usted debe tratar de conciliar el sueño en uno de los siguientes horarios:',
		'zh' => '你应该尝试入睡的以下任一：',
		'ja' => 'あなたは、以下のいずれかの時点で眠りに落ちるようにしてください：'
	),
	'wake_up_time_results' => array(
		'vi' => 'Bạn nên thức dậy vào một trong những giờ sau:',
		'en' => 'You should try to get up at one of the following times:',
		'ru' => 'Вы должны попробовать, чтобы встать в один из следующих случаях:',
		'es' => 'Usted debe tratar de levantarse a uno de los siguientes horarios:',
		'zh' => '你应该尝试起床的以下任一：',
		'ja' => 'あなたは、以下のいずれかの時点で、最大取得しようとする必要があります：'
	),
	'news' => array(
		'vi' => 'Tin tức',
		'en' => 'News',
		'ru' => 'Новости',
		'es' => 'Noticias',
		'zh' => '新闻',
		'ja' => 'ニュース'
	),
	'apps' => array(
		'vi' => 'Ứng dụng',
		'en' => 'Applications',
		'ru' => 'Приложений',
		'es' => 'Aplicaciones',
		'zh' => '应用',
		'ja' => 'アプリ'
	),
	'apps_six_lang' => array(
		'vi' => '6 ngôn ngữ',
		'en' => '6 language',
		'ru' => '6 язык',
		'es' => '6 lenguaje',
		'zh' => '6語言',
		'ja' => '6言語'
	),
	'apps_one_lang' => array(
		'vi' => 'Một ngôn ngữ',
		'en' => 'Single language',
		'ru' => 'Один язык',
		'es' => 'Uno lenguaje',
		'zh' => '一語言',
		'ja' => '一言語'
	),
	'email' => array(
		'vi' => 'Thư điện tử',
		'en' => 'Email',
		'ru' => 'Электронная почта',
		'es' => 'Correo electrónico',
		'zh' => '电子邮件',
		'ja' => '電子メール'
	),
	'fullname' => array(
		'vi' => 'Họ tên',
		'en' => 'Full name',
		'ru' => 'Полное имя',
		'es' => 'Nombre',
		'zh' => '全名',
		'ja' => 'フルネーム'
	),
	'dob' => array(
		'vi' => 'Ngày sinh',
		'en' => 'Date of birth',
		'ru' => 'Дата рождения',
		'es' => 'Fecha de nacimiento',
		'zh' => '出生日期',
		'ja' => '生まれた日'
	),
	'password' => array(
		'vi' => 'Mật khẩu',
		'en' => 'Password',
		'ru' => 'Пароль',
		'es' => 'Contraseña',
		'zh' => '密码',
		'ja' => 'パスワード'
	),
	'repeat_password' => array(
		'vi' => 'Lặp lại',
		'en' => 'Repeat',
		'ru' => 'Повторять',
		'es' => 'Repetición',
		'zh' => '重复',
		'ja' => 'リピート'
	),
	'register' => array(
		'vi' => 'Đăng ký',
		'en' => 'Register',
		'ru' => 'Регистр',
		'es' => 'Registrarse',
		'zh' => '注册',
		'ja' => '登録'
	),
	'login' => array(
		'vi' => 'Đăng nhập',
		'en' => 'Log In',
		'ru' => 'Войти',
		'es' => 'Iniciar Sesión',
		'zh' => '登录',
		'ja' => 'ログイン'
	),
	'not_yet_registered' => array(
		'vi' => 'Chưa đăng ký?',
		'en' => 'Not yet registered?',
		'ru' => 'Еще не зарегистрированы?',
		'es' => '¿Todavía no estás registrado?',
		'zh' => '尚未注册？',
		'ja' => 'まだ登録されていませんか？'
	),
	'already_registered' => array(
		'vi' => 'Đã đăng ký?',
		'en' => 'Already registered?',
		'ru' => 'Уже зарегистрирован?',
		'es' => '¿Ya registrado?',
		'zh' => '已经注册？',
		'ja' => '既に登録されています？'
	),
	'reset_password' => array(
		'vi' => 'Đặt lại mật khẩu',
		'en' => 'Reset password',
		'ru' => 'Сброс пароля',
		'es' => 'Restablecer la contraseña',
		'zh' => '重设密码',
		'ja' => 'パスワードを再設定する'
	),
	'forgot_password' => array(
		'vi' => 'Quên mật khẩu?',
		'en' => 'Forgot password?',
		'ru' => 'Забыли пароль?',
		'es' => '¿Contraseña olvidada?',
		'zh' => '忘记密码？',
		'ja' => 'パスワードをお忘れですか？'
	),
	'forgot_password_hint' => array(
		'vi' => '[cho mình biết họ tên đăng ký của bạn]',
		'en' => '[let me know your registered full name]',
		'ru' => '[Дайте мне знать ваше зарегистрированное полное имя]',
		'es' => '[Me dejó saber su nombre completo registrada]',
		'zh' => '[让我知道你注册的全名]',
		'ja' => '[私はあなたの登録フルネームをお知らせ]'
	),
	'trial_expire' => array(
		'vi' => 'Đã hết hạn dùng thử. Hãy đăng ký ngay để không còn quảng cáo.',
		'en' => 'Your trial has expired. Sign up for ads free.',
		'ru' => 'Ваш суд истек. Подпишитесь на объявления бесплатно.',
		'es' => 'Su prueba ha caducado. Inscríbete gratis anuncios.',
		'zh' => '您的试用已过期。订阅的分类广告。',
		'ja' => '試用期限が切れています。無料広告にサインアップしてください。'
	),
	'register_modal' => array(
		'vi' => 'Hãy đăng ký ngay và khám phá thêm nhiều tính năng.',
		'en' => 'Exploring more about your health, emotion, and mind.',
		'ru' => 'Зарегистрируйтесь сейчас, чтобы изучить больше возможностей.',
		'es' => 'Registrate ahora para explorar más características.',
		'zh' => '现在注册探索更多的功能。',
		'ja' => 'より多くの機能を探索するために今すぐ登録。'
	),
	'biorhythm' => array(
		'vi' => 'nhịp sinh học',
		'en' => 'biorhythm',
		'ru' => 'биоритм',
		'es' => 'biorritmo',
		'zh' => '生理节律',
		'ja' => 'バイオリズム'
	),
	'health' => array(
		'vi' => 'sức khỏe',
		'en' => 'health',
		'ru' => 'здоровье',
		'es' => 'salud',
		'zh' => '健康',
		'ja' => 'ヘルス'
	),
	'year' => array(
		'vi' => 'năm',
		'en' => 'year',
		'ru' => 'год',
		'es' => 'año',
		'zh' => '年',
		'ja' => '年'
	),
	'advertisements' => array(
		'vi' => 'Quảng cáo',
		'en' => 'Advertisements',
		'ru' => 'Объявления',
		'es' => 'Anuncios',
		'zh' => '广告',
		'ja' => '広告'
	),
	'keyboard_shortcuts' => array(
		'vi' => 'Phím tắt',
		'en' => 'Keyboard shortcuts',
		'ru' => 'Горячие клавиши',
		'es' => 'Atajos de teclado',
		'zh' => '快捷键',
		'ja' => 'キーボードショートカット'
	),
	'keyboard_shortcuts_long' => array(
		'vi' => '<h6>Phím tắt:</h6><ul><li>S / G / K -> Hôm nay</li><li>A / F / J -> Trước</li><li>D / H / L -> Sau</li><li>W / T / I -> Sinh nhật</li><li>E / Y / O -> Nhịp sinh học phụ</li><li>R / U / P -> Thành ngữ</li><li>1 -> Tiếng Việt</li><li>2 -> Tiếng Anh</li><li>3 -> Tiếng Nga</li><li>4 -> Tiếng Tây Ban Nha</li><li>5 -> Tiếng Trung</li><li>6 -> Tiếng Nhật</li></ul>',
		'en' => '<h6>Keyboard shortcuts:</h6><ul><li>S / G / K -> Today</li><li>A / F / J -> Back</li><li>D / H / L -> Forward</li><li>W / T / I -> Birthday</li><li>E / Y / O -> Secondary rhythm</li><li>R / U / P -> Proverb</li><li>1 -> Vietnamese</li><li>2 -> English</li><li>3 -> Russian</li><li>4 -> Spanish</li><li>5 -> Chinese</li><li>6 -> Japanese</li></ul>',
		'ru' => '<h6>Горячие клавиши:</h6><ul><li>S / G / K -> Сегодня</li><li>A / F / J -> Назад</li><li>D / H / L -> Вперед</li><li>W / T / I -> День рождения</li><li>E / Y / O -> Вторичный ритм</li><li>R / U / P -> Пословица</li><li>1 -> Вьетнамский язык</li><li>2 -> Английский язык</li><li>3 -> Русский язык</li><li>4 -> Испанский язык</li><li>5 -> Китайский язык</li><li>6 -> Японский язык</li></ul>',
		'es' => '<h6>Atajos de teclado:</h6><ul><li>S / G / K -> Hoy</li><li>A / F / J -> Atrás</li><li>D / H / L -> Enviar</li><li>W / T / I -> Cumpleaños</li><li>E / Y / O -> Ritmo secundaria</li><li>R / U / P -> Proverbio</li><li>1 -> Idioma vietnamita</li><li>2 -> Idioma Inglés</li><li>3 -> Idioma Ruso</li><li>4 -> Idioma Espanol</li><li>5 -> Idioma Chino</li><li>6 -> Idioma Japones</li></ul>',
		'zh' => '<h6>快捷键：</h6><ul><li>S，G，K -> 今天</li><li>A，F，J -> 回去</li><li>D，H，L -> 前进</li><li>W，T，I -> 生辰</li><li>E，Y，O -> 次要节奏</li><li>R，U，P -> 谚语</li><li>1 -> 越南语</li><li>2 -> 英语</li><li>3 -> 俄语语言</li><li>4 -> 西班牙语</li><li>5 -> 中文</li><li>6 -> 日文</li></ul>',
		'ja' => '<h6>キーボードショートカット：</h6><ul><li>S、G、K -> 今日</li><li>A、F、J -> 戻る</li><li>D、H、L -> 前進する</li><li>W、T、I -> バースデー</li><li>E、Y、O -> セカンダリリズム</li><li>R、U、P -> ことわざ</li><li>1 -> ベトナム語</li><li>2 -> 英語</li><li>3 -> ロシア語</li><li>4 -> スペイン語</li><li>5 -> チャン語</li><li>6 -> 日本語</li></ul>'
	),
	'keyboard_shortcuts_short' => array(
		'vi' => '<h6>Phím tắt:</h6><ul><li>1 -> Tiếng Việt</li><li>2 -> Tiếng Anh</li><li>3 -> Tiếng Nga</li><li>4 -> Tiếng Tây Ban Nha</li><li>5 -> Tiếng Trung</li><li>6 -> Tiếng Nhật</li></ul>',
		'en' => '<h6>Keyboard shortcuts:</h6><ul><li>1 -> Vietnamese</li><li>2 -> English</li><li>3 -> Russian</li><li>4 -> Spanish</li><li>5 -> Chinese</li><li>6 -> Japanese</li></ul>',
		'ru' => '<h6>Горячие клавиши:</h6><ul><li>1 -> Вьетнамский язык</li><li>2 -> Английский язык</li><li>3 -> Русский язык</li><li>4 -> Испанский язык</li><li>5 -> Китайский язык</li><li>6 -> Японский язык</li></ul>',
		'es' => '<h6>Atajos de teclado:</h6><ul><li>1 -> Idioma vietnamita</li><li>2 -> Idioma Inglés</li><li>3 -> Idioma Ruso</li><li>4 -> Idioma Espanol</li><li>5 -> Idioma Chino</li><li>6 -> Idioma Japones</li></ul>',
		'zh' => '<h6>快捷键：</h6><ul><li>1 -> 越南语</li><li>2 -> 英语</li><li>3 -> 俄语语言</li><li>4 -> 西班牙语</li><li>5 -> 中文</li><li>6 -> 日文</li></ul>',
		'ja' => '<h6>キーボードショートカット：</h6><ul><li>1 -> ベトナム語</li><li>2 -> 英語</li><li>3 -> ロシア語</li><li>4 -> スペイン語</li><li>5 -> チャン語</li><li>6 -> 日本語</li></ul>'
	),
	'bank_account' => array(
		'vi' => '<h6>Tài khoản ngân hàng:</h6><ul><li>Ngân hàng: TECHCOMBANK</li><li>Số tài khoản: 19027906069012</li><li>Tên người thụ hưởng: PHAM TUNG</li><li>Chi nhánh: Phú Mỹ Hưng</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'en' => '<h6>Bank account:</h6><ul><li>Bank: TECHCOMBANK</li><li>Account number: 19027906069012</li><li>Beneficiary name: PHAM TUNG</li><li>Branch: Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'ru' => '<h6>банковский счет:</h6><ul><li>Банка: TECHCOMBANK</li><li>Номер аккаунта: 19027906069012</li><li>Имя Получателя: PHAM TUNG</li><li>Филиал: Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'es' => '<h6>Cuenta bancaria:</h6><ul><li>Banco: TECHCOMBANK</li><li>Número de cuenta: 19027906069012</li><li>Nombre del Beneficiario: PHAM TUNG</li><li>Branch: Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'zh' => '<h6>银行账户：</h6><ul><li>银行： TECHCOMBANK</li><li>帐号： 19027906069012</li><li>受益人姓名： PHAM TUNG</li><li>科： Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
		'ja' => '<h6>銀行口座：</h6><ul><li>バンク： TECHCOMBANK</li><li>口座番号： 19027906069012</li><li>受取人名： PHAM TUNG</li><li>ブランチ： Phu My Hung</li><li>SWIFT code: VTCBVNVX</li</ul>',
	),
	'for_reference_only' => array(
		'vi' => 'Chỉ mang tính tham khảo',
		'en' => 'For reference only',
		'ru' => 'Только для справки',
		'es' => 'Solo por referencia',
		'zh' => '仅供参考',
		'ja' => '参考のためのみ'
	),
	'comments_head' => array(
		'vi' => 'Mong nhận được ý kiến của các bạn. Hãy để lại bình luận dưới đây.',
		'en' => 'Look forward to your comments. Put them down here.',
		'ru' => 'Посмотрите ждем ваших комментариев. Положите их сюда.',
		'es' => 'Esperamos sus comentarios. Póngalos aquí.',
		'zh' => '期待您的意见。把它们放在这儿。',
		'ja' => 'あなたのコメントを楽しみにしています。ここではそれらを置きます。'
	),
	'bmi_weight' => array(
		'vi' => 'Cân nặng:',
		'en' => 'Weight:',
		'ru' => 'Вес:',
		'es' => 'Peso:',
		'zh' => '重量：',
		'ja' => '重さ：'
	),
	'bmi_height' => array(
		'vi' => 'Chiều cao:',
		'en' => 'Height:',
		'ru' => 'Высота:',
		'es' => 'Altura:',
		'zh' => '身高：',
		'ja' => '身長：'
	),
	'bmi_weight_unit' => array(
		'vi' => 'ký',
		'en' => 'kg',
		'ru' => 'кг',
		'es' => 'kilo',
		'zh' => '千克',
		'ja' => 'キロ'
	),
	'bmi_height_unit' => array(
		'vi' => 'mét',
		'en' => 'metre',
		'ru' => 'метр',
		'es' => 'medidor',
		'zh' => '计',
		'ja' => 'メーター'
	),
	'bmi_value' => array(
		'vi' => 'Chỉ số BMI:',
		'en' => 'BMI value:',
		'ru' => 'Значение ИМТ:',
		'es' => 'Valor de IMC:',
		'zh' => 'BMI值：',
		'ja' => 'BMI値：'
	),
	'bmi_explanation' => array(
		'vi' => 'Đánh giá:',
		'en' => 'Explanation:',
		'ru' => 'Объяснение:',
		'es' => 'Explicación:',
		'zh' => '说明：',
		'ja' => '説明：'
	),
	'bmi_ideal_weight' => array(
		'vi' => 'Cân nặng lý tưởng:',
		'en' => 'Ideal weight:',
		'ru' => 'Идеальный вес:',
		'es' => 'Peso ideal:',
		'zh' => '理想的体重：',
		'ja' => '理想的な体重：'
	),
	'bmi_ideal_height' => array(
		'vi' => 'Chiều cao lý tưởng:',
		'en' => 'Ideal height:',
		'ru' => 'Идеальная высота:',
		'es' => 'Altura ideal:',
		'zh' => '理想的身高：',
		'ja' => '理想の高さ：'
	),
	'bmi_recommendation' => array(
		'vi' => 'Lời khuyên:',
		'en' => 'Recommendation:',
		'ru' => 'Рекомендация:',
		'es' => 'Recomendación:',
		'zh' => '建议：',
		'ja' => '推奨事項：'
	),
	'bmi' => array(
		'vi' => 'Chỉ số khối cơ thể',
		'en' => 'Body mass index',
		'ru' => 'Индекс массы тела',
		'es' => 'IMC',
		'zh' => '身高體重指數',
		'ja' => 'ボディマス指数'
	),
	'vip' => array(
		'vi' => 'Nâng cấp tài khoản VIP để sử dụng các tính năng đầy đủ, và không có quảng cáo. Thanh toán một lần. Chỉ có 12 USD.',
		'en' => 'Upgrade to VIP account to use full features, and no ads. One time payment. Only 12 USD.',
		'ru' => 'Обновление до VIP счет использования полных возможностей и без рекламы. Одноразовый платеж. Только 12 долларов США.',
		'es' => 'Actualizar a cuenta VIP para usar las características completas y sin anuncios. Pago único. Sólo 12 USD.',
		'zh' => '升级为VIP帐户才能使用全部功能，而且没有广告。一次性付款。只有12美元。',
		'ja' => 'フル機能、および広告がないを使用するVIPアカウントにアップグレードしてください。一回払い。わずか12ドル。'
	),
	'vip_title' => array(
		'vi' => 'Nâng cấp lên tài khoản VIP',
		'en' => 'Upgrade to VIP account',
		'ru' => 'Обновление до VIP счет',
		'es' => 'Actualizar a cuenta VIP',
		'zh' => '升级为VIP帐号。',
		'ja' => 'VIPアカウントにアップグレード'
	),
	'upgrade' => array(
		'vi' => 'Nâng cấp',
		'en' => 'Upgrade',
		'ru' => 'Обновить',
		'es' => 'Mejorar',
		'zh' => '升级',
		'ja' => 'アップグレード'
	),
	'latest_posts' => array(
		'vi' => 'Bài viết mới',
		'en' => 'Latest posts',
		'ru' => 'Последние посты',
		'es' => 'Últimas publicaciones',
		'zh' => '最新文章',
		'ja' => '最新の投稿'
	),
	'view_more' => array(
		'vi' => 'Xem thêm',
		'en' => 'View more',
		'ru' => 'Показать еще',
		'es' => 'Ver más',
		'zh' => '查看更多',
		'ja' => 'もっと見る'
	),
	'or' => array(
		'vi' => 'Hoặc',
		'en' => 'Or',
		'ru' => 'Или',
		'es' => 'O',
		'zh' => '要么',
		'ja' => 'または'
	),
	'donate_intro' => array(
		'vi' => '<h2>Kính chào quý khách</h2><br/><h4>Nhằm duy trì sự hiện diện của trang Nhịp Sinh Học . VN, kính mong quý khách dành 20.000 đồng để đóng góp vào quỹ xây dựng website thông qua các hình thức thanh toán sau:</h4><br/>',
		'en' => '<h2>Dear visitors</h2><br/><h4>In order to raise the funds to maintain Nhịp Sinh Học . VN, please spend 20.000 VND through the payment methods below:</h4><br/>',
		'ru' => '<h2>Уважаемые посетители</h2><br/><h4>Для того чтобы поддерживать Nhip Sinh Hoc. VN, пожалуйста, потратить 20.000 донгов с помощью методов оплаты ниже:</h4><br/>',
		'es' => '<h2>Estimados visitantes</h2><br/><h4>Con el fin de mantener la Nhip Sinh Hoc . VN, por favor pasar 20.000 VND a través de los siguientes métodos de pago:</h4><br/>',
		'zh' => '<h2>亲爱的访客</h2><br/><h4>为了保持Nhip Sinh Hoc. VN，请花20.000 VND经过下面的付款方式：</h4><br/>',
		'ja' => '<h2>親愛なる訪問者</h2><br/><h4>Nhip Sinh Hoc. VNを維持するためには、以下のお支払い方法を介して20.000 VNDを過ごしてください。</h4><br/>'
	),
	'donate' => array(
		'vi' => 'Ủng hộ',
		'en' => 'Donate',
		'ru' => 'Дарить',
		'es' => 'Donar',
		'zh' => '捐赠',
		'ja' => '寄付する'
	),
	'donate_reason' => array(
		'vi' => 'nếu bạn thấy trang có ích',
		'en' => 'if you find it useful',
		'ru' => 'коли вас считать это полезным',
		'es' => 'si usted lo encuentra útil',
		'zh' => '如果您发现它有用',
		'ja' => 'あなたはそれが役立つかどう'
	),
	'sponsor' => array(
		'vi' => 'Tài trợ',
		'en' => 'Sponsor',
		'ru' => 'Спонсор',
		'es' => 'Patrocinio',
		'zh' => '贊助',
		'ja' => 'スポンサー'
	),
	'contact' => array(
		'vi' => 'Liên hệ với chúng tôi',
		'en' => 'Contact us',
		'ru' => 'Свяжитесь с нами',
		'es' => 'Contáctenos',
		'zh' => '联系我们',
		'ja' => 'お問い合わせ'
	),
	'content' => array(
		'vi' => 'Nội dung',
		'en' => 'Content',
		'ru' => 'Содержание',
		'es' => 'Contenido',
		'zh' => '内容',
		'ja' => 'コンテンツ'
	),
	'update_success' => array(
		'vi' => 'Cập nhật thành công!',
		'en' => 'Updated successfully!',
		'ru' => 'Успешно Обновлено!',
		'es' => '¡Actualizado correctamente!',
		'zh' => '成功更新！',
		'ja' => '正常に更新！'
	),
	'submit_success' => array(
		'vi' => 'Đã gửi thành công!',
		'en' => 'Submitted successfully!',
		'ru' => 'Успешно!',
		'es' => '¡Enviado satisfactoriamente!',
		'zh' => '提交成功！',
		'ja' => '正常に送信！'
	),
	'adblock' => array(
		'vi' => 'Trang web của chúng tôi vận hành được là nhờ hiển thị quảng cáo cho quý vị độc giả.<br/> Vui lòng hỗ trợ chúng tôi bằng cách tắt phần mềm chặn quảng cáo của bạn.',
		'en' => 'Our website is made possible by displaying online advertisements to our visitors.<br/> Please consider supporting us by disabling your ad blocker.',
		'ru' => 'Наш веб-сайт стал возможным благодаря показа рекламы в Интернете для наших посетителей.<br/> Пожалуйста, обратите внимание поддержку нас, отключив объявление блокатор.',
		'es' => 'Nuestro sitio web es posible gracias a la visualización de anuncios en línea para nuestros visitantes.<br/> Por favor considere apoyarnos mediante la desactivación de su bloqueador de anuncios.',
		'zh' => '我们的网站是通过向我们的访客显示在线广告。<br/> 请考虑通过停用广告拦截器来支持我们。',
		'ja' => '弊社のウェブサイトは、当社の訪問者にオンライン広告を表示することによって可能となります。<br/>広告ブロッカーを無効にすることによって私たちをサポートしてご検討ください。'
	),
	'proverbs' => array(
		'vi' => 'Danh ngôn',
		'en' => 'Proverbs',
		'ru' => 'Пословицы',
		'es' => 'Proverbios',
		'zh' => '谚语',
		'ja' => '諺'
	),
	'all_proverbs' => array(
		'vi' => 'Tất cả danh ngôn',
		'en' => 'All proverbs',
		'ru' => 'Все пословицы',
		'es' => 'Todos los proverbios',
		'zh' => '所有谚语',
		'ja' => 'すべての諺'
	),
	'unsubscribe' => array(
		'vi' => 'Hủy đăng ký',
		'en' => 'Unsubscribe',
		'ru' => 'Отказаться',
		'es' => 'Darse de baja',
		'zh' => '退订',
		'ja' => '退会'
	),
	'unsubscribed_email' => array(
		'vi' => 'Thư điện tử đã được hủy đăng ký',
		'en' => 'Email has already been unsubscribed',
		'ru' => 'Электронная почта уже отписана',
		'es' => 'El correo electrónico ya ha sido cancelado',
		'zh' => '电子邮件已被取消订阅',
		'ja' => 'メールは既に登録解除されています'
	),
	'has_been_unsubscribed' => array(
		'vi' => 'Bạn đã được hủy đăng ký',
		'en' => 'You have been unsubscribed',
		'ru' => 'Ваша подписка была отменена',
		'es' => 'Has sido cancelado',
		'zh' => '你已经退订了',
		'ja' => 'あなたがサブスクライブ解除されています'
	),
	'resubscribe' => array(
		'vi' => 'Đăng ký lại',
		'en' => 'Resubscribe',
		'ru' => 'Переоформить подписку',
		'es' => 'Reinscribirse',
		'zh' => '重新订阅',
		'ja' => '再登録'
	)
);
$email_interfaces = array(
	'hi' => array(
		'vi' => 'Xin chào bạn',
		'en' => 'Hi',
		'ru' => 'Привет',
		'es' => 'Hola',
		'zh' => '你好',
		'ja' => 'こんにちは'
	),
	'happy_birthday' => array(
		'vi' => 'Chúc mừng sinh nhật bạn',
		'en' => 'Happy birthday',
		'ru' => 'Днем Рождения',
		'es' => 'Feliz cumpleaños',
		'zh' => '生日快乐',
		'ja' => 'お誕生日おめでとうございます'
	),
	'create_user_thank' => array(
		'vi' => 'Cám ơn bạn đã quan tâm đến Nhịp sinh học.',
		'en' => 'Thank you for your interest in Biorhythm.',
		'ru' => 'Спасибо за ваш интерес к Биоритмы.',
		'es' => 'Gracias por su interés en Biorritmo usted.',
		'zh' => '感谢您对生物节律的兴趣。',
		'ja' => 'バイオリズムにご関心をお寄せいただき、ありがとうございます。'
	),
	'create_user_detail' => array(
		'vi' => 'Sau đây là thông tin tài khoản của bạn:',
		'en' => 'Here is your account information:',
		'ru' => 'Вот информация Ваш счет:',
		'es' => 'Aquí está la información de su cuenta:',
		'zh' => '这是您的帐户信息：',
		'ja' => 'ここにあなたのアカウント情報は、次のとおりです：'
	),
	'edit_user_notify' => array(
		'vi' => 'Bạn đã cập nhật hồ sơ Nhịp sinh học.',
		'en' => 'You have updated your Biorhythm profile.',
		'ru' => 'Вы обновили свой профиль Биоритм.',
		'es' => 'Ha actualizado su perfil Biorritmo.',
		'zh' => '您已经更新了您的个人资料生物节律。',
		'ja' => 'あなたのバイオリズムのプロフィールを更新しました。'
	),
	'edit_user_detail' => array(
		'vi' => 'Sau đây là thông tin tài khoản của bạn sau khi sửa đổi hồ sơ:',
		'en' => 'Here is your account information after updating profile:',
		'ru' => 'Вот информация Ваш счет после обновления профиля:',
		'es' => 'Aquí está la información de su cuenta después de actualizar el perfil:',
		'zh' => '这里是更新配置文件后您的帐户信息：',
		'ja' => 'ここでは、プロファイルを更新した後、あなたのアカウント情報は、次のとおりです：'
	),
	'daily_suggestion' => array(
		'vi' => 'Đây là lời khuyên cho bạn ngày hôm nay',
		'en' => 'This is your daily suggestion',
		'ru' => 'Это ваш день предложение',
		'es' => 'Esta es su sugerencia diaria',
		'zh' => '这是你的每日建议',
		'ja' => 'これはあなたの毎日の提案です'
	),
	'daily_values' => array(
		'vi' => 'Các chỉ số nhịp sinh học chính của bạn',
		'en' => 'Your primary biorhythm values',
		'ru' => 'Ваши первичные значения биоритмов',
		'es' => 'Sus valores biorritmo primarias',
		'zh' => '您的主要生物节律值',
		'ja' => 'あなたの主なバイオリズム値'
	),
	'go_to_your_profile' => array(
		'vi' => '✭✭✭ Đi đến hồ sơ của bạn ✭✭✭',
		'en' => '✭✭✭ Go to your profile ✭✭✭',
		'ru' => '✭✭✭ Перейти в профиль ✭✭✭',
		'es' => '✭✭✭ Ir a su perfil ✭✭✭',
		'zh' => '✭✭✭ 转到您的个人资料 ✭✭✭',
		'ja' => '✭✭✭ あなたのプロフィールに移動します ✭✭✭'
	),
	'colon' => array(
		'vi' => ':',
		'en' => ':',
		'ru' => ':',
		'es' => ':',
		'zh' => '：',
		'ja' => '：'
	),
	'going_up' => array(
		'vi' => 'đang lên',
		'en' => 'going up',
		'ru' => 'подниматься',
		'es' => 'subiendo',
		'zh' => '上升',
		'ja' => '上がっていく'
	),
	'going_down' => array(
		'vi' => 'đang xuống',
		'en' => 'going down',
		'ru' => 'спускаться',
		'es' => 'bajando',
		'zh' => '下降',
		'ja' => '下っていく'
	),
	'regards' => array(
		'vi' => 'Trân trọng,',
		'en' => 'Best regards,',
		'ru' => 'С уважением,',
		'es' => 'Atentamente,',
		'zh' => '最好的问候，',
		'ja' => '宜しくお願いします、'
	),
	'not_changed' => array(
		'vi' => 'Không thay đổi',
		'en' => 'Not changed',
		'ru' => 'Не изменилось',
		'es' => 'Sin cambio',
		'zh' => '没有改变',
		'ja' => '変更されていません'
	),
	'not_mark_as_spam' => array(
		'vi' => 'Đây không phải là thư rác. Vui lòng không đánh dấu thư rác.',
		'en' => 'This is not a spam. Please do not mark it as spam.',
		'ru' => 'Это не спам. Пожалуйста, не отметить его как спам.',
		'es' => 'Esto no es un spam. Por favor, no marcarlo como spam.',
		'zh' => '这不是一个垃圾邮件。请不要将其标记为垃圾邮件。',
		'ja' => 'これはスパムではありません。スパムとしてそれをマークしないでください。'
	),
	'definition' => array(
		'vi' => 'Nhịp sinh học (tiếng Anh: biorhythm) là một chu trình giả thiết về tình trạng khỏe mạnh hay năng lực sinh lý, cảm xúc, hoặc trí thông minh. Một nghiên cứu ở Nhật Bản trên công ty giao thông Ohmi Railway cũng đã lập các biểu đồ sinh học cho các tài xế lái xe của công ty để họ có sự cảnh giác và phòng tránh. Kết quả tai nạn của các tài xế đã giảm 50% từ năm 1969 đến 1970 tại Tokyo.',
		'en' => 'A biorhythm (from Greek βίος - bios, "life" and ῥυθμός - rhuthmos, "any regular recurring motion, rhythm") is an attempt to predict various aspects of a person\'s life through simple mathematical cycles. Most scientists believe that the idea has no more predictive power than chance and consider the concept an example of pseudoscience.',
		'ru' => 'Биоритм - (биоритмы) периодически повторяющиеся изменения характера и интенсивности биологических процессов и явлений. Они свойственны живой материи на всех уровнях ее организации — от молекулярных и субклеточных до биосферы. Являются фундаментальным процессом в живой природе. Одни биологические ритмы относительно самостоятельны (например, частота сокращений сердца, дыхания), другие связаны с приспособлением организмов к геофизическим циклам — суточным (например, колебания интенсивности деления клеток, обмена веществ, двигательной активности животных), приливным (например, открывание и закрывание раковин у морских моллюсков, связанные с уровнем морских приливов), годичным (изменение численности и активности животных, роста и развития растений и др.)',
		'es' => 'Los biorritmos constituyen un intento de predecir aspectos diversos de la vida de un individuo recurriendo a ciclos matemáticos sencillos. La mayoría de los investigadores estima que esta idea no tendría más poder predictivo que el que podría atribuirse al propio azar, considerándola un caso claro de pseudociencia.',
		'zh' => '生理节律是一種描述人類的身體、情感及智力的假想周期的理論。該概念与生物节律无关。在生物学和医学领域，这个词都是会被小心避免的，因為它被一些人認為是一种伪科学或是前科学。',
		'ja' => 'バイオリズム（英: biorhythm）とは、「生命」を意味する bio-（バイオ）と「規則的な運動」を意味する rhythm（リズム）の合成語で、生命体の生理状態、感情、知性などは周期的パターンに沿って変化するという仮説、およびそれを図示したグラフである。'
	),
	'instruction_video_text' => array(
		'vi' => 'Video hướng dẫn',
		'en' => 'Instruction video',
		'ru' => 'Видео инструкции',
		'es' => 'Instrucción de vídeo',
		'zh' => '教学视频',
		'ja' => '教育ビデオ'
	),
	'instruction_video_youtube_id' => array(
		'vi' => '0od3PsgixvQ',
		'en' => '7dRGGRcvI0E',
		'ru' => 'rp8_cTRP4ro',
		'es' => 'sifJsC3v-Lw',
		'zh' => 'TG2ngtokaVc',
		'ja' => 'SJw7lMuKipc'
	),
	'keyboard_shortcuts' => array(
		'vi' => '<h3>Phím tắt:</h3><ul><li>S / G / K -> Hôm nay</li><li>A / F / J -> Trước<li>D / H / L -> Sau</li><li>W / T / I -> Sinh nhật</li><li>E / Y / O -> Nhịp sinh học phụ</li><li>R / U / P -> Thành ngữ</li><li>1 -> Tiếng Việt</li><li>2 -> Tiếng Anh</li><li>3 -> Tiếng Nga</li><li>4 -> Tiếng Tây Ban Nha</li><li>5 -> Tiếng Trung</li><li>6 -> Tiếng Nhật</li></ul>',
		'en' => '<h3>Keyboard shortcuts:</h3><ul><li>S / G / K -> Today</li><li>A / F / J -> Back</li><li>D / H / L -> Forward</li><li>W / T / I -> Birthday</li><li>E / Y / O -> Secondary rhythm</li><li>R / U / P -> Proverb</li><li>1 -> Vietnamese</li><li>2 -> English</li><li>3 -> Russian</li><li>4 -> Spanish</li><li>5 -> Chinese</li><li>6 -> Japanese</li></ul>',
		'ru' => '<h3>Горячие клавиши:</h3><ul><li>S / G / K -> Сегодня</li><li>A / F / J -> Назад</li><li>D / H / L -> Вперед</li><li>W / T / I -> День рождения</li><li>E / Y / O -> Вторичный ритм</li><li>R / U / P -> Пословица</li><li>1 -> Вьетнамский язык</li><li>2 -> Английский язык</li><li>3 -> Русский язык</li><li>4 -> Испанский язык</li><li>5 -> Китайский язык</li><li>6 -> Японский язык</li></ul>',
		'es' => '<h3>Atajos de teclado:</h3><ul><li>S / G / K -> Hoy</li><li>A / F / J -> Atrás</li><li>D / H / L -> Enviar</li><li>W / T / I -> Cumpleaños</li><li>E / Y / O -> Ritmo secundaria</li><li>R / U / P -> Proverbio</li><li>1 -> Idioma vietnamita</li><li>2 -> Idioma Inglés</li><li>3 -> Idioma Ruso</li><li>4 -> Idioma Espanol</li><li>5 -> Idioma Chino</li><li>6 -> Idioma Japones</li></ul>',
		'zh' => '<h3>快捷键：</h3><ul><li>S，G，K -> 今天</li><li>A，F，J -> 回去</li><li>D，H，L -> 前进</li><li>W，T，I -> 生辰</li><li>E，Y，O -> 次要节奏</li><li>R，U，P -> 谚语</li><li>1 -> 越南语</li><li>2 -> 英语</li><li>3 -> 俄语语言</li><li>4 -> 西班牙语</li><li>5 -> 中文</li><li>6 -> 日文</li></ul>',
		'ja' => '<h3>キーボードショートカット：</h3><ul><li>S、G、K -> 今日</li><li>A、F、J -> 戻る</li><li>D、H、L -> 前進する</li><li>W、T、I -> バースデー</li><li>E、Y、O -> セカンダリリズム</li><li>R、U、P -> ことわざ</li><li>1 -> ベトナム語</li><li>2 -> 英語</li><li>3 -> ロシア語</li><li>4 -> スペイン語</li><li>5 -> チャン語</li><li>6 -> 日本語</li></ul>'
	),
	'unsubscribe' => array(
		'vi' => 'Hủy đăng ký',
		'en' => 'Unsubscribe',
		'ru' => 'Отказаться',
		'es' => 'Darse de baja',
		'zh' => '退订',
		'ja' => '退会'
	),
	'forgot_password' => array(
		'vi' => 'Quên mật khẩu?',
		'en' => 'Forgot password?',
		'ru' => 'Забыли пароль?',
		'es' => '¿Contraseña olvidada?',
		'zh' => '忘记密码？',
		'ja' => 'パスワードをお忘れですか？'
	),
	'forgot_password_alert' => array(
		'vi' => 'Có vẻ như thư điện tử bạn điền không nằm trong hệ thống. Bạn có thể muốn đăng ký tài khoản mới.',
		'en' => 'It seems that your email address is not registered in our system. You may want to register a new account.',
		'ru' => 'Кажется, что ваш адрес электронной почты не зарегистрирован в нашей системе. Возможно, вы захотите зарегистрировать новую учетную запись.',
		'es' => 'Parece que su dirección de correo electrónico no está registrada en nuestro sistema. Es posible que desee registrar una nueva cuenta.',
		'zh' => '看来您的电子邮件地址未在我们的系统中注册。您可能需要注册一个新帐户。',
		'ja' => 'あなたのメールアドレスが私たちのシステムに登録されていないようです。新しいアカウントを登録することができます。'
	),
	'forgot_password_alert_title' => array(
		'vi' => 'Đăng ký tài khoản mới?',
		'en' => 'Register new account?',
		'ru' => 'Регистрация нового аккаунта?',
		'es' => '¿Registrar una cuenta nueva?',
		'zh' => '注册新帐户？',
		'ja' => '新しいアカウントを登録しますか？'
	),
	'reset_password' => array(
		'vi' => 'Đặt lại mật khẩu',
		'en' => 'Reset password',
		'ru' => 'Сброс пароля',
		'es' => 'Restablecer la contraseña',
		'zh' => '重设密码',
		'ja' => 'パスワードを再設定する'
	),
	'reset_password_notify' => array(
		'vi' => 'Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản Nhịp Sinh Học, hãy ấn nút "Đặt lại mật khẩu" bên dưới. Nếu không phải là bạn yêu cầu đặt lại mật khẩu, vui lòng bỏ qua thư này.',
		'en' => 'You requested to reset the password for your account, click the button "Reset password" below. If it was not you requesting the reset, please ignore this email.',
		'ru' => 'Вы попросили сбросить пароль для своей учетной записи, нажмите кнопку «Сбросить пароль» ниже. Если вы не запрашивали сброс, пожалуйста, проигнорируйте это письмо.',
		'es' => 'Ha solicitado restablecer la contraseña de su cuenta, haga clic en el botón "Restablecer contraseña" a continuación. Si no fue usted quien solicita el restablecimiento, ignore este correo electrónico.',
		'zh' => '您要求重置您帐户的密码，请点击下面的“重置密码”按钮。 如果不是您请求重置，请忽略此电子邮件。',
		'ja' => 'アカウントのパスワードをリセットするように要求された場合は、下の[パスワードをリセット]ボタンをクリックしてください。 リセットをリクエストしていない場合は、このメールを無視してください。'
	),
	'register' => array(
		'vi' => 'Đăng ký',
		'en' => 'Register',
		'ru' => 'Регистр',
		'es' => 'Registrarse',
		'zh' => '注册',
		'ja' => '登録'
	)
);
$menu_interfaces = array(
	'today' => array(
		'vi' => 'Hôm nay',
		'en' => 'Today',
		'ru' => 'Сегодня',
		'es' => 'Hoy',
		'zh' => '今天',
		'ja' => '今日'
	),
	'prev' => array(
		'vi' => 'Trước',
		'en' => 'Back',
		'ru' => 'Назад',
		'es' => 'Atrás',
		'zh' => '回去',
		'ja' => '戻る'
	),
	'next' => array(
		'vi' => 'Sau',
		'en' => 'Forward',
		'ru' => 'Вперед',
		'es' => 'Enviar',
		'zh' => '前进',
		'ja' => '前進する'
	),
	'next_birthday' => array(
		'vi' => 'Sinh nhật tới',
		'en' => 'Next birthday',
		'ru' => 'Следующий день рождения',
		'es' => 'Próximo cumpleaños',
		'zh' => '下一个生日',
		'ja' => '次の誕生日'
	)
);
$error_interfaces = array(
	'not_filled' => array(
		'vi' => 'Chưa điền hết các mục',
		'en' => 'All the fields must be filled in',
		'ru' => 'Все поля должны быть заполнены',
		'es' => 'Todos los campos deben ser llenados',
		'zh' => '所有字段必须填写',
		'ja' => 'すべてのフィールドは記入する必要があります'
	),
	'invalid_member' => array(
		'vi' => 'Thư điện tử hoặc mật khẩu không chính xác',
		'en' => 'Incorrect email or password',
		'ru' => 'Неверный адрес электронной почты или пароль',
		'es' => 'Correo o contraseña incorrectos',
		'zh' => '不正确的电子邮件或密码',
		'ja' => '不適切な電子メールやパスワード'
	),
	'invalid_email' => array(
		'vi' => 'Thư điện tử không hợp lệ',
		'en' => 'Invalid email',
		'ru' => 'Неверный адрес электронной почты',
		'es' => 'Email inválido',
		'zh' => '不合规电邮',
		'ja' => '無効なメール'
	),
	'short_pass' => array(
		'vi' => 'Mật khẩu quá ngắn (≥ 8)',
		'en' => 'Password too short (≥ 8)',
		'ru' => 'Пароль слишком короткий (≥ 8)',
		'es' => 'Contraseña demasiado corta (≥ 8)',
		'zh' => '密码太短 (≥= 8)',
		'ja' => 'パスワードが短すぎます (≥ 8)'
	), 
	'long_pass' => array(
		'vi' => 'Mật khẩu quá dài (≤ 20)',
		'en' => 'Password too long (≤ 20)',
		'ru' => 'Пароль слишком долго (≤ 20)',
		'es' => 'Contraseña demasiado largo (≤ 20)',
		'zh' => '密码太长 (≤ 20)',
		'ja' => 'あまりにも長いパスワード (≤ 20)'
	),
	'no_number_pass' => array(
		'vi' => 'Mật khẩu phải chứa ít nhất 1 chữ số',
		'en' => 'Password must include at least one number',
		'ru' => 'Пароль должен содержать по крайней мере один номер',
		'es' => 'La contraseña debe incluir al menos un número',
		'zh' => '密码必须包括至少一个数',
		'ja' => 'パスワードは、少なくとも1つの番号を含める必要があります'
	),
	'no_letter_pass' => array(
		'vi' => 'Mật khẩu phải chứa ít nhất 1 chữ cái',
		'en' => 'Password must include at least one letter',
		'ru' => 'Пароль должен содержать по меньшей мере одну букву',
		'es' => 'La contraseña debe incluir al menos una letra',
		'zh' => '密码必须包含至少一个字母',
		'ja' => 'パスワードは、少なくとも1つの文字を含める必要があります'
	),
	'no_caps_pass' => array(
		'vi' => 'Mật khẩu phải chứa ít nhất 1 chữ cái VIẾT HOA',
		'en' => 'Password must include at least one CAPS',
		'ru' => 'Пароль должен содержать по крайней мере один ЗАГЛАВНАЯ БУКВА',
		'es' => 'La contraseña debe incluir al menos un MAYÚSCULA',
		'zh' => '密码必须包括至少一个大写字母',
		'ja' => 'パスワードは、少なくとも1つの大文字を含める必要があります'
	),
	'no_symbol_pass' => array(
		'vi' => 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt',
		'en' => 'Password must include at least one symbol',
		'ru' => 'Пароль должен содержать по меньшей мере одну символ',
		'es' => 'La contraseña debe incluir al menos una símbolo',
		'zh' => '密码必须包含至少一个符号',
		'ja' => 'パスワードは、少なくとも1つのシンボルを含める必要があります'
	),
	'not_match_pass' => array(
		'vi' => 'Mật khẩu không khớp',
		'en' => 'Password not match',
		'ru' => 'Пароль не совпадает',
		'es' => 'La contraseña no coincide',
		'zh' => '密码不匹配',
		'ja' => 'パスワードが一致しません'
	),
	'invalid_dob' => array(
		'vi' => 'Ngày sinh không hợp lệ',
		'en' => 'Invalid date of birth',
		'ru' => 'Неправильная дата рождения',
		'es' => 'Fecha no válida de nacimiento',
		'zh' => '出生日期无效',
		'ja' => '誕生の無効な日付'
	),
	'taken_email' => array(
		'vi' => 'Thư điện tử đã được người khác dùng',
		'en' => 'Email taken',
		'ru' => 'Электронная почта приняты',
		'es' => 'Email tomada',
		'zh' => '采取电子邮件',
		'ja' => 'メール撮影'
	),
	'unsubscribed_email' => array(
		'vi' => 'Thư điện tử đã được hủy đăng ký rồi',
		'en' => 'Email has already been unsubscribed',
		'ru' => 'Электронная почта уже отписана',
		'es' => 'El correo electrónico ya ha sido cancelado',
		'zh' => '电子邮件已被取消订阅',
		'ja' => 'メールは既に登録解除されています'
	),
	'no_email' => array(
		'vi' => 'Thư điện tử chưa đăng ký',
		'en' => 'Email has not been subscribed yet',
		'ru' => 'Электронная почта еще не подписана',
		'es' => 'El correo electrónico no se ha suscrito aún',
		'zh' => '电子邮件尚未订阅',
		'ja' => '電子メールはまだ購読されていません'
	),
	'invalid_input' => array(
		'vi' => 'Đầu vào không hợp lệ',
		'en' => 'Invalid input',
		'ru' => 'Неправильный ввод',
		'es' => 'Entrada inválida',
		'zh' => '输入无效',
		'ja' => '無効入力'
	)
);
$help_interfaces = array(
	'help_h5' => array(
		'vi' => 'Hướng dẫn',
		'en' => 'Instruction',
		'ru' => 'Инструкция',
		'es' => 'Instrucción',
		'zh' => '指令',
		'ja' => '命令'
	),
	'help_p' => array(
		'vi' => 'Nhập thông tin ngày tháng năm sinh Dương lịch của bạn vào ô Ngày sinh theo định dạng YYYY-MM-DD (năm-tháng-ngày). Sau đó, nhấn nút &#9658; để hiển thị dự đoán Sức khỏe, Tình cảm, Trí tuệ. Nếu bạn chỉ quan tâm đến Nhịp sinh học ngủ, bạn không cần điền Họ tên và Ngày sinh.',
		'en' => 'Type in your date of birth into the Date of birth field with the format YYYY-MM-DD (year-month-day). Then click &#9658; to know your physical, emotional, intellectual values. If you only care about Sleep Rhythm, you don\'t need to type in your Full name or Date of birth.',
		'ru' => 'Введите дату своего рождения в поле День Рождения с форматом YYYY-MM-DD (год-месяц-день). Затем нажмите кнопку &#9658;, чтобы узнать ваши физические, эмоциональные, интеллектуальные ценности. Если вы только заботиться о Sleep ритм, вам не нужно вводить полное имя или дату рождения.',
		'es' => 'Escriba su fecha de nacimiento en el campo Fecha de nacimiento con el formato YYYY-MM-DD (año-mes-dia). Luego haga clic en &#9658; para conocer sus valores, físicas, intelectuales y emocionales. Si sólo se preocupa por el sueño Ritmo, usted no tiene que escribir su nombre o fecha de nacimiento completa.',
		'zh' => '输入你的出生日期为出生场的日期格式为YYYY-MM-DD（年-月-日）。然后点击&#9658;就知道你的身体，情绪，智力值。如果你只在乎睡眠节律，你不需要输入您的姓名和出生日期。',
		'ja' => '誕生フィールドの日にあなたの生年月日を入力しますフォーマットYYYY-MM-DDと（年-月-日）。 次に、あなたの物理的、感情的、知的な値を知るために実行]をクリックします。あなたが唯一の睡眠リズムに関心があるのであれば、あなたは自分のフルネームや生年月日を入力する必要はありません。'
	),
	'news_box' => array(
		'vi' => 'Hiển thị các tin tức liên quan đến bạn.',
		'en' => 'Show the news related to you.',
		'ru' => 'Показать новости, связанные с вами.',
		'es' => 'Mostrar las noticias relacionadas con usted.',
		'zh' => '显示的消息与你。',
		'ja' => 'あなたに関連するニュースを表示します。'
	),
	'stats_box' => array(
		'vi' => 'Hiển thị các thông số liên quan đến ngày sinh của bạn.',
		'en' => 'Display the general statistics related to your birth date.',
		'ru' => 'Показать общую статистику, связанные с вашей даты рождения.',
		'es' => 'Mostrar las estadísticas generales relacionadas con su fecha de nacimiento.',
		'zh' => '显示与你的出生日期的统计资料。',
		'ja' => 'あなたの誕生日に関連する一般的な統計情報を表示します。'
	),
	'lunar_box' => array(
		'vi' => 'Hiển thị ngày tháng năm sinh Âm lịch của bạn. Ấn nút Ngày Âm lịch sẽ hiện ra ngày tháng năm Âm lịch hiện tại. Đây là tính năng VIP.',
		'en' => 'Display the Lunar Calendar year, month and day of your birth date. Click on Lunar date to show the current Lunar year, month and day. This is a VIP feature.',
		'ru' => 'Отображение лунному календарю год, месяц и день вашего дня рождения. Нажмите на Лунной даты, чтобы показать текущий лунный год, месяц и день. Это VIP-функция.',
		'es' => 'Visualizar el calendario lunar año, mes y día de su fecha de nacimiento. Clic en la fecha Lunar para mostrar la corriente Lunar año, mes y día. Esta es una característica VIP.',
		'zh' => '显示您的出生日期是农历日历年，月，日。点击农历日期显示当前的农历年，月，日。这是一个VIP功能。',
		'ja' => 'あなたの誕生日の太陰暦の年、月、日を表示します。現在の月の年、月、日を示すために月の日付をクリックします。これはVIPの機能です。'
	),
	'compatibility_box' => array(
		'vi' => 'Cho biết sự khả năng hòa hợp giữa Bạn và Đối tác (người yêu, bạn bè, thân hữu). Chọn ngày sinh (theo thứ tự năm, tháng, ngày) của Đối tác để xem các chỉ số thể hiện mức độ hòa hợp, chỉ số phần trăm càng cao thì càng gần gũi.',
		'en' => 'Show the Compatibility between you and your Partner (lover, friends, acquaintance). Choose the birth date of your partner to get the values indicating compatibility, the higher the more compatible.',
		'ru' => 'Показать совместимость между вами и вашим партнером (любовник, друзья, знакомства). Выберите дату рождения вашего партнера, чтобы получить значения, указывающие совместимость, выше более совместимыми.',
		'es' => 'Mostrar el Compatibilidad entre usted y su pareja (amante, amigos, conocidos). Elija la fecha de nacimiento de su pareja para obtener los valores que indican la compatibilidad, el más alto es el más compatible.',
		'zh' => '告诉你和你的伴侣（情人，朋友，熟人）之间的兼容性。选择你的伴侣的出生日期以获得显示兼容性值，越高越不兼容。',
		'ja' => 'あなたとあなたのパートナー（恋人、友人、知人）間の互換性を表示します。より互換性が高い、互換性を示す値を取得するためにあなたのパートナーの誕生日を選択してください。'
	),
	'info_box' => array(
		'vi' => 'Hiển thị lời khuyên cho ngày hiện tại.',
		'en' => 'Display the suggestion for current date.',
		'ru' => 'Показать предложение для текущей даты.',
		'es' => 'Visualice la sugerencia para la fecha actual.',
		'zh' => '显示的建议为当前日期。',
		'ja' => '現在の日付のための提案を表示します。'
	),
	'controls_box' => array(
		'vi' => 'Hiển thị các chỉ số nhịp sinh học cho ngày hiện tại, chỉ số phần trăm càng cao thì càng tốt. Ấn nút Hiện nhịp sinh học phụ để hiện ra thêm các chỉ số phụ. Chọn ngày bằng cách ấn ô Xem ngày. Ấn nút Trước và Sau để thay đổi ngày hiện tại, nút Hôm nay để trở về Hôm nay.',
		'en' => 'Display the biorhythm values for the current date, the higher the better. Press the button Show secondary rhythms to show more biorhythm values. Choose the date by clicking on the field View date. Click on Back or Forward to change the current date, show today values by clicking on Today.',
		'ru' => 'Отображение значения биоритмов на текущую дату. Нажмите кнопку Показать вторичные ритмы, чтобы показать несколько значений биоритмов. Выберите дату, нажав на дату поле зрения. Нажмите на вперед или назад, чтобы изменить текущую дату, показать значения сегодня, нажав на Сегодня.',
		'es' => 'Muestra los valores de biorritmo para la fecha actual. Pulse el botón Mostrar ritmos secundarias para mostrar más valores biorritmo. Elija la fecha haciendo clic en el campo de fecha Vista. Haga clic en Atrás o en Siguiente para cambiar la fecha actual, mostrar los valores actuales, haga clic en Hoy.',
		'zh' => '显示的生物节律的值对于当前日期。按下按钮显示次要节奏，表现出更多的生物节律值。通过单击现场查看日期。单击后退或前进，以改变当前的日期，点击今天显示今天的价值观。',
		'ja' => '現在の日付のためのバイオリズム値を表示します。より多くのバイオリズムの値を表示するためにボタンを表示二次リズムを押します。フィールドビューの日付をクリックして日付を選択してください。 、現在の日付を変更するには、戻るまたは進むをクリックして今日をクリックすることで、今日の値を表示。'
	),
	'biorhythm' => array(
		'vi' => 'nhịp sinh học',
		'en' => 'biorhythm',
		'ru' => 'биоритм',
		'es' => 'biorritmo',
		'zh' => '生理节律',
		'ja' => 'バイオリズム'
	),
	'definition' => array(
		'vi' => 'Nhịp sinh học (tiếng Anh: biorhythm) là một chu trình giả thiết về tình trạng khỏe mạnh hay năng lực sinh lý, cảm xúc, hoặc trí thông minh.',
		'en' => 'A biorhythm (from Greek βίος - bios, "life" and ῥυθμός - rhuthmos, "any regular recurring motion, rhythm"[2]) is an attempt to predict various aspects of a person\'s life through simple mathematical cycles. Most scientists believe that the idea has no more predictive power than chance and consider the concept an example of pseudoscience.',
		'ru' => 'Биологи́ческие ри́тмы — (биоритмы) периодически повторяющиеся изменения характера и интенсивности биологических процессов и явлений. Они свойственны живой материи на всех уровнях ее организации — от молекулярных и субклеточных до биосферы. Являются фундаментальным процессом в живой природе.',
		'es' => 'Los biorritmos constituyen un intento de predecir aspectos diversos de la vida de un individuo recurriendo a ciclos matemáticos sencillos. La mayoría de los investigadores estima que esta idea no tendría más poder predictivo que el que podría atribuirse al propio azar, considerándola un caso claro de pseudociencia.',
		'zh' => '生理节律是一種描述人類的身體、情感及智力的假想周期的理論。該概念与生物节律无关。在生物学和医学领域，这个词都是会被小心避免的，因為它被一些人認為是一种伪科学或是前科学。',
		'ja' => 'バイオリズム（英: biorhythm）とは、「生命」を意味する bio-（バイオ）と「規則的な運動」を意味する rhythm（リズム）の合成語で、生命体の生理状態、感情、知性などは周期的パターンに沿って変化するという仮説、およびそれを図示したグラフである。'
	),
	'wiki' => array(
		'vi' => 'https://vi.wikipedia.org/wiki/Nh%E1%BB%8Bp_sinh_h%E1%BB%8Dc',
		'en' => 'https://en.wikipedia.org/wiki/Biorhythm',
		'ru' => 'https://ru.wikipedia.org/wiki/%D0%91%D0%B8%D0%BE%D1%80%D0%B8%D1%82%D0%BC',
		'es' => 'https://es.wikipedia.org/wiki/Biorritmo',
		'zh' => 'https://zh.wikipedia.org/wiki/%E7%94%9F%E7%90%86%E8%8A%82%E5%BE%8B',
		'ja' => 'https://ja.wikipedia.org/wiki/%E3%83%90%E3%82%A4%E3%82%AA%E3%83%AA%E3%82%BA%E3%83%A0'
	),
	'life_path_number_prefix' => array(
		'vi' => 'https://nhipsinhhoc.vn/wiki/Con_s%E1%BB%91_cu%E1%BB%99c_%C4%91%E1%BB%9Di_',
		'en' => 'https://www.astrology-numerology.com/num-lifepath.html#lp',
		'ru' => 'https://www.astroland.ru/numerology/lw/lifeway',
		'es' => 'https://www.horoscopius.es/numerologia/perfil-numerologico-para-el-numero-',
		'zh' => 'https://nhipsinhhoc.vn/blog/life-path-number-',
		'ja' => 'https://www.heavenlyblue.jp/num/'
	),
	'life_path_number_suffix' => array(
		'vi' => '',
		'en' => "",
		'ru' => '.htm',
		'es' => '/',
		'zh' => '/',
		'ja' => '.html'
	),
	'youtube_id' => array(
		'vi' => '0od3PsgixvQ',
		'en' => '7dRGGRcvI0E',
		'ru' => 'rp8_cTRP4ro',
		'es' => 'sifJsC3v-Lw',
		'zh' => 'TG2ngtokaVc',
		'ja' => 'SJw7lMuKipc'
	)
);
$information_interfaces = array(
	'average' => array(
		'vi' => array(
			'excellent' => 'Xin chúc mừng bạn. Ngày hiện tại của bạn rất tốt, bạn nên tận hưởng ngày này.',
			'good' => 'Chúc mừng bạn. Ngày hiện tại của bạn khá tốt, tuy nhiên bạn không nên chủ quan trong ngày này.',
			'gray' => 'Chúc bạn một ngày tốt lành. Ngày hiện tại của bạn không được tốt lắm, bạn nên cẩn trọng hơn.',
			'bad' => 'Chúc bạn một ngày vui vẻ. Rất tiếc phải thông báo rằng ngày hiện tại của bạn hơi xấu, mong bạn cẩn thận.'
		),
		'en' => array(
			'excellent' => 'Your current day is excellent, enjoy it.',
			'good' => 'Your current day is quite good, take a little care.',
			'gray' => 'Your current day is not good, take more care of yourself.',
			'bad' => 'Your current day is bad, should take a lot of care.'
		),
		'ru' => array(
			'excellent' => 'Ваш текущий день отлично, наслаждаться ею.',
			'good' => 'Ваш текущий день является достаточно хорошим, возьмите немного заботы.',
			'gray' => 'Ваш текущий день не очень хорошо, больше заботиться о себе.',
			'bad' => 'Ваш текущий день плохо, должно занять много ухода.'
		),
		'es' => array(
			'excellent' => 'Su día actual es excelente, que lo disfruten.',
			'good' => 'Su día actual es bastante buena, tomar un poco de cuidado.',
			'gray' => 'Su día actual no es bueno, tener más cuidado de ti mismo.',
			'bad' => 'Su día actual es mala, hay que tener mucho cuidado.'
		),
		'zh' => array(
			'excellent' => '您当前的一天是优秀的，享受它。',
			'good' => '您当前的一天是相当不错的，需要一点点的关心。',
			'gray' => '您当前的日子是不好的，把自己的更多的关怀。',
			'bad' => '您当前的日子是不好的，应该采取大量的关怀。'
		),
		'ja' => array(
			'excellent' => 'あなたの現在の日が優れている、それを楽しむ。',
			'good' => '現在の日はかなり良いです、少し注意してください。',
			'gray' => '現在の日はよくない、自分のことをより多くの世話をする。',
			'bad' => 'あなたの現在の日が悪い、介護の多くを取る必要があります。'
		)
	),
	'physical' => array(
		'vi' => array(
			'excellent' => 'Sức khỏe hiện tại của bạn rất sung mãn, hãy tham gia vận động nhiều hơn, như vận động thể dục, thể thao, tham gia các cuộc vui, để tận dụng năng lượng nhé. Sức đề kháng của bạn rất cao nên đây có thể là lúc phát bệnh mà bạn đã ủ trong suốt thời gian vừa qua.',
			'good' => 'Sức khỏe hiện tại của bạn khá sung mãn, hãy vận động điều độ, như các hoạt động thể dục nhẹ nhàng nha bạn.',
			'critical' => 'Sức khỏe hiện tại của bạn đang rơi vào giai đoạn chuyển tiếp, bạn nên nghỉ ngơi nhiều lên nhé, do thể lực bạn đang biến đổi khó lường.',
			'gray' => 'Sức khỏe hiện tại của bạn hơi kém, hãy nghỉ ngơi một tí, do thể lực đã ở vào mức khá thấp, hãy tích trữ năng lượng để dành vào những lúc sung mãn nha.',
			'bad' => 'Sức khỏe hiện tại của bạn khá kém, hãy nghỉ ngơi nhiều hơn, bạn đã hoạt động nhiều rồi, thời gian này nên dành để ngủ đông nhé. Sức đề kháng của bạn lúc này khá kém nên đây có thể là thời gian ủ bệnh.'
		),
		'en' => array(
			'excellent' => 'Your current health is excellent, you should work out more, take part in sport events so as to make use of this full energy time. Your immune system at this time will be high so your illness will be discovered. But no worry, your body will have overcome it easily.',
			'good' => 'Your current health is quite good, you should work out with care because your health has slightly decreased.',
			'critical' => 'Your current health is in critical period, you should be extremely careful. It is because you are in an unstable state.',
			'gray' => 'Your current health is not good, take a little rest because your physical state is quite low. You should save you energy for use in hyper states.',
			'bad' => 'Your current health is bad, take more rest, you have worked out a lot, this time is for hibernation.'
		),
		'ru' => array(
			'excellent' => 'Ваше текущее здоровье отличное, вы должны работать больше.',
			'good' => 'Ваше текущее здоровье неплохое, вы должны работать с осторожностью.',
			'critical' => 'Ваше текущее здоровье в критический период, вы должны быть очень осторожны.',
			'gray' => 'Ваше текущее здоровье не хорошо, немного отдохнуть.',
			'bad' => 'Ваше текущее здоровье плохо, взять больше отдыхать.'
		),
		'es' => array(
			'excellent' => 'Su estado de salud actual es excelente, debe trabajar más.',
			'good' => 'Su estado de salud actual es bastante bueno, usted debe hacer ejercicio con cuidado.',
			'critical' => 'Su salud actual está en el período crítico , debe ser extremadamente cuidadoso.',
			'gray' => 'Su estado de salud actual no es buena, tomar un poco de descanso.',
			'bad' => 'Su estado de salud actual es mala, tener más descanso.'
		),
		'zh' => array(
			'excellent' => '您当前的健康是优秀的，你应该更多。',
			'good' => '您当前的健康是相当不错的，你应该制定出谨慎。',
			'critical' => '您当前的健康是关键时期，你应该非常小心。',
			'gray' => '你目前的身体不好，需要一点休息。',
			'bad' => '您当前的健康是不好的，需要更多的休息。'
		),
		'ja' => array(
			'excellent' => 'あなたの現在の健康状態が優れている、あなたはより多くを動作するはずです。',
			'good' => 'あなたの現在の健康状態はかなり良いですが、あなたが注意して動作するはずです。',
			'critical' => 'あなたの現在の健康状態は、臨界期に、あなたは非常に慎重であるべきです。',
			'gray' => 'あなたの現在の健康状態が良くない、少し休憩を取る。',
			'bad' => 'あなたの現在の健康状態が悪いと、より多くの休息を取る。'
		)
	),
	'emotional' => array(
		'vi' => array(
			'excellent' => 'Tâm trạng hiện tại của bạn rất ổn, hãy tham gia gặp gỡ bạn bè nhiều hơn, dành thời gian hẹn hò, đi chơi với những người thân yêu của mình để tận dụng lúc cảm xúc đang thăng hoa bạn nhé.',
			'good' => 'Tâm trạng hiện tại của bạn khá ổn, hãy gặp gỡ bạn bè, người thân, nhưng cũng chú ý tránh những xung đột nhỏ để cho cuộc vui được trọn vẹn nha bạn.',
			'critical' => 'Tâm trạng hiện tại của bạn đang rơi vào giai đoạn chuyển giao, hãy chú ý nhiều đến cảm xúc của mình, do đây là lúc cảm xúc thay đổi khó lường.',
			'gray' => 'Tâm trạng hiện tại của bạn hơi tệ, bạn hơi dễ cáu kỉnh, dễ cãi nhau, vì thế, bạn nên tìm đến những góc nhỏ cho riêng mình, để tĩnh tâm lại bạn nhé.',
			'bad' => 'Tâm trạng hiện tại của bạn khá tệ, bạn nên tránh các cuộc xung đột, cãi vã, vì lúc này điều đó rất dễ xảy ra. Bạn nên dành thời gian ở một mình, khoảng thời gian này sẽ qua mau thôi.'
		),
		'en' => array(
			'excellent' => 'Your current mood is excellent, you should meet more friends, spend time dating, go out with your beloved ones.',
			'good' => 'Your current mood is quite good, you should meet some friends and avoid some arguments so as to have happy moments together.',
			'critical' => 'Your current mood is in critical period, you should pay more attention to your feelings because this is the unstable state in your mood.',
			'gray' => 'Your current mood is not good, you are easily annoyed. You should spend more time alone to calm your mood',
			'bad' => 'Your current mood is bad, avoid conflicts as they will occur more. Spend time alone and hope that this time will not last long.'
		),
		'ru' => array(
			'excellent' => 'Ваше текущее настроение отличное, вы встретите больше друзей.',
			'good' => 'Ваше текущее настроение неплохое, вы должны встретиться с друзьями.',
			'critical' => 'Ваше текущее настроение в критический период, вы должны уделять больше внимания на ваши чувства.',
			'gray' => 'Ваше текущее настроение не очень хорошо, вы легко раздражаться.',
			'bad' => 'Ваше текущее настроение плохое, во избежание конфликтов.'
		),
		'es' => array(
			'excellent' => 'Su estado de ánimo actual es excelente, te encuentras con más amigos.',
			'good' => 'Su estado de ánimo actual es bastante buena, usted debe cumplir con algunos amigos.',
			'critical' => 'Su estado de ánimo actual está en el período crítico, se debe prestar más atención a sus sentimientos.',
			'gray' => 'Su estado de ánimo actual no es bueno, ustedes son fácilmente molesto.',
			'bad' => 'Su estado de ánimo actual es mala, evitar conflictos.'
		),
		'zh' => array(
			'excellent' => '你现在的心情非常好，你认识更多的朋友。',
			'good' => '你现在的心情是相当不错的，你应该满足一些朋友。',
			'critical' => '您现在的心情是关键时期，你应该更加注意你的感受。',
			'gray' => '你现在的心情不是很好，你很容易生气。',
			'bad' => '你现在的心情不好，避免冲突。'
		),
		'ja' => array(
			'excellent' => 'あなたの現在の気分が優れている、あなたはより多くの友人に会う。',
			'good' => 'あなたの現在の気分はかなり良いですが、あなたは何人かの友人を満たしている必要があります。',
			'critical' => 'あなたの現在の気分は、臨界期に、あなたはあなたの気持ちにもっと注意を払う必要があります。',
			'gray' => 'あなたの現在の気分が良くない、あなたは簡単にイライラです。',
			'bad' => 'あなたの現在の気分が悪い、競合を避ける。'
		)
	),
	'intellectual' => array(
		'vi' => array(
			'excellent' => 'Trí tuệ hiện tại của bạn rất sáng suốt, bạn có thể đưa ra những quyết định đúng đắn, có những suy nghĩ rất chính xác, hợp lý.',
			'good' => 'Trí tuệ hiện tại của bạn khá sáng suốt, bạn có thể đưa ra quyết định nhưng cần suy tính kỹ, bởi vì suy nghĩ của bạn chưa đạt độ chính xác cao nhất có thể.',
			'critical' => 'Trí tuệ hiện tại của bạn đang ở trong giai đoạn chuyển biến, bạn nên chú ý kỹ hơn đến suy nghĩ của mình, vì nó có thể dẫn đến những quyết định sai lầm.',
			'gray' => 'Trí tuệ hiện tại của bạn hơi thiếu sáng suốt, bạn nên suy nghĩ kỹ trước khi ra quyết định. Nếu cần thiết, hãy hỏi thêm ý kiến của người thân, bạn bè, đồng nghiệp.',
			'bad' => 'Trí tuệ hiện tại của bạn khá thiếu sáng suốt, bạn không nên đưa ra quyết định lớn. Nếu phải ra quyết định, bạn nhất định nên hỏi ý kiến người khác.'
		),
		'en' => array(
			'excellent' => 'Your current intellect is excellent, you can make great decisions, think logically and precisely.',
			'good' => 'Your current intellect is quite good, you can make decisions with a little care.',
			'critical' => 'Your current intellect is in critical period, you should pay extra attention to your thoughts, as it may lead to wrong decisions.',
			'gray' => 'Your current intellect is not good, you should think twice before making decisions.',
			'bad' => 'Your current intellect is bad, you should not make big decisions.'
		),
		'ru' => array(
			'excellent' => 'Ваше текущее интеллект отлично, вы можете сделать большие решения.',
			'good' => 'Ваше текущее интеллект является достаточно хорошим, вы можете принимать решения с особого ухода.',
			'critical' => 'Ваше текущее интеллект в критический период, следует обратить особое внимание на ваши мысли, так как это может привести к неправильным решениям.',
			'gray' => 'Ваше текущее интеллект не является хорошим, вы должны подумать дважды, прежде чем принимать решения.',
			'bad' => 'Ваше текущее интеллект плохо, вы не должны делать большие решения.'
		),
		'es' => array(
			'excellent' => 'Su intelecto actual es excelente, puedes tomar grandes decisiones.',
			'good' => 'Su intelecto actual es bastante buena, se puede tomar decisiones con un poco de cuidado.',
			'critical' => 'Su intelecto actual está en período crítico, se debe prestar especial atención a sus pensamientos, ya que puede conducir a decisiones equivocadas.',
			'gray' => 'Su intelecto actual no es buena, usted debe pensar dos veces antes de tomar decisiones.',
			'bad' => 'Su intelecto actual es mala, no debe tomar decisiones importantes.'
		),
		'zh' => array(
			'excellent' => '您当前的智力是优秀的，你可以做出伟大的决定。',
			'good' => '您当前的智力是相当不错的，你可以用一点点小心做出决定。',
			'critical' => '您当前的智力是关键时期，你要格外注意你的想法，因为这可能会导致错误的决策。',
			'gray' => '您当前的智力不好，你做决策前，应三思而后行。',
			'bad' => '您当前的智力是坏的，你不应该做出重大的决定。'
		),
		'ja' => array(
			'excellent' => 'あなたの現在の知性は、あなたは偉大な決定を行うことができ、優れたものである。',
			'good' => 'あなたの現在の知性はかなり良いですが、あなたは少し注意して意思決定を行うことができます。',
			'critical' => 'それは間違った意思決定につながる可能性としてあなたの現在の知性は、臨界期に、あなたは、あなたの考えに特別な注意を払う必要がありますされています。',
			'gray' => 'あなたの現在の知性はあなたが意思決定をする前に二度考える必要があり、良いではありません。',
			'bad' => 'あなたの現在の知性は、あなたは大きな意思決定を行うべきではない、悪いです。'
		)
	)
);
// DKIM is used to sign e-mails. If you change your RSA key, apply modifications to the DNS DKIM record of the mailing (sub)domain too !
// Disclaimer : the php openssl extension can be buggy with Windows, try with Linux first

// To generate a new private key with Linux :
// openssl genrsa -des3 -out private.pem 1024
// Then get the public key
// openssl rsa -in private.pem -out public.pem -outform PEM -pubout

// Edit with your own info :

define('MAIL_RSA_PASSPHRASE', 'nhipsinhhoc');

define('MAIL_RSA_PRIV',
'-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA2FAIgMDFIe/HC3oQEeAFXglSzVNlWsEUW7YZmEEKT86Usdxh
BxSpdWDBC6F6USmjV7QO22L+KLHh3o0CnPgKxKetdWNX72kATq/WYMF4jHMFPCi+
yJ+j9+j5HyW+w4kARoRfzZcEf39SeO2YQnWVgjAn6vDfOGvU2R7jpEME7Q7iEwJz
xhrFqnT4WqkH/iscAOgpP897vNLALKHlNTY8cM40jbQfeybu96i0nIGlbeH11nf0
y3zmnMjdX5yY37443+9CjFjtotIo9GhciWN50+O9KtS7MZM19/z8HZzrIXqy4yJu
JgC4JPq5SshO83Hzbipe0kUeqe625QUFkr+zyQIDAQABAoIBAQCEp7g/NXjvkfuQ
R0AZpjfEbpVQBAfROz1/7NIPdDudq8O2u77pN7ugl0BsIJBBu/ZHL844rqHNVSF+
eR2UI+1+opIWvmDMGqmsl9sxpRSHlXYtaZNut7A3tbEpb91oTtlTZZTXIPkKM4vh
S1wnzbJtj5i7VRKfqEl1CaNzNgKMWYYtRyT43P/ZVx2zzruwWsnZm4D0EYaeXqsi
dg9PYhqaDUKLW1uEgCR6/sC5WhxAwdaUczPlv+ZHoBMxZK3oANJwQCHOhtiZw81A
LfEl0djhvhIf3YXtT1JN/NE6JXnGTBS1ykimxW7rCMD3lpi55y5gsonktUal9vMx
Hnw72RIxAoGBAPiTq/GEweAdpDrUOBkTr36gp7lnG5Q5AMshnMtPyxNusjIo7jdO
OOUWNiWnGxHrM9UH3Ln9RSDiDyabjvpD/mp8RFerJGSRq9q/lC7be1mnFiO3HcY+
XaZLBAhBlCIt6VpMxwlcmoKAKf0BKhOpNpF70BRDpdN+FL6rR8h7IeKVAoGBAN7F
tQGiWY+aY6Kr5dzqOZyfMZX4n8JkxeCTpXNXZ/M0D4IoP/l2pa/E7euDrd38CG8Z
pDcI+OKhmF0rE2Ax4dElmuqvNmnj7ev8y1q+41mzi65yUm1Pj20BCZhNpoc7Q8VU
aAc0VNDxreMyeuxI6f8i1qj1jBsuaiGnI46yf1NlAoGBANn/kATAu9KTzEq3gPcl
F3yC4nUrorksALEkqkB3mw5Qv0BUOw4PsL/f6d69nXTqg8tpGL+YCX8cIdNnC04a
QU4b60fDVKhKRKAT8Z3iaFwot7bcyeTpHvJOyZt//6y1/PdvbAKezvZx9eDnm4Ig
nTWbktGivhQrd3/78Hk223G9AoGANOCw6kZjA9cdt0seMKXYLvkKiBKPuVC6N2hU
aWBh6DR/SeHL7abgBSy1XKXQ61QbnGN1uyqCCSlaRSMoYjj05PQMJZCZVeEoZE+I
5W1SAcTAbxPrxM6RuMn6QRCNaqQCniA+4Rbm2wHCyJ7+1n8oK8tVMJ24N+t4faxy
oDh2SJECgYEAl4nSqxFO5cQlArum8qjfDFC/1qQp1Px/NfrG1QssvUYHuPoIuHk9
G06bBta22pT+BxdX48b9tTdoz5szJNgFrAfnc6sJjELeyO/pOhNDwInPdO4VSQ0Z
NWlfMw/pZZd3hmr2vzTJhylISVv4FArB+LzOo10YQesDE4ZCXrmdb44=
-----END RSA PRIVATE KEY-----');

define('MAIL_RSA_PUBL',
'-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2FAIgMDFIe/HC3oQEeAF
XglSzVNlWsEUW7YZmEEKT86UsdxhBxSpdWDBC6F6USmjV7QO22L+KLHh3o0CnPgK
xKetdWNX72kATq/WYMF4jHMFPCi+yJ+j9+j5HyW+w4kARoRfzZcEf39SeO2YQnWV
gjAn6vDfOGvU2R7jpEME7Q7iEwJzxhrFqnT4WqkH/iscAOgpP897vNLALKHlNTY8
cM40jbQfeybu96i0nIGlbeH11nf0y3zmnMjdX5yY37443+9CjFjtotIo9GhciWN5
0+O9KtS7MZM19/z8HZzrIXqy4yJuJgC4JPq5SshO83Hzbipe0kUeqe625QUFkr+z
yQIDAQAB
-----END PUBLIC KEY-----');

// Domain or subdomain of the signing entity (i.e. the domain where the e-mail comes from)
define('MAIL_DOMAIN', 'mail.nhipsinhhoc.vn');  

// Allowed user, defaults is "@<MAIL_DKIM_DOMAIN>", meaning anybody in the MAIL_DKIM_DOMAIN domain. Ex: 'admin@mydomain.tld'. You'll never have to use this unless you do not control the "From" value in the e-mails you send.
define('MAIL_IDENTITY', NULL);

// Selector used in your DKIM DNS record, e.g. : selector._domainkey.MAIL_DKIM_DOMAIN
define('MAIL_SELECTOR', 'x');