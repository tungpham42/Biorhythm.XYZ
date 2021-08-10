<?php
require realpath($_SERVER['DOCUMENT_ROOT']).'/inc/variables.vi.inc.php';

function get_wiki_url(string $title): string {
	return '/wiki/'.str_replace(' ', '_', $title);
}
function get_wiki_url_nsh(string $title): string {
	return 'http://'.$_SERVER['SERVER_NAME'].'/wiki/'.str_replace(' ', '_', $title);
}
function error($msg): void { //Show popup meesage
    echo '
    <html>
    <head>
    <script language="JavaScript">
    <!--
        alert("'.$msg.'");
        history.back();
    //-->
    </script>
    </head>
    <body>
    </body>
    </html>
    ';
    exit;
}
function has_dob(): bool {
	global $dob;
	if ($dob != "") {
		return true;
	} else {
		return false;
	}
}
/* User Functions */
function has_birthday(string $dob,string $time): bool {
	if (date('m-d',strtotime($dob)) == date('m-d',$time)) {
		return true;
	} else {
		return false;
	}
}
function is_birthday(): bool {
	global $dob;
	if (isset($_GET['date'])) {
		return has_birthday($dob, strtotime($_GET['date']));
	} else if (!isset($_GET['date'])) {
		return has_birthday($dob, time());
	}
}
function can_wish(): bool {
	if (has_dob() && is_birthday()) {
		return true;
	} else {
		return false;
	}
}
function list_user_same_birthday_links(string $name): string {
	global $dob;
	global $lang_code;
	global $span_interfaces;
	$output = "";
	$users = array_filter(load_all_array('nsh_users'), array(new filter(true), 'filter_has_same_birthday'));
	usort($users,'sort_name_ascend');
	if (!empty($users) && $dob != "") {
		$output .= '<div class="dates-box">';
		$output .= '<h2 class="dates-header">'.translate_span('list_user_same_birthday_links').'</h2>';
		$output .= '<ul class="dates" id="'.$name.'">';
		$count = count($users);
		for ($i = 0; $i < $count; ++$i) {
			$output .= '<li><a title="'.$users[$i]['name'].'" class="m-btn top-left-corner top-right-corner bottom-left-corner bottom-right-corner" href="/?fullname='.str_replace(' ','+',$users[$i]['name']).'&amp;dob='.$users[$i]['dob'].'"><span>'.$users[$i]['name'].' - '.$users[$i]['dob'].'</span></a><a class="wiki_icon" href="'.get_wiki_url($users[$i]['name']).'" target="_blank" title="Wiki"><i class="social-wikipedia"></i></a></li>';
		}
		$output .= '</ul>';
		$output .= '<div class="clear"></div>';
		$output .= '</div>';
	}
	return $output;
}
function get_json_same_birthday(string $dob): string {
	$users = array_filter(load_all_array('nsh_users'), array(new filter(true), 'filter_has_same_birthday'));
	usort($users,'sort_name_ascend');
	if (!empty($users) && $dob != "") {
		return json_encode($users);
	} else {
		return json_encode(null);
	}
}
function list_user_birthday_links(string $name): string {
	global $lang_code;
	global $span_interfaces;
	$output = "";
	$users = array_filter(load_all_array('nsh_users'), array(new filter(true), 'filter_has_birthday'));
	usort($users,'sort_name_ascend');
	if (!empty($users)) {
		$output .= '<div class="dates-box">';
		$output .= '<h2 class="dates-header">'.translate_span('list_user_birthday_links').'</h2>';
		$output .= '<ul class="dates" id="'.$name.'">';
		$count = count($users);
		for ($i = 0; $i < $count; ++$i) {
			$output .= '<li><a title="'.$users[$i]['name'].'" class="m-btn top-left-corner top-right-corner bottom-left-corner bottom-right-corner" href="/?fullname='.str_replace(' ','+',$users[$i]['name']).'&amp;dob='.$users[$i]['dob'].'"><span>'.$users[$i]['name'].' - '.$users[$i]['dob'].'</span></a><a class="wiki_icon" href="'.get_wiki_url($users[$i]['name']).'" target="_blank" title="Wiki"><i class="social-wikipedia"></i></a></li>';
		}
		$output .= '</ul>';
		$output .= '<div class="clear"></div>';
		$output .= '</div>';
	}
	return $output;
}
function list_user_links(string $name): string {
	global $lang_code;
	global $span_interfaces;
	$output = "";
	$output .= '<a id="birthdates_toggle" class="button top-left-corner top-right-corner bottom-left-corner bottom-right-corner">'.translate_span('list_user_links').'</a>';
	$output .= '<div class="clear"></div>';
	$output .= '<input id="user_birthdates_search" type="text" name="user_birthdates_search" class="m-wrap top-left-corner top-right-corner bottom-left-corner bottom-right-corner" size="60" maxlength="128" />';
	$output .= '<i id="user_birthdates_search_label" class="icon-search"></i>';
	$output .= '<div id="birthdates">';
	$output .= list_ajax_user_links($name);
	$output .= '</div>';
	$output .= '<script>
				var dobListStatus;
				if (!$.cookie("NSH:list-'.$name.'-status")) {
					dobListStatus = "show";
				} else {
					dobListStatus = $.cookie("NSH:list-'.$name.'-status");
				}
				if (dobListStatus == "hide") {
					$("#birthdates_toggle").removeClass("clicked");
					hideBirthdates();
				} else if (dobListStatus == "show") {
					$("#birthdates_toggle").addClass("clicked");
					showBirthdates();
				}
				$("#birthdates_toggle").on("click",function(){
					if ($(this).hasClass("clicked") && dobListStatus == "show") {
						$.cookie("NSH:list-'.$name.'-status", "hide")
						dobListStatus = "hide";
						$(this).removeClass("clicked");
						hideBirthdates();
					} else if (!$(this).hasClass("clicked") && dobListStatus == "hide") {
						$.cookie("NSH:list-'.$name.'-status", "show")
						dobListStatus = "show";
						$(this).addClass("clicked");
						showBirthdates();
					}
				});
				$("#user_birthdates_search").on({
					input: function(){
						searchBirthdates($("#user_birthdates_search").val());
					},
					change: function(){
						searchBirthdates($("#user_birthdates_search").val());
					}
				});
				</script>';
	return $output;
}
function list_ajax_user_links(string $name,string $keyword=""): string {
	$output = "";
	$users = ($keyword != "") ? array_filter(load_all_array('nsh_users'), array(new filter($keyword), 'filter_keyword')): load_all_array('nsh_users');
	usort($users,'sort_name_ascend');
	$output .= '<div class="dates-box">';
	//$output .= '<div class="dates-nav" id="'.$name.'-nav"></div>';
	$output .= '<ul class="dates" id="'.$name.'">';
	$count = count($users);
	for ($i = 0; $i < $count; ++$i) {
		$output .= '<li><a title="'.$users[$i]['name'].'" class="m-btn top-left-corner top-right-corner bottom-left-corner bottom-right-corner" href="/?fullname='.str_replace(' ','+',$users[$i]['name']).'&amp;dob='.$users[$i]['dob'].'"><span>'.$users[$i]['name'].' - '.$users[$i]['dob'].'</span></a><a class="wiki_icon" href="'.get_wiki_url($users[$i]['name']).'" target="_blank" title="Wiki"><i class="social-wikipedia"></i></a></li>';
	}
	$output .= '</ul>';
	$output .= '<div class="clear"></div>';
	$output .= '<script>
				lang = $.cookie("NSH:lang");
				minBirthdatesCount = 12;
				maxBirthdatesCount = 24;
				//$("#'.$name.'").listnav({
				//	includeOther: true,
				//	cookieName: "NSH:list-'.$name.'"
				//});
				$("a.all > span.translate").text($("a.all > span.translate").attr("data-lang-"+lang));
				$("#birthdates .m-btn, .ln-letters a").ripple();
				if ($("#birthdates").length) {
					$("#birthdates").find("ul.dates").find("li").slice(12).hide();
				}
				$(window).on("scroll mousewheel wheel DOMMouseScroll resize", function(){
					if ($("#birthdates").length) {
						if (($(document).scrollTop()+$(window).height()) >= ($(document).height()-$("#bottom").height()+$("#bottom_banner").height())) {
							$("#birthdates").find("ul.dates").find("li").slice(minBirthdatesCount,maxBirthdatesCount).fadeIn(1200);
							minBirthdatesCount += 12;
							maxBirthdatesCount += 12;
						}
					}
				});
				</script>';
	$output .= '</div>';
	return $output;
}
function list_users(int $page=1,string $keyword=""): string { //Return users list, for admin use
	$output = "";
	$count = 10;
	$users = ($keyword != "") ? array_filter(load_all_array('nsh_users'), array(new filter($keyword), 'filter_keyword')): load_all_array('nsh_users');
	usort($users,'sort_name_ascend');
	$users_count = count($users);
	$pagination = new Pagination($users,$page,$count,20);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$users = $pagination->getResults();
	$output .= '<a class="button right" href="/user/create/">Create user</a>';
	$output .= '<span class="count">'.$users_count.' user'.(($users_count > 1) ? 's': "").'.</span>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<table class="admin">';
	$output .= '<tr><th>ID</th><th>User ID</th><th>Username</th><th>DOB</th><th colspan="2">Operations</th></tr>';
	for ($i = 0; $i < $count; ++$i) {
		if (isset($users[$i])) {
			$class = 'class="'.table_row_class($i).'"';
			$output .= '<tr '.$class.'>';
			$output .= '<td>'.(($page-1)*$count+($i+1)).'</td>';
			$output .= '<td>'.$users[$i]['uid'].'</td>';
			$output .= '<td><a style="color: black" target="_blank" href="/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'].'">'.$users[$i]['name'].'</a></td>';
			$output .= '<td>'.$users[$i]['dob'].'</td>';
			$output .= '<td><form method="POST" action="/user/edit/"><input type="hidden" name="uid" value="'.$users[$i]['uid'].'" /><input type="hidden" name="old_name" value="'.$users[$i]['name'].'" /><input type="hidden" name="old_dob" value="'.$users[$i]['dob'].'" /><input name="user_edit" type="submit" value="Edit"/></form></td>';
			$output .= '<td><form method="POST" action="/user/delete/"><input type="hidden" name="uid" value="'.$users[$i]['uid'].'" /><input name="user_delete" type="submit" value="Delete"/></form></td>';
			$output .= '</tr>';
		}
	}
	$output .= '</table>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#admin_user").load("/triggers/admin_user.php",{page:page,keyword:"'.$keyword.'"});
				}
				</script>';
	return $output;
}
function list_same_birthday(string $keyword=""): string { //Return users list, for admin use
	$output = "";
	$users = ($keyword != "") ? array_filter(load_all_array('nsh_users'), array(new filter($keyword), 'filter_keyword')): load_all_array('nsh_users');
	usort($users,'sort_name_ascend');
	$count = count($users);
	$output .= '<table id="same_birthday_suggestion">';
	for ($i = 0; $i < $count; ++$i) {
		if (isset($users[$i])) {
			$class = 'class="'.table_row_class($i).'"';
			$output .= '<tr '.$class.'>';
			$output .= '<td><a style="color: black" target="_blank" href="/?fullname='.str_replace(' ','+',$users[$i]['name']).'&dob='.$users[$i]['dob'].'">'.$users[$i]['name'].'</a></td>';
			$output .= '<td>'.$users[$i]['dob'].'</td>';
			$output .= '</tr>';
		}
	}
	$output .= '</table>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#admin_user").load("/triggers/admin_user.php",{page:page,keyword:"'.$keyword.'"});
				}
				</script>';
	return $output;
}
/* Rhythm Functions */
function list_rhythms(): string { //Return list of rhythms, for admin use
	$output = "";
	$rhythms = load_all_array('nsh_rhythms');
	$output .= '<a class="button" href="/rhythm/create/">Create rhythm</a>';
	$output .= '<table class="admin">';
	$output .= '<tr><th>Rhythm ID</th><th>Rhythm name</th><th colspan="3">Operations</th></tr>';
	$count = count($rhythms);
	$output .= '<tr><td class="count" colspan="6">'.$count.' item'.(($count > 1) ? 's': "").' to display.</td></tr>';
	for ($i = 0; $i < $count; ++$i) {
		$class = 'class="'.table_row_class($i).'"';
		$output .= '<tr '.$class.'>';
		$output .= '<td>'.$rhythms[$i]['rid'].'</td>';
		$output .= '<td>'.$rhythms[$i]['name'].'</td>';
		$output .= '<td><form method="POST" action="/rhythm/edit/"><input type="hidden" name="rid" value="'.$rhythms[$i]['rid'].'" /><input type="hidden" name="old_name" value="'.$rhythms[$i]['name'].'" /><input type="hidden" name="old_scale" value="'.$rhythms[$i]['scale'].'" /><input type="hidden" name="old_description_en" value="'.str_replace('"',"'",$rhythms[$i]['description_en']).'" /><input type="hidden" name="old_description_ru" value="'.str_replace('"',"'",$rhythms[$i]['description_ru']).'" /><input type="hidden" name="old_description_es" value="'.str_replace('"',"'",$rhythms[$i]['description_es']).'" /><input type="hidden" name="old_description_zh" value="'.str_replace('"',"'",$rhythms[$i]['description_zh']).'" /><input type="hidden" name="old_description_ja" value="'.str_replace('"',"'",$rhythms[$i]['description_ja']).'" /><input name="rhythm_edit" type="submit" value="Edit"/></form></td>';
		$output .= '<td><form method="POST" action="/triggers/change_rhythm_type.php"><input type="hidden" name="rid" value="'.$rhythms[$i]['rid'].'" /><input name="rhythm_chang_type" type="submit" title="Make rhythm '.(($rhythms[$i]['is_secondary'] == 0) ? 'secondary': 'primary').'" value="'.(($rhythms[$i]['is_secondary'] == 0) ? 'Primary': 'Secondary').'"/></form></td>';
		$output .= '<td><form method="POST" action="/rhythm/delete/"><input type="hidden" name="rid" value="'.$rhythms[$i]['rid'].'" /><input name="rhythm_delete" type="submit" value="Delete"/></form></td>';
		$output .= '</tr>';
	}
	$output .= '</table>';
	return $output;
}
function create_rhythm(string $name,$scale,string $description_en,string $description_ru,string $description_es,string $description_zh,string $description_ja): void { //Create new rhythm
	$array = array(
				'name' => $name,
				'scale' => $scale,
				'description_en' => $description_en,
				'description_ru' => $description_ru,
				'description_es' => $description_es,
				'description_zh' => $description_zh,
				'description_ja' => $description_ja
			);
	insert_record($array,'nsh_rhythms');
}
function edit_rhythm($rid,string $name,$scale,string $description_en,string $description_ru,string $description_es,string $description_zh,string $description_ja): void { //Edit rhythm details
	$array = array(
				'name' => $name,
				'scale' => $scale,
				'description_en' => $description_en,
				'description_ru' => $description_ru,
				'description_es' => $description_es,
				'description_zh' => $description_zh,
				'description_ja' => $description_ja
			);
	update_record($array,'rid',$rid,'nsh_rhythms');
}
function delete_rhythm($rid): void { //Delete rhythm
	delete_record('rid',$rid,'nsh_rhythms');
}
function make_rhythm_secondary($rid): void {
	$array = array(
				'is_secondary' => '1'
			);
	update_record($array,'rid',$rid,'nsh_rhythms');
}
function make_rhythm_primary($rid): void {
	$array = array(
				'is_secondary' => '0'
			);
	update_record($array,'rid',$rid,'nsh_rhythms');
}
/* Graph Functions */
function differ_date(string $start,string $end): int {
	$start_ts = strtotime($start);
	$end_ts = strtotime($end);
	$diff = $end_ts - $start_ts;
	return round($diff/86400);
}
function differ_year(string $start,string $end): int {
	$start_dt = new DateTime(date('Y-m-d', strtotime($start)));
	$end_dt = new DateTime(date('Y-m-d', strtotime($end)));
	$diff = $end_dt->diff($start_dt);
	return $diff->y;
}
function bio_count(string $dob,string $date,int $scale): string { //http://en.wikipedia.org/wiki/Biorhythm
	$x = differ_date($dob,$date);
	//return (number_format((sin(2*pi()*$x/$scale)*100),2) != '-0.00') ? number_format((sin(2*pi()*$x/$scale)*100),2): '0.00';
	return number_format(((sin(2*pi()*$x/$scale)*100)+100)/2,2);
}
function percent_bio_count(string $dob,string $date,int $scale): string {
	return bio_count($dob,$date,$scale).' %';
}
function average_bio_count(string $dob,string $date,array $rhythms): string {
	$total = 0;
	$count = (count($rhythms) > 0) ? count($rhythms): 1;
	$i = 0;
	foreach ($rhythms as $rhythm) {
		$total += bio_count($dob,$date,$rhythm['scale']);
		++$i;
	}
	return number_format($total/$count,2);
}
function percent_average_bio_count(string $dob,string $date,array $rhythms): string {
	return average_bio_count($dob,$date,$rhythms).' %';
}
function day_of_rhythm(string $dob,string $date,int $scale): string {
	$x = differ_date($dob,$date);
	$ceil = ceil($x / $scale);
	$y = $ceil;
	return ' ('.$y.'/'.$scale.')';
}
// https://github.com/rmanasyan/compatzz/blob/master/js/main.js
function compatible_count(string $this_dob,string $that_dob,int $scale): string {
	$x = abs(differ_date($this_dob,$that_dob));
	return number_format(100*abs(cos(pi()*$x/$scale)),2);
}
function percent_compatible_count(string $this_dob,string $that_dob,int $scale): string {
	return ($this_dob == $that_dob) ? 'N/A': compatible_count($this_dob,$that_dob,$scale).' %';
}
function average_compatible_count(string $this_dob,string $that_dob,array $rhythms): string {
	$total = 0;
	$count = (count($rhythms) > 0) ? count($rhythms): 1;
	$i = 0;
	foreach ($rhythms as $rhythm) {
		$total += compatible_count($this_dob,$that_dob,$rhythm['scale']);
		++$i;
	}
	return number_format($total/$count,2);
}
function percent_average_compatible_count(string $this_dob,string $that_dob,array $rhythms): string {
	return ($this_dob == $that_dob) ? 'N/A': average_compatible_count($this_dob,$that_dob,$rhythms).' %';
}
/* Zodiac Sign */
function get_zodiac_sign(string $dob): string {
	$person = new astro($dob);
	return $person->chaldeanSign;
}
/* Zodiac Feeds */
function get_daily_horoscope(string $dob): string {
	$sign = get_zodiac_sign($dob);
	$rss = new RSSReader('http://findyourfate.com/rss/dailyhoroscope-feed.asp?sign='.$sign);
	$nn = $rss->getNumberOfNews();
	$output = "";
	$output .= '<table width="40%"  border="0" cellspacing="0" cellpadding="5" class="box">';
	$output .= '<tr><td>'.$rss->getImage().'</td></tr>';
	$output .= '<tr><td>'.$rss->getChannelTitle('rsstitle').'</td></tr>';
	for ($i = 0; $i <$nn; ++$i){
		$output .= '<tr><td>'.$rss->getItemTitle('rsslink',$i).'</td></tr>';
		$output .= '<tr><td>'.$rss->getItemDescription('rsstext',$i).'</td></tr>';
		$output .= '<tr><td>'.$rss->getItemPubdate('rssdate',$i).'</td></tr>';
		$output .= '<tr><td height="10"></td></tr>';
	}
	$output .= '</table>';
	return $output;
}
function get_zodiac_from_dob(string $birthdate,string $lang='vi'): string {
	list($year, $month, $day) = array_pad(explode('-', $birthdate, 3), 3, null);
	if (($month == 3 && $day > 20) || ($month == 4 && $day < 20)) {
		switch ($lang) {
			case 'vi': return 'Bạch Dương'; break;
			case 'en': return 'Aries'; break;
			case 'ru': return 'Овен'; break;
			case 'es': return 'Aries'; break;
			case 'zh': return '白羊宮'; break;
			case 'ja': return 'おひつじ座'; break;
		}
	} else if (($month == 4 && $day > 19) || ($month == 5 && $day < 21)) {
		switch ($lang) {
			case 'vi': return 'Kim Ngưu'; break;
			case 'en': return 'Taurus'; break;
			case 'ru': return 'Телец'; break;
			case 'es': return 'Tauro'; break;
			case 'zh': return '金牛宮'; break;
			case 'ja': return 'おうし座'; break;
		}
	} else if (($month == 5 && $day > 20) || ($month == 6 && $day < 21)) {
		switch ($lang) {
			case 'vi': return 'Song Tử'; break;
			case 'en': return 'Gemini'; break;
			case 'ru': return 'Близнецы'; break;
			case 'es': return 'Géminis'; break;
			case 'zh': return '雙子宮'; break;
			case 'ja': return 'ふたご座'; break;
		}
	} else if (($month == 6 && $day > 20) || ($month == 7 && $day < 23)) {
		switch ($lang) {
			case 'vi': return 'Cự Giải'; break;
			case 'en': return 'Cancer'; break;
			case 'ru': return 'Рак'; break;
			case 'es': return 'Cáncer'; break;
			case 'zh': return '巨蟹宮'; break;
			case 'ja': return 'かに座'; break;
		}
	} else if (($month == 7 && $day > 22) || ($month == 8 && $day < 23)) {
		switch ($lang) {
			case 'vi': return 'Sư Tử'; break;
			case 'en': return 'Leo'; break;
			case 'ru': return 'Лев'; break;
			case 'es': return 'Leo'; break;
			case 'zh': return '獅子宮'; break;
			case 'ja': return 'しし座'; break;
		}
	} else if (($month == 8 && $day > 22) || ($month == 9 && $day < 23)) {
		switch ($lang) {
			case 'vi': return 'Xử Nữ'; break;
			case 'en': return 'Virgo'; break;
			case 'ru': return 'Дева'; break;
			case 'es': return 'Virgo'; break;
			case 'zh': return '室女宮'; break;
			case 'ja': return 'おとめ座'; break;
		}
	} else if (($month == 9 && $day > 22) || ($month == 10 && $day < 23)) {
		switch ($lang) {
			case 'vi': return 'Thiên Bình'; break;
			case 'en': return 'Libra'; break;
			case 'ru': return 'Весы'; break;
			case 'es': return 'Libra'; break;
			case 'zh': return '天秤宮'; break;
			case 'ja': return 'てんびん座'; break;
		}
	} else if (($month == 10 && $day > 22) || ($month == 11 && $day < 22)) {
		switch ($lang) {
			case 'vi': return 'Thiên Yết'; break;
			case 'en': return 'Scorpio'; break;
			case 'ru': return 'Скорпион'; break;
			case 'es': return 'Escorpio'; break;
			case 'zh': return '天蠍宮'; break;
			case 'ja': return 'さそり座'; break;
		}
	} else if (($month == 11 && $day > 21) || ($month == 12 && $day < 22)) {
		switch ($lang) {
			case 'vi': return 'Nhân Mã'; break;
			case 'en': return 'Sagittarius'; break;
			case 'ru': return 'Стрелец'; break;
			case 'es': return 'Sagitario'; break;
			case 'zh': return '人馬宮'; break;
			case 'ja': return 'いて座'; break;
		}
	} else if (($month == 12 && $day > 21) || ($month == 1 && $day < 20)) {
		switch ($lang) {
			case 'vi': return 'Ma Kết'; break;
			case 'en': return 'Capricorn'; break;
			case 'ru': return 'Козерог'; break;
			case 'es': return 'Capricornio'; break;
			case 'zh': return '摩羯宮'; break;
			case 'ja': return 'やぎ座'; break;
		}
	} else if (($month == 1 && $day > 19) || ($month == 2 && $day < 19)) {
		switch ($lang) {
			case 'vi': return 'Bảo Bình'; break;
			case 'en': return 'Aquarius'; break;
			case 'ru': return 'Водолей'; break;
			case 'es': return 'Acuario'; break;
			case 'zh': return '寶瓶宮'; break;
			case 'ja': return 'みずがめ座'; break;
		}
	} else if (($month == 2 && $day > 18) || ($month == 3 && $day < 21)) {
		switch ($lang) {
			case 'vi': return 'Song Ngư'; break;
			case 'en': return 'Pisces'; break;
			case 'ru': return 'Рыбы'; break;
			case 'es': return 'Piscis'; break;
			case 'zh': return '雙魚宮'; break;
			case 'ja': return 'うお座'; break;
		}
	}
}
function get_zodiac_csv_char($id) {
	$zodiac_csv = new parseCSV(realpath($_SERVER['DOCUMENT_ROOT']).'/hoangdao/'.$id.'.csv');
	$count = count($zodiac_csv->data);
	$index = rand(0, $count-1);
	return $zodiac_csv->data[$index]['char'];
}
function get_zodiac_chars($id) {
	return get_zodiac_csv_char('ngoaihinh_'.$id).', '.get_zodiac_csv_char('tinhcach_'.$id).', '.get_zodiac_csv_char('tinhcam_'.$id);
}
function get_zodiac_character(string $birthdate): string {
	$char = "";
	list($year, $month, $day) = array_pad(explode('-', $birthdate, 3), 3, null);
	if (($month == 3 && $day > 20) || ($month == 4 && $day < 20)) { // Bạch Dương
		$char = get_zodiac_chars('01');
	} else if (($month == 4 && $day > 19) || ($month == 5 && $day < 21)) { // Kim Ngưu
		$char = get_zodiac_chars('02');
	} else if (($month == 5 && $day > 20) || ($month == 6 && $day < 21)) { // Song Tử
		$char = get_zodiac_chars('03');
	} else if (($month == 6 && $day > 20) || ($month == 7 && $day < 23)) { // Cự Giải
		$char = get_zodiac_chars('04');
	} else if (($month == 7 && $day > 22) || ($month == 8 && $day < 23)) { // Sư Tử
		$char = get_zodiac_chars('05');
	} else if (($month == 8 && $day > 22) || ($month == 9 && $day < 23)) { // Xử Nữ
		$char = get_zodiac_chars('06');
	} else if (($month == 9 && $day > 22) || ($month == 10 && $day < 23)) { // Thiên Bình
		$char = get_zodiac_chars('07');
	} else if (($month == 10 && $day > 22) || ($month == 11 && $day < 22)) { // Thiên Yết
		$char = get_zodiac_chars('08');
	} else if (($month == 11 && $day > 21) || ($month == 12 && $day < 22)) { // Nhân Mã
		$char = get_zodiac_chars('09');
	} else if (($month == 12 && $day > 21) || ($month == 1 && $day < 20)) { // Ma Kết
		$char = get_zodiac_chars('10');
	} else if (($month == 1 && $day > 19) || ($month == 2 && $day < 19)) { // Bảo Bình
		$char = get_zodiac_chars('11');
	} else if (($month == 2 && $day > 18) || ($month == 3 && $day < 21)) { // Song Ngư
		$char = get_zodiac_chars('12');
	}
	return $char;
}
function countdown_birthday(string $dob,string $date = 'today'): int {
	$countdown = 0;
	$birthday = date('m-d', strtotime($dob));
	$diff = differ_date($date, date('Y', strtotime($date)).'-'.$birthday);
	if ($diff <= 0) {
		$countdown = differ_date($date, (date('Y', strtotime($date)) + 1).'-'.$birthday);
	} else if ($diff > 0) {
		$countdown = $diff;
	}
	return $countdown;
}
/* RACING Functions */
function player_input(int $count): string {
	$output = "";
	for ($i = 0; $i < $count; ++$i) {
		$output .= '<div class="m-input-prepend"><span class="add-on">Tên/mã hộp thứ '.($i+1).':</span><input data-position="'.($i+1).'" type="text" name="player'.($i+1).'" size="42" maxlength="128" class="player-name m-wrap required" value="'.($i+1).'"></div>';
	}
	return $output;
}
function render_game(): void {
	$count = (isset($_POST['count'])) ? $_POST['count']: 0;
	$time = (isset($_POST['time'])) ? $_POST['time']: 0;
	$player_names = array();
	$player_colors = array();
	$game_id = "";
	for ($i = 0; $i < $count; ++$i) {
		$player_names[$i] = (isset($_POST['player'.($i + 1)])) ? $_POST['player'.($i + 1)]: "";
	}
	for ($i = 0; $i < $count; ++$i) {
		$player_colors[$i] = get_random_color_hex();
		$game_id .= $player_colors[$i];
		$game_id .= ($i < $count - 1) ? '-': "";
	}
	for ($i = 0; $i < $count; ++$i) {
		echo ($i != 0) ? '<div class="race_lane">': '<div class="race_lane first">';
		echo '<div style="background: #'.$player_colors[$i].';border-color:#'.$player_colors[$i].';" class="player" id="player'.($i+1).'"><div class="name">'.$player_names[$i].'</div><span class="position timer"></span></div>';
		echo '</div>';
	}
	echo '
	<a class="m-btn green" id="start"><span>Chơi</span></a>
	<a class="m-btn blue" id="restart"><span>Chơi lại</span></a>
	<a class="m-btn blue" href="/duahop/"><span>Quay về bước 1</span></a>
	<div class="error_msg '.$game_id,'"></div>
	<script>
	$("#start").click(function(){
		if (!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");';
	for ($i = 0; $i < $count; ++$i) {
		echo 'raceRandom("#player'.($i + 1).'");';
	}
	echo '
		} else {
			$(".error_msg.'.$game_id.'").html("Nút \"Chơi\" bị vô hiệu hóa, hãy ấn nút \"Chơi lại\" rồi ấn nút \"Chơi\"").dialog({
				resizable: false,
				modal: false,
				open: function(event, ui){
					setTimeout("$(\".error_msg\").dialog(\"close\")",4200);
				}
			});
			$(".ui-dialog").draggable({
				containment: "#main"
			});
			return false;
		}
	});
	$("#restart").click(function() {
		$("#game").load("/triggers/game.php",{count: '.$count.',time: '.$time.',';
	for ($i = 0; $i < count($player_names); ++$i) {
		echo 'player'.($i+1).': "'.$player_names[$i].'"'.(($i != (count($player_names) - 1)) ? ',': "");
	}
	echo '
		});
	});
	$(".player").attrchange(function(){
		$("#leader").text("Hộp dẫn đầu: "+$(".player.first").first().find(".name").text());
	});
	</script>';
}
/**
 * Get random color hex value
 *
 * @param integer $max_r Maximum value for the red color
 * @param integer $max_g Maximum value for the green color
 * @param integer $max_b Maximum value for the blue color
 * @return string
 */
function get_random_color_hex(int $max_r = 255,int $max_g = 255,int $max_b = 255): string {
    // ensure that values are in the range between 0 and 255
    if ($max_r > 255) { $max_r = 255; }
    if ($max_g > 255) { $max_g = 255; }
    if ($max_b > 255) { $max_b = 255; }
    if ($max_r < 0) { $max_r = 0; }
    if ($max_g < 0) { $max_g = 0; }
    if ($max_b < 0) { $max_b = 0; }
   
    // generate and return the random color
    return str_pad(dechex(rand(0, $max_r)), 2, '0', STR_PAD_LEFT) .
           str_pad(dechex(rand(0, $max_g)), 2, '0', STR_PAD_LEFT) .
           str_pad(dechex(rand(0, $max_b)), 2, '0', STR_PAD_LEFT);
}
/* Sort Functions */
function sort_descend($a,$b): int { //Call back function to sort descendently
	if (isset($a['sort']) && isset($b['sort'])) {
		if ((int)$a['sort'] == (int)$b['sort']) {
			return 0;
		}
		return ((int)$b['sort'] < (int)$a['sort']) ? -1 : 1;
	}
}
function sort_ascend($a,$b): int { //Call back function to sort ascendently
	if (isset($a['sort']) && isset($b['sort'])) {
		if ((int)$a['sort'] == (int)$b['sort']) {
			return 0;
		}
		return ((int)$a['sort'] < (int)$b['sort']) ? -1 : 1;
	}
}
function sort_name_descend($a,$b): int { //Call back function to sort date descendently
	if (isset($a['name']) && isset($b['name'])) {
		return strcmp($b['name'],$a['name']);
	}
}
function sort_name_ascend($a,$b): int { //Call back function to sort date ascendently
	if (isset($a['name']) && isset($b['name'])) {
		return strcmp($a['name'],$b['name']);
	}
}
function sort_date_descend($a,$b): int { //Call back function to sort date descendently
	if (isset($a['created']) && isset($b['created'])) {
		return strcmp($b['created'],$a['created']);
	}
}
function sort_date_ascend($a,$b): int { //Call back function to sort date ascendently
	if (isset($a['created']) && isset($b['created'])) {
		return strcmp($a['created'],$b['created']);
	}
}
function sort_date_member_descend($a,$b): int { //Call back function to sort date descendently
	if (isset($a['created_at']) && isset($b['created_at'])) {
		return strcmp(strtotime($b['created_at']),strtotime($a['created_at']));
	}
}
function sort_date_member_ascend($a,$b): int { //Call back function to sort date ascendently
	if (isset($a['created_at']) && isset($b['created_at'])) {
		return strcmp(strtotime($a['created_at']),strtotime($b['created_at']));
	}
}
function sort_fullname_descend($a,$b): int { //Call back function to sort fullname descendently
	if (isset($a['fullname']) && isset($b['fullname'])) {
		return strcmp($b['fullname'],$a['fullname']);
	}
}
function sort_fullname_ascend($a,$b): int { //Call back function to sort fullname ascendently
	if (isset($a['fullname']) && isset($b['fullname'])) {
		return strcmp($a['fullname'],$b['fullname']);
	}
}
function sort_dob_descend($a,$b): int { //Call back function to sort dob descendently
	if (isset($a['dob']) && isset($b['dob'])) {
		return strcmp($b['dob'],$a['dob']);
	}
}
function sort_dob_ascend($a,$b): int { //Call back function to sort dob ascendently
	if (isset($a['dob']) && isset($b['dob'])) {
		return strcmp($a['dob'],$b['dob']);
	}
}
function site_name(): string {
	global $lang_code;
	switch ($lang_code) {
		case 'vi': return 'Biểu đồ nhịp sinh học | Bieu do nhip sinh hoc'; break;
		case 'en': return 'Biorhythm chart'; break;
		case 'ru': return 'Биоритм диаграммы'; break;
		case 'es': return 'Biorritmo carta'; break;
		case 'zh': return '生理节律图'; break;
		case 'ja': return 'バイオリズムチャート'; break;
	}
}
function intro_title(): string {
	global $lang_code;
	switch ($lang_code) {
		case 'vi': return 'Giới thiệu'; break;
		case 'en': return 'Introduction'; break;
		case 'ru': return 'Введение'; break;
		case 'es': return 'Introducción'; break;
		case 'zh': return '介绍'; break;
		case 'ja': return 'はじめに'; break;
	}
}
function home_title(): string {
	global $lang_code;
	global $fullname;
	switch ($lang_code) {
		case 'vi': return 'Nhịp sinh học'.(($fullname != "") ? ' của '.$fullname: ""); break;
		case 'en': return 'Biorhythm'.(($fullname != "") ? ' of '.$fullname: ""); break;
		case 'ru': return 'Биоритм'.(($fullname != "") ? ' из '.$fullname: ""); break;
		case 'es': return 'Biorritmo'.(($fullname != "") ? ' de '.$fullname: ""); break;
		case 'zh': return '生理节律'.(($fullname != "") ? '的'.$fullname: ""); break;
		case 'ja': return 'バイオリズム'.(($fullname != "") ? 'の'.$fullname: ""); break;
	}
}
function birthday_title(): string {
	global $lang_code;
	global $fullname;
	switch ($lang_code) {
		case 'vi': return 'Chúc mừng sinh nhật'.(($fullname != "") ? ' '.$fullname: ""); break;
		case 'en': return 'Happy birthday'.(($fullname != "") ? ' '.$fullname: ' to you'); break;
		case 'ru': return 'Днем Рождения'.(($fullname != "") ? ' '.$fullname: ""); break;
		case 'es': return 'Feliz cumpleaños'.(($fullname != "") ? ' '.$fullname: ""); break;
		case 'zh': return '生日快乐'.(($fullname != "") ? $fullname: ""); break;
		case 'ja': return 'お誕生日おめでとうございます'.(($fullname != "") ? $fullname: ""); break;
	}
}
function get_wish(string $lang): string {
	$wishes = new parseCSV();
	$wishes->parse(realpath($_SERVER['DOCUMENT_ROOT']).'/wishes/'.$lang.'.csv');
	$count = count($wishes->data);
	$index = rand(0, $count-1);
	return $wishes->data[$index]['wish'];
}
function birthday_wish(): string {
	global $lang_code;
	global $fullname;
	switch ($lang_code) {
		case 'vi': return get_wish('vi'); break;
		case 'en': return get_wish('en'); break;
		case 'ru': return get_wish('ru'); break;
		case 'es': return get_wish('es'); break;
		case 'zh': return get_wish('zh'); break;
		case 'ja': return get_wish('ja'); break;
	}
}
function search_title(): string {
	global $lang_code;
	global $q;
	switch ($lang_code) {
		case 'vi': return 'Kết quả tìm kiếm cho "'.$q.'"'; break;
		case 'en': return 'Search results for "'.$q.'"'; break;
		case 'ru': return 'Результаты поиска для "'.$q.'"'; break;
		case 'es': return 'Resultados de búsqueda para "'.$q.'"'; break;
		case 'zh': return '搜索结果"'.$q.'"'; break;
		case 'ja': return 'の検索結果"'.$q.'"'; break;
	}
}
function head_description(): string {
	global $lang_code;
	switch ($lang_code) {
		case 'vi': return 'Biểu đồ nhịp sinh học hiển thị dự đoán Sức khỏe, Tình cảm, Trí tuệ, Trực giác, Thẩm mỹ, Nhận thức và Tinh thần của bạn'; break;
		case 'en': return 'Biorhythm chart tells your physical, emotional, intellectual, intuitive, aesthetic, awareness and spiritual values'; break;
		case 'ru': return 'Биоритм диаграммы рассказывает ваш физические, эмоциональные, интеллектуальные ценности'; break;
		case 'es': return 'Biorritmo carta le dice tu valores físicos, intelectuales y emocionales'; break;
		case 'zh': return '生理节律图表告诉你的身体，情绪，智力值'; break;
		case 'ja': return 'バイオリズムチャートは、物理的、感情的、知的なあなたの値を伝えます'; break;
	}
}
function chrome_webstore_item(): string {
	global $lang_code;
	$item_link = 'https://chrome.google.com/webstore/detail/';
	switch ($lang_code) {
		case 'vi': $item_link .= 'piomnolafccfmmingfmkffakbkdndngm'; break;
		case 'en': $item_link .= 'ddejngbhchmilhefdhejblgclkjjhada'; break;
		case 'ru': $item_link .= 'gbkgpnnjegmankjficecafbhogpeghlp'; break;
		case 'es': $item_link .= 'fcmpoljpimefbadihigjecfadohnmngb'; break;
		case 'zh': $item_link .= 'ihahiddfifjhomhiggelpecglncdcjbm'; break;
		case 'ja': $item_link .= 'afpahinfkpefgbfnlcogfdjokhgeolhm'; break;
	}
	return $item_link;
}
function youtube_id(): string {
	global $lang_code, $help_interfaces;
	return $help_interfaces['youtube_id'][$lang_code];
}
function change_url_lang(string $url,string $lang): string {
	global $lang_codes;
	$changed_url = "";
	if (in_array($lang, $lang_codes)) {
		if (strpos($url,'lang=') !== false) {
			$lang_pos = strpos($url,'lang=')+5;
			$lang_code = substr($url,$lang_pos,2);
			$changed_url = str_replace('lang='.$lang_code, 'lang='.$lang, $url);
		} else if (strpos($url,'?') !== false) {
			$changed_url = $url.'&lang='.$lang;
		} else {
			$changed_url = $url.'?lang='.$lang;
		}
	}
	return str_replace('&', '&amp;', $changed_url);
}
function default_url(string $url): string {
	$lang_pos = strpos($url,'lang=');
	$lang_str = substr($url,$lang_pos,7);
	if (strpos($url,'?'.$lang_str.'&') !== false) {
		$default_url = str_replace($lang_str.'&', "", $url);
	} else if (strpos($url,'?lang=') !== false) {
		$default_url = str_replace('?'.$lang_str, "", $url);
	} else if (strpos($url,'&lang=') !== false) {
		$default_url = str_replace('&'.$lang_str, "", $url);
	} else {
		$default_url = $url;
	}
	return str_replace('&', '&amp;', $default_url);
}
function current_url(): string { //Get current page URL
	$page_url = 'http';
	if (isset($_SERVER['HTTPS'])){
		if ($_SERVER['HTTPS'] == 'on') {$page_url .= 's';}
	}
	$page_url .= '://';
//	if ($_SERVER['SERVER_PORT'] != '80') {
//		$page_url .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
//	} else {
		$page_url .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
//	}
	return $page_url;
}
function current_url_lang(string $lang): string {
	global $p;
	global $navs;
	$current_url = current_url();
	return !in_array($p, $navs) ? change_url_lang($current_url, $lang): $current_url;
}
function current_default_url(): string {
	$current_url = current_url();
	return default_url($current_url);
}
function base_url(): string {
	$page_url = 'http';
	if (isset($_SERVER['HTTPS'])){
		if ($_SERVER['HTTPS'] == 'on') {$page_url .= 's';}
	}
	$page_url .= '://';
	if ($_SERVER['SERVER_PORT'] != '80') {
		$page_url .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
	} else {
		$page_url .= $_SERVER['SERVER_NAME'];
	}
	return $page_url;
}
function is_mobile(): bool {
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt|kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
		return true;
	} else {
		return false;
	}
}
function class_mobile(): string {
	return is_mobile() ? 'class="mobile"': "";
}
function style_mobile(): string {
	return is_mobile() ? 'style="width: 100%; background: none; margin: 0px auto;"': "";
}
/**
 * Grab Images from Wikipedia via thier API
 *
 * @author     http://techslides.com
 * @link       http://techslides.com/grab-wikipedia-pictures-by-api-with-php
 */
 
//curl request returns json output via json_decode php function
function curl(string $url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
 
//parse the json output
function get_wiki_results(string $json): array {
	$results = array();
	$json_array = json_decode($json, true);
	foreach($json_array['query']['pages'] as $page){
		$count = count($page['images']);
		if($count > 0){
		    foreach($page['images'] as $image){
		    	$title = str_replace(' ', '_', $image['title']);
		    	$imageinfourl = 'http://en.wikipedia.org/w/api.php?action=query&titles='.$title.'&prop=imageinfo&iiprop=url&format=json';
		    	$imageinfo = curl($imageinfourl);
		    	$iamge_array = json_decode($imageinfo, true);
		    	$image_pages = $iamge_array['query']['pages'];
				foreach($image_pages as $a){
					$results[] = $a['imageinfo'][0]['url'];
				}
			}
		}
	}
	return $results;
}
function get_wiki_image(string $keyword,$limit): string {
	$output = "";
	$term = str_replace(' ', '_', $keyword);
    $url = 'http://en.wikipedia.org/w/api.php?action=query&titles='.$term.'&prop=images&format=json&imlimit='.$limit;
 
    $json = curl($url);
    $results = get_wiki_results($json);
 
	//print the results using an unordered list
	$output .= '<table style="border: 0px;" border="0" cellpadding="2" width="100%"><tr>';
	foreach ($results as $a) {
	     $output .= '<td><a class="zoom" data-gall="wiki" href="'.$a.'"><img width="210" alt="'.$keyword.'" src="'.$a.'"></a></td>';
	}
	$output .= '</tr></table>';
	return $output;
}
function render_ad(string $name) {
	$ads = new parseCSV(realpath($_SERVER['DOCUMENT_ROOT']).'/ads/'.$name.'.csv');
	$count = count($ads->data);
	$index = rand(0, $count-1);
	switch ($name) {
		case 'itunes_160x600':
			echo '<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&amp;aId=10lpuQ&amp;bt=genre&amp;t=genre_matrix_'.$ads->data[$index]['style'].'&amp;ft='.$ads->data[$index]['feed_type'].'&amp;st='.$ads->data[$index]['media_type'].'&amp;c='.$ads->data[$index]['country_code'].'&amp;l=en-US&amp;s='.$ads->data[$index]['s'].'&amp;p='.$ads->data[$index]['p'].'&amp;w=160&amp;h=600"></iframe>';
			break;
		case 'itunes_300x250':
			echo '<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&amp;aId=10lpuQ&amp;bt=genre&amp;t=genre_matrix_'.$ads->data[$index]['style'].'&amp;ft='.$ads->data[$index]['feed_type'].'&amp;st='.$ads->data[$index]['media_type'].'&amp;c='.$ads->data[$index]['country_code'].'&amp;l=en-US&amp;s='.$ads->data[$index]['s'].'&amp;p='.$ads->data[$index]['p'].'&amp;w=300&amp;h=250"></iframe>';
			break;
		case 'itunes_728x90':
			echo '<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&amp;aId=10lpuQ&amp;bt=genre&amp;t=genre_matrix_'.$ads->data[$index]['style'].'&amp;ft='.$ads->data[$index]['feed_type'].'&amp;st='.$ads->data[$index]['media_type'].'&amp;c='.$ads->data[$index]['country_code'].'&amp;l=en-US&amp;s='.$ads->data[$index]['s'].'&amp;p='.$ads->data[$index]['p'].'&amp;w=728&amp;h=90"></iframe>';
			break;
		case 'amazon_160x600':
			echo '<iframe src="//rcm-na.amazon-adsystem.com/e/cm?t=tungpham42-20&amp;o=1&amp;p=14&amp;l=ur1&amp;category='.$ads->data[$index]['cat'].'&amp;f=ifr&amp;linkID='.$ads->data[$index]['link_id'].'"></iframe>';
			break;
		case 'amazon_300x250':
			echo '<iframe src="//rcm-na.amazon-adsystem.com/e/cm?t=tungpham42-20&amp;o=1&amp;p=12&amp;l=ur1&amp;category='.$ads->data[$index]['cat'].'&amp;f=ifr&amp;linkID='.$ads->data[$index]['link_id'].'"></iframe>';
			break;
		case 'amazon_728x90':
			echo '<iframe src="//rcm-na.amazon-adsystem.com/e/cm?t=tungpham42-20&amp;o=1&amp;p=48&amp;l=ur1&amp;category='.$ads->data[$index]['cat'].'&amp;f=ifr&amp;linkID='.$ads->data[$index]['link_id'].'"></iframe>';
			break;
		default:
			echo '<a class="ads" target="_blank" href="'.$ads->data[$index]['link_href'].'"><img alt="'.$name.'" src="'.$ads->data[$index]['img_src'].'" /></a>'.((isset($ads->data[$index]['other_img_src'])) ? '<img class="other_img" style="border:0" src="'.$ads->data[$index]['other_img_src'].'" width="1" height="1" alt="" />': "");
	}
}
function get_ad(string $name): string {
	$ads = new parseCSV(realpath($_SERVER['DOCUMENT_ROOT']).'/ads/'.$name.'.csv');
	$count = count($ads->data);
	$index = rand(0, $count-1);
	$ad = "";
	switch ($name) {
		case 'itunes_160x600':
			$ad = '<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&amp;aId=10lpuQ&amp;bt=genre&amp;t=genre_matrix_'.$ads->data[$index]['style'].'&amp;ft='.$ads->data[$index]['feed_type'].'&amp;st='.$ads->data[$index]['media_type'].'&amp;c='.$ads->data[$index]['country_code'].'&amp;l=en-US&amp;s='.$ads->data[$index]['s'].'&amp;p='.$ads->data[$index]['p'].'&amp;w=160&amp;h=600"></iframe>';
			break;
		case 'itunes_300x250':
			$ad = '<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&amp;aId=10lpuQ&amp;bt=genre&amp;t=genre_matrix_'.$ads->data[$index]['style'].'&amp;ft='.$ads->data[$index]['feed_type'].'&amp;st='.$ads->data[$index]['media_type'].'&amp;c='.$ads->data[$index]['country_code'].'&amp;l=en-US&amp;s='.$ads->data[$index]['s'].'&amp;p='.$ads->data[$index]['p'].'&amp;w=300&amp;h=250"></iframe>';
			break;
		case 'itunes_728x90':
			$ad = '<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&amp;aId=10lpuQ&amp;bt=genre&amp;t=genre_matrix_'.$ads->data[$index]['style'].'&amp;ft='.$ads->data[$index]['feed_type'].'&amp;st='.$ads->data[$index]['media_type'].'&amp;c='.$ads->data[$index]['country_code'].'&amp;l=en-US&amp;s='.$ads->data[$index]['s'].'&amp;p='.$ads->data[$index]['p'].'&amp;w=728&amp;h=90"></iframe>';
			break;
		case 'amazon_160x600':
			$ad = '<iframe src="//rcm-na.amazon-adsystem.com/e/cm?t=tungpham42-20&amp;o=1&amp;p=14&amp;l=ur1&amp;category='.$ads->data[$index]['cat'].'&amp;f=ifr&amp;linkID='.$ads->data[$index]['link_id'].'"></iframe>';
			break;
		case 'amazon_300x250':
			$ad = '<iframe src="//rcm-na.amazon-adsystem.com/e/cm?t=tungpham42-20&amp;o=1&amp;p=12&amp;l=ur1&amp;category='.$ads->data[$index]['cat'].'&amp;f=ifr&amp;linkID='.$ads->data[$index]['link_id'].'"></iframe>';
			break;
		case 'amazon_728x90':
			$ad = '<iframe src="//rcm-na.amazon-adsystem.com/e/cm?t=tungpham42-20&amp;o=1&amp;p=48&amp;l=ur1&amp;category='.$ads->data[$index]['cat'].'&amp;f=ifr&amp;linkID='.$ads->data[$index]['link_id'].'"></iframe>';
			break;
		default:
			$ad = '<a class="ads" target="_blank" href="'.$ads->data[$index]['link_href'].'"><img style="width: 360px" alt="'.$name.'" src="'.$ads->data[$index]['img_src'].'" /></a>'.((isset($ads->data[$index]['other_img_src'])) ? '<img class="other_img" style="border:0" src="'.$ads->data[$index]['other_img_src'].'" width="1" height="1" alt="" />': "");
	}
	return $ad;
}
function generate_proverb(string $lang): array {
	$proverbs = new parseCSV();
	$proverbs->delimiter = '|';
	$proverbs->parse(realpath($_SERVER['DOCUMENT_ROOT']).'/proverbs/'.$lang.'.csv');
	$count = count($proverbs->data);
	$index = rand(0, $count-1);
	return $proverbs->data[$index];
}
function render_proverb(string $lang): void {
	$proverb = generate_proverb($lang);
	echo '<blockquote id="proverb_content"><div id="proverb_text"><span class="content" data-toggle="tooltip" data-placement="bottom" title="Ấn để sao chép" data-disable-interaction="true" data-step="15" data-intro="Đây là một câu thành ngữ ngẫu nhiên cho bạn. Đọc và thư giãn nhé.">'.$proverb['content'].'</span></div></blockquote ><span class="arrow_down"></span><p id="proverb_author" class="float-right">'.$proverb['author'].'</p><div id="proverb_refresh" data-toggle="tooltip" data-placement="right" title="Thành ngữ khác"><i class="fad fa-sync-alt"></i></div>';
}
function render_proverb_json(string $lang): void {
	$proverb = generate_proverb($lang);
	echo json_encode($proverb);
}
function list_proverbs(int $page=1,string $lang='vi'): string { //Return users list, for admin use
	$output = "";
	$count = 10;
	$proverbs_csv = new parseCSV();
	$proverbs_csv->delimiter = '|';
	$proverbs_csv->parse(realpath($_SERVER['DOCUMENT_ROOT']).'/proverbs/'.$lang.'.csv');
	$proverbs = $proverbs_csv->data;
	$proverbs_count = count($proverbs);
	$pagination = new Pagination($proverbs,$page,$count,20);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$proverbs = $pagination->getResults();
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<table id="proverbs">';
	for ($i = 0; $i < $count; ++$i) {
		if (isset($proverbs[$i])) {
			$class = 'class="'.table_row_class($i).'"';
			$output .= '<tr '.$class.'>';
			$output .= '<td class="id">'.(($page-1)*$count+($i+1)).'</td>';
			$output .= '<td class="content">'.$proverbs[$i]['content'].'</td>';
			$output .= '<td class="author"><a target="_blank" href="'.get_wiki_url($proverbs[$i]['author']).'">'.$proverbs[$i]['author'].'</a></td>';
			$output .= '</tr>';
		}
	}
	$output .= '</table>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					var langCode = $("body").attr("lang");
					$.ajax({
						url: "/triggers/html/proverbs_list.php",
						type: "GET",
						cache: false,
						data: {
							page: page,
							lang: langCode
						},
						dataType: "html"
					}).done(function(data) {
						$("#proverbs_wrapper").html(data);
					});
				}
				</script>';
	return $output;
}
function render_country_json(): void {
	global $geoip_record;
	global $time_zone;
	init_timezone();
	$json_array = array(
//		'country' => $geoip_record->country->isoCode,
		'country' => $geoip_record->country_code,
		'utc_offset' => $time_zone,
		'timezone' => date_default_timezone_get()
	);
	echo json_encode($json_array);
}
/* ass */
function credential(int $type): string {
	global $credential_id;
	$credential = load_credential($credential_id);
	switch($type) {
		case 0;
			return $credential['user'];
			break;
		case 1;
			return $credential['pass'];
			break;
	}
}
function hash_pass(string $password): string {
	$hasher = new PasswordHash(8, true);
	return $hasher->HashPassword(trim($password));
}
function check_pass(string $password,string $hash): bool {
	$hasher = new PasswordHash(8, true);
	return $hasher->CheckPassword(trim($password), $hash);
}
/* http://www.informatik.uni-leipzig.de/~duc/amlich/calrules.html */
function jd_from_date(int $dd,int $mm,int $yy): int {
	$a = floor((14 - $mm) / 12);
	$y = $yy + 4800 - $a;
	$m = $mm + 12 * $a - 3;
	$jd = $dd + floor((153 * $m + 2) / 5) + 365 * $y + floor($y / 4) - floor($y / 100) + floor($y / 400) - 32045;
	if ($jd < 2299161) {
		$jd = $dd + floor((153* $m + 2)/5) + 365 * $y + floor($y / 4) - 32083;
	}
	return $jd;
}
function jd_to_date(int $jd): array {
	if ($jd > 2299160) { // After 5/10/1582, Gregorian calendar
		$a = $jd + 32044;
		$b = floor((4*$a+3)/146097);
		$c = $a - floor(($b*146097)/4);
	} else {
		$b = 0;
		$c = $jd + 32082;
	}
	$d = floor((4*$c+3)/1461);
	$e = $c - floor((1461*$d)/4);
	$m = floor((5*$e+2)/153);
	$day = $e - floor((153*$m+2)/5) + 1;
	$month = $m + 3 - 12*floor($m/10);
	$year = $b*100 + $d - 4800 + floor($m/10);
	//echo "day = $day, month = $month, year = $year\n";
	return array($day, $month, $year);
}
function get_new_moon_day($k,int $time_zone): int {
	$T = $k/1236.85; // Time in Julian centuries from 1900 January 0.5
	$T2 = $T * $T;
	$T3 = $T2 * $T;
	$dr = M_PI/180;
	$Jd1 = 2415020.75933 + 29.53058868*$k + 0.0001178*$T2 - 0.000000155*$T3;
	$Jd1 = $Jd1 + 0.00033*sin((166.56 + 132.87*$T - 0.009173*$T2)*$dr); // Mean new moon
	$M = 359.2242 + 29.10535608*$k - 0.0000333*$T2 - 0.00000347*$T3; // Sun's mean anomaly
	$Mpr = 306.0253 + 385.81691806*$k + 0.0107306*$T2 + 0.00001236*$T3; // Moon's mean anomaly
	$F = 21.2964 + 390.67050646*$k - 0.0016528*$T2 - 0.00000239*$T3; // Moon's argument of latitude
	$C1=(0.1734 - 0.000393*$T)*sin($M*$dr) + 0.0021*sin(2*$dr*$M);
	$C1 = $C1 - 0.4068*sin($Mpr*$dr) + 0.0161*sin($dr*2*$Mpr);
	$C1 = $C1 - 0.0004*sin($dr*3*$Mpr);
	$C1 = $C1 + 0.0104*sin($dr*2*$F) - 0.0051*sin($dr*($M+$Mpr));
	$C1 = $C1 - 0.0074*sin($dr*($M-$Mpr)) + 0.0004*sin($dr*(2*$F+$M));
	$C1 = $C1 - 0.0004*sin($dr*(2*$F-$M)) - 0.0006*sin($dr*(2*$F+$Mpr));
	$C1 = $C1 + 0.0010*sin($dr*(2*$F-$Mpr)) + 0.0005*sin($dr*(2*$Mpr+$M));
	if ($T < -11) {
		$deltat= 0.001 + 0.000839*$T + 0.0002261*$T2 - 0.00000845*$T3 - 0.000000081*$T*$T3;
	} else {
		$deltat= -0.000278 + 0.000265*$T + 0.000262*$T2;
	};
	$Jd_new = $Jd1 + $C1 - $deltat;
	//echo "Jd_new = $Jd_new\n";
	return floor($Jd_new + 0.5 + $time_zone/24);
}
function get_sun_longitude($jdn,int $time_zone): int {
	$T = ($jdn - 2451545.5 - $time_zone/24) / 36525; // Time in Julian centuries from 2000-01-01 12:00:00 GMT
	$T2 = $T * $T;
	$dr = M_PI/180; // degree to radian
	$M = 357.52910 + 35999.05030*$T - 0.0001559*$T2 - 0.00000048*$T*$T2; // mean anomaly, degree
	$L0 = 280.46645 + 36000.76983*$T + 0.0003032*$T2; // mean longitude, degree
	$DL = (1.914600 - 0.004817*$T - 0.000014*$T2)*sin($dr*$M);
	$DL = $DL + (0.019993 - 0.000101*$T)*sin($dr*2*$M) + 0.000290*sin($dr*3*$M);
	$L = $L0 + $DL; // true longitude, degree
	//echo "\ndr = $dr, M = $M, T = $T, DL = $DL, L = $L, L0 = $L0\n";
    // obtain apparent longitude by correcting for nutation and aberration
    $omega = 125.04 - 1934.136 * $T;
    $L = $L - 0.00569 - 0.00478 * sin($omega * $dr);
	$L = $L*$dr;
	$L = $L - M_PI*2*(floor($L/(M_PI*2))); // Normalize to (0, 2*PI)
	return floor($L/M_PI*6);
}
function get_lunar_month_11(int $yy,int $time_zone): int {
	$off = jd_from_date(31, 12, $yy) - 2415021;
	$k = floor($off / 29.530588853);
	$nm = get_new_moon_day($k, $time_zone);
	$sun_long = get_sun_longitude($nm, $time_zone); // sun longitude at local midnight
	if ($sun_long >= 9) {
		$nm = get_new_moon_day($k-1, $time_zone);
	}
	return $nm;
}
function get_leap_month_offset($a11,int $time_zone): int {
	$k = floor(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
	$last = 0;
	$i = 1; // We start with the month following lunar month 11
	$arc = get_sun_longitude(get_new_moon_day($k + $i, $time_zone), $time_zone);
	do {
		$last = $arc;
		$i = $i + 1;
		$arc = get_sun_longitude(get_new_moon_day($k + $i, $time_zone), $time_zone);
	} while ($arc != $last && $i < 14);
	return $i - 1;
}
/* Comvert solar date dd/mm/yyyy to the corresponding lunar date */
function convert_solar_to_lunar(int $dd,int $mm,int $yy,int $time_zone): array {
	$day_number = jd_from_date($dd, $mm, $yy);
	$k = floor(($day_number - 2415021.076998695) / 29.530588853);
	$month_start = get_new_moon_day($k+1, $time_zone);
	if ($month_start > $day_number) {
		$month_start = get_new_moon_day($k, $time_zone);
	}
	$a11 = get_lunar_month_11($yy, $time_zone);
	$b11 = $a11;
	if ($a11 >= $month_start) {
		$lunar_year = $yy;
		$a11 = get_lunar_month_11($yy-1, $time_zone);
	} else {
		$lunar_year = $yy+1;
		$b11 = get_lunar_month_11($yy+1, $time_zone);
	}
	$lunar_day = $day_number - $month_start + 1;
	$diff = floor(($month_start - $a11)/29);
	$lunar_leap = 0;
	$lunar_month = $diff + 11;
	if ($b11 - $a11 > 365) {
		$leap_month_diff = get_leap_month_offset($a11, $time_zone);
		if ($diff >= $leap_month_diff) {
			$lunar_month = $diff + 10;
			if ($diff == $leap_month_diff) {
				$lunar_leap = 1;
			}
		}
	}
	if ($lunar_month > 12) {
		$lunar_month = $lunar_month - 12;
	}
	if ($lunar_month >= 11 && $diff < 4) {
		$lunar_year -= 1;
	}
	return array($lunar_day, $lunar_month, $lunar_year, $lunar_leap);
}
/* Convert a lunar date to the corresponding solar date */
function convert_lunar_to_solar(int $lunar_day,int $lunar_month,int $lunar_year,int $lunar_leap,int $time_zone): array {
	if ($lunar_month < 11) {
		$a11 = get_lunar_month_11($lunar_year-1, $time_zone);
		$b11 = get_lunar_month_11($lunar_year, $time_zone);
	} else {
		$a11 = get_lunar_month_11($lunar_year, $time_zone);
		$b11 = get_lunar_month_11($lunar_year+1, $time_zone);
	}
	$k = floor(0.5 + ($a11 - 2415021.076998695) / 29.530588853);
	$off = $lunar_month - 11;
	if ($off < 0) {
		$off += 12;
	}
	if ($b11 - $a11 > 365) {
		$leap_off = get_leap_month_offset($a11, $time_zone);
		$leap_month = $leap_off - 2;
		if ($leap_month < 0) {
			$leap_month += 12;
		}
		if ($lunar_leap != 0 && $lunar_month != $leap_month) {
			return array(0, 0, 0);
		} else if ($lunar_leap != 0 || $off >= $leap_off) {
			$off += 1;
		}
	}
	$month_start = get_new_moon_day($k + $off, $time_zone);
	return jd_to_date($month_start + $lunar_day - 1);
}
function get_lunar_date(string $date = 'today',bool $formatted = false) {
	global $time_zone;
	$solar_year = date('Y', strtotime($date));
	$solar_month = date('m', strtotime($date));
	$solar_day = date('d', strtotime($date));
	$lunar_date = convert_solar_to_lunar($solar_day, $solar_month, $solar_year, $time_zone);
	return ($formatted == true) ? str_pad($lunar_date[2], 4, '0', STR_PAD_LEFT).'-'.str_pad($lunar_date[1], 2, '0', STR_PAD_LEFT).'-'.str_pad($lunar_date[0], 2, '0', STR_PAD_LEFT): $lunar_date;
}
function get_lunar_values(array $lunar_index,string $lang = 'vi'): array {
	$stem_index = $lunar_index[0];
	$branch_index = $lunar_index[1];
	$stem = "";
	$branch = "";
	switch($lang) {
		case 'vi':
			switch($stem_index) {
				case 0:	$stem = 'Giáp'; break;
				case 1:	$stem = 'Ất'; break;
				case 2:	$stem = 'Bính'; break;
				case 3:	$stem = 'Đinh'; break;
				case 4:	$stem = 'Mậu'; break;
				case 5:	$stem = 'Kỷ'; break;
				case 6:	$stem = 'Canh'; break;
				case 7:	$stem = 'Tân'; break;
				case 8:	$stem = 'Nhâm'; break;
				case 9:	$stem = 'Quý'; break;
			}
			switch($branch_index) {
				case 0:	$branch = 'Tý';	break;
				case 1:	$branch = 'Sửu'; break;
				case 2:	$branch = 'Dần'; break;
				case 3:	$branch = 'Mẹo'; break;
				case 4:	$branch = 'Thìn'; break;
				case 5:	$branch = 'Tỵ';	break;
				case 6:	$branch = 'Ngọ'; break;
				case 7:	$branch = 'Mùi'; break;
				case 8:	$branch = 'Thân'; break;
				case 9:	$branch = 'Dậu'; break;
				case 10: $branch = 'Tuất'; break;
				case 11: $branch = 'Hợi'; break;
			}
			break;
		case 'en':
			switch($stem_index) {
				case 0:	$stem = 'Yang Wood'; break;
				case 1:	$stem = 'Yin Wood'; break;
				case 2:	$stem = 'Yang Fire'; break;
				case 3:	$stem = 'Yin Fire'; break;
				case 4:	$stem = 'Yang Earth'; break;
				case 5:	$stem = 'Yin Earth'; break;
				case 6:	$stem = 'Yang Metal'; break;
				case 7:	$stem = 'Yin Metal'; break;
				case 8:	$stem = 'Yang Water'; break;
				case 9:	$stem = 'Yin Water'; break;
			}
			switch($branch_index) {
				case 0:	$branch = 'Rat'; break;
				case 1:	$branch = 'Ox'; break;
				case 2:	$branch = 'Tiger'; break;
				case 3:	$branch = 'Cat'; break;
				case 4:	$branch = 'Dragon'; break;
				case 5:	$branch = 'Snake'; break;
				case 6:	$branch = 'Horse'; break;
				case 7:	$branch = 'Goat'; break;
				case 8:	$branch = 'Monkey'; break;
				case 9:	$branch = 'Rooster'; break;
				case 10: $branch = 'Dog'; break;
				case 11: $branch = 'Pig'; break;
			}
			break;
		case 'ru':
			switch($stem_index) {
				case 0:	$stem = 'Ян Дерево'; break;
				case 1:	$stem = 'Инь Дерево'; break;
				case 2:	$stem = 'Ян Огонь'; break;
				case 3:	$stem = 'Инь Огонь'; break;
				case 4:	$stem = 'Ян Земля'; break;
				case 5:	$stem = 'Инь Земля'; break;
				case 6:	$stem = 'Ян Металл'; break;
				case 7:	$stem = 'Инь Металл'; break;
				case 8:	$stem = 'Ян Вода'; break;
				case 9:	$stem = 'Инь Вода'; break;
			}
			switch($branch_index) {
				case 0:	$branch = 'Мышь'; break;
				case 1:	$branch = 'Корова'; break;
				case 2:	$branch = 'Тигр'; break;
				case 3:	$branch = 'Кролик'; break;
				case 4:	$branch = 'Дракон'; break;
				case 5:	$branch = 'Змея'; break;
				case 6:	$branch = 'Конь'; break;
				case 7:	$branch = 'Овца'; break;
				case 8:	$branch = 'Обезьяна'; break;
				case 9:	$branch = 'Петух'; break;
				case 10: $branch = 'Собака'; break;
				case 11: $branch = 'Свинья'; break;
			}
			break;
		case 'es':
			switch($stem_index) {
				case 0:	$stem = 'Yang Madera'; break;
				case 1:	$stem = 'Yin Madera'; break;
				case 2:	$stem = 'Yang Fuego'; break;
				case 3:	$stem = 'Yin Fuego'; break;
				case 4:	$stem = 'Yang Tierra'; break;
				case 5:	$stem = 'Yin Tierra'; break;
				case 6:	$stem = 'Yang Metal'; break;
				case 7:	$stem = 'Yin Metal'; break;
				case 8:	$stem = 'Yang Agua'; break;
				case 9:	$stem = 'Yin Agua'; break;
			}
			switch($branch_index) {
				case 0:	$branch = 'Rata'; break;
				case 1:	$branch = 'Buey'; break;
				case 2:	$branch = 'Tigre'; break;
				case 3:	$branch = 'Conejo'; break;
				case 4:	$branch = 'Dragón'; break;
				case 5:	$branch = 'Serpiente'; break;
				case 6:	$branch = 'Caballo'; break;
				case 7:	$branch = 'Oveja'; break;
				case 8:	$branch = 'Mono'; break;
				case 9:	$branch = 'Gallo'; break;
				case 10: $branch = 'Perro'; break;
				case 11: $branch = 'Cerdo'; break;
			}
			break;
		case 'zh':
			switch($stem_index) {
				case 0:	$stem = '甲'; break;
				case 1:	$stem = '乙'; break;
				case 2:	$stem = '丙'; break;
				case 3:	$stem = '丁'; break;
				case 4:	$stem = '戊'; break;
				case 5:	$stem = '己'; break;
				case 6:	$stem = '庚'; break;
				case 7:	$stem = '辛'; break;
				case 8:	$stem = '壬'; break;
				case 9:	$stem = '癸'; break;
			}
			switch($branch_index) {
				case 0:	$branch = '子';	break;
				case 1:	$branch = '丑';	break;
				case 2:	$branch = '寅';	break;
				case 3:	$branch = '卯';	break;
				case 4:	$branch = '辰';	break;
				case 5:	$branch = '巳';	break;
				case 6:	$branch = '午';	break;
				case 7:	$branch = '未';	break;
				case 8:	$branch = '申';	break;
				case 9:	$branch = '酉';	break;
				case 10: $branch = '戌'; break;
				case 11: $branch = '亥'; break;
			}
			break;
		case 'ja':
			switch($stem_index) {
				case 0:	$stem = 'こう'; break;
				case 1:	$stem = 'いつ'; break;
				case 2:	$stem = 'へい'; break;
				case 3:	$stem = 'てい'; break;
				case 4:	$stem = 'ぼ'; break;
				case 5:	$stem = 'き'; break;
				case 6:	$stem = 'こう'; break;
				case 7:	$stem = 'しん'; break;
				case 8:	$stem = 'じん'; break;
				case 9:	$stem = 'き'; break;
			}
			switch($branch_index) {
				case 0:	$branch = 'ね'; break;
				case 1:	$branch = 'うし'; break;
				case 2:	$branch = 'とら'; break;
				case 3:	$branch = 'う'; break;
				case 4:	$branch = 'たつ'; break;
				case 5:	$branch = 'み';	break;
				case 6:	$branch = 'うま'; break;
				case 7:	$branch = 'ひつじ'; break;
				case 8:	$branch = 'さる'; break;
				case 9:	$branch = 'とり'; break;
				case 10: $branch = 'いぬ'; break;
				case 11: $branch = 'い'; break;
			}
			break;
	}
	return array($stem, $branch);
}
function get_leap_value(string $lang = 'vi'): string {
	$leap_value = "";
	switch($lang) {
		case 'vi': $leap_value = 'nhuận'; break;
		case 'en': $leap_value = 'leap'; break;
		case 'ru': $leap_value = 'високосный'; break;
		case 'es': $leap_value = 'bisiesto'; break;
		case 'zh': $leap_value = '闰'; break;
		case 'ja': $leap_value = '閏'; break;
	}
	return $leap_value;
}
function get_lunar_year(string $date = 'today',string $lang = 'vi'): string {
	$lunar_date = get_lunar_date($date);
	$lunar_year = $lunar_date[2];
	$stem_index = ($lunar_year+6)%10;
	$branch_index = ($lunar_year+8)%12;
	$lunar_values = get_lunar_values(array($stem_index, $branch_index), $lang);
	$stem = $lunar_values[0];
	$branch = $lunar_values[1];
	return (($stem != "" && $branch != "") ? $stem.' '.$branch.' - ': "").$lunar_year;
}
function get_lunar_month(string $date = 'today',string $lang = 'vi'): string {
	$lunar_date = get_lunar_date($date);
	$lunar_year = $lunar_date[2];
	$lunar_month = $lunar_date[1];
	$lunar_leap = $lunar_date[3];
	$stem_index = ($lunar_year*12+$lunar_month+3)%10;
	$branch_index = ($lunar_month+1)%12;
	$lunar_values = get_lunar_values(array($stem_index, $branch_index), $lang);
	$stem = $lunar_values[0];
	$branch = $lunar_values[1];
	return (($stem != "" && $branch != "") ? $stem.' '.$branch.' - ': "").$lunar_month.(($lunar_leap == 1) ? ' '.get_leap_value($lang): "");
}
function get_lunar_day(string $date = 'today',string $lang = 'vi'): string {
	$lunar_date = get_lunar_date($date);
	$lunar_day = $lunar_date[0];
	$solar_year = date('Y', strtotime($date));
	$solar_month = date('m', strtotime($date));
	$solar_day = date('d', strtotime($date));
	$jd = jd_from_date($solar_day, $solar_month, $solar_year);
	$stem_index = ($jd+9)%10;
	$branch_index = ($jd+1)%12;
	$lunar_values = get_lunar_values(array($stem_index, $branch_index), $lang);
	$stem = $lunar_values[0];
	$branch = $lunar_values[1];
	return (($stem != "" && $branch != "") ? $stem.' '.$branch.' - ': "").$lunar_day;
}
function get_lunar_years_old(string $dob,string $date = 'today'): int {
	$lunar_date = get_lunar_date($date);
	$lunar_year = $lunar_date[2];
	$lunar_birth_date = get_lunar_date($dob);
	$lunar_birth_year = $lunar_birth_date[2];
	return $lunar_year-$lunar_birth_year;
}
function render_rss_feed(string $rss_url,string $feed_header,$feed_id): void {
	$result = '<section id="'.$feed_id.'" class="rss_feed">';
	$result .= '<h2>'.$feed_header.'</h2>';
	//$result .= '<div class="help help_rss_feed changeable"><i class="m-icon-white"></i></div>';
	$result .= '<div class="feed"></div>';
	$result .= '</section>';
	$result .= '<span id="'.$feed_id.'_end"></span>';
	$result .= '<script>
				loadFeed("'.$rss_url.'","'.$feed_id.'");
				$("#'.$feed_id.' > h2").on("click", function(){
					$("body, html").animate({scrollTop: ($("#'.$feed_id.'_end").offset().top-$("header").height())}, 700);
				});
				</script>';
	echo $result;
}
function rss_feed_email(string $rss_url,string $feed_header,$feed_id): string {
	$rss = new Rss;
	$feed = $rss->getFeed($rss_url, Rss::XML);
	$result = '<section id="'.$feed_id.'" class="rss_feed">';
	$result .= '<h2>'.$feed_header.'</h2>';
	$result .= '<table class="feed">';
	foreach($feed as $item) {
		$result .= '<tr class="feed_item">';
		$result .= '<td>';
		$result .= '<h4><a target="_blank" class="item_title rotate" href="'.$item['link'].'"><span data-title="'.$item['title'].'">'.$item['title'].'</span></a></h4>';
		$result .= '<p class="item_date">'.date('Y-m-d',strtotime($item['date'])).'</p>';
		$result .= '<div class="item_desc">'.$item['description'].'</div>';
		$result .= '</td>';
		$result .= '</tr>';
	}
	$result .= '</table>';
	$result .= '</section>';
	return $result;
}
function load_rss_feed(string $rss_url): void {
	$rss = new Rss;
	$feed = $rss->getFeed($rss_url, Rss::XML);
	$result = "";
	foreach($feed as $item) {
		$result .= '<div class="feed_item">';
		$result .= '<a target="_blank" class="item_title rotate" href="'.$item['link'].'"><span data-title="'.$item['title'].'">'.$item['title'].'</span></a>';
		$result .= '<p class="item_date">'.date('Y-m-d',strtotime($item['date'])).'</p>';
		$result .= '<div class="item_desc">'.$item['description'].'</div>';
		$result .= '</div>';
	}
	echo $result;
}
function load_news_feed(string $keyword=""): void {
	global $faroo_key;
	$keyword = ($keyword != "") ? '%22'.urlencode($keyword).'%22' : "";
	$url = 'http://www.faroo.com/api?q='.$keyword.'&start=1&length=20&src='.(($keyword == "") ? 'news': 'web').'&i=false&f=json&key='.$faroo_key;
	$body = file_get_contents($url);
	$json = json_decode($body);
	$result = "";
	if (isset($json->results)) {
		$count = count($json->results);
		for ($i=0; $i < $count; ++$i) {
			$result .= '<li>';
			$result .= '<a target="_blank" class="news_item" href="'.$json->results[$i]->url.'"><span class="news_title">'.$json->results[$i]->title.'</span><span class="news_domain">'.$json->results[$i]->domain.'</span><span class="news_date">'.date('Y-m-d h:i:s A',$json->results[$i]->date/1000).'</span></a>';
			$result .= '<i class="icon-info-sign news_toggle"></i>';
			$result .= '<div class="news_thumb">'.((isset($json->results[$i]->iurl) && $json->results[$i]->iurl != "") ? '<img class="news_thumb_img" src="'.$json->results[$i]->iurl.'" />' : "").'<span class="news_author">'.$json->results[$i]->author.'</span><span class="news_text">'.$json->results[$i]->kwic.'</span></div>';
			$result .= '</li>';
		}
	} else if (!isset($json->results)) {
		$result .= '<li>...</li>';
	}
	echo $result;
}
function curl_get_contents(string $url,string $api = ""): string {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  if ($api != "") {
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: ' . $api
  ));
  }
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
function render_pexels_photo_of_the_day(): void {
  $api = '563492ad6f917000010000010a63acd1b2194f1e8676a5cb4fff63b6';
  $url = 'https://api.pexels.com/v1/search?query=nature&per_page=1&page=1';
  //$nasa_json = file_get_contents($nasa_url);
  $json = curl_get_contents($url,$api);
  $array = json_decode($json, true);
  //echo '<pre>';
  //print_r($bing_array);
  //echo '</pre>';
  $photo_url = $array['photos'][0]['src']['landscape'];
  echo '
<style>
body:not(.birthday) {
  background: url("'.$photo_url.'") no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
</style>
  ';
}
function render_nasa_photo_of_the_day(): void {
	$nasa_api = 'SqccgSap9C0luAWhNxIYYQOourgnZiR60MD721ho';
	$nasa_url = 'https://api.nasa.gov/planetary/apod?api_key='.$nasa_api;
  //$nasa_json = file_get_contents($nasa_url);
	$nasa_json = curl_get_contents($nasa_url);
	$nasa_array = json_decode($nasa_json, true);
	//echo '<pre>';
	//print_r($bing_array);
	//echo '</pre>';
	$nasa_photo_url = $nasa_array['url'];
	echo '
<style>
body:not(.birthday) {
	background: url("'.$nasa_photo_url.'") no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
</style>
	';
}
function render_yahoo_photo_of_the_day(): void {
  $f = new phpFlickr("d6278a068212210f61f04eb279896b0e");
  $recent = $f->photos_getRecent();
  //echo '<pre>';
  //print_r($recent);
  //echo '</pre>';
  $photo = $recent['photos']['photo'][0];
  $photo_url = "https://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'] . "/";
  echo '
<style>
body:not(.birthday) {
  background: url("'.$photo_url.'") no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
</style>
  ';
}
function render_bing_photo_of_the_day(): void {
  $bing = new grubersjoe\BingPhoto([
    'locale' => 'vi-VN',
    'quality' => grubersjoe\BingPhoto::QUALITY_LOW,
  ]);
	//$bing_url = 'https://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=en-US';
	//$bing_json = curl_get_contents($bing_url);
	//$bing_array = json_decode($bing_json, true);
	//echo '<pre>';
	//print_r($bing_array);
	//echo '</pre>';
	$bing_photo_url = $bing->getImage()['url'];
	echo '
<style>
body:not(.birthday) {
	background: url("'.$bing_photo_url.'") no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
</style>
	';
}
function render_unsplash_photo_of_the_day(): void {
	$access_key = '50dd24725488cc55e1a1fce60fde17b5f3e282b1aae827cc3cda4586efec8f81';
	$url = 'https://api.unsplash.com/photos/random?featured=true&client_id='.$access_key.'&orientation=landscape';
	$json = curl_get_contents($url);
	$array = json_decode($json, true);
	//echo '<pre>';
	//print_r($array);
	//echo '</pre>';
	$photo_url = $array['urls']['small'];
	$photo_credit = $array['user']['name'];
	$photo_credit_url = $array['user']['links']['html'];
	echo '
<style>
body:not(.birthday) {
	background: url("'.$photo_url.'") no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
#photo_credit {
  position: fixed;
  bottom: 0;
  right: 0;
  color: rgba(24, 24, 24, 0.84);
  font-family: Tahoma, sans-serif;
  font-size: 12px;
  font-weight: bold;
  white-space: pre;
  z-index: 0;
  pointer-events: all;
  background-color: rgba(240,240,240,0.84);
  padding: 2px 6px;
  border-radius: 8px;
}
</style>
<a id="photo_credit" href="'.$photo_credit_url.'?utm_source=Nhip Sinh Hoc . VN&utm_medium=referral" target="_blank">Credit: '.$photo_credit.' - Unsplash Profile: '.$photo_credit_url.'</a>';
}
function render_natgeo_photo_of_the_day(): void {
	$url = 'http://www.nationalgeographic.com/photography/photo-of-the-day/_jcr_content/.gallery.json';
  //$nasa_json = file_get_contents($nasa_url);
	$result = curl_get_contents($url);
	$obj = json_decode($result);

	$item = $obj->items[0];
	$parts = (array) $item->sizes;
	ksort($parts, SORT_NUMERIC);
	$part = end(array_values($parts));

	$photo_url = $item->url.$part;
	echo '
<style>
body:not(.birthday) {
	background: url("'.$photo_url.'") no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
</style>
	';
}
/* Member Management */
function hash_token(string $email): string {
	$hasher = new PasswordHash(12, true);
	return $hasher->HashPassword(trim($email));
}
function check_token(string $email,string $hash): bool {
	$hasher = new PasswordHash(12, true);
	return $hasher->CheckPassword(trim($email), $hash);
}
/* Validation */
function invalid_email(string $email): bool {
	if (!filter_var(strtolower($email), FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
}
function short_pass(string $pass): bool {
	if (strlen($pass) < 8) {
		return true;
	} else {
		return false;
	}
}
function long_pass(string $pass): bool {
	if (strlen($pass) > 20) {
		return true;
	} else {
		return false;
	}
}
function no_number_pass(string $pass): bool {
	if (!preg_match('#[0-9]+#', $pass)) {
		return true;
	} else {
		return false;
	}
}
function no_letter_pass(string $pass): bool {
	if (!preg_match('#[a-z]+#', $pass)) {
		return true;
	} else {
		return false;
	}
}
function no_caps_pass(string $pass): bool {
	if (!preg_match('#[A-Z]+#', $pass)) {
		return true;
	} else {
		return false;
	}
}
function no_symbol_pass(string $pass): bool {
	if (!preg_match('#\W+#', $pass)) {
		return true;
	} else {
		return false;
	}
}
function not_match_pass(string $pass,string $repeat): bool {
	if ($pass != $repeat) {
		return true;
	} else {
		return false;
	}
}
function invalid_dob(string $dob): bool {
	list($year, $month, $day) = explode('-', $dob);
	if (is_numeric($year) && is_numeric($month) && is_numeric($day)) {
		if (!checkdate($month,$day,$year)) {
			return true;
		} else {
			return false;
		}
	}
}
function taken_email(string $email): bool {
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/'.strtolower($email);
	if (is_dir($path)) {
		return true;
	} else {
		return false;
	}
}
function unsubscribed_email(string $email): bool {
	$unsubscriber_emails = array();
	$unsubscribers = new parseCSV();
	$unsubscribers->parse(realpath($_SERVER['DOCUMENT_ROOT']).'/member/unsubscribers_list.csv');
	$unsubscribers_count = count($unsubscribers->data);
	for ($i = 0; $i < $unsubscribers_count; ++$i) {
		$unsubscriber_emails[$i] = $unsubscribers->data[$i]['email'];
	}
	sort($unsubscriber_emails);
	if (in_array($email, $unsubscriber_emails)) {
		return true;
	} else {
		return false;
	}
//	$i = 0
//	while($unsubscribers->data[$i]['email']==$email){
//		++$i;
//		if ($unsubscribers->data[$i]['email']==null) return false;
//	}
//	return true;
}
function invalid_member(string $email,string $password): bool {
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/'.strtolower($email);
	if (is_dir($path)) {
		$db_path = $path.'/member.db';
		$db_sql = 'SELECT password FROM `member`';
		try {
			$db = new PDO('sqlite:'.$db_path);
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$db_query = $db->prepare($db_sql);
			$db_query->execute();
			if ($db_query) {
				$retrieved_password = $db_query->fetchColumn();
			}
		} catch (PDOException $e) {
			echo 'ERROR: '.$e->getMessage();
		}
		if (check_pass($password, $retrieved_password)) {
			return false;
		} else {
			return true;
		}
	} else {
		return true;
	}
}
function load_member_from_email(string $email): array {
	$array = array();
	if ($email != "") {
		$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/'.strtolower($email);
		$db_path = $path.'/member.db';
		$db_sql = 'SELECT * FROM `member`';
		try {
			$db = new PDO('sqlite:'.$db_path);
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$db_query = $db->prepare($db_sql);
			$db_query->execute();
			if ($db_query) {
				while($row = $db_query->fetch(PDO::FETCH_ASSOC)) {
					$array = $row;
				}
			}
		} catch (PDOException $e) {
			echo 'ERROR: '.$e->getMessage();
		}
		return $array;
	} else {
		return null;
	}
}
function list_members(int $page=1,string $keyword=""): string { //Return members list, for admin use
	$output = "";
	$count = 10;
	$emails = array();
	$members = array();
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/';
	$directories = glob($path.'*', GLOB_ONLYDIR|GLOB_NOSORT);
	if (!count($directories)) {
		echo 'No matches';
	} else {
		$n = 0;
		foreach ($directories as $directory) {
			//if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register' && is_dir($path.str_replace($path, "", $directory))) {
			if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register') {
				$emails[] = str_replace($path, "", $directory);
			}
		}
	}
	$emails_count = count($emails);
	sort($emails);
	for ($m = 0; $m < $emails_count; ++$m) {
		$members[$m] = load_member_from_email($emails[$m]);
	}
	$members = ($keyword != "") ? array_filter($members, array(new filter($keyword), 'filter_keyword_member')): $members;
	usort($members,'sort_date_member_ascend');
	$members_count = count($members);
	$pagination = new Pagination($members,$page,$count,20);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$members = $pagination->getResults();
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<table class="admin">';
	$output .= '<thead>';
	$output .= '<tr><th>Order</th><th>Email</th><th>Fullname</th><th>DOB</th><th>Language</th><th>Created at</th><th>Edited at</th><th colspan="2">Operations</th></tr>';
	$output .= '<tr><td class="count" colspan="9">'.$members_count.' item'.(($members_count > 1) ? 's': "").' to display.</td></tr>';
	$output .= '</thead>';
	$output .= '<tbody id="table_body">';
	for ($i = 0; $i < $count; ++$i) {
		if (isset($members[$i])) {
			$class = 'class="'.table_row_class($i).'"';
			$output .= '<tr '.$class.'>';
			$output .= '<td>'.(($page-1)*$count+($i+1)).'</td>';
			$output .= '<td>'.$members[$i]['email'].'</td>';
			$output .= '<td>'.$members[$i]['fullname'].'</td>';
			$output .= '<td>'.$members[$i]['dob'].'</td>';
			$output .= '<td>'.$members[$i]['lang'].'</td>';
			$output .= '<td>'.$members[$i]['created_at'].'</td>';
			$output .= '<td>'.$members[$i]['edited_at'].'</td>';
			$output .= '<td><a class="button" href="/member/'.$members[$i]['email'].'/">Edit</a></td>';
			$output .= '<td><a class="button" onclick="return confirm(\'Are you sure?\')" href="/triggers/member_delete.php?email='.$members[$i]['email'].'">Delete</a></td>';
			$output .= '</tr>';
		}
	}
	$output .= '</tbody>';
	$output .= '</table>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#admin_member").load("/triggers/admin_member.php",{page:page,keyword:"'.$keyword.'"});
				}
				</script>';
	return $output;
}
function bulk_sql_members(string $db_sql): void {
	$emails = array();
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/';
	$directories = glob($path.'*', GLOB_ONLYDIR|GLOB_NOSORT);
	if (!count($directories)) {
		echo 'No matches';
	} else {
		$n = 0;
		foreach ($directories as $directory) {
			//if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register' && is_dir($path.str_replace($path, "", $directory))) {
			if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register') {
				$emails[] = str_replace($path, "", $directory);
			}
		}
	}
	$count = count($emails);
	sort($emails);
	foreach ($emails as $email) {
		$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/'.$email;
		$db_path = $path.'/member.db';
		try {
			$db = new PDO('sqlite:'.$db_path);
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$db_query = $db->prepare($db_sql);
			$db_query->execute();
		} catch (PDOException $e) {
			echo 'ERROR: '.$e->getMessage();
		}
	}
}
function generate_message_id(): string {
	return sprintf("<%s.%s@%s>",base_convert(microtime(), 10, 36),base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),$_SERVER['SERVER_NAME']);
}
function send_mail(string $to,string $subject,array $message): void {
	global $lang_code, $span_interfaces, $email_credentials, $brand;
//	$unsubscriber_emails = array();
//	$unsubscribers = new parseCSV();
//	$unsubscribers->parse(realpath($_SERVER['DOCUMENT_ROOT']).'/member/unsubscribers_list.csv');
//	$unsubscribers_count = count($unsubscribers->data);
//	for ($i = 0; $i < $unsubscribers_count; ++$i) {
//		$unsubscriber_emails[$i] = $unsubscribers->data[$i]['email'];
//	}
//	sort($unsubscriber_emails);
//	if (!in_array(strtolower($to), $unsubscriber_emails)) {
		$fullname = taken_email($to) ? load_member_from_email($to)['fullname']: "";
		$boundary = uniqid('np');
		$headers = "";
		$headers .= "Organization: \"".$brand."\"".PHP_EOL;
		$headers  = "MIME-Version: 1.0".PHP_EOL;
		$headers .= "X-Priority: 1 (Highest)".PHP_EOL;
		$headers .= "Importance: High".PHP_EOL;
		$headers .= "X-Mailer: PHP/". phpversion().PHP_EOL;
		$headers .= "Content-Transfer-Encoding: 8bit".PHP_EOL;
		$headers .= "From: \"".$brand."\" <noreply@".$_SERVER['SERVER_NAME'].">".PHP_EOL;
		$headers .= "Sender: <noreply@".$_SERVER['SERVER_NAME'].">".PHP_EOL;
		$headers .= "Reply-To: \"".$brand."\" <admin@nhipsinhhoc.vn>".PHP_EOL;
		$headers .= "Return-Path: \"".$brand."\" <admin@nhipsinhhoc.vn>".PHP_EOL;
		$headers .= "List-Unsubscribe: <mailto:admin@nhipsinhhoc.vn?subject=Unsubscribe me out of ".$brand." mailing list&body=Please unsubscribe my email&cc=tung.42@gmail.com>".PHP_EOL;
		$headers .= "Content-Type: multipart/alternative;boundary=".$boundary.PHP_EOL;
		//here is the content body
		$body = "This is a MIME encoded message.".PHP_EOL;
		$body .= PHP_EOL.PHP_EOL."--".$boundary.PHP_EOL;
		$body .= "Content-type: text/plain;charset=utf-8".PHP_EOL.PHP_EOL;
		//Plain text body
		$body .= $message['plain'].PHP_EOL;
		$body .= PHP_EOL.PHP_EOL."--".$boundary.PHP_EOL;
		$body .= "Content-type: text/html;charset=utf-8".PHP_EOL.PHP_EOL;
		//Html body
		$body .= $message['html'].PHP_EOL;
		$body .= PHP_EOL.PHP_EOL."--".$boundary."--";
		mail("\"".$fullname."\" <".strtolower($to).">", '=?utf-8?B?'.base64_encode('☺ '.$subject).'?=', $body, $headers);
//	}
}
function email_message(string $heading,string $content): array {
	$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> <meta name="viewport" content="width=device-width"/></head><body style="padding: 0px; margin: 0px; width: 100%; min-width: 100%"> <table class="body" style="color: #222222;background-image: url(\'https://'.$_SERVER['SERVER_NAME'].'/css/images/coin.png\'); min-height: 420px;border: none; border-spacing: 0px; position: relative; height: 100%;width: 100%; top: 0px; left: 0px; margin: 0px;"> <tr style="padding: 0px; margin: 0px;text-align: center; width: 100%;"> <td style="padding: 0px; margin: 0px;text-align: center; width: 100%;" align="center" valign="top"> <center> <table style="height: 100px;padding: 0px;width: 100%;position: relative;background: #007799;" class="row header"> <tr> <td style="text-align: center;" align="center"> <center> <table style="margin: 0 auto;text-align: inherit;width: 95% !important;" class="container"> <tr> <td style="padding: 10px 20px 0px 0px;position: relative;display: block !important;padding-right: 0 !important;" class="wrapper last"> <table style="width: 95%;" class="twelve columns"> <tr> <td style="padding: 8px;" class="six sub-columns"> <a target="_blank" href="https://'.$_SERVER['SERVER_NAME'].'/"><img alt="logo" src="https://'.$_SERVER['SERVER_NAME'].'/app-icons/icon-60.png"> </a> </td><td class="six sub-columns last" style="text-align:left; vertical-align:middle;padding-right: 0px; color: white; width: 90%"> <span class="template-label"><a style="font-size: 24px;color: white; text-decoration: none;" target="_blank" href="https://'.$_SERVER['SERVER_NAME'].'/">'.$heading.'</a></span> </td><td class="expander"></td></tr></table> </td></tr></table> </center> </td></tr></table> <table class="container"> <tr> <td> <table class="row"> <tr> <td style="padding: 10px 10px 0px 0px;position: relative;display: block !important;padding-right: 0 !important;" class="wrapper last"> <table style="width: 80%;font-size:16px;margin: auto;" class="twelve columns"> <tr> <td> '.$content.' </td><td class="expander"></td></tr></table> </td></tr></table> </td></tr></table> </center> </td></tr></table></body></html>';
	return array(
		'html' => $message,
		'plain' => strip_tags($content)
	);
}
function email_create_member(string $email,string $fullname,string $password,string $dob): void {
	global $lang_code, $email_interfaces, $input_interfaces, $span_interfaces, $clickbank;
	$my_email = 'tung.42@gmail.com';
	$hidden_password = str_repeat('*',strlen($password)-3).substr($password,-3);
	$heading = site_name();
	$proverb = generate_proverb($lang_code);
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/';
	$member = load_member_from_email($email);
//	$feed_email = rss_feed_email('https://'.$_SERVER['SERVER_NAME'].'/blog/feed/?cat=3%2C81',$span_interfaces['latest_posts']['vi'],'feed_blog');
	$content = "";
	$content .= '<h1>'.$email_interfaces['hi'][$lang_code].' '.$fullname.' (<a style="text-decoration: none; font-size: 25px; color: green;" href="'.get_wiki_url_nsh($fullname).'">WIKI</a>)</h1>';
//	$content .= '<a href="https://bitminer.io/2537977" target="_blank"><img src="https://bitminer.io/s/bitminer_4.gif" alt="BitMiner - free and simple next generation Bitcoin mining software" /></a>';
	$content .= '<p class="lead">'.$email_interfaces['create_user_thank'][$lang_code].'</p>';
	$content .= '<p>'.$email_interfaces['create_user_detail'][$lang_code].'</p>';
	$content .= '<ul>';
	$content .= '<li>'.$input_interfaces['email'][$lang_code].': '.$email.'</li>';
	$content .= '<li>'.$input_interfaces['fullname'][$lang_code].': '.$fullname.'</li>';
	$content .= '<li>'.$input_interfaces['dob'][$lang_code].': '.$dob.'</li>';
	$content .= '<li>'.$input_interfaces['password'][$lang_code].': '.$hidden_password.'</li>';
	$content .= '</ul>';
	$content .= '<p><a href="https://'.$_SERVER['SERVER_NAME'].'/member/'.$email.'/">'.$email_interfaces['go_to_your_profile'][$lang_code].'</a></p>';
	$content .= '<p><a href="https://www.youtube.com/watch?v='.$email_interfaces['instruction_video_youtube_id'][$lang_code].'">'.$email_interfaces['instruction_video_text'][$lang_code].'</a></p>';
	$content .= '<p>'.$email_interfaces['regards'][$lang_code].'</p>';
	$content .= '<p>'.$span_interfaces['pham_tung'][$lang_code].'</p>';
	if ($lang_code == 'vi') {
//		$content .= $feed_email;
		$content .= '<a target="_blank" href="https://docs.google.com/forms/d/1iMLcQNKnDoHyqMaS-uQo9ocvZawhc2JUPUtjcz1WR4E/viewform">Link Góp ý</a>';
	}
	$content .= '<h4><a href="https://'.$_SERVER['SERVER_NAME'].'/donate/?lang='.$lang_code.'">'.$span_interfaces['donate'][$lang_code].'</a> '.$span_interfaces['donate_reason'][$lang_code].'</h4>';
	$content .= '<p><em>"'.$proverb['content'].'"</em></p><em><a href="'.get_wiki_url_nsh($proverb['author']).'">'.$proverb['author'].'</a></em>';
	$content .= '<p><em>'.$email_interfaces['definition'][$lang_code].'</em></p>';
	$content .= '<p>'.$span_interfaces['for_reference_only'][$lang_code].'</p>';
	$content .= '<p>'.$email_interfaces['keyboard_shortcuts'][$lang_code].'</p>';
	$content .= '<p>'.$email_interfaces['not_mark_as_spam'][$lang_code].'</p>';
//	$content .= '<p><a href="mailto:admin@nhipsinhhoc.vn?subject='.$email_interfaces['unsubscribe'][$lang_code].'&body='.$email_interfaces['unsubscribe'][$lang_code].' '.$email.'&cc=tung.42@gmail.com">'.$email_interfaces['unsubscribe'][$lang_code].'</a></p>';
//	$content .= '<form method="POST" action="https://nhipsinhhoc.vn/unsubscribe/"><input type="hidden" name="email" value="'.$email.'" /><input type="submit" name="unsubscribe_submit" value="'.$email_interfaces['unsubscribe'][$lang_code].'" /></form>';
	$content .= '<a href="https://'.$_SERVER['SERVER_NAME'].'/unsubscribe/?email='.$email.'&token='.hash_token($email).'">'.$email_interfaces['unsubscribe'][$lang_code].'</a>';
	$message = email_message($heading, $content);
	send_mail($email,$email_interfaces['hi'][$lang_code].' '.$fullname.', '.$email_interfaces['create_user_thank'][$lang_code],$message);
	send_mail($my_email,$email_interfaces['hi'][$lang_code].' '.$fullname.', '.$email_interfaces['create_user_thank'][$lang_code],$message);
}
function email_edit_member($email,$fullname,$password,$dob): void {
	global $lang_code, $email_interfaces, $input_interfaces, $span_interfaces, $clickbank;
	$my_email = 'tung.42@gmail.com';
	$hidden_password = str_repeat('*',strlen($password)-3).substr($password,-3);
	$heading = site_name();
	$proverb = generate_proverb($lang_code);
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/';
	$member = load_member_from_email($email);
//	$feed_email = rss_feed_email('https://'.$_SERVER['SERVER_NAME'].'/blog/feed/?cat=3%2C81',$span_interfaces['latest_posts']['vi'],'feed_blog');
	$content = "";
	$content .= '<h1>'.$email_interfaces['hi'][$lang_code].' '.$fullname.' (<a style="text-decoration: none; font-size: 25px; color: green;" href="'.get_wiki_url_nsh($fullname).'">WIKI</a>)</h1>';
//	$content .= '<a href="https://bitminer.io/2537977" target="_blank"><img src="https://bitminer.io/s/bitminer_4.gif" alt="BitMiner - free and simple next generation Bitcoin mining software" /></a>';
	$content .= '<p class="lead">'.$email_interfaces['edit_user_notify'][$lang_code].'</p>';
	$content .= '<p>'.$email_interfaces['edit_user_detail'][$lang_code].'</p>';
	$content .= '<ul>';
	$content .= '<li>'.$input_interfaces['email'][$lang_code].': '.$email.'</li>';
	$content .= '<li>'.$input_interfaces['fullname'][$lang_code].': '.$fullname.'</li>';
	$content .= '<li>'.$input_interfaces['dob'][$lang_code].': '.$dob.'</li>';
	$content .= '<li>'.$input_interfaces['password'][$lang_code].': '.($password == $email_interfaces['not_changed'][$lang_code] ? $email_interfaces['not_changed'][$lang_code] : $hidden_password).'</li>';
	$content .= '</ul>';
	$content .= '<p><a href="https://'.$_SERVER['SERVER_NAME'].'/member/'.$email.'/">'.$email_interfaces['go_to_your_profile'][$lang_code].'</a></p>';
	$content .= '<p><a href="https://www.youtube.com/watch?v='.$email_interfaces['instruction_video_youtube_id'][$lang_code].'">'.$email_interfaces['instruction_video_text'][$lang_code].'</a></p>';
	$content .= '<p>'.$email_interfaces['regards'][$lang_code].'</p>';
	$content .= '<p>'.$span_interfaces['pham_tung'][$lang_code].'</p>';
	if ($lang_code == 'vi') {
//		$content .= $feed_email;
		$content .= '<a target="_blank" href="https://docs.google.com/forms/d/1iMLcQNKnDoHyqMaS-uQo9ocvZawhc2JUPUtjcz1WR4E/viewform">Link Góp ý</a>';
	}
	$content .= '<h4><a href="https://'.$_SERVER['SERVER_NAME'].'/donate/?lang='.$lang_code.'">'.$span_interfaces['donate'][$lang_code].'</a> '.$span_interfaces['donate_reason'][$lang_code].'</h4>';
	$content .= '<p><em>"'.$proverb['content'].'"</em></p><em><a href="'.get_wiki_url_nsh($proverb['author']).'">'.$proverb['author'].'</a></em>';
	$content .= '<p><em>'.$email_interfaces['definition'][$lang_code].'</em></p>';
	$content .= '<p>'.$span_interfaces['for_reference_only'][$lang_code].'</p>';
	$content .= '<p>'.$email_interfaces['keyboard_shortcuts'][$lang_code].'</p>';
	$content .= '<p>'.$email_interfaces['not_mark_as_spam'][$lang_code].'</p>';
//	if ($email == $my_email) {
//		$member_chart = new Chart($dob,0,0,date('Y-m-d'),$dob,$lang_code);
//		$content .= '<p>'.$member_chart->get_infor_values().'</p>';
//	}
//	$content .= '<p><a href="mailto:admin@nhipsinhhoc.vn?subject='.$email_interfaces['unsubscribe'][$lang_code].'&body='.$email_interfaces['unsubscribe'][$lang_code].' '.$email.'&cc=tung.42@gmail.com">'.$email_interfaces['unsubscribe'][$lang_code].'</a></p>';	
//	$content .= '<form method="POST" action="https://nhipsinhhoc.vn/unsubscribe/"><input type="hidden" name="email" value="'.$email.'" /><input type="submit" name="unsubscribe_submit" value="'.$email_interfaces['unsubscribe'][$lang_code].'" /></form>';
	$content .= '<a href="https://'.$_SERVER['SERVER_NAME'].'/unsubscribe/?email='.$email.'&token='.hash_token($email).'">'.$email_interfaces['unsubscribe'][$lang_code].'</a>';
	$message = email_message($heading, $content);
	send_mail($email,$email_interfaces['hi'][$lang_code].' '.$fullname.', '.$email_interfaces['edit_user_notify'][$lang_code],$message);
	send_mail($my_email,$email_interfaces['hi'][$lang_code].' '.$fullname.', '.$email_interfaces['edit_user_notify'][$lang_code],$message);
}
function email_contact($email,$fullname,$body): void {
	global $lang_code, $email_interfaces, $input_interfaces, $span_interfaces;
	$my_email = 'tung.42@gmail.com';
	$heading = site_name();
	$content = "";
	$content .= '<h1>Email phản hồi từ Form Contact</h1>';
	$content .= '<p>Email người gửi: '.$email.'</p>';
	$content .= '<p>Họ tên người gửi: '.$fullname.'</p>';
	$content .= '<p>Nội dung gửi: '.$body.'</p>';
	$message = email_message($heading, $content);
	send_mail($my_email,'Email phản hồi từ Form Contact',$message);
}
function do_unsubscribe($email): void {
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member//unsubscribers_list.csv';
	if (!$handle = fopen($path, 'a+')) {
		echo 'Cannot open index file ('.$path.')';
		exit;
	}
	if (fwrite($handle, PHP_EOL.$email) === false) {
		echo 'Cannot write to index file ('.$path.')';
		exit;
	} else {
		echo translate_span('has_been_unsubscribed','class: success');
	}
	fclose($handle);

	$my_email = 'tung.42@gmail.com';
	$heading = site_name();
	$content = "";
	$content .= '<h1>Email hủy đăng ký</h1>';
	$content .= '<p>Email người hủy đăng ký: '.$email.'</p>';
	$message = email_message($heading, $content);
	send_mail($my_email,'Email hủy đăng ký: "'.$email.'"',$message);
}
function do_resubscribe($email): void {
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member//unsubscribers_list.csv';
	$lines = file($path, FILE_IGNORE_NEW_LINES);
	$remove = $email;
	foreach ($lines as $key => $line) {
		if (stristr($line, $remove)) {
			unset($lines[$key]);
		}
	}
	$data = implode(PHP_EOL, array_values($lines));
	$file = fopen($path, 'w');
	fwrite($file, $data);
	fclose($file);

	$my_email = 'tung.42@gmail.com';
	$heading = site_name();
	$content = "";
	$content .= '<h1>Email đăng ký lại</h1>';
	$content .= '<p>Email người đăng ký lại: '.$email.'</p>';
	$message = email_message($heading, $content);
	send_mail($my_email,'Email đăng ký lại: "'.$email.'"',$message);
}
function email_forgot_password($email): void {
	global $lang_code, $email_interfaces, $input_interfaces, $span_interfaces;
	$member = load_member_from_email($email);
	$fullname = $member['fullname'];
	$my_email = 'tung.42@gmail.com';
	if (taken_email($email)) {
		$heading = site_name();
		$content = "";
		$content .= '<h1>'.$email_interfaces['hi'][$lang_code].' '.$fullname.' (<a style="text-decoration: none; font-size: 25px; color: green;" href="'.get_wiki_url_nsh($fullname).'">WIKI</a>)</h1>';
		$content .= '<p>'.$email_interfaces['reset_password_notify'][$lang_code].'</p>';
//		$content .= '<form method="POST" action="https://'.$_SERVER['SERVER_NAME'].'/reset_password/"><input type="hidden" name="forgot_password_email" value="'.$email.'" /><input type="submit" name="forgot_password_submit" value="'.$email_interfaces['reset_password'][$lang_code].'" /></form>';
		$content .= '<a href="https://'.$_SERVER['SERVER_NAME'].'/reset_password/?forgot_password_email='.$email.'&token='.hash_token($email).'">'.$email_interfaces['reset_password'][$lang_code].'</a>';
		$message = email_message($heading, $content);
		send_mail($email,$email_interfaces['reset_password'][$lang_code],$message);
		send_mail($my_email,$email_interfaces['reset_password'][$lang_code],$message);
	}
}
function email_forgot_password_alert($email): void {
	global $lang_code, $email_interfaces, $input_interfaces, $span_interfaces;
	$my_email = 'tung.42@gmail.com';
	if (!taken_email($email)) {
		$heading = site_name();
		$content = "";
		$content .= '<h1>'.$email_interfaces['hi'][$lang_code].'</h1>';
		$content .= '<p>'.$email_interfaces['forgot_password_alert'][$lang_code].'</p>';
//		$content .= '<form method="POST" action="https://'.$_SERVER['SERVER_NAME'].'/reset_password/"><input type="hidden" name="forgot_password_email" value="'.$email.'" /><input type="submit" name="forgot_password_submit" value="'.$email_interfaces['reset_password'][$lang_code].'" /></form>';
		$content .= '<a href="https://'.$_SERVER['SERVER_NAME'].'/member/register/">'.$email_interfaces['register'][$lang_code].'</a>';
		$message = email_message($heading, $content);
		send_mail($email,$email_interfaces['forgot_password_alert_title'][$lang_code],$message);
		send_mail($my_email,$email_interfaces['forgot_password_alert_title'][$lang_code],$message);
	}
}
function email_daily_suggestion(): void {
	global $email_interfaces, $span_interfaces;
	//$my_email = 'nhipsinhhoc@mail-tester.com';
	$my_email = 'tung.42@gmail.com';
	$unsubscriber_emails = array();
	$all_emails = array();
	$emails = array();
	$members = array();
	$unsubscribers = new parseCSV();
	$unsubscribers->parse(realpath($_SERVER['DOCUMENT_ROOT']).'/member/unsubscribers_list.csv');
	$unsubscribers_count = count($unsubscribers->data);
	for ($i = 0; $i < $unsubscribers_count; ++$i) {
		$unsubscriber_emails[$i] = $unsubscribers->data[$i]['email'];
	}
	sort($unsubscriber_emails);
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/';
	$directories = glob($path.'*', GLOB_ONLYDIR|GLOB_NOSORT);
	if (!count($directories)) {
		echo 'No matches';
	} else {
		$n = 0;
		foreach ($directories as $directory) {
			//if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register' && is_dir($path.str_replace($path, "", $directory))) {
			if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register') {
				$all_emails[] = str_replace($path, "", $directory);
			}
		}
	}
	sort($all_emails);
	$emails = array_diff($all_emails, $unsubscriber_emails);
	$count = count($emails);
	//$count = 420; // test only
	sort($emails);
	for ($m = 0; $m < $count; ++$m) {
		$members[$m] = load_member_from_email($emails[$m]);
	}
	usort($members,'sort_date_member_ascend');
//	$feed_email = rss_feed_email('https://'.$_SERVER['SERVER_NAME'].'/blog/feed/?cat=3%2C81',$span_interfaces['latest_posts']['vi'],'feed_blog');
	for ($i = 0; $i < $count; ++$i) {
		$member_chart = new Chart($members[$i]['dob'],0,0,date('Y-m-d'),$members[$i]['dob'],$members[$i]['lang']);
		$heading = "";
		switch ($members[$i]['lang']) {
			case 'vi': $heading = 'Biểu đồ nhịp sinh học | Bieu do nhip sinh hoc'; break;
			case 'en': $heading = 'Biorhythm chart'; break;
			case 'ru': $heading = 'Биоритм диаграммы'; break;
			case 'es': $heading = 'Biorritmo carta'; break;
			case 'zh': $heading = '生理节律图'; break;
			case 'ja': $heading = 'バイオリズムチャート'; break;
		}
		$proverb = generate_proverb($members[$i]['lang']);
		$content = "";
		$content .= (has_birthday($members[$i]['dob'], time())) ? '<style>body {background-image: url("https://'.$_SERVER['SERVER_NAME'].'/css/images/gifts_mobile.png") !important;}</style>' : "";
		$content .= '<h1>'.((has_birthday($members[$i]['dob'], time())) ? $email_interfaces['happy_birthday'][$members[$i]['lang']] : $email_interfaces['hi'][$members[$i]['lang']]).' '.$members[$i]['fullname'].'</h1>';
		$content .= '<p class="lead">'.$email_interfaces['daily_suggestion'][$members[$i]['lang']].$email_interfaces['colon'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$member_chart->get_infor().'</p>';
		$content .= '<p>'.$member_chart->get_birthday_countdown().'</p>';
		$content .= '<p class="lead">'.$email_interfaces['daily_values'][$members[$i]['lang']].$email_interfaces['colon'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$member_chart->get_infor_values().'</p>';
		$content .= '<p><a href="https://'.$_SERVER['SERVER_NAME'].'/member/'.$members[$i]['email'].'/">'.$email_interfaces['go_to_your_profile'][$members[$i]['lang']].'</a></p>';
		$content .= '<p><a href="https://www.youtube.com/watch?v='.$email_interfaces['instruction_video_youtube_id'][$members[$i]['lang']].'">'.$email_interfaces['instruction_video_text'][$members[$i]['lang']].'</a></p>';
		$content .= '<p>'.$email_interfaces['regards'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$span_interfaces['pham_tung'][$members[$i]['lang']].'</p>';
		if ($members[$i]['lang'] == 'vi') {
//			$content .= $feed_email;
			$content .= '<a target="_blank" href="https://docs.google.com/forms/d/1iMLcQNKnDoHyqMaS-uQo9ocvZawhc2JUPUtjcz1WR4E/viewform">Link Góp ý</a>';
		}
		$content .= '<h4><a href="https://'.$_SERVER['SERVER_NAME'].'/donate/?lang='.$members[$i]['lang'].'">'.$span_interfaces['donate'][$members[$i]['lang']].'</a> '.$span_interfaces['donate_reason'][$members[$i]['lang']].'</h4>';
		$content .= '<p><em>"'.$proverb['content'].'"</em></p><em>'.$proverb['author'].'</em>';
		$content .= '<p><em>'.$email_interfaces['definition'][$members[$i]['lang']].'</em></p>';
		$content .= '<p>'.$span_interfaces['for_reference_only'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$email_interfaces['keyboard_shortcuts'][$members[$i]['lang']].'</p>';
		$content .= '<p>'.$email_interfaces['not_mark_as_spam'][$members[$i]['lang']].'</p>';
		$content .= '<p><a href="mailto:admin@nhipsinhhoc.vn?subject='.$email_interfaces['unsubscribe'][$members[$i]['lang']].'&body='.$email_interfaces['unsubscribe'][$members[$i]['lang']].' '.$members[$i]['email'].'&cc=tung.42@gmail.com">'.$email_interfaces['unsubscribe'][$members[$i]['lang']].'</a></p>';
		$message = email_message($heading, $content);
		//send_mail($my_email,$email_interfaces['hi'][$members[$i]['lang']].' '.$members[$i]['fullname'].', '.$email_interfaces['daily_suggestion'][$members[$i]['lang']].' | '.date('Y-m-d'),$message); // test only
		send_mail($members[$i]['email'],$email_interfaces['hi'][$members[$i]['lang']].' '.$members[$i]['fullname'].', '.$email_interfaces['daily_suggestion'][$members[$i]['lang']].' | '.date('Y-m-d'),$message);
		//sleep(2);
	}
}
function test_email_daily_suggestion(): void {
	global $email_interfaces, $span_interfaces;
	//$my_email = 'nhipsinhhoc@mail-tester.com';
	$my_email = 'tung.42@gmail.com';
	$unsubscriber_emails = array();
	$all_emails = array();
	$emails = array();
	$members = array();
	$unsubscribers = new parseCSV();
	$unsubscribers->parse(realpath($_SERVER['DOCUMENT_ROOT']).'/member/unsubscribers_list.csv');
	$unsubscribers_count = count($unsubscribers->data);
	for ($i = 0; $i < $unsubscribers_count; ++$i) {
		$unsubscriber_emails[$i] = $unsubscribers->data[$i]['email'];
	}
	sort($unsubscriber_emails);
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/';
	$directories = glob($path.'*', GLOB_ONLYDIR|GLOB_NOSORT);
	if (!count($directories)) {
		echo 'No matches';
	} else {
		$n = 0;
		foreach ($directories as $directory) {
			//if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register' && is_dir($path.str_replace($path, "", $directory))) {
			if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register') {
				$all_emails[] = str_replace($path, "", $directory);
			}
		}
	}
	sort($all_emails);
	$emails = array_diff($all_emails, $unsubscriber_emails);
	$count = count($emails);
	sort($emails);
	for ($m = 0; $m < $count; ++$m) {
		$members[$m] = load_member_from_email($emails[$m]);
	}
	usort($members,'sort_date_member_ascend');
	echo '<pre>';
	print_r($unsubscriber_emails);
	print_r($all_emails);
	print_r($emails);
	print_r($members);
	echo '</pre>';
}
function list_emails() {
	global $email_interfaces, $span_interfaces;
//	$my_email = 'nhipsinhhoc@mail-tester.com';
//	$my_email = 'tung.42@gmail.com';
//	$unsubscriber_emails = array();
//	$all_emails = array();
	$emails = array();
	$members = array();
//	$unsubscribers = new parseCSV();
//	$unsubscribers->parse(realpath($_SERVER['DOCUMENT_ROOT']).'/member/unsubscribers_list.csv');
//	$unsubscribers_count = count($unsubscribers->data);
//	for ($i = 0; $i < $unsubscribers_count; ++$i) {
//		$unsubscriber_emails[$i] = $unsubscribers->data[$i]['email'];
//	}
//	sort($unsubscriber_emails);
	$path = realpath($_SERVER['DOCUMENT_ROOT']).'/member/';
	$directories = glob($path.'*', GLOB_ONLYDIR|GLOB_NOSORT);
	if (!count($directories)) {
		echo 'No matches';
	} else {
		$n = 0;
		foreach ($directories as $directory) {
			//if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register' && is_dir($path.str_replace($path, "", $directory))) {
			if (str_replace($path, "", $directory) != 'login' && str_replace($path, "", $directory) != 'register') {
				$emails[] = str_replace($path, "", $directory);
			}
		}
	}
	sort($emails);
//	$emails = array_diff($all_emails, $unsubscriber_emails);
	$count = count($emails);
	for ($m = 0; $m < $count; ++$m) {
		$members[$m] = load_member_from_email($emails[$m]);
	}
	usort($members,'sort_date_member_ascend');
	echo '<pre>';
//	print_r($unsubscriber_emails);
//	print_r($all_emails);
	print_r($emails);
	print_r($members);
	echo '</pre>';
}