<?php
/**************************************************************************/
/* PHP-NUKE: Advanced Content Management System                           */
/* ============================================                           */
/*                                                                        */
/* This is the language module with all the system messages               */
/*                                                                        */
/* If you made a translation, please go to the site and send to me        */
/* the translated file. Please keep the original text order by modules,   */
/* and just one message per line, also double check your translation!     */
/*                                                                        */
/* You need to change the second quoted phrase, not the capital one!      */
/*                                                                        */
/* If you need to use double quotes (") remember to add a backslash (\),  */
/* so your entry will look like: This is \"double quoted\" text.          */
/* And, if you use HTML code, please double check it.                     */
/**************************************************************************/

if (empty($nuke_languages['farsi']) || !is_array($nuke_languages['farsi']))
{
	$nuke_languages['farsi'] = array();
}

$nuke_languages['farsi'] = array_merge($nuke_languages['farsi'], array(
	"_GIAPI" => "سرویس Indexing Google",
	"_GIAPI_READ_OUR_GUIDE" => "راهنمای دریافت کد",
	"_GIAPI_AUTO_SUBMIT_POSTS" => "ارسال خودکار پستها به گوگل",
	"_GIAPI_MANUAL_SUBMIT" => "ارسال دستی",
	"_GIAPI_MAX_URLS" => "حداکثر %d لینک را میتوانید وارد کنید. هر لینک در یک ردیف",
	"_GIAPI_SUBMIT_ACTION" => "توع عملیات",
	"_GIAPI_SUBMIT_ACTION_PUBLISH_UPDATE" => "انتشار/بروزرسانی",
	"_GIAPI_SUBMIT_ACTION_REMOVE" => "حذف",
	"_GIAPI_SUBMIT_ACTION_STATUS" => "وضعیت",
	"_GIAPI_SUCCESS" => "عملیات با موفقیت انجام شد",
	"_GIAPI_ERROR" => "عملیات با خطا مواجه شد",
	"_GIAPI_LAST_UPDATE" => "آخرین بروزرسانی : ",
	"_GIAPI_SEE_RESPONSE" => "مشاهده جزئیات",
	"_GIAPI_GOOGLEAPI_REM_QUOTA" => "سهمیه باقیمانده Google API",
	"_GIAPI_SEND_TO_API_LOG_SUCCESS" => "<span style=\"color:#0a802f\">عملیات '%s' بر روی لینک %s و ارسال به google با موفقیت انجام شد.%s<span>",
	"_GIAPI_SEND_TO_API_LOG_ERROR" => "<span style=\"color:#ff0000\">عملیات '%s' بر روی لینک %s و ارسال به google با خطا انجام شد. خطا: %s<span>",
));
?>