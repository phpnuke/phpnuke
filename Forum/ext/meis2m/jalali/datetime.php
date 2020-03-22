<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

namespace meis2m\jalali;

/**
* phpBB custom extensions to the PHP DateTime class
* This handles the relative formats phpBB employs
*/
class datetime extends \phpbb\datetime
{
	/**
	* Formats the current date time into the specified format
	*
	* @param string $format Optional format to use for output, defaults to users chosen format
	* @param boolean $force_absolute Force output of a non relative date
	* @return string Formatted date time
	*/
	public function format($format = '', $force_absolute = false)
	{
		$format		= $format ? $format : $this->user->date_format;
		$format		= self::format_cache($format, $this->user);
		$relative	= ($format['is_short'] && !$force_absolute);
		$now		= new self($this->user, 'now', $this->user->timezone);

		$timestamp	= $this->getTimestamp();
		$now_ts		= $now->getTimeStamp();

		$delta		= $now_ts - $timestamp;

		if ($relative)
		{
			/*
			* Check the delta is less than or equal to 1 hour
			* and the delta not more than a minute in the past
			* and the delta is either greater than -5 seconds or timestamp
			* and current time are of the same minute (they must be in the same hour already)
			* finally check that relative dates are supported by the language pack
			*/
			if ($delta <= 3600 && $delta > -60 &&
				($delta >= -5 || (($now_ts / 60) % 60) == (($timestamp / 60) % 60))
				&& isset($this->user->lang['datetime']['AGO']))
			{
				return $this->user->lang(array('datetime', 'AGO'), max(0, (int) floor($delta / 60)));
			}
			else
			{
				$midnight = clone $now;
				$midnight->setTime(0, 0, 0);

				$midnight	= $midnight->getTimestamp();

				if ($timestamp <= $midnight + 2 * 86400)
				{
					$day = false;

					if ($timestamp > $midnight + 86400)
					{
						$day = 'TOMORROW';
					}
					else if ($timestamp > $midnight)
					{
						$day = 'TODAY';
					}
					else if ($timestamp > $midnight - 86400)
					{
						$day = 'YESTERDAY';
					}

					if ($day !== false)
					{
						// Format using the short formatting and finally swap out the relative token placeholder with the correct value
						return str_replace(self::RELATIVE_WRAPPER . self::RELATIVE_WRAPPER, $this->user->lang['datetime'][$day], strtr(parent::format($format['format_short']), $format['lang']));
					}
				}
			}
		}

		list($gregorian_year, $gregorian_month, $gregorian_day) = explode('-', parent::format('Y-m-d', $this->getTimestamp()));
		list($jalali_year, $jalali_month, $jalali_day) = $this->gregorian_to_jalali($gregorian_year, $gregorian_month, $gregorian_day);


		return strtr(@parent::format('D ', $this->getTimestamp()), $format['lang']).
		$jalali_day . ' ' . $this->givemonth($jalali_month) . ' ' . $jalali_year.
		strtr(@parent::format(', g:i a', $this->getTimestamp()), $format['lang']);
	}

	protected function div($a, $b)
	{
		return (int) ($a / $b);
	}

	protected function gregorian_to_jalali($g_y, $g_m, $g_d)
	{
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$gy = $g_y-1600;
		$gm = $g_m-1;
		$gd = $g_d-1;
		$g_day_no = 365*$gy+$this->div($gy+3,4)-$this->div($gy+99,100)+$this->div($gy+399,400);
		for ($i=0; $i < $gm; ++$i)
			$g_day_no += $g_days_in_month[$i];
		if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
			/* leap and after Feb */
			$g_day_no++;
		$g_day_no += $gd;
		$j_day_no = $g_day_no-79;
		$j_np = $this->div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
		$j_day_no = $j_day_no % 12053;
		$jy = 979+33*$j_np+4*$this->div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */
		$j_day_no %= 1461;
		if ($j_day_no >= 366) {
			$jy += $this->div($j_day_no-1, 365);
			$j_day_no = ($j_day_no-1)%365;
		}
		for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
			$j_day_no -= $j_days_in_month[$i];
		$jm = $i+1;
		$jd = $j_day_no+1;
		return array($jy, $jm, $jd);
	}

	// http://en.wikipedia.org/wiki/Iranian_calendars#Zoroastrian_calendar
	protected function givemonth($givemonth)
	{
		if ($givemonth == 1) {
			$persianm = 'فروردین';
		} elseif ($givemonth == 2) {
			$persianm = 'اردیبهشت';
		}  elseif ($givemonth == 3) {
			$persianm = 'خرداد';
		}  elseif ($givemonth == 4) {
			$persianm = 'تیر';
		}  elseif ($givemonth == 5) {
			$persianm = 'مرداد';
		}  elseif ($givemonth == 6) {
			$persianm = 'شهریور';
		}  elseif ($givemonth == 7) {
			$persianm = 'مهر';
		}  elseif ($givemonth == 8) {
			$persianm = 'آبان';
		}  elseif ($givemonth == 9) {
			$persianm = 'آذر';
		}  elseif ($givemonth == 10) {
			$persianm = 'دی';
		}  elseif ($givemonth == 11) {
			$persianm = 'بهمن';
		}  elseif ($givemonth == 12) {
			$persianm = 'اسفند';
		}

		return $persianm;
	}
}