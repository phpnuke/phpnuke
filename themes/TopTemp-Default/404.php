<?php
define("_ERROR_PAGE", true);
include("header.php");
$html_output .="
<div id=\"content\" class=\"site-content error-404 not-found text-center mjr-py-50\">
    <div class=\"container\">
        <div class=\"row\">
            <header class=\"page-header w-100\">
                <div class=\"w-100\">
                    <img src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/assets/img/404.png\">
                    <h4 class=\"page-title2 mjr-mt-10 mjr-sliding-headline font-weight-bold mjr-ready\"><span class=\"slide-in-container\"><span class=\"d-inline-block undefined animated\">"._SORRY."</span></span>
					</h4>
                </div>
            </header>
            <!-- .page-header -->
            <div class=\"page-content text-center w-100\">
                <p>".$error_message." </p>
                <div class=\"mjr-pb-10\">
					<a href=\"'._GOBACK_CLEAN.'\" class=\"btn btn-info btn-lg\"><span class=\"fa fa-share-alt\">
					</span> "._GOBACK_TEXT." </a>
					<a href=\"".$nuke_configs['nukeurl']."\" class=\"btn btn-primary btn-lg\"><span class=\"fa fa-home\"></span> "._GO_TO_MAIN_PAGE." </a>
					<a href=\"".LinkToGT("index.php?modname=Feedback")."\" class=\"btn btn-info btn-lg\">
					<span class=\"fa fa-envelope\"></span> "._CONTACT_US." </a>
				</div>
                <form class=\"mjr-small-search position-relative bg-white shadow-sm rounded-lg mjr-small-search  d-inline-block w-100\" style=\"max-width:100%;width: 600px !important;\" method=\"post\" action=\"".LinkToGT("index.php?modname=Search")."\">
                    <div class=\"input-group input-group-lg2 \">
                        <input type=\"text\" class=\"form-control mjr-ajax-search form-control-lg shadow-0 font-weight-bold text-body-default\" autocomplete=\"off\" value=\"$search_query\" name=\"search_query\" aria-label=\"Search\">
                        <div class=\"input-group-append\">
                            <button class=\"btn btn-lg2 btn-white m-0 text-body-default\" type=\"submit\">
                                <i class='fa fa-search'></i>
                            </button>
                        </div>
                    </div>
                    <input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
                </form>
            </div>
        </div>
    </div>
</div>";
include("footer.php");