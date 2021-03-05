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

if (empty($nuke_languages['english']) || !is_array($nuke_languages['english']))
{
	$nuke_languages['english'] = array();
}

$nuke_languages['english'] = array_merge($nuke_languages['english'], array(
	"_CREDITS_ADMIN" => "سیستم اعتبارات",
	"_CREDITS_NORMAL" => "انجام نشده",
	"_CREDITS_OK" => "تأیید شده",
	"_CREDITS_PENDING" => "در انتظار بررسی",
	"_CREDITS_CANCELED" => "لغو شده",
	"_CREDITS_DELETED" => "حذف شده",
	"_CREDITS_FAILED" => "رد شده",
	"_CREDIT_NO_TRANSACTION" => "هیچ تراکنشی ثبت نشده است",
	"_CREDITS_BY_ADMIN" => "مدیر %s",
	"_CREDIT_DETAILS_USERNAME" => "نام کاربری",
	"_CREDIT_DETAILS_USER_REALNAME" => "نام و نام خانوادگی",
	"_CREDIT_DETAILS_TRANSACTION_BY" => "انجام دهنده تراکنش",
	"_CREDIT_DETAILS_FACTOR_NUMBER" => "شماره فاکتور",
	"_CREDIT_DETAILS_CREATE_TIME" => "تاریخ ایجاد",
	"_CREDIT_DETAILS_UPDATE_TIME" => "آخرین بروزرسانی",
	"_CREDIT_DETAILS_OFFLINE_TIME" => "تاریخ فیش واریزی",
	"_CREDIT_DETAILS_OFFLINE_NUMBER" => "شماره فیش واریزی",
	"_CREDIT_DETAILS_STATUS" => "وضعیت",
	"_CREDIT_DETAILS_TYPE" => "نوع تراکنش",
	"_CREDIT_DETAILS_DATETIME" => "تاریخ تراکنش",
	"_CREDIT_DETAILS_GATEWAY" => "درگاه پرداخت",
	"_CREDIT_DETAILS_DATA" => "اطلاعات پرداخت",
	"_CREDIT_DETAILS_PAYMENT_TRANC_ID" => "شماره پیگیری بانک",
	"_CREDIT_DETAILS_FISH_IMAGE_URL" => "عکس فیش",
	"_CREDIT_DETAILS_AMOUNT" => "مبلغ",
	"_CREDIT_DETAILS_TITLE" => "عنوان",
	"_CREDIT_DETAILS_DESCRIPTION" => "شرح",
	"_CREDIT_DETAILS_ORDER_PART" => "سیستم مربوطه",
	"_CREDIT_DETAILS_ORDER_ID" => "شماره سفارش",
	"_CREDIT_DETAILS_ORDER_LINK" => "آدرس سفارش",
	"_CREDIT_DETAILS_ORDER_DATA" => "اطلاعات سفارش",
	"_CREDIT_DETAILS_OTHER_ONLINE_DATA" => "سایر اطلاعات پرداخت آنلاین",
	"_CREDIT_DETAILS_REL_FROM_USER_REALNAME" => "انتقال از",
	"_CREDIT_DETAILS_REL_TO_USER_REALNAME" => "انتقال به",
	"_CREDITS_STATISTICS" => "آمار تراکنشهای 30 روز اخیر",
	"_CREDITS_AMOUNT" => "مبلغ",
	"_CREDITS_DEPOSIT" => "واریز",
	"_CREDITS_WITHDRAW" => "برداشت",
	"_CREDITS_GATEWAY_ONLINE" => "آنلاین",
	"_CREDITS_GATEWAY_OFFLINE" => "غیر آنلاین",
	"_CREDITS_TOTAL_TRANSACTIONS" => "تعداد تراکنشها",
	"_CREDITS_LIST" => "لیست تراکنشها",
	"_CREDITS_VIEW_DETAILS" => "مشاهده جزئیات تراکنش",
	"_CREDITS_SETTINGS" => "تنظیمات سیستم اعتبارات",
	"_CREDIT_CHART_USERS_TITLE" => "آمار بالاترین تراکنشهای کاربران طی 30 روز اخیر",
	"_CREDIT_CHART_USERS" => "<b>[[value]]</b> ریال طی [[transactions]] تراکنش",
	"_CREDITS_SORT_LIST_BY" => "نمایش تراکنشها بر اساس",
	"_CREDITS_SEARCH_IN_ALL" => "همه موارد",
	"_CREDITS_SEARCH_IN_TRANSACTION_ID" => "کد تراکنش",
	"_CREDITS_SEARCH_IN_ORDER_ID" => "کد سفارش",
	"_CREDITS_SEARCH_IN_TITLE" => "عنوان",
	"_CREDITS_SEARCH_IN_DESC" => "شرح",
	"_CREDITS_SEARCH_IN_GATEWAY" => "درگاه",
	"_CREDITS_SHOW_MORE_SEARCH_OPTIONS" => "نمایش گزینه های بیشتر",
	"_CREDITS_APPROVED" => "تراکنش تأیید شد",
	"_CREDITS_MIN_AMOUNT" => "کمترین مبلغ قابل قبول",
	"_CREDITS_MAX_AMOUNT" => "بیشترین مبلغ قابل قبول",
	"_CREDITS_DIRECT_MESSAGE" => "پیام صفحه افزایش موجودی",
	"_CREDITS_LIST_MESSAGE" => "پیام صفحه لیست تراکنشها",
	"_CREDITS_REMAIN" => "مانده اعتبار",
	"_CREDITS_CHARGE" => "افزایش اعتبار",
	"_CREDITS_DEDUCTION" => "کسر اعتبار",
	"_CREDITS_TRANSFER" => "انتقال اعتبار",
	"_CREDITS_SUSPEND" => "مسدودی اعتبار",
	"_CREDITS_ACCOUNT_REMAIN" => "موجودی حساب اعتباری",
	"_CREDITS_ACCOUNT_CHARGE" => "افزایش موجودی حساب اعتباری",
	"_CREDITS_TRANSACTION_DONE" => "این تراکنش قبلاً کارسازی شده است",
	"_CREDITS_PAY_ORDER" => "پرداخت سفارش",
	"_CREDITS_MORE_THAN_AMOUNT" => "مبلغ ارسالی بیشتر از حد مجاز است",
	"_CREDITS_LESS_THAN_AMOUNT" => "مبلغ ارسالی کمتر از حد مجاز است",
	"_CREDITS_GATEWAY_CON_ERROR" => "خطایی در هنگام اتصال به درگاه پرداخت رخ داده است",
	"_CREDITS_UPLOAD_FISH" => "لطفاً فایل فیش واریزی را ارسال نمایید",
	"_CREDITS_REWARD" => "جایزه",
	"_CREDITS_REWARD_FOR" => "اعتبار جایزه بابت سفارش : %s",
	"_CREDITS_REMAIN_LESS_THAN_AMOUNT" => "موجودی حساب کمتر از مبلغ تراکنش است",
	"_CREDITS_BAD_GATEWAY" => "درگاه نامعتبر است",
	"_CREDITS_NO_RELATED_ORDER" => "هیچ سفارش مرتبطی یافت نشد",
	"_CREDITS_USERS_OP" => "عملیات اعتباری کاربران",
	"_CREDITS_SUSPEND_LIST" => "لیست مسدودی حسابهای کاربران",
	"_CREDIT_SUSPEND_REASON" => "علت مسدودی",
	"_CREDITS_SUSPEND_ALL" => "مسدودی کل",
	"_CREDITS_UNSUSPEND" => "رفع مسدودی",
	"_CREDITS_UNSUSPEND_SURE" => "آیا از رفع مسدودی این مورد اطمینان دارید ؟",
	"_CREDITS_INCREASE" => "آیا از رفع مسدودی این مورد اطمینان دارید ؟",
	"_CREDITS_FROM_USERNAME" => "انتقال اعتبار از کاربر",
	"_CREDITS_TO_USERNAME" => "انتقال اعتبار به کاربر",
	"_CREDITS_AMOUNT" => "مقدار اعتبار",
	"_CREDITS_TRANSFER_TYPE" => "نوع انتقال",
	"_CREDITS_TRANSFER_ALL" => "انتقال کل اعتبار",
	"_CREDITS_TR_ALL_1" => "کل اعتبار قابل انتقال و مجاز و مسدود نشده کاربر اول به کاربر دوم منتقل می شود",
	"_CREDITS_TRANSFER_TYPE_DESC" => "در صورتی که اعتبار قابل انتقال مجاز از کاربر اول کمتر از مقدار اعتبار درخواستی شما باشد یکی از گزینه ها را انتخاب کنید",
	"_CREDITS_TR_TY_1" => "انتقال کل اعتبار کاربر اول به کاربر دوم و اتمام عملیات",
	"_CREDITS_TR_TY_2" => "انتقال کل اعتبار کاربر اول به کاربر دوم و منفی نمودن اعتبار کاربر اول به اندازه مابه التفاوت اعتبار وارد شده",
	"_CREDITS_TR_TY_3" => "انتقال کل اعتبار کاربر اول به کاربر دوم و منفی نمودن اعتبار کاربر اول و افزایش اعتبار کاربر دوم به اندازه مابه التفاوت اعتبار وارد شده",
	"_CREDITS_TR_TY_4" => "لغو عملیات انتقال در صورت کمبود اعتبار",
	"_CREDITS_CHARGE_MSG" => "اعتبار کاربر %s به مبلغ %s ریال افزوده شد",
	"_CREDITS_DEDUCTION_MSG" => "اعتبار کاربر %s به مبلغ %s ریال کاهش یافت",
	"_CREDITS_TRANSFER_MSG" => "اعتبار کاربر %s به مبلغ %s ریال کاهش یافت و اعتبار کاربر %s به مبلغ %s ریال افزوده شد",
	"_CREDITS_SUSPEND_AMOUNT" => "مبلغ مسدودی",
	"_CREDITS_SUSPEND_DESC" => "0 یعنی مسدودی کل حساب",
	"_CREDITS_TRANSFER_D" => "واریز انتقالی",
	"_CREDITS_TRANSFER_W" => "برداشت انتقالی",
	"_CREDITS_SUSPEND_MSG" => "اعتبار کاربر %s به مبلغ %s ریال مسدود شد",
	"_CREDITS_SUSPEND_ALL_MSG" => "اعتبار کاربر %s به طور کامل مسدود شد",
	"_CREDIT_DELETE_LOG" => "تراکنش مالی شماره %d حذف گردید",
	"_CREDIT_FAILED_LOG" => "تراکنش مالی شماره %d رد شد",
	"_CREDIT_APPROVE_LOG" => "تراکنش مالی شماره %d تأیید شد",
	"_CREDIT_UNSUSPEND_LOG" => "تراکنش مسدودی شماره %d رفع انسداد گردید",
	"_CREDITS_GATEWAYS_SETTINGS" => "تنظیمات درگاه های پرداخت",
	"_CREDITS_TERMINAL_ID" => "کد ترمینال",
	"_CREDITS_TERMINAL_API" => "کد API",
	"_CREDITS_NO_ERROR_MESSAGED" => "خطایی با کد %d رخ داده است.",
	"_CREDITS_BAD_PARAMS" => "پارامترهای ارسالی صحیح نیست",
	"_CREDITS_ERROR_IN_APPROVE_TRANSACTION" => "پارامترهای ارسالی صحیح نیست",
	"_CREDITS_ERROR_IN_TRANSACTION_SETTEL" => "خطا در درخواست واریز وجه",
	"_CREDITS_CURRENCY_SETTINGS" => "تنظیمات نرخ ارز",
	"_CREDITS_CUR_CODE" => "نام اختصاری",
	"_CREDITS_CUR_NAME" => "عنوان",
	"_CREDITS_CUR_EXRATE" => "نرخ برابری",
	"_CREDITS_CUR_USD" => "دلار آمریکا",
	"_CREDITS_CUR_EUR" => "یورو",
	"_CREDITS_CUR_GBP" => "پوند انگلیس",
	"_CREDITS_CUR_AED" => "درهم امارات",
	"_CREDITS_CUR_KWD" => "دینار کویت",
	"_CREDITS_CUR_TRY" => "لیر ترکیه",
	"_CREDITS_PAY_METHOD" => "نحوه پرداخت",
	"_CREDITS_PAY_ONLINE" => "اینترنتی",
	"_CREDITS_PAY_OFFLINE" => "فیش واریزی",
	"_CREDITS_PAY_BY_CREDIT" => "پرداخت از حساب اعتباری",
	"_CREDITS_PAY_BY_CREDIT_ERROR" => "موجودی حساب اعتباری کافی نیست، لطفا پردخت را به صورت اینترنتی انجام دهید",
	"_CREDITS_ORDER_TITLE" => "عنوان سفارش",
	"_CREDITS_ORDER_ID" => "شماره سفارش",
	"_CREDITS_AMOUNT_IN_RIAL" => "مبلغ را به ریال وارد نمایید",
	"_CREDITS_RECEIPT_DATE" => "تاریخ فیش",
	"_CREDITS_RECEIPT_NUMBER" => "شماره فیش",
	"_CREDITS_RECEIPT_PICTURE_DESC" => "الصاق تصویر فیش الزامی نیست ولی باعث تسریع روند تایید مالی خواهد شد.<br />حداکثر اندازه فایل ارسالی: 204,800 کیلوبایت<br />پسوندهای مجاز برای فایل ارسالی: gif,jpg,jpeg",
	"_CREDIT_DELETE_THIS_TRANSACTION" => "آیا از رد این تراکنش اطمینان دارید ؟ دلیل خود را وارد کنید",
	"_CREDIT_ENTER_REASON" => "دلیل خود را وارد نمایید",
	"_CREDITS_DELETE_ALL_FILTERS" => "حذف کلیه فیلترها",
	"_CREDIT_OFFLINE_FORM_PENDING" => "مشخصات پرداخت شماره %d ثبت شد و در انتظار تأیید می باشد.\nمبلغ: %s\nتاریخ:%s",
	"_CREDIT_OFFLINE_FORM_APPROVED" => "مشخصات پرداخت شماره %d تأیید شد.\nتاریخ:%s",
	"_CREDIT_OFFLINE_FORM_DELETED" => "مشخصات پرداخت شماره %d حذف شد.\nعلت:%s\nتاریخ:%s",
	"_CREDIT_OFFLINE_FORM_FAILED" => "مشخصات پرداخت شماره %d تأیید نشد.\nعلت:%s\nتاریخ:%s",
	"_CREDITS_PAY_BY_CREDIT_SMS" => "برداشت از موجودی حساب اعتباری با موفقیت انجام شد.\nشماره : %d\nمبلغ: %s\nمانده اعتبار: %s\nتاریخ:%s",
	"_CREDITS_PAY_ONLINE_ORDER_SMS" => "پرداخت اینترنتی با موفقیت انجام شد.\nشماره : %d\nمبلغ: %s\nشماره سفارش: %s\nتاریخ:%s",
	"_CREDITS_REWARD_SMS" => "اعطای جایزه اعتباری.\nشماره : %d\nمبلغ: %s\nشماره سفارش: %s\nتاریخ:%s",
	"_CREDITS_PAY_ONLINE_NOORDER_SMS" => "افزایش اعتبار کاربری با موفقیت انجام شد.\nشماره : %d\nمبلغ: %s\nتاریخ:%s",
	"_CREDITS_TRANSFER_SMS" => "انتقال اعتبار.\nاز کاربر : %s\nبه کاربر : %s\nمبلغ: %s\nتاریخ:%s",
	"_CREDITS_CHAREG_SMS" => "افزایش اعتبار.\nبه کاربر : %s\nمبلغ: %s\nتاریخ:%s",
	"_CREDITS_DEDUCTION_SMS" => "کسر اعتبار.\nاز کاربر : %s\nمبلغ: %s\nتاریخ:%s",
	"_CREDITS_SUSPEND_ALL_SMS" => "مسدودی کل اعتبار.\nکاربر : %s\nتاریخ:%s",
	"_CREDITS_SUSPEND_SMS" => "مسدودی قسمتی از اعتبار.\nکاربر : %s\nمبلغ: %s\nتاریخ:%s",
	"_CREDIT_UNSUSPEND_SMS" => "رفع انسداد تراکنش %d.\nکاربر : %s\nمبلغ: %s\nتاریخ:%s",	
	"_CREDITS_EMAIL_OFFLINE_PENDING" => "عملیات واریز غیر آنلاین در سایت %s",
	"_CREDITS_EMAIL_ORDER_OK" => "رسید تأیید پرداخت آنلاین در سایت %s",
	"_CREDITS_EMAIL_ONLINE_OK" => "رسید تأیید افزایش موجودی اعتباری در سایت %s",	
	"_CREDITS_EMAIL_REWARD_OK" => "اعطای جایزه نقدی در سایت %s",
	"_CREDITS_EMAIL_ADMIN_TRANSACTION" => "ایجاد تغییرات در حساب اعتباری در سایت %s",
	"_CREDITS_EMAIL_ADMIN_APPROVE" => "تأیید تراکنش افزایش موجودی در سایت %s",
	"_CREDIT_EMAIL_DED" => "کسر اعتبار"	,
));
?>