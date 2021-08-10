<?php
header("Expires: Mon, 29 Jan 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

class ChartAnalysis extends Chart {
  function serialize_chart_data(): string {
    $chart_data = '[{name:"'.$this->_average_text.'",
    data:[';
    for ($d = 0; $d < $this->_dates_count; ++$d) {
      $chart_data .= average_bio_count($this->_dob,$this->_dates[$d],$this->_rhythms);
      $chart_data .= ($d != ($this->_dates_count-1)) ? ',': "";
    }
    $chart_data .= '],
    dragDrop:{
        draggableY: true,
        dragMaxY: 100,
        dragMinY: 0
    },
    lineWidth: 2},';
    for ($r = 0; $r < $this->_rhythms_count; ++$r) {
      $this->_rhythms[$r]['rhythm_name'] = $this->get_rhythm_name($this->_rhythms[$r]);
      $chart_data .= '{name:"'.$this->_rhythms[$r]['rhythm_name'].'",
      data:[';
      for ($d = 0; $d < $this->_dates_count; ++$d) {
        $chart_data .= bio_count($this->_dob,$this->_dates[$d],$this->_rhythms[$r]['scale']);
        $chart_data .= ($d != ($this->_dates_count-1)) ? ',': "";
      }
      $chart_data .= '],
      dragDrop:{
          draggableY: true,
          dragMaxY: 100,
          dragMinY: 0
      },';
      $chart_data .= ($r != ($this->_rhythms_count-1)) ? '},': '}';
    }
    $chart_data .= ']';
    return $chart_data;
  }

  function output_main_chart(): string {
    global $menu_interfaces;
    global $help_interfaces;
    $output = "";
    $output .= '
<input type="hidden" id="dt_curr" value="'.date('Y-m-d',time()+86400*$this->_diff).'" />
<i onclick="goToPrevMain()" data-disable-interaction="true" data-step="6" data-intro="'.$this->_chart_prev.'" data-original-title="'.$this->_chart_prev.'" data-toggle="tooltip" data-placement="right" class="chart-nav position-absolute fad fa-calendar-minus" style="left: 4px;"></i>
<i id="chart-today" onclick="goToTodayMain()" data-disable-interaction="true" data-step="4" data-intro="'.$this->_chart_today.'" data-original-title="'.$this->_chart_today.'" data-toggle="tooltip" data-placement="bottom" class="chart-nav position-absolute fad fa-calendar'.(($this->_diff == 0) ? '-day': '-alt').'" style="bottom: auto; top: 8px; left: calc(50% - 21px);"></i>
<input onchange="changeDateMain()" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->_dt_change_label.'" data-disable-interaction="true" data-step="5" data-intro="'.$this->_dt_change_label.'" type="text" class="mx-auto form-control" name="dt_change" id="change-date" placeholder="'.$this->_dt_change_label.'" required="required" value="'.(($this->_dt_change == date('Y-m-d')) ? 'YYYY-MM-DD': $this->_dt_change).'" data-date-start-view="decade">
<i onclick="goToNextMain()" data-disable-interaction="true" data-step="7" data-intro="'.$this->_chart_next.'" data-original-title="'.$this->_chart_next.'" data-toggle="tooltip" data-placement="left" class="chart-nav position-absolute fad fa-calendar-plus" style="right: 4px;"></i>
<i id="chart-prev-week" onclick="goToPrevWeekMain()" data-disable-interaction="true" data-step="8" data-intro="'.$this->_chart_prev_week.'" data-original-title="'.$this->_chart_prev_week.'" data-toggle="tooltip" data-placement="top" class="chart-nav position-absolute fad fa-backward" style="bottom: 8px; top: auto; left: calc(50% - 84px);"></i>
<i id="chart-next-week" onclick="goToNextWeekMain()" data-disable-interaction="true" data-step="9" data-intro="'.$this->_chart_next_week.'" data-original-title="'.$this->_chart_next_week.'" data-toggle="tooltip" data-placement="top" class="chart-nav position-absolute fad fa-forward" style="bottom: 8px; top: auto; right: calc(50% - 84px);"></i>
<i id="chart-birthday" onclick="goToBirthdayMain()" data-disable-interaction="true" data-step="10" data-intro="'.$this->_chart_birthday.'" data-original-title="'.$this->_chart_birthday.'" data-toggle="tooltip" data-placement="top" class="chart-nav position-absolute fad fa-birthday-cake" style="bottom: 8px; top: auto; left: calc(50% - 21px);"></i>
<div id="main_chart" class="mx-auto shadow-lg rounded" data-disable-interaction="true" data-step="3" data-intro="'.$this->_intro_chart.'"></div>
<script>
lang = $("html").attr("lang");
dt_curr = $("#dt_curr").val();
dt_change = $("#change-date").val();
function goToTodayMain() {
  loadAnalysisResults("'.$this->_dob.'","0","'.$this->_is_secondary.'","'.date('Y-m-d').'","'.$this->_partner_dob.'",lang);
  $("[data-toggle=tooltip]").tooltip("hide");
}
function goToPrevMain() {
  loadAnalysisResults("'.$this->_dob.'","'.($this->_diff-1).'","'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*($this->_diff-1)).'","'.$this->_partner_dob.'",lang);
  $("[data-toggle=tooltip]").tooltip("hide");
}
function goToNextMain() {
  loadAnalysisResults("'.$this->_dob.'","'.($this->_diff+1).'","'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*($this->_diff+1)).'","'.$this->_partner_dob.'",lang);
  $("[data-toggle=tooltip]").tooltip("hide");
}
function goToPrevWeekMain() {
  loadAnalysisResults("'.$this->_dob.'","'.($this->_diff-7).'","'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*($this->_diff-7)).'","'.$this->_partner_dob.'",lang);
  $("[data-toggle=tooltip]").tooltip("hide");
}
function goToNextWeekMain() {
  loadAnalysisResults("'.$this->_dob.'","'.($this->_diff+7).'","'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*($this->_diff+7)).'","'.$this->_partner_dob.'",lang);
  $("[data-toggle=tooltip]").tooltip("hide");
}
function goToBirthdayMain() {
  loadAnalysisResults("'.$this->_dob.'","'.($this->_diff+countdown_birthday($this->_dob, $this->_date)).'","'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*($this->_diff+countdown_birthday($this->_dob, $this->_date))).'","'.$this->_partner_dob.'",lang);
  $("[data-toggle=tooltip]").tooltip("hide");
}
function changeDateMain() {
  loadAnalysisResults("'.$this->_dob.'",'.$this->_diff.'+dateDiff(dt_curr,$("#change-date").val()),"'.$this->_is_secondary.'",$("#change-date").val(),"'.$this->_partner_dob.'",lang);
  $("[data-toggle=tooltip]").tooltip("hide");
}
$("#change-date").datepicker({
  format: "yyyy-mm-dd",
  defaultViewDate: "'.date('Y-m-d',strtotime($this->_dt_change)).'",
//  endDate: "'.date('Y-m-d').'",
  autoclose: true,
//  immediateUpdates: true,
//  todayHighlight: true,
//  todayBtn: true,
  orientation: "bottom",
  title: "'.$this->_dt_change_label.'"
}).attr("readonly","readonly");
if ($("#change-date").val() != "YYYY-MM-DD") {
  renderChartAnalysis("#main_chart","'.$this->_title_text.$this->_dob.' | '.date('Y-m-d',time()+86400*$this->_diff).'","'.$this->_percentage_text.'","'.$this->_date_text.'",'.$this->_dates_json.',"'.$this->_today_index.'","'.$this->_dob.'",'.$this->_diff.',"'.$this->_is_secondary.'",$("#change-date").val(),'.$this->serialize_chart_data().',"main");
} else if ($("#change-date").val() == "YYYY-MM-DD") {
  renderChartAnalysis("#main_chart","'.$this->_title_text.$this->_dob.' | '.date('Y-m-d',time()+86400*$this->_diff).'","'.$this->_percentage_text.'","'.$this->_date_text.'",'.$this->_dates_json.',"'.$this->_today_index.'","'.$this->_dob.'",'.$this->_diff.',"'.$this->_is_secondary.'","'.date('Y-m-d').'",'.$this->serialize_chart_data().',"main");
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
renderChartAnalysis("#main_chart","'.$this->_title_text.$this->_dob.' | '.date('Y-m-d',time()+86400*$this->_diff).'","'.$this->_percentage_text.'","'.$this->_date_text.'",'.$this->_dates_json.',"'.$this->_today_index.'","'.$this->_dob.'",'.$this->_diff.',"'.$this->_is_secondary.'","'.date('Y-m-d',time()+86400*$this->_diff).'",'.$this->serialize_chart_data().',"main");
</script>
    ';
    return $output;
  }
}