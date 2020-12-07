<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (empty($nuke_languages['english']) || !is_array($nuke_languages['english']))
{
	$nuke_languages['english'] = array();
}

$nuke_languages['english'] = array_merge($nuke_languages['english'], array(
	"_USERNAME_NOT_EXISTS" => "نام کاربری معتبر نمی باشد",
	"_MR" => "آقا",
	"_MRS" => "خانم",
	"_USER_ID" => "شماره کاربری",
	"_USERINFO" => "مشخصات کاربری",
	"_REALNAME" => "نام واقعي",
	"_USER_PROFILE" => "پروفایل",
	"_USER_PROFILE_PAGE" => "صفحه پروفایل کاربر",
	"_USER_AVATAR" => "نماد کاربر",
	"_USER_SETTINGS" => "تنظیمات کاربری",
	"_DISPLAY_EMAIL" => "ایمیل نمایشی",
	"_USER_WEBSITE" => "وبلاگ",
	"_USER_GENDER" => "جنسیت",
	"_USER_ADDRESS" => "آدرس",
	"_USER_BIRTHDAY" => "تاریخ تولد",
	"_USER_REGDATE" => "تاریخ عضویت",
	"_USER_LASTVISIT" => "زمان آخرین بازدید",
	"_USER_SIGN" => "امضاء",
	"_USER_INTERESTS" => "علاقه مندی ها",
	"_USER_ABOUT" => "درباره",
	"_USER_GROUP" => "گروه کاربری",
	"_OTHER_USER_GROUP" => "سایر گروههای کاربری",
	"_USER_POINTS" => "امتیاز کاربری",
	"_USER_FAMILYNAME" => "نام و نام خانوادگی",
	"_USER_NOT_FOUND" => "مشخصاتی برای نام کاربری %s یافت نشد",
	"_INCORRECT_USERNAME" => "نام کاربری وارد شده اشتباه می باشد",
	"_LAST_LOGIN_ATTEMPTS_ERROR" => "دفعات مجاز ورود به سایت برای شما به اتمام رسیده است. مجوز ورود بعدی : <br />%s",
	"_SUCCESS_LOGIN_LOG" => "ورود موفق کاربر %s به محیط کاربری",
	"_ERROR_LOGIN_LOG" => "ورود ناموفق به کاربری %s<br />%s",
	"_BAD_LOGIN_ATTEMPTS_ERROR" => "کلمه عبور نادرست است<br />تعداد دفعات ورود نا موفق : %d از %d بار",
	"_USER_SUSPENDED" => "نام کاربری %s موقتاً مسدود می باشد",
	"_USER_UNALLOWED" => "ورود با این نام کاربری مجاز نمی باشد",
	"_USER_NOT_APPROVED" => "نام کاربری %s هنوز مورد تأیید مدیر سایت قرار نگرفته است",
	"_USER_NO_ACTIVATED" => "شما هنوز نام کاربری خود را تأیید نکرده اید. جهت تأیید به ایمیلی که برایتان ارسال گردیده مراجعه نمایید.",
	"_USER_OTHER_REASONS" => "کاربری شما مجوز ورود به سایت را ندارد",
	"_USER_POST_INCORRECT" => "مشخصات وارد شده نادرست است",
	"_USER_OR_PASS_NOT_SENT" => "نام کاربری یا کلمه عبور ارسال نشده است",
	"_USER_LOGIN_TITLE" => "ورود به ناحیه کاربری",
	"_USER_FORGET_PASSWORD" => "کلمه عبور خود را فراموش کرده اید ؟",
	"_USER_NOT_REGISTERED" => "ثبت نام نکرده اید ؟",
	"_USER_REGISTER_INSITE" => "عضویت در سایت",
	"_USER_REGISTRATION_DISABLED" => "سیستم عضویت غیر فعال می باشد",
	"_YACOPPA1" => "کودکان حمايت خواهند شد",
	"_YACOPPA2" => "براي عضويت در سايت شما ميبايستي <b>13</b> سال به بالا سن داشته باشيد",
	"_YACOPPA3" => "آيا سن شما<b>13</b> به بالا ميباشد؟ <small>براي ادامه عضويت پاسخ را انتخاب نمائيد</small>",
	"_YACOPPA4" => "با عرض پوزش براي عضويت در اين سايت شما ميبايستي حداقل 13 سال سن داشته باشيد يا اينکه در صورتي که سن شما زير 13 سال ميباشد اجازه کتبي والدين خود را براي مدير سايت ارسال نمائيد %s.",
	"_YA_CONTINUE" => "ادامه",
	"_YA_GOBACK" => "بازگشت",
	"_CLICK_HERE" => "اينجا کليک کنيد",
	"_YATOS1" => "شرايط و ضوابط",
	"_YATOS2" => "قوانين ما به زودي در اين بخش منتشر خواهد شد",
	"_YATOS3" => "من قوانين را با دقت خوانده ام و قبول دارم",
	"_YATOS4" => "عضويت کاربر جديد مستلزم تائيد قوانين ميباشد",
	"_USER_CONFIRMED" => "موافقم",
	"_USER_NOT_CONFIRMED" => "موافق نیستم",
	"_YATOS5" => "تمام کاربر ميبايستي قوانين و ضوابط سايت را تائيد نمايند",
	"_RESELECT" => "انتخاب مجدد",
	"_INVITATION" => "عضویت فقط با دعوتنامه",
	"_ENTERINCITATIONCODE" => "لطفاً کد مربوط به دعوتنامه خود را در این قسمت وارد نمایید.",
	"_YINVITE1" => "تأییدیه کد دعوتنامه",
	"_YINVITE" => "کد دعوتنامه",
	"_WRONG_CODE" => "کد دعوتنامه وارد شده صحیح نمی باشد.",
	"_REAGENT" => "معرف",
	"_USER_HAS_REGISTERED" => "این نام کاربری قبلاً ثبت شده است",
	"_USER_EMAIL_HAS_SELECTED" => "این ایمیل قبلاً انتخاب شده است",
	"_USER_BAD_EMAIL" => "ایمیل غیر مجاز است",
	"_USER_BAD_NAME" => "نام کاربری غیر مجاز است",
	"_USER_BAD_REALNAME" => "نام واقعی غیر مجاز است",
	"_USER_EMAILS_NOT_EQUALED" => "ایمیلهای وارد شده یکسان نیستند",
	"_REGISTRATIONSUB" => "ايجاد اشتراک جديد %s در سایت %s",
	"_USER_ACTIVATION_EMAIL_SENT" => "با سلام<br />ایمیلی حاوی لینک فعال سازی برای شما ارسال گردیده است. برای تکمیل فرآیند ثبت نام به ایمیل خود مراجعه نمایید",
	"_USER_ADMIN_APPROVE_EMAIL_SENT" => "با سلام<br />عضویت شما در سایت %s نیاز به تأیید مدیر دارد. بعد از فعال سازی حساب کاربری ، ایمیلی برای شما حاوی اطلاعات ورود به سایت ارسال می کردد.",
	"_SOMETHINGWRONG" => "اميدواريم که باعث ناراحتي شما نشده باشيم",
	"_USER_ADDED" => "کاربر %s افزوده شد",
	"_USER_END_OF_REGISTERATION" => "پایان عضویت",
	"_ENTER_USERNAME" => "لطفاً نام کاربری را وارد نمایید",
	"_ENTER_USER_EMAIL" => "لطفاً ایمیل را وارد نمایید",
	"_MIN_USERNAME_LEN" => "نام کابری باید حداقل %d کاراکتر باشد",
	"_MAX_USERNAME_LEN" => "نام کابری باید حداکثر %d کاراکتر باشد",
	"_ENTER_VALID_EMAIL" => "لطفاً یک ایمیل معتبر وارد نمایید",
	"_ENTER_PASSWORD" => "لطفاً کلمه عبور را وارد نمایید",
	"_USER_PASSWORD_MIN_LEN" => "کلمه عبور باید حداقل %s کاراکتر باشد",
	"_USER_PASSWORD_MAX_LEN" => "کلمه عبور باید حداکثر %s کاراکتر باشد",
	"_REENTER_PASSWORD" => "لطفاً کلمه عبور را مجدداً وارد نمایید",
	"_USER_PASSWORDS_NOT_EQUALED" => "کلمات عبور وارد شده یکسان نمی باشند",
	"_USER_PASSWORDS_NOT_EXISTS" => "لطفاً هر دو گزینه را وارد نمایید",
	"_USER_REGISTRATION_TITLE" => "فرم ثبت نام",
	"_RETYPEEMAIL" => "تايپ مجدد ايميل",
	"_RETYPEPASSWORD" => "تايپ مجدد کلمه عبور",
	"_REGISTER" => "ثبت نام",
	"_USER_IS_REGISTERED" => "قبلا ثبت نام کرده اید ؟",
	"_USER_REGENERATE_PASSWORD" => "بازیابی کلمه عبور",
	"_ENTER_EMAIL_OR_USERNAME" => "ایمیل یا نام کاربری خود را وارد نمایید.",
	"_RESTART_RESENT_CODE" => "امکان ارسال مجدد کد وجود ندارد. علیات بازیابی کلمه عبور ر ااز نو شروع کنید",
	"_USER_EMAILS_NOT_EQUALED_TO_USER" => "ایمیل وارد شده با ایمیل کاربر همخوانی ندارد",
	"_USER_REGENERATE_PASS_MESSAGE1" => "شما اخیراً درخواستی جهت تغییر رمز خود داده اید. از کد زیر جهت تغییر آن استفاده کنید",
	"_USER_REGENERATE_PASS_MESSAGE2" => "این کد فقط 24 ساعت از زمان ارسال این پیام معتبر است",
	"_USER_REGENERATE_PASS_MESSAGE3" => "اگر شما این درخواست را صادر نکرده اید این پیام را نادیده بگیرید یا اگر سؤالی دارید با %s تماس بگیرید",
	"_USER_REGENERATE_PASS_MESSAGE4" => "یک ایمیل حاوی کد اعتبار سنجی به ایمیل شما ارسال گردید. لطفاً کد دریافتی را وارد نمایید",
	"_USER_REGENERATE_PASS_MESSAGE5" => "اشتراک کاربری شما در %s فعال شد. هم اکنون به صفحه کاربریتان هدایت خواهید شد.",
	"_USER_REGENERATE_PASS_MESSAGE6" => "اطلاعات ارسالی صحیح نمی باشد",
	"_USER_REGENERATE_PASS_RESEND_CODE" => "ارسال مجدد کد اعتبار سنجی",
	"_THANKSAPPL" => "متشکریم",
	"_USER_REGENERATE_PASS_CODE_EXPIRED" => "این کد منقضی شده است. لطفاً مجدداً تلاش فرمایید",
	"_USER_REGENERATE_PASS_CODE_INCORRECT" => "کد ارسالی نامعتبر است",
	"_USER_REGENERATE_PASS_CODE_NOTFOUND" => "هیچ گونه درخواست بازیابی کلمه عبوری یافت نشد",
	"_USER_NEW_PASSWORD" => "کلمه عبور جدید",
	"_USER_RETYPE_NEW_PASSWORD" => "تکرار کلمه عبور جدید",
	"_USER_PASSWORD_CHANGED" => "کلمه عبور با موفقیت تغییر کرد. شما هم اکنون به صفحه اصلی سایت هدایت می شوید.",
	"_USER_PROFILE_EDIT" => "ویرایش تنظیمات کاربری",
	"_USER_PROFILE_UPDATED" => "اطلاعات کاربری شما ویرایش شد.",
	"_USER_GO_TO_PROFILE_SETTINGS" => "بازگشت به صفحه تنظیمات کاربری",
	"_USER_EDIT_AVATAR" => "ویرایش نماد",
	"_USER_DELETE_AVATAR" => "حذف نماد",
	"_CUSTOM_FIELDS_ERROR" => "لطفاً %s را وارد نمایید",
	"_USER_ABOUT_ME" => "درباره من",
	"_USER_NEWSLETTER" => "ارسال خبر نامه",
	"_USER_VIEW_ONLINE" => "مشاهده وضعیت آنلاین",
	"_USER_CURRENT_PASSWORD" => "کلمه عبور فعلی",
	"_USER_INCORRECT_CURRENT_PASSWORD" => "کلمه عبور فعلی اشتباه است",
	"_USER_AVATAR_TYPE" => "نوع نماد",
	"_USER_AVATAR_UPLOAD" => "آپلود",
	"_USER_AVATAR_DIRECT" => "لینک مستقیم",
	"_USER_AVATAR_GRAVATAR" => "سرویس Gravater",
	"_USER_SELECT_FILE" => "انتخاب فایل",
	"_USER_RESET" => "بازنشانی",
	"_USER_AVATAR_DIMENTIONS" => "حد اکثر اندازه ها; عرض: %d پيکسل, طول: %d پيکسل, حجم فايل: %d",
	"_USER_DIRECT_AVATAR_UPLOAD" => "آپلود از آدرس مستقیم",
	"_USER_DIRECT_AVATAR_UPLOAD_MSG" => "نشاني عكس را وارد كنيد.سيستم آن را از نشاني به سايت كپي مي كند",
	"_USER_DIRECT_AVATAR_LINK" => "لینک مستقیم عکس",
	"_USER_EMAIL_IN_GRAVATAR" => "آدرس ایمیل در Gravatar",
	"_CLOSE_AND_CONTINUE" => "بستن و ادامه",
	"_USER_GROUP_GUESTS" => "مهمانان",
	"_USER_GROUP_REGISTERED" => "اعضای عادی",
	"_USER_GROUP_ADMINISTRATORS" => "مدیران",
	"_USER_CONFIGS" => "تنظمیات کاربری",
	"_USERS_ADMIN" => "مدیریت کاربران",
	"_ADD_USER" => "افزودن کاربر",
	"_GROUPS_ADMIN" => "مدیریت گروه ها",
	"_NORMAL_USERS" => "عادی",
	"_DELETED_USERS" => "حذف شده",
	"_SUSPENDED_USERS" => "مسدود شده",
	"_PENDING_USERS" => "در انتظار تأیید",
	"_SEARCH_BY_USERNAME" => "جستجو بر اساس نام کاربری",
	"_PROMOTEUSER" => "ترفیع کاربر",
	"_SUSPENDUSER" => "معلق کردن کاربر",
	"_APPROVEUSER" => "تائيد کاربر",
	"_RESEND_EMAIL" => "ارسال مجدد ايميل فعال سازي",
	"_USER_EMAIL_ACTIVATION_RESENT" => "ايميل فعال سازي برای کاربر %s مجدداً ارسال گردید.",
	"_NO_USER_FOUND" => "کاربری یافت نشد",
	"_NOT_ACTIVATED_USERS" => "عدم تأیید ایمیل",
	"_VIEW_USER_PROFILE" => "مشاهده اطلاعات کاربری",
	"_USER_DELETED" => "نام کاربری %s حذف شده است.",
	"_USER_DELETE_LOG" => "حذف نام کاربری %s.",
	"_USER_EDIT_LOG" => "ویرایش نام کاربری %s.",
	"_USER_ADD_LOG" => "افزودن نام کاربری %s.",
	"_IS_USER_ADMIN" => "مدیر",
	"_USER_APPROVED" => "تأیید عضویت کاربر %s در سایت %s",
	"_USER_SUSPENDED" => "تعلیق عضویت کاربر %s در سایت %s",
	"_USER_SUSPEND_REASON" => "دلیل تعلیق کاربر ؟",
	"_USER_SUSPEND_EXPIRE_DATE" => "پایان تعلیق (به دقیقه)",
	"_USER_SUSPEND_EXPIRE_DESC" => "کاربر پس از این مدت مجدداً فعال خواهد شد. <b>-1</b> یعنی مسدودی دائم",
	"_YA_ACTIVATEUSER" => "فعال سازي کاربر",
	"_USER_EMAIL_ACTIVATED" => "عضويت کاربر %s در سایت %s فعال شد ، هم اکنون مينوانید وارد سيستم شوید",
	"_YA_CHNGRISK" => "تغيير نام کاربري نوعي ريسک محسوب ميشود",
	"_SORT_USERS_BY" => "محدود سازی نمایش بر اساس لیست کاربران ",
	"_INVALID_AVATAR_LINK" => "لینک آواتار معتبر نیست",
	"_PROBLEM_IN_AVATAR_INFO" => "مشکلی در دریافت اطلاعات آواتار وجود دارد",
	"_AVATAR_HAS_LOW_DIM" => "اندازه آواتار کمتر از حد مجاز است",
	"_AVATAR_NOT_SUPPORTED" => "نوع فایل پشتیبانی نمی شود",
	"_AVATAR_HAS_HIGH_DIM" => "اندازه آواتار بیشتر از حد مجاز است",
	"_GROUPS_ADMIN" => "مدیریت گروه های کاربری",
	"_ADD_NEW_GROUP" => "افزودن گروه جدید",
	"_SEARCH_BY_GROUPNAME" => "جستجو با نام گروه",
	"_EDIT_GROUP" => "ویرایش گروه",
	"_DELETE_GROUP" => "حذف گروه",
	"_NO_GROUP_FOUND" => "گروه کاربری ای یافت نشد",
	"_GROUP_NAME" => "عنوان گروه",
	"_GROUP_TITLE_IN_LANG" => "نام گروه به زبان فعلی",
	"_GROUP_COLOR" => "رنگ گروه کاربری",
	"_SEARCH_BY_GROUPNAME" => "جستجو بر اساس نام گروه",
	"_NO_GROUP_SELECTED" => "هیچ گروهی انتخاب نشده است",
	"_NO_GROUP_FOUND" => "هیچ گروهی یافت نشد",
	"_MUST_SELECT_REPLACE_GROUP" => "چنانچه این گروه دارای عضو باشد باید یک گروه جایگزین انتخاب نمایید",
	"_REPLACED_GROUP" => "گروه جایگزین",
	"_USERS_CONFIGS" => "تنظیمات کاربران",
	"_LOGIN_SIGN_UP_THEME" => "استفاده از قالب جدا برای ورود و عضویت",
	"_ALLOW_REGISTER" => "امکان عضویت",
	"_COPPA_CHECK" => "بررسی سن کاربر",
	"_TOS_CHECK" => "نمایش قوانین",
	"_NICK_MAX" => "حداکثر تعداد کاراکتر نام کاربری",
	"_NICK_MIN" => "حداقل تعداد کاراکتر نام کاربری",
	"_PASS_MAX" => "حداکثر تعداد کاراکتر کلمه عبور",
	"_PASS_MIN" => "حداقل تعداد کاراکتر کلمه عبور",
	"_DOUBLE_CHECK_EMAIL" => "تایپ مجدد ایمیل",
	"_BAD_MAILS" => "ایمیلهای غیر مجاز",
	"_BAD_USERNAMES" => "نام های کاربری غیر مجاز",
	"_BAD_NICKS" => "نامهای واقعی غیر مجاز",
	"_REQUIRE_ADMIN_CONFIRM" => "نیار به تأیید مدیر",
	"_REQUIRE_EMAIL_ACTIVATION" => "نیاز به تأیید ایمیل فعالسازی",
	"_SEND_REGISTER_EMAIL" => "ارسال ایمیل اتمام عضویت",
	"_SEND_REGISTER_EMAIL_TO_ADMIN" => "اعلام عضویت جدید به مدیر",
	"_AVATAR_SALT" => "پیشوند آوارتار های آپلودی",
	"_AVATAR_PATH" => "مسیر آپلود آواتار",
	"_ALLOW_AVATAR" => "اجازه استفاده از آواتار",
	"_ALLOW_AVATAR_UPLOAD" => "اجازه آپلود آواتار",
	"_ALLOW_AVATAR_REMOTE" => "اجازه استفاده از لینک مستیقیم آواتار",
	"_ALLOW_GRAVATAR" => "اجازه استفاده از gravatar",
	"_ALLOW_CHANGE_MAIL" => "اجازه تغییر ایمیل",
	"_AVATAR_MAX_WIDTH" => "حداکثر عرض آواتار",
	"_AVATAR_MIN_WIDTH" => "حداقل عرض آواتار",
	"_AVATAR_MAX_HEIGHT" => "حداکثر ارتفاع آواتار",
	"_AVATAR_MIN_HEIGHT" => "حداقل ارتفاع آواتار",
	"_AVATAR_MAX_FILESIZE" => "حداکثر حجم آواتار",
	"_SITE_MTTOS" => "قوانین سایت",
	"_NOTIFY_METHODS" => "شیوه اطلاع رسانی",
	"_GROUPS" => "گروه های کاربری",
	"_LOGIN_SIGNUP" => "صفحه ورود و عضویت",
	"_USER_REGEN_PASSWORD_SMS" => "کد بازیابی رمز عبور : %s",
	"_USERS_FIELDS_SETTING" => "تنظیمات فیلدهای اضافه",
	"_USER_FILED_NAME" => "نام (انگلیسی)",
	"_USER_FILED_DISPLAY" => "عنوان",
	"_USER_FILED_VALUE" => "مقدار پیشفرض",
	"_USER_FILED_TYPE" => "نوع",
	"_USER_FILED_SIZE" => "اندازه",
	"_USER_FILED_REQUIERD" => "ضروری",
	"_USER_FILED_ACTIVE" => "فعال",	
	"_USER_FILED_SIZE_MAX_ERROR" => "حدامثر تعداد کاراکتر %d می باشد.",	
	"_USER_FILED_SIZE_MIN_ERROR" => "حداقل تعداد کاراکتر %d می باشد.",	
	"_FAIL" => "رد کردن",	
	));
?>