<?php
class Chart {
	protected $_dob, $_diff, $_date, $_is_secondary, $_dt_change, $_lang_code, $_partner_dob, $_rhythms_count,  $_dates_count, $_today_index, $_title_text, $_explanation_title_text, $_percentage_text, $_average_text, $_full_screen_text, $_download_jpeg_text, $_download_pdf_text, $_download_png_text, $_download_svg_text, $_print_chart_text, $_reset_zoom_text, $_date_text, $_life_path_text, $_age, $_news_h5, $_info_h5, $_statistics_h5, $_compatibility_h5, $_lunar_h5, $_controls_h5, $_dates_json, $_rhythms_json, $_fullname, $_intro_info, $_intro_chart, $_chart_today, $_chart_birthday, $_chart_prev, $_chart_next;
	protected $_rhythms = array();
	protected $_days = array();
	protected $_dates = array();
	protected $_is_registered = false;

	function __construct(string $dob,int $diff,int $is_secondary,string $dt_change,string $partner_dob,string $lang_code) {
		global $lang_codes;
		$this->_dob = $dob;
		$this->_partner_dob = isset($partner_dob) ? $partner_dob: $dob;
		$this->_diff = $diff;
		$this->_date = date('Y-m-d', time()+86400*$this->_diff);
//		$this->_lang_code = in_array($lang_code, $lang_codes) ? $lang_code: 'vi';
		$this->_lang_code = $lang_code;
		$this->_is_secondary = $is_secondary;
		$this->_dt_change = isset($dt_change) ? $dt_change: $this->_date;
		$this->_rhythms = array(
			'0' => array(
						'rid' => '1',
						'name_vi' => 'Sức khỏe',
						'name_en' => 'Physical',
						'scale' => '23'
					),
			'1' => array(
						'rid' => '2',
						'name_vi' => 'Tình cảm',
						'name_en' => 'Emotional',
						'scale' => '28'
					),
			'2' => array(
						'rid' => '3',
						'name_vi' => 'Trí tuệ',
						'name_en' => 'Intellectual',
						'scale' => '33'
					)
		);
		sort($this->_rhythms);
		for ($i = -14; $i <= 14; ++$i) {
			$j = $i + 14;
			$day = time()+86400*($i+$diff);
			$this->_days[$j] = date('d',$day);
			$this->_dates[$j] = date('Y-m-d',$day);
		}
		$this->_rhythms_count = count($this->_rhythms);
		$this->_dates_count = count($this->_dates);
		$this->_today_index = array_search(date('Y-m-d'),$this->_dates);
		$this->_dates_json = json_encode($this->_dates);
		$this->_rhythms_json = json_encode($this->_rhythms);
		switch ($this->_lang_code) {
			case 'vi':
				setlocale(LC_TIME, 'vi_VN');
				$this->_chart_today = 'Đi đến ngày hôm nay';
				$this->_chart_birthday = 'Đi đến ngày sinh nhật';
				$this->_chart_prev = 'Đi đến ngày trước đó';
				$this->_chart_next = 'Đi đến ngày kế tiếp';
				$this->_title_text = 'Ngày sinh: ';
				$this->_intro_chart = 'Biểu đồ này được tạo ra từ Ngày sinh bạn đã nhập. Khám phá từng ngày bằng cách bấm vào ngày muốn xem.';
				$this->_intro_info = 'Ngày này bạn như thế nào? Tìm hiểu nào';
				$this->_explanation_title_text = 'Biểu đồ nhịp sinh học';
				$this->_percentage_text = 'Phần trăm';
				$this->_average_text = 'Trung bình';
				$this->_full_screen_text = 'Xem toàn màn hình';
				$this->_download_jpeg_text = 'Tải về tập tin JPEG';
				$this->_download_pdf_text = 'Tải về tập tin PDF';
				$this->_download_png_text = 'Tải về tập tin PNG';
				$this->_download_svg_text = 'Tải về tập tin SVG';
				$this->_print_chart_text = 'In biểu đồ';
				$this->_reset_zoom_text = 'Thiết lập lại';
				$this->_date_text = 'Ngày';
				$this->_life_path_text = 'con số cuộc đời '.calculate_life_path($this->_dob);
				$this->_age = 'tuổi';
				$this->_news_h5 = 'Tin tức';
				$this->_info_h5 = 'Lời khuyên cho '.(($this->_diff == 0) ? 'hôm nay': (($this->_diff == 1) ? 'ngày mai': (($this->_diff == -1) ? 'hôm qua': strftime('%A, %e %B, %Y', strtotime($this->_dt_change)))));
				$this->_statistics_h5 = 'Thống kê';
				$this->_compatibility_h5 = 'Độ hòa hợp với đối tác';
				$this->_lunar_h5 = 'Âm lịch';
				if (date('m-d',time()+86400*$this->_diff) == date('m-d',strtotime($this->_dob))) {
					$this->_controls_h5 = 'Sinh nhật';
				} else if ($this->_diff == 0) {
					$this->_controls_h5 = 'Hôm nay';
				} else if ($this->_diff == 1) {
					$this->_controls_h5 = 'Ngày mai';
				} else if ($this->_diff == -1) {
					$this->_controls_h5 = 'Hôm qua';
				} else {
					$this->_controls_h5 = 'Ngày: '.$this->_date;
				}
				break;
			case 'en':
				$this->_chart_today = 'Go to today';
				$this->_chart_birthday = 'Go to the next birthday';
				$this->_chart_prev = 'Go to the previous day';
				$this->_chart_next = 'Go to the next day';
				$this->_title_text = 'Date of birth: ';
				$this->_intro_chart = 'This chart is created based on your Date of Birth. Discover the days by clicking on the day you want to see.';
				$this->_intro_info = 'How are you on this day? Find out here';
				$this->_explanation_title_text = 'Biorhythm chart';
				$this->_percentage_text = 'Percentage';
				$this->_average_text = 'Average';
				$this->_full_screen_text = 'View in full screen';
				$this->_download_jpeg_text = 'Download JPEG file';
				$this->_download_pdf_text = 'Download PDF file';
				$this->_download_png_text = 'Download PNG file';
				$this->_download_svg_text = 'Download SVG file';
				$this->_print_chart_text = 'Print chart';
				$this->_reset_zoom_text = 'Reset zoom';
				$this->_date_text = 'Date';
				$this->_life_path_text = 'life path number '.calculate_life_path($this->_dob);
				$this->_age = pluralize(differ_year($this->_dob, $this->_date),'year').' old';
				$this->_news_h5 = 'News';
				$this->_info_h5 = 'Advice for '.(($this->_diff == 0) ? 'today': (($this->_diff == 1) ? 'tomorrow': (($this->_diff == -1) ? 'yesterday': strftime('%A, %B %e, %Y', strtotime($this->_dt_change)))));
				$this->_statistics_h5 = 'Statistics';
				$this->_compatibility_h5 = 'Compatibility';
				$this->_lunar_h5 = 'Lunar';
				if (date('m-d',time()+86400*$this->_diff) == date('m-d',strtotime($this->_dob))) {
					$this->_controls_h5 = 'Birthday';
				} else if ($this->_diff == 0) {
					$this->_controls_h5 = 'Today';
				} else if ($this->_diff == 1) {
					$this->_controls_h5 = 'Tomorrow';
				} else if ($this->_diff == -1) {
					$this->_controls_h5 = 'Yesterday';
				} else {
					$this->_controls_h5 = 'Date: '.$this->_date;
				}
				break;
		}
	}
	function get_rhythm_name(array $rhythm): string {
		switch ($this->_lang_code) {
			case 'vi':
				return $rhythm['name_vi'];
				break;
			case 'en':
				return $rhythm['name_en'];
				break;
		}
	}
	function set_fullname(string $fullname): void {
		$this->_fullname = $fullname;
	}
	function set_registered(bool $is_registered): void {
		$this->_is_registered = $is_registered;
	}
	function serialize_chart_data(): string {
		$chart_data = '[{name:"'.$this->_average_text.'",data:[';
		for ($d = 0; $d < $this->_dates_count; ++$d) {
			$chart_data .= average_bio_count($this->_dob,$this->_dates[$d],$this->_rhythms);
			$chart_data .= ($d != ($this->_dates_count-1)) ? ',': "";
		}
		$chart_data .= '],lineWidth: 2},';
		for ($r = 0; $r < $this->_rhythms_count; ++$r) {
			$this->_rhythms[$r]['rhythm_name'] = $this->get_rhythm_name($this->_rhythms[$r]);
			$chart_data .= '{name:"'.$this->_rhythms[$r]['rhythm_name'].'",data:[';
			for ($d = 0; $d < $this->_dates_count; ++$d) {
				$chart_data .= bio_count($this->_dob,$this->_dates[$d],$this->_rhythms[$r]['scale']);
				$chart_data .= ($d != ($this->_dates_count-1)) ? ',': "";
			}
			$chart_data .= ']';
			$chart_data .= ($r != ($this->_rhythms_count-1)) ? '},': '}';
		}
		$chart_data .= ']';
		return $chart_data;
	}
	function get_infor(): string {
		global $information_interfaces;
		$average = (float)average_bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),$this->_rhythms);
		$physical = (float)bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),23);
		$emotional = (float)bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),28);
		$intellectual = (float)bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),33);
		$average_text = "";
		$physical_text = "";
		$emotional_text = "";
		$intellectual_text = "";
		if ($average >= 75 && $average <= 100) {
			$average_text = $information_interfaces['average'][$this->_lang_code]['excellent'];
		} else if ($average >= 50 && $average < 75) {
			$average_text = $information_interfaces['average'][$this->_lang_code]['good'];
		} else if ($average >= 25 && $average < 50) {
			$average_text = $information_interfaces['average'][$this->_lang_code]['gray'];
		} else if ($average >= 0 && $average < 25) {
			$average_text = $information_interfaces['average'][$this->_lang_code]['bad'];
		}
		if ($physical >= 80 && $physical <= 100) {
			$physical_text = $information_interfaces['physical'][$this->_lang_code]['excellent'];
		} else if ($physical >= 60 && $physical < 80) {
			$physical_text = $information_interfaces['physical'][$this->_lang_code]['good'];
		} else if ($physical >= 40 && $physical < 60) {
			$physical_text = $information_interfaces['physical'][$this->_lang_code]['critical'];
		} else if ($physical >= 20 && $physical < 40) {
			$physical_text = $information_interfaces['physical'][$this->_lang_code]['gray'];
		} else if ($physical >= 0 && $physical < 20) {
			$physical_text = $information_interfaces['physical'][$this->_lang_code]['bad'];
		}
		if ($emotional >= 80 && $emotional <= 100) {
			$emotional_text = $information_interfaces['emotional'][$this->_lang_code]['excellent'];
		} else if ($emotional >= 60 && $emotional < 80) {
			$emotional_text = $information_interfaces['emotional'][$this->_lang_code]['good'];
		} else if ($emotional >= 40 && $emotional < 60) {
			$emotional_text = $information_interfaces['emotional'][$this->_lang_code]['critical'];
		} else if ($emotional >= 20 && $emotional < 40) {
			$emotional_text = $information_interfaces['emotional'][$this->_lang_code]['gray'];
		} else if ($emotional >= 0 && $emotional < 20) {
			$emotional_text = $information_interfaces['emotional'][$this->_lang_code]['bad'];
		}
		if ($intellectual >= 80 && $intellectual <= 100) {
			$intellectual_text = $information_interfaces['intellectual'][$this->_lang_code]['excellent'];
		} else if ($intellectual >= 60 && $intellectual < 80) {
			$intellectual_text = $information_interfaces['intellectual'][$this->_lang_code]['good'];
		} else if ($intellectual >= 40 && $intellectual < 60) {
			$intellectual_text = $information_interfaces['intellectual'][$this->_lang_code]['critical'];
		} else if ($intellectual >= 20 && $intellectual < 40) {
			$intellectual_text = $information_interfaces['intellectual'][$this->_lang_code]['gray'];
		} else if ($intellectual >= 0 && $intellectual < 20) {
			$intellectual_text = $information_interfaces['intellectual'][$this->_lang_code]['bad'];
		}
		return '<p>'.$average_text.'</p><p>'.$physical_text.'</p><p>'.$emotional_text.'</p><p>'.$intellectual_text.'</p>';
	}
	function get_infor_details(): string {
		global $information_interfaces;
		$average = (float)average_bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),$this->_rhythms);
		$physical = (float)bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),23);
		$emotional = (float)bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),28);
		$intellectual = (float)bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),33);
		$average_text = "";
		$physical_text = "";
		$emotional_text = "";
		$intellectual_text = "";
		$plus = '<i class="icon-circle-plus"></i> ';
		$minus = '<i class="icon-circle-minus"></i> ';
		$mark = '<i class="icon-circle-exclamation-mark"></i> ';
		$hand_right = ' <i class="icon-hand-right"></i>  ';
		if ($average >= 75 && $average <= 100) {
			$average_text = $plus.$plus.$hand_right.$information_interfaces['average'][$this->_lang_code]['excellent'];
		} else if ($average >= 50 && $average < 75) {
			$average_text = $plus.$hand_right.$information_interfaces['average'][$this->_lang_code]['good'];
		} else if ($average >= 25 && $average < 50) {
			$average_text = $minus.$hand_right.$information_interfaces['average'][$this->_lang_code]['gray'];
		} else if ($average >= 0 && $average < 25) {
			$average_text = $minus.$minus.$hand_right.$information_interfaces['average'][$this->_lang_code]['bad'];
		}
		if ($physical >= 80 && $physical <= 100) {
			$physical_text = $plus.$plus.$hand_right.$information_interfaces['physical'][$this->_lang_code]['excellent'];
		} else if ($physical >= 60 && $physical < 80) {
			$physical_text = $plus.$hand_right.$information_interfaces['physical'][$this->_lang_code]['good'];
		} else if ($physical >= 40 && $physical < 60) {
			$physical_text = $mark.$hand_right.$information_interfaces['physical'][$this->_lang_code]['critical'];
		} else if ($physical >= 20 && $physical < 40) {
			$physical_text = $minus.$hand_right.$information_interfaces['physical'][$this->_lang_code]['gray'];
		} else if ($physical >= 0 && $physical < 20) {
			$physical_text = $minus.$minus.$hand_right.$information_interfaces['physical'][$this->_lang_code]['bad'];
		}
		if ($emotional >= 80 && $emotional <= 100) {
			$emotional_text = $plus.$plus.$hand_right.$information_interfaces['emotional'][$this->_lang_code]['excellent'];
		} else if ($emotional >= 60 && $emotional < 80) {
			$emotional_text = $plus.$hand_right.$information_interfaces['emotional'][$this->_lang_code]['good'];
		} else if ($emotional >= 40 && $emotional < 60) {
			$emotional_text = $mark.$hand_right.$information_interfaces['emotional'][$this->_lang_code]['critical'];
		} else if ($emotional >= 20 && $emotional < 40) {
			$emotional_text = $minus.$hand_right.$information_interfaces['emotional'][$this->_lang_code]['gray'];
		} else if ($emotional >= 0 && $emotional < 20) {
			$emotional_text = $minus.$minus.$hand_right.$information_interfaces['emotional'][$this->_lang_code]['bad'];
		}
		if ($intellectual >= 80 && $intellectual <= 100) {
			$intellectual_text = $plus.$plus.$hand_right.$information_interfaces['intellectual'][$this->_lang_code]['excellent'];
		} else if ($intellectual >= 60 && $intellectual < 80) {
			$intellectual_text = $plus.$hand_right.$information_interfaces['intellectual'][$this->_lang_code]['good'];
		} else if ($intellectual >= 40 && $intellectual < 60) {
			$intellectual_text = $mark.$hand_right.$information_interfaces['intellectual'][$this->_lang_code]['critical'];
		} else if ($intellectual >= 20 && $intellectual < 40) {
			$intellectual_text = $minus.$hand_right.$information_interfaces['intellectual'][$this->_lang_code]['gray'];
		} else if ($intellectual >= 0 && $intellectual < 20) {
			$intellectual_text = $minus.$minus.$hand_right.$information_interfaces['intellectual'][$this->_lang_code]['bad'];
		}
		//return '<p>'.$average_text.'</p><p>'.$physical_text.day_of_rhythm($this->_dob,date('Y-m-d',time()+86400*$this->_diff),23).'</p><p>'.$emotional_text.day_of_rhythm($this->_dob,date('Y-m-d',time()+86400*$this->_diff),28).'</p><p>'.$intellectual_text.day_of_rhythm($this->_dob,date('Y-m-d',time()+86400*$this->_diff),33).'</p>';
		return '<p>'.$average_text.'</p><p>'.$physical_text.'</p><p>'.$emotional_text.'</p><p>'.$intellectual_text.'</p>';
	}
	function get_birthday_countdown(): string {
		$birthday_countdown = "";
		switch ($this->_lang_code) {
			case 'vi':
				$birthday_countdown = 'Còn '.countdown_birthday($this->_dob, $this->_date).' ngày nữa là tới sinh nhật của bạn.';
				break;
			case 'en':
				$birthday_countdown = countdown_birthday($this->_dob, $this->_date).' '.pluralize(countdown_birthday($this->_dob, $this->_date),'day').' until your birthday.';
				break;
		}
		return $birthday_countdown;
	}
	function get_infor_values(): string {
		global $email_interfaces;
		$infor_values = '<ul>';
		$infor_values .= '<li>'.$this->_average_text.$email_interfaces['colon'][$this->_lang_code].' '.percent_average_bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),$this->_rhythms).' - '.((average_bio_count($this->_dob,date('Y-m-d',time()+86400*($this->_diff-1)),$this->_rhythms) < average_bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),$this->_rhythms)) ? $email_interfaces['going_up'][$this->_lang_code]: $email_interfaces['going_down'][$this->_lang_code]).'</li>';
		foreach ($this->_rhythms as $rhythm) {
			$infor_values .= '<li>'.$this->get_rhythm_name($rhythm).$email_interfaces['colon'][$this->_lang_code].' '.percent_bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),$rhythm['scale']).((bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),$rhythm['scale']) == 0 || bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),$rhythm['scale']) == 100) ? "": ' - '.((bio_count($this->_dob,date('Y-m-d',time()+86400*($this->_diff-1)),$rhythm['scale']) < bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),$rhythm['scale'])) ? $email_interfaces['going_up'][$this->_lang_code]: $email_interfaces['going_down'][$this->_lang_code])).'</li>';
		}
		$infor_values .= '</ul>';
		return $infor_values;
	}
	function render_meta_description(): string {
		$meta_description = differ_year($this->_dob, $this->_date).' '.$this->_age;
		$meta_description .= ' - '.date('Y-m-d',time()+86400*$this->_diff).' -';
		foreach ($this->_rhythms as $rhythm) {
			$meta_description .= ' '.$this->get_rhythm_name($rhythm).' '.percent_bio_count($this->_dob,date('Y-m-d',time()+86400*$this->_diff),$rhythm['scale']);
		}
		return $meta_description;
	}
	// Render info
	function output_info(): string {
		global $help_interfaces;
		$output = "";
		$output .= '
<section id="info" data-disable-interaction="true" data-step="8" data-intro="'.$this->_intro_info.'">
	<h2 class="mb-4">'.$this->_info_h5.'</h2>
	<div class="helper changeable"><i class="icon-circle-question-mark icon-white"></i></div>
	'.$this->get_infor_details().'
</section>
		';
		return $output;
	}
	// Render Highcharts
	function output_main_chart(): string {
		global $menu_interfaces;
		global $help_interfaces;
		$output = "";
		$output .= '
<i id="chart-prev" onclick="goToPrevMain()" data-disable-interaction="true" data-step="5" data-intro="'.$this->_chart_prev.'" data-original-title="'.$this->_chart_prev.'" data-toggle="tooltip" data-placement="right" class="chart-nav position-absolute fas fa-angle-left" style="left: 6px;"></i>
<i id="chart-next" onclick="goToNextMain()" data-disable-interaction="true" data-step="6" data-intro="'.$this->_chart_next.'" data-original-title="'.$this->_chart_next.'" data-toggle="tooltip" data-placement="left" class="chart-nav position-absolute fas fa-angle-right" style="right: 6px;"></i>
<i id="chart-today" onclick="goToTodayMain()" data-disable-interaction="true" data-step="4" data-intro="'.$this->_chart_today.'" data-original-title="'.$this->_chart_today.'" data-toggle="tooltip" data-placement="bottom" class="chart-nav position-absolute fas fa-calendar-day" style="bottom: auto; top: 12px; left: calc(50% - 21px);"></i>
<i id="chart-birthday" onclick="goToBirthdayMain()" data-disable-interaction="true" data-step="7" data-intro="'.$this->_chart_birthday.'" data-original-title="'.$this->_chart_birthday.'" data-toggle="tooltip" data-placement="top" class="chart-nav position-absolute fas fa-birthday-cake" style="bottom: 12px; top: auto; left: calc(50% - 21px);"></i>
<div id="main_chart" class="w-100 shadow-lg rounded" data-disable-interaction="true" data-step="3" data-intro="'.$this->_intro_chart.'"></div>
<script>
lang = $("html").attr("lang");
function goToTodayMain() {
	loadResults("'.$this->_dob.'","0","'.$this->_is_secondary.'","'.date('Y-m-d').'","'.$this->_partner_dob.'",lang);
	$("[data-toggle=tooltip]").tooltip("hide");
}
function goToPrevMain() {
	loadResults("'.$this->_dob.'","'.($this->_diff-1).'","'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*($this->_diff-1)).'","'.$this->_partner_dob.'",lang);
	$("[data-toggle=tooltip]").tooltip("hide");
}
function goToNextMain() {
	loadResults("'.$this->_dob.'","'.($this->_diff+1).'","'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*($this->_diff+1)).'","'.$this->_partner_dob.'",lang);
	$("[data-toggle=tooltip]").tooltip("hide");
}
function goToBirthdayMain() {
	loadResults("'.$this->_dob.'","'.($this->_diff+countdown_birthday($this->_dob, $this->_date)).'","'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*($this->_diff+countdown_birthday($this->_dob, $this->_date))).'","'.$this->_partner_dob.'",lang);
	$("[data-toggle=tooltip]").tooltip("hide");
}
$.ajax({
  success : function() {
    if (!Modernizr.touch) {
      $("[data-toggle=tooltip]").tooltip();
    }
  },
  global: false
});
setChartOptions("'.$this->_full_screen_text.'","'.$this->_download_jpeg_text.'","'.$this->_download_pdf_text.'","'.$this->_download_png_text.'","'.$this->_download_svg_text.'","'.$this->_print_chart_text.'","'.$this->_reset_zoom_text.'");
renderChart("#main_chart","'.$this->_title_text.$this->_dob.' | '.date('Y-m-d',time()+86400*$this->_diff).'","'.$this->_percentage_text.'","'.$this->_date_text.'",'.$this->_dates_json.',"'.$this->_today_index.'","'.$this->_dob.'",'.$this->_diff.',"'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*$this->_diff).'",'.$this->serialize_chart_data().',"main");
</script>
		';
		return $output;
	}
	function render_results() {
		echo $this->output_stats();
		if ($this->_is_registered == true) {
			echo $this->output_lunar();
		}
		echo $this->output_info();
		echo $this->output_compatibility();
		echo $this->output_controls();
		echo $this->output_main_chart();
	}
	function render_array() {
		echo '<pre>';
		print_r($this->_rhythms);
		echo '</pre>';
	}
}