<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'QR_ATTACH_NOTICE'                 => 'This reply contains at least one attachment.',
	'QR_BBCODE'                        => 'BBCode',
	'QR_CANCEL_SUBMISSION'             => 'Cancel submission',
	'QR_CTRL_ENTER'                    => 'You may also submit your reply by simultaneous pressing Ctrl and Enter keys on your keyboard.',
	'QR_FORM_HIDE'                     => 'Collapse quick reply form',
	'QR_FULLSCREEN'                    => 'Fullscreen editor',
	'QR_FULLSCREEN_EXIT'               => 'Exit fullscreen mode',
	'QR_INSERT_TEXT'                   => 'Insert quote in the Quick Reply form',
	'QR_QUICKQUOTE'                    => 'Quick quote',
	'QR_QUICKQUOTE_TITLE'              => 'Reply with quick quote',
	'QR_LOADING'                       => 'Loading',
	'QR_LOADING_ATTACHMENTS'           => 'Waiting for the finish of attachments uploading...',
	'QR_LOADING_NEW_FORM_TOKEN'        => 'The form token was outdated and has been updated.<br />Submitting the form again...',
	'QR_LOADING_NEW_POSTS'             => 'At least one new post has been added to the topic.<br />Your reply has not been submitted because you will probably want to update it.<br />Fetching new posts...',
	'QR_LOADING_PREVIEW'               => 'Fetching the preview...',
	'QR_LOADING_SUBMITTED'             => 'Your post has been submitted successfully.<br />Fetching the result...',
	'QR_LOADING_SUBMITTING'            => 'Submitting your reply...',
	'QR_LOADING_WAIT'                  => 'Waiting for server response...',
	'QR_MORE'                          => 'More actions',
	'QR_NO_FULL_QUOTE'                 => 'Please select a part of the message to be able to quote it.',
	'QR_PREVIEW_CLOSE'                 => 'Close preview block',
	'QR_PROFILE'                       => 'Go to profile',
	'QR_QUICKNICK'                     => 'Refer by username',
	'QR_QUICKNICK_TITLE'               => 'Insert username in the Quick Reply form',
	'QR_REPLY_IN_PM'                   => 'Reply in PM',
	'QR_TYPE_REPLY'                    => 'Type your reply here...',
	'QR_WARN_BEFORE_UNLOAD'            => 'Your entered reply has not been submitted and may be lost!',
	// begin mod Translit
	'QR_TRANSLIT_TEXT'                 => 'Translit',
	'QR_TRANSLIT_TEXT_TO_RU'           => 'to russian', // can be changed to your language here and below
	'QR_TRANSLIT_TEXT_TOOLTIP'         => 'For instant view in Russian click the button',
	'QR_TRANSLIT_FROM'                 => 'je,jo,ayu,ay,aj,oju,oje,oja,oj,uj,yi,ya,ja,ju,yu,ja,y,zh,i\',shch,sch,ch,sh,ea,a,b,v,w,g,d,e,z,i,k,l,m,n,o,p,r,s,t,u,f,x,c,\'e,\',`,j,h', // language specific adaptation required (do not use spaces or line breaks), use commas as separators here and below
	'QR_TRANSLIT_TO'                   => 'э,ё,aю,ай,ай,ою,ое,оя,ой,уй,ый,я,я,ю,ю,я,ы,ж,й,щ,щ,ч,ш,э,а,б,в,в,г,д,е,з,и,к,л,м,н,о,п,р,с,т,у,ф,х,ц,э,ь,ъ,й,х',
	'QR_TRANSLIT_FROM_CAPS'            => 'Yo,Jo,Ey,Je,Ay,Oy,Oj,Uy,Uj,Ya,Ja,Ju,Yu,Ja,Y,Zh,I\',Sch,Ch,Sh,Ea,Tz,A,B,V,W,G,D,E,Z,I,K,L,M,N,O,P,R,S,T,U,F,X,C,EA,J,H',
	'QR_TRANSLIT_TO_CAPS'              => 'Ё,Ё,Ей,Э,Ай,Ой,Ой,Уй,Уй,Я,Я,Ю,Ю,Я,Ы,Ж,Й,Щ,Ч,Ш,Э,Ц,А,Б,В,В,Г,Д,Е,З,И,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Э,Й,Х',
	// end mod Translit
	// begin mod CapsLock Transform
	'QR_TRANSFORM_TEXT'                => 'Change Text Case',
	'QR_TRANSFORM_TEXT_TOOLTIP'        => 'Press a button to change the case of the selected text',
	'QR_TRANSFORM_TEXT_LOWER'          => '&#9660; abc',
	'QR_TRANSFORM_TEXT_UPPER'          => '&#9650; ABC',
	'QR_TRANSFORM_TEXT_INVERS'         => '&#9660;&#9650; aBC',
	'QR_TRANSFORM_TEXT_LOWER_TOOLTIP'  => 'lower case',
	'QR_TRANSFORM_TEXT_UPPER_TOOLTIP'  => 'UPPER CASE',
	'QR_TRANSFORM_TEXT_INVERS_TOOLTIP' => 'iNVERT cASE',
	// end mod CapsLock Transform
));
