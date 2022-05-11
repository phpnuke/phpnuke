<?php

if (!defined('NUKE_FILE')) {
	die ("You can't access this file directly...");
}

global $nuke_configs;

$theme_setup = array(
	"default_css" => array(
    "<link rel='stylesheet' id='essentials-bootstrap-css'  href='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/css/build/bootstrap.min.css' type='text/css' media='all' />",
    "".((_DIRECTION == 'rtl') ? "<link rel=\"stylesheet\" href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/css/bootstrap-rtl.css\">":"")."",

    "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."includes/fonts/vazir/style.css\" />",
    "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."includes/fonts/fontawesome/style.css\" />",
    "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/jquery-ui.min.css\" />",
    "<link href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/style/bootstrap-social.css\" rel=\"stylesheet\">",
    "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/style/style.css\" />",
    "<link rel='stylesheet' id='style-min-css'  href='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/css/build/style.min.css' type='text/css' media='all' />",
    "<link rel='stylesheet' id='mjr-flickity-style-css'  href='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/css/build/flickity.min.css' type='text/css' media='all' />",
    "<link rel='stylesheet' id='mjr-popups-style-css'  href='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/css/jquery-confirm.min.css' type='text/css' media='all' />",
    "<link rel='stylesheet' id='bootstrap-select-css'  href='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/css/build/bootstrap-select.min.css' type='text/css' media='all' />",
    "<link rel='stylesheet' id='mjr-style-css'  href='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/styles.css' type='text/css' media='all' />",
    "<link rel='stylesheet' id='mjr-style-css'  href='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/custom.css' type='text/css' media='all' />",
	"".((_DIRECTION == 'rtl') ? "<link rel=\"stylesheet\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/rtl.css\" type=\"text/css\" media=\"screen\" />":"")."",

	),
	"default_js" => array(),
	"defer_js" => array(
		"<script type=\"text/javascript\">var phpnuke_url = '".$nuke_configs['nukeurl']."', phpnuke_cdnurl = '".$nuke_configs['nukecdnurl']."', phpnuke_theme = '".$nuke_configs['ThemeSel']."', nuke_lang = '".(($nuke_configs['multilingual'] == 1) ? $nuke_configs['currentlang']:$nuke_configs['language'])."', nuke_date = ".$nuke_configs['datetype'].";var theme_languages = { success_voted : '"._SUCCESS_VOTED."', try_again : '"._ERROR_TRY_AGAIN."'};var pn_csrf_token = '"._PN_CSRF_TOKEN."';
		</script>",
		"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/jquery.min.js\"></script>",
		"<script type=\"text/javascript\" language=\"javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/jquery-ui.min.js\"></script>",
		"<!--[if lt IE 9]> <script src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/plugins/html5shiv/dist/html5shiv.js\"></script><script src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/plugins/respond/respond.min.js\"></script> <![endif]-->",
		"<script type=\"text/javascript\" src=\" ".$nuke_configs['nukecdnurl']."includes/jrating/jRating.jquery.js\"></script>",
		"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/clipboard.min.js\"></script>",
		"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/script/script.js\"></script>",
        "<script type='text/javascript' src='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/js/build/front.min.js' id='front-js'></script>",
	),
	"default_link_rel" => array(
		"<link rel=\"apple-touch-icon-precomposed\" sizes=\"114x114\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/114x114.png\">",
		"<link rel=\"apple-touch-icon-precomposed\" sizes=\"72x72\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/72x72.png\">",
		"<link rel=\"apple-touch-icon-precomposed\" sizes=\"57x57\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/57x57.png\">",
		"<link rel=\"apple-touch-icon-precomposed\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/default.png\">",
		"".((file_exists("themes/".$nuke_configs['ThemeSel']."/images/favicon.ico")) ? "<link rel=\"shortcut icon\" href=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/images/favicon.ico\">":"")."",
	),
	"default_meta" => array(),
	"theme_nav_menus" => array(
		'primary' => _MAIN_MENU,
		'footer'  => _FOOTER_MENU,
	),
	"theme_widgets" => array(
		"right" => _RIGHT_BLOCKS,
		"left" => _LEFT_BLOCKS,
		"topcenter" => _TOPCENTER_BLOCKS,
		"bottomcenter" => _BOTTOMCENTER_BLOCKS,
	),
	"theme_boxes_templates" => "themes/".$nuke_configs['ThemeSel']."/theme_boxes.html",
	"theme_boxes_templates_classes" => array(
		"modules_boxes"	=> "row mjr-mt-20",
		"top_full_module_boxes" => "",
		"right_module_boxes" => array(
			"_r" => "col-md-4 col-md-pull-8 sidebar widget-area sticky-bottom mjr-sticky-sidebar",
			"_l_r" => "col-md-3 col-md-pull-6",
		),
		"middle_module_boxes" => array(
			"_full" => "text-right",
			"_l" => "col-md-8",
			"_r" => "col-md-8 col-md-push-4",
			"_l_r" => "col-md-6 col-md-push-3"
		),
		"left_module_boxes" => array(
			"_l" => "col-md-4 sidebar widget-area sticky-bottom mjr-sticky-sidebar",
			"_l_r" => "col-md-3",
		),
		"top_middle_module_boxes" => "",
		"main_middle_module_boxes" => "",
		"bottom_middle_module_boxes" => "",
		"bottom_full_module_boxes" => ""
	),
	'toptemp_default_configs' => isset($nuke_configs['toptemp_default_configs']) ? phpnuke_unserialize($nuke_configs['toptemp_default_configs']):array(
		'active_slider' => 0,
	)
);

function toptemp_default_theme_config()
{
global $nuke_configs, $db, $admin_file, $theme_setup;
$contents = '';
$toptemp_default_configs = $theme_setup['toptemp_default_configs'];
$contents .="
<link rel='stylesheet' href='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/css/admin/bootstrap.min.css'>
<style>
.nav {
    float: none;
    font-size: 16px;
    height: inherit;
}
.nav-pills-custom .nav-link {
    color: #aaa;
    background: #fff;
    position: relative;
}

.nav-pills-custom .nav-link.active {
    color: #007fff;
    background: #fff;
}

.mb-3, .my-3 {
    margin-bottom: .4rem!important;
}
/* Add indicator arrow for the active tab */
@media (min-width: 992px) {
    .nav-pills-custom .nav-link::before {
        content: '';
        display: block;
        border-top: 8px solid transparent;
        border-right: 10px solid #fff;
        border-bottom: 8px solid transparent;
        position: absolute;
        top: 50%;
        left: -10px;
        transform: translateY(-50%);
        opacity: 0;
    }
}

.nav-pills-custom .nav-link.active::before {
    opacity: 1;
}


.ui-widget {

}



.panel-primary {
border-color: #f84270;
}
.input-number{
width: 30%;
height: 34px;
}
.input-text{
width: 60%;
height: 34px;
}

.inp-form-ltr {
    height: 34px;
}
.inp-form-r {
direction:rtl;
text-align:right;
}
.control-label span {
font-size: 10px;
color:#444444
}
.input-control {
display: block;
padding: 6px 12px;

line-height: 1.428571429;
color: #555;
vertical-align: middle;
background-color: #fff;
border: 1px solid #ccc;
border-radius: 4px;
-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}


</style>



</tr>
</tbody>
</table>
<section class='py-5 header'>
<div class='container py-4'>

<div class='row'>
<div class='col-md-3'>

<div class='nav flex-column nav-pills nav-pills-custom' id='v-pills-tab' role='tablist' aria-orientation='vertical'>
<a class='nav-link mb-3 p-3 shadow active' id='v-pills-general-tab' data-toggle='pill' href='#v-pills-general' role='tab' aria-controls='v-pills-general' aria-selected='true'>
<i class='fa fa-cog mr-2'></i>
<span class='font-weight-bold small text-uppercase'>"._GENERAL_SETTINGS."</span></a>

<a class='nav-link mb-3 p-3 shadow' id='v-pills-articles-tab' data-toggle='pill' href='#v-pills-articles' role='tab' aria-controls='v-pills-articles' aria-selected='false'>
<i class='fa fa-pencil mr-2'></i>
<span class='font-weight-bold small text-uppercase'>"._ARTICLES_SETTINGS."</span></a>

<a class='nav-link mb-3 p-3 shadow' id='v-pills-insta-tab' data-toggle='pill' href='#v-pills-insta' role='tab' aria-controls='v-pills-insta' aria-selected='false'>
<i class='fa fa-instagram mr-2'></i>
<span class='font-weight-bold small text-uppercase'>"._SOCIAL_SETTINGS."</span></a>

<a class='nav-link mb-3 p-3 shadow' id='v-pills-footer-tab' data-toggle='pill' href='#v-pills-footer' role='tab' aria-controls='v-pills-footer' aria-selected='false'>
<i class='fa fa-braille mr-2'></i>
<span class='font-weight-bold small text-uppercase'>"._FOOTER_SETTINGS."</span></a>

</div>
</div>
<div class='col-md-9'>

<div class='tab-content' id='v-pills-tabContent'>
<div class='tab-pane fade shadow rounded bg-white show active p-5' id='v-pills-general' role='tabpanel' aria-labelledby='v-pills-general-tab'>
<h4 class='font-italic mb-4'>"._GENERAL_SETTINGS."</h4>



 <div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'> "._SITE_LOGO."
          <span class='form-text text-muted'></span></label>
          <div class='col-sm-9'>
          <input type='text'  class='input-text input-control' placeholder='"._LOGO_IMG."' name='config_fields[toptemp_default_configs][tp_logo]' value='".$toptemp_default_configs['tp_logo']."'>
          </div>
  </div>

 <div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'> "._SITE_MOBILE_LOGO."
          <span class='form-text text-muted'></span></label>
          <div class='col-sm-9'>
          <input type='text'  class='input-text input-control' placeholder='"._LOGO_IMG."' name='config_fields[toptemp_default_configs][tp_logo_scroll]' value='".$toptemp_default_configs['tp_logo_scroll']."'>
          </div>
  </div>

<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._STICKY_HEADER."
          <span class='form-text text-muted'>"._STICKY_HEADER_DESC."</span></label>
          <div class='col-sm-8'>
          ";
  $nav_sticky1 = ($toptemp_default_configs['nav_sticky'] == 0) ? "checked":"";
  $nav_sticky2 = ($toptemp_default_configs['nav_sticky'] == 1) ? "checked":"";


  $contents .="
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][nav_sticky]' value='1' data-label='"._YES."' $nav_sticky2> &nbsp; &nbsp;
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][nav_sticky]' value='0' data-label='"._NO."' $nav_sticky1> &nbsp; &nbsp;
          </div>
  </div>

<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._TOPBAR."
          <span class='form-text text-muted'>"._TOPBAR_DESC."</span></label>
          <div class='col-sm-8'>";
    $top_info1 = ($toptemp_default_configs['top_info'] == 0) ? "checked":"";
    $top_info2 = ($toptemp_default_configs['top_info'] == 1) ? "checked":"";
    $contents .="
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][top_info]' value='1' data-label='"._YES."' $top_info2> &nbsp; &nbsp;
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][top_info]' value='0' data-label='"._NO."' $top_info1> &nbsp; &nbsp;
          </div>
  </div>

<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._MENU_STYLE."
          <span class='form-text text-muted'></span></label>
          <div class='col-sm-8'>";
          $header_box1 = ($toptemp_default_configs['header_box'] == 0) ? "checked":"";
          $header_box2 = ($toptemp_default_configs['header_box'] == 1) ? "checked":"";
$contents .="
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][header_box]' value='0' data-label='"._BOXED."' $header_box1> &nbsp; &nbsp;
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][header_box]' value='1' data-label='"._WIDE."' $header_box2> &nbsp; &nbsp;
          </div>
  </div>


<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'> "._ACTIVATE_SPECIAL_ARTICLES."
          <span class='form-text text-muted'>"._SPECIAL_ARTICLES_DESC."</span></label>
          <div class='col-sm-9'>";
    $active_fpost1 = ($toptemp_default_configs['active_spost'] == 0) ? "checked":"";
    $active_fpost2 = ($toptemp_default_configs['active_spost'] == 1) ? "checked":"";
	$contents .= "
         <input type='radio' class='styled' name='config_fields[toptemp_default_configs][active_spost]' value='1' data-label=\"" . _YES . "\" $active_fpost2> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[toptemp_default_configs][active_spost]' value='0' data-label=\"" . _NO . "\" $active_fpost1>
          </div>
  </div>

<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'> "._ACTIVATE_TOP_ARTICLES."
          <span class='form-text text-muted'>"._TOP_ARTICLES_DESC."</span></label>
          <div class='col-sm-9'>";
    $active_tpost1 = ($toptemp_default_configs['active_tpost'] == 0) ? "checked":"";
    $active_tpost2 = ($toptemp_default_configs['active_tpost'] == 1) ? "checked":"";
	$contents .= "
         <input type='radio' class='styled' name='config_fields[toptemp_default_configs][active_tpost]' value='1' data-label=\"" . _YES . "\" $active_tpost2> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[toptemp_default_configs][active_tpost]' value='0' data-label=\"" . _NO . "\" $active_tpost1>
          </div>
  </div>



</div>


<div class='tab-pane fade shadow rounded bg-white p-5' id='v-pills-articles' role='tabpanel' aria-labelledby='v-pills-articles-tab'>
<h4 class='font-italic mb-4'> "._ARTICLES_SETTINGS."</h4>

<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._CATEGORY_TEMPLATE."
          <span class='form-text text-muted'>"._CATEGORY_TEMPLATE_DESC."</span></label>
          <div class='col-sm-9'>
<select name='config_fields[toptemp_default_configs][category_template]' class='styledselect-select select2-hidden-accessible' style='width: 200px;' tabindex='-1' aria-hidden='true'>
";
for($i=1;$i<=2;$i++){
   $category_template = ($toptemp_default_configs['category_template'] == $i) ? "selected" :"";
  $contents .="
    <option value='$i' $category_template> "._STYLE." $i </option>
  ";
}  $contents .="
</select>

          </div>
  </div>

<div class='form-group'>
    <label for='concept' class='col-sm-4 control-label'>"._ARTICLES_IMAGES."
    <span class='form-text text-muted'> </span></label>
    <div class='col-sm-8'>";
    $post_photo_style1 = ($toptemp_default_configs['post_photo_style'] == 1) ? "checked":"";
    $post_photo_style2 = ($toptemp_default_configs['post_photo_style'] == 0) ? "checked":"";
    $contents .="
        <input type='radio' class='styled' name='config_fields[toptemp_default_configs][post_photo_style]' value='0' data-label='"._ORIGINAL_PHOTO."' $post_photo_style2> &nbsp; &nbsp;
        <input type='radio' class='styled' name='config_fields[toptemp_default_configs][post_photo_style]' value='1' data-label='"._THUMB_PHOTO."' $post_photo_style1> &nbsp; &nbsp;
            </div>
</div>


<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._SHOW_CATEGORY."
          <span class='form-text text-muted'> "._SHOW_CATEGORY_DESC." </span></label>
          <div class='col-sm-8'>";
          $cat_show1 = ($toptemp_default_configs['cat_show'] == 1) ? "checked":"";
          $cat_show2 = ($toptemp_default_configs['cat_show'] == 0) ? "checked":"";
          $contents .="
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][cat_show]' value='0' data-label='"._YES."' $cat_show2> &nbsp; &nbsp;
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][cat_show]' value='1' data-label='"._NO."' $cat_show1> &nbsp; &nbsp;
          </div>
  </div>

<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._SHOW_EXCERPT_ARTICLES."
          <span class='form-text text-muted'> </span></label>
          <div class='col-sm-8'>";
          $excerpt_show1 = ($toptemp_default_configs['excerpt_show'] == 1) ? "checked":"";
          $excerpt_show2 = ($toptemp_default_configs['excerpt_show'] == 0) ? "checked":"";
          $contents .="
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][excerpt_show]' value='0' data-label='"._YES."' $excerpt_show1> &nbsp; &nbsp;
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][excerpt_show]' value='1' data-label='"._NO."' $excerpt_show2> &nbsp; &nbsp;
          </div>
  </div>

<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._READ_MORE_BUTTON."
          <span class='form-text text-muted'>"._READ_MORE_BUTTON_DESC."</span></label>
          <div class='col-sm-8'>";
          $read_more1 = ($toptemp_default_configs['read_more'] == 1) ? "checked":"";
          $read_more2 = ($toptemp_default_configs['read_more'] == 0) ? "checked":"";
          $contents .="
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][read_more]' value='0' data-label='"._YES."' $read_more2> &nbsp; &nbsp;
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][read_more]' value='1' data-label='"._NO."' $read_more1> &nbsp; &nbsp;
          </div>
          <label for='concept' class='col-sm-4 control-label'>
          <span class='form-text text-muted'>"._READ_MORE_BUTTON_TEXT."</span></label>
          <div class='col-sm-8'>
                  <input type='text' class='input-text input-control' placeholder='"._READ_MORE_BUTTON."' name='config_fields[toptemp_default_configs][read_more_text]' value='".$toptemp_default_configs['read_more_text']."'>
          </div>
  </div>

</div>

<div class='tab-pane fade shadow rounded bg-white p-5' id='v-pills-insta' role='tabpanel' aria-labelledby='v-pills-insta-tab'>
<h4 class='font-italic mb-4'> "._SOCIAL_SETTINGS."</h4>
 <div class='form-group'>
          <label for='concept' class='col-sm-3 control-label'> "._WHATSAPP_LINKS."</label>
          <div class='col-sm-9'>
                  <input type='text' name='config_fields[toptemp_default_configs][whatsapp]'  class='input-text input-control' value='".$toptemp_default_configs['whatsapp']."'>
          </div>
  </div>
  <div class='form-group'>
          <label for='description' class='col-sm-3 control-label'>"._INSTAGRAM_LINKS."</label>
          <div class='col-sm-9'>
                  <input type='text' name='config_fields[toptemp_default_configs][instagram]'  class='input-text input-control' value='".$toptemp_default_configs['instagram']."'>
          </div>
  </div>
  <div class='form-group'>
          <label for='description' class='col-sm-3 control-label'>"._TELEGRAM_LINKS."</label>
          <div class='col-sm-9'>
                  <input type='text' name='config_fields[toptemp_default_configs][telegram]'  class='input-text input-control' value='".$toptemp_default_configs['telegram']."'>
          </div>
  </div>

<div class='form-group'>
          <label for='description' class='col-sm-3 control-label'>"._FACEBOOK_LINKS."</label>
          <div class='col-sm-9'>
                  <input type='text' name='config_fields[toptemp_default_configs][facebook]'  class='input-text input-control' value='".$toptemp_default_configs['facebook']."'>
          </div>
  </div>

</div>

<div class='tab-pane fade shadow rounded bg-white p-5' id='v-pills-footer' role='tabpanel' aria-labelledby='v-pills-footer-tab'>
<h4 class='font-italic mb-4'> "._FOOTER_SETTINGS."</h4>

<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._SHOW_OLD_ARTICLES."
          <span class='form-text text-muted'>"._SHOW_OLD_ARTICLES_FOOTER."</span></label>
          <div class='col-sm-8'>";
          $last_aticles1 = ($toptemp_default_configs['last_aticles'] == 0) ? "checked":"";
          $last_aticles2 = ($toptemp_default_configs['last_aticles'] == 1) ? "checked":"";
$contents .="
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][last_aticles]' value='1' data-label='"._YES."' $last_aticles2> &nbsp; &nbsp;
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][last_aticles]' value='0' data-label='"._NO."' $last_aticles1> &nbsp; &nbsp;
          </div>
  </div>



<div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._SOCIAL_SHARE."
          <span class='form-text text-muted'>"._SOCIAL_SHARE_FOOTER." </span></label>
          <div class='col-sm-8'>";
          $footer_social1 = ($toptemp_default_configs['footer_social'] == 0) ? "checked":"";
          $footer_social2 = ($toptemp_default_configs['footer_social'] == 1) ? "checked":"";
$contents .="
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][footer_social]' value='1' data-label='"._YES."' $footer_social2> &nbsp; &nbsp;
                  <input type='radio' class='styled' name='config_fields[toptemp_default_configs][footer_social]' value='0' data-label='"._NO."' $footer_social1> &nbsp; &nbsp;
          </div>
  </div>

  <div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._BACK_TO_TOP."
          <span class='form-text text-muted'></span></label>
         <div class='col-sm-8'>";
          $back_to_top1 = ($toptemp_default_configs['back_to_top'] == 0) ? "checked":"";
          $back_to_top2 = ($toptemp_default_configs['back_to_top'] == 1) ? "checked":"";
          $contents .="
          <input type='radio' class='styled' name='config_fields[toptemp_default_configs][back_to_top]' value='1' data-label='"._YES."' $back_to_top2> &nbsp; &nbsp;
          <input type='radio' class='styled' name='config_fields[toptemp_default_configs][back_to_top]' value='0' data-label='"._NO."' $back_to_top1> &nbsp; &nbsp;
          </div>
  </div>




  <div class='form-group'>
          <label for='concept' class='col-sm-4 control-label'>"._COPYRIGHT_TEXT."
          <span class='form-text text-muted'>"._COPYRIGHT_DESC."</span></label>
          <div class='col-sm-9'>
                  <input type='text' placeholder='"._COPYRIGHT_TITLE."'  class='input-text input-control' name='config_fields[toptemp_default_configs][footer_copyright]' value='".$toptemp_default_configs['footer_copyright']."' >
          </div>
  </div>

</div>

</div>
</div>
</div>
</div>
</section>

<script src='".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/css/admin/bootstrap.bundle.min.js'></script>";
return $contents;
}

function toptemp_default_data_cache($cache_systems)
{
	$cache_systems['toptemp_default_data'] = array(
		'name'			=> "_toptemp_default_DATA",
		'table'			=> 'default',
		'auto_load'		=> true
	);
	return $cache_systems;
}

$hooks->add_filter("cache_systems", "toptemp_default_data_cache", 10);

function toptemp_default_settings($other_admin_configs){
	$other_admin_configs['themes']['Mashhadteam-Default'] = array("title" => _THEME_SETTINGS, "function" => "toptemp_default_theme_config", "God" => false);
	return $other_admin_configs;
}

$hooks->add_filter("other_admin_configs", "toptemp_default_settings", 10);

function get_toptemp_default_posts_media()
{
	global $block_global_contents, $nuke_configs;

	$all_medias = array();
	$all_media_titles = array();
	$all_audio_links = array();
	$all_video_links = array();
	$first_audio = '';
	$first_video = '';
	$first_atitle = '';
	$first_vtitle = '';

	//fetch from text
	$text = (isset($block_global_contents['bodytext'])) ? $block_global_contents['bodytext']:"";

	preg_match_all("#<a(.*)href=['|'](.*)['|'](.*)>(.*)</a>#isU", $text, $text_media);

	if(isset($text_media[2]) && !empty($text_media[2]))
	{
		$all_media_links = $text_media[2];
		$all_media_titles = $text_media[4];

		foreach($all_media_links as $key => $media_link)
		{
			$media_link_name = str_replace(array("%5B","%5D","%20"),array("[","]"," "),$media_link);

			$media_title_arr = explode("/", $media_link_name);
			$media_title_full = end($media_title_arr);

			$media_name_arr = explode(".", $media_link_name);
			$ext = end($media_name_arr);

			if(!in_array($ext, ["mp3","ogg","oga","mp4","m4v"]))
				continue;

			$media_title = strip_tags($all_media_titles[$key]);
			$media_title = (strtolower($media_title) == "download") ? $block_global_contents['title']:$media_title;
			$media_data = array($media_title, $media_link, $ext);

			if(in_array($ext, array("mp3","ogg","oga")))
			{
				$all_audio_links[$ext][] = $media_data;
			}
			if(in_array($ext, array("mp4","m4v")))
			{
				$all_video_links[$ext][] = $media_data;
			}

			$all_medias[$media_link] = $media_data;
		}
	}

	if(!empty($all_audio_links))
	{
		ksort($all_audio_links);
		$all_audio_links_arr = array_values($all_audio_links);

		$first_audio = (isset($all_audio_links_arr[0][0][1])) ? str_replace(" ","%20", $all_audio_links_arr[0][0][1]):"";
		$first_atitle = (isset($all_audio_links_arr[0][0][0])) ? $all_audio_links_arr[0][0][0]:"";
	}

	if(!empty($all_video_links))
	{
		ksort($all_video_links);
		$all_video_links_arr = array_values($all_video_links);

		$first_video = (isset($all_video_links_arr[0][0][1])) ? str_replace(" ","%20", $all_video_links_arr[0][0][1]):"";
		$first_vtitle = (isset($all_video_links_arr[0][0][0])) ? $all_video_links_arr[0][0][0]:"";
	}
	//fetch from text

	//fetch from post files
	if(isset($block_global_contents['download']) && $block_global_contents['download'] != '')
	{
		$post_media = (!is_array($block_global_contents['download'])) ? phpnuke_unserialize(stripslashes($block_global_contents['download'])):$block_global_contents['download'];

		$first_audio_sel = false;
		$first_video_sel = false;

		foreach($post_media as $file_data)
		{
			if(empty($file_data))
				continue;

			$filename = $file_data[0];
			$filelink = $file_data[1];
			$filesize = $file_data[2];
			$filedesc = $file_data[3];
			$filetype = (isset($file_data[3]) && $file_data[3] != '') ? $file_data[3]:'files';
			$media_name_arr = explode(".", $filelink);
			$ext = end($media_name_arr);
			$all_medias[$filelink] = array($filename, $filelink, $ext);

			if($filetype == 'audios' && !$first_audio_sel)
			{
				$first_audio = $filelink;
				$first_atitle = $filename;
				$first_audio_sel = true;
			}

			if($filetype == 'videos' && !$first_video_sel)
			{
				$first_video = $filelink;
				$first_vtitle = $filename;
				$first_video_sel = true;
			}
		}
	}
	//fetch from post files

	$poster = (isset($block_global_contents['post_image']) && $block_global_contents['post_image'] != '') ? $block_global_contents['post_image']:"";

	return array($all_medias, $poster, $first_audio, $first_video, $first_atitle, $first_vtitle);
}

$hooks->add_filter("get_theme_media_function", function($theme_get_media_function){return "get_toptemp_default_posts_media";}, 10);

class toptemp_default_nav_menus extends Walker
{

	public function start_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent\t<".$args->list_type." class='".((isset($args->list_class) && $args->list_class != '') ? $args->list_class:"")."'>\n";
	}

	public function end_lvl(&$output, $depth = 0, $args = array())
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent\t</".$args->list_type.">\n";
	}

	public function start_el(&$output, $element, $depth = 0, $args = array(), $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes	= empty($element->attributes->classes)	?'' : ' '.$element->attributes->classes;
		$styles		= empty($element->attributes->styles)	?'' : $element->attributes->styles;
		$xfn		= empty($element->attributes->xfn)		?'' : $element->attributes->xfn;
		$target		= empty($element->attributes->target)	?'' : $element->attributes->target;

		$class_names = ' class="menu-item menu-item-'.$element->nid.' '.filter($classes, "nohtml").''.(($this->has_children == 1
	&& $depth != 0) ? ' dropdown-menu':'').''.(($depth == 0) ? ' menu-item-type-custom menu-item-object-custom current-menu-ancestor  dropdown  nav-item dropdown align-self-stretch overflow-visible align-items-center':'').'"';
		$styles_codes = ($styles != '') ? ' style="'.$styles.'"':'';
		$xfn_rels_codes = ($xfn != '') ? ' rel="'.$xfn.'"':'';
		$idrel = ' id="menu-item-'.$element->nid.'"';

		$id				= $element->nid;
		$title			= $element->title;
		$type			= $element->type;
		$part_id		= $element->part_id;
		$module			= $element->module;
		$url			= $element->url;

		if($type == 'categories' && $url == '' && $module != '')
		{
			$cat_title = sanitize(filter(implode("/", array_reverse(get_parent_names($part_id, $args->nuke_categories[$module], "parent_id", "catname_url"))), "nohtml"), array("/"));
			$cat_link = category_link($module, $cat_title, $attrs=array(), 3);
			$url = end($cat_link);
		}

		$output .= $indent . '<li'.$class_names.$styles_codes.$idrel.'>';

		$link_before = sprintf($args->link_before, $url, $target, (($this->has_children == 1) ? "dropdown-toggle":""), (($this->has_children == 1) ? "dropdown":""));

		$item_output = $args->before . $link_before . $title . $args->link_after . $args->after;

		$output .= $item_output;
	}

	public function end_el(&$output, $element, $depth = 0, $args = array())
	{
		$output .= "</li>\n";
	}
}

function theme_color(){

}

?>