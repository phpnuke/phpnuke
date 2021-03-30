<?php
/**
 *
 * This file is part of the PHP-NUKE Software package.
 *
 * @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('NUKE_FILE')) {
    die("You can't access this file directly...");
}

function show_micro_data_inputs($micro_data)
{
    $html_tags = [
        "text" =>
            "<input class=\"{PNM_CLASS_NAME}\" type=\"text\" name=\"micro_data[{PNM_SCHEMA}][{PNM_KEY}]\" id=\"_pnmm_{PNM_SCHEMA}_{PNM_KEY}\" value=\"{VALUE}\" size=\"40\" />",
        "textarea" =>
            "<textarea class=\"{PNM_CLASS_NAME}\" name=\"micro_data[{PNM_SCHEMA}][{PNM_KEY}]\" id=\"_pnmm_{PNM_SCHEMA}_{PNM_KEY}\" cols=\"60\" rows=\"4\">{VALUE}</textarea>",
        "number" =>
            "<input class=\"{PNM_CLASS_NAME}\" type=\"number\" min=\"{PNM_MIN}\" max=\"{PNM_MAX}\" name=\"micro_data[{PNM_SCHEMA}][{PNM_KEY}]\" id=\"_pnmm_{PNM_SCHEMA}_{PNM_KEY}\" value=\"{VALUE}\" size=\"40\" />",
    ];

    $micro_data_box = [
        // NewsArticle
        "article" => [
            "headline" => [
                "label" => _POST_TITLE,
                "class" => "inp-form article",
                "type" => "text",
                "bubble" => _ENTER_POST_TITLE,
            ],
            "desc" => [
                "label" => _BRIEF_DESCRIPTION,
                "class" => "inp-form article",
                "type" => "textarea",
                "bubble" => _ENTER_BRIEF_DESCRIPTION,
            ],
            "datePublished" => [
                "label" => _PUBLISH_DATE,
                "class" => "inp-form article",
                "type" => "text",
                "bubble" =>
                    "" .
                    _PUBLISH_DATE_FORMAT .
                    " <span dir=\"ltr\">yyyy-mm-dd</span>",
            ],
            "image" => [
                "label" => _POST_IMAGE,
                "class" => "inp-form article",
                "type" => "text",
                "bubble" => _ENTER_POST_IMAGE,
            ],
            "ratingValue" => [
                "label" => _POST_RATE,
                "class" => "inp-form article",
                "type" => "number",
                "min" => 1,
                "max" => 5,
                "bubble" => _POST_RATE_DESCRIPTION,
            ],
        ],
        // NewsArticle

        //organization
        "organization" => [
            "name" => [
                "label" => _TITLE,
                "class" => "inp-form organization",
                "type" => "text",
            ],
            "streetAddress" => [
                "label" => _AVENUE,
                "class" => "inp-form organization",
                "type" => "text",
            ],
            "addressLocality" => [
                "label" => _CITY,
                "class" => "inp-form organization",
                "type" => "text",
            ],
            "addressRegion" => [
                "label" => _PROVINCE_OR_CITY,
                "class" => "inp-form organization",
                "type" => "text",
            ],
            "addressRegion" => [
                "label" => _POSTAL_CODE,
                "class" => "inp-form organization",
                "type" => "text",
            ],
            "telephone" => [
                "label" => _PHONE,
                "class" => "inp-form organization",
                "type" => "text",
            ],
        ],
        //organization

        //event
        "event" => [
            "event_name" => [
                "label" => _TITLE,
                "class" => "inp-form event",
                "type" => "text",
            ],
            "description" => [
                "label" => _BRIEF_DESCRIPTION,
                "class" => "inp-form event",
                "type" => "textarea",
            ],
            "startDate" => [
                "label" => _START_DATE,
                "class" => "inp-form event",
                "type" => "textarea",
            ],
            "endDate" => [
                "label" => _END_DATE,
                "class" => "inp-form event",
                "type" => "textarea",
            ],
            "place_name" => [
                "label" => _PLACE_NAME,
                "class" => "inp-form event",
                "type" => "text",
            ],
            "streetAddress" => [
                "label" => _AVENUE,
                "class" => "inp-form event",
                "type" => "text",
            ],
            "addressLocality" => [
                "label" => _CITY,
                "class" => "inp-form event",
                "type" => "text",
            ],
            "addressRegion" => [
                "label" => _PROVINCE_OR_CITY,
                "class" => "inp-form event",
                "type" => "text",
            ],
            "addressRegion" => [
                "label" => _POSTAL_CODE,
                "class" => "inp-form event",
                "type" => "text",
            ],
        ],
        //event

        //Person
        "person" => [
            "name" => [
                "label" => _TITLE,
                "class" => "inp-form person",
                "type" => "text",
            ],
            "jobTitle" => [
                "label" => _JOB,
                "class" => "inp-form person",
                "type" => "text",
            ],
            "addressLocality" => [
                "label" => _CITY,
                "class" => "inp-form person",
                "type" => "text",
            ],
            "addressRegion" => [
                "label" => _PROVINCE_OR_CITY,
                "class" => "inp-form person",
                "type" => "text",
            ],
            "telephone" => [
                "label" => _PHONE,
                "class" => "inp-form person",
                "type" => "text",
            ],
        ],
        //Person

        //Review
        "review" => [
            "itemReviewed_name" => [
                "label" => _REVIEWED_POST,
                "class" => "inp-form review",
                "type" => "text",
            ],
            "author_name" => [
                "label" => _REVIEWER,
                "class" => "inp-form review",
                "type" => "text",
            ],
            "datePublished" => [
                "label" => _PUBLISH_DATE,
                "class" => "inp-form review",
                "type" => "text",
                "bubble" =>
                    "" .
                    _PUBLISH_DATE_FORMAT .
                    " <span dir=\"ltr\">yyyy-mm-dd</span>",
            ],
            "description" => [
                "label" => _BRIEF_DESCRIPTION,
                "class" => "inp-form review",
                "type" => "textarea",
            ],
            "ratingValue" => [
                "label" => _POST_RATE,
                "class" => "inp-form review",
                "type" => "number",
                "min" => 1,
                "max" => 5,
                "bubble" => _POST_RATE_DESCRIPTION,
            ],
        ],
        //Review

        //Product
        "product" => [
            "brand" => [
                "label" => _PRODUCT_BRAND,
                "class" => "inp-form product",
                "type" => "text",
            ],
            "name" => [
                "label" => _PRODUCT_NAME,
                "class" => "inp-form product",
                "type" => "text",
            ],
            "image" => [
                "label" => _PRODUCT_IMAGE_URL,
                "class" => "inp-form product",
                "type" => "text",
            ],
            "description" => [
                "label" => _BRIEF_DESCRIPTION,
                "class" => "inp-form product",
                "type" => "textarea",
            ],
            "productID" => [
                "label" => _PRODUCT_ID,
                "class" => "inp-form product",
                "type" => "text",
            ],
            "priceCurrency" => [
                "label" => _CURRENCY,
                "class" => "inp-form product",
                "type" => "text",
            ],
            "price" => [
                "label" => _PRODUCT_PRICE,
                "class" => "inp-form product",
                "type" => "text",
            ],
            "ratingValue" => [
                "label" => _POST_RATE,
                "class" => "inp-form product",
                "type" => "number",
                "min" => 1,
                "max" => 5,
                "bubble" => _POST_RATE_DESCRIPTION,
            ],
        ],
        //Product

        //Software
        "software" => [
            "name" => [
                "label" => _SOFTWARE_NAME,
                "class" => "inp-form software",
                "type" => "text",
            ],
            "applicationCategory" => [
                "label" => _CATEGORY,
                "class" => "inp-form software",
                "type" => "text",
                "bubble" => _EX_SOFTWARE,
            ],
            "operatingSystem" => [
                "label" => _OPERATION_SYSTEM,
                "class" => "inp-form software",
                "type" => "text",
                "bubble" => _OPEARTION_SYS_EX,
            ],
            "priceCurrency" => [
                "label" => _CURRENCY,
                "class" => "inp-form software",
                "type" => "text",
            ],
            "price" => [
                "label" => _SOFTWARE_PRICE,
                "class" => "inp-form software",
                "type" => "text",
            ],
            "ratingValue" => [
                "label" => _POST_RATE,
                "class" => "inp-form software",
                "type" => "number",
                "min" => 1,
                "max" => 5,
                "bubble" => _POST_RATE_DESCRIPTION,
            ],
        ],
        //Software

        //Video
        "video" => [
            "name" => [
                "label" => _VIDEO_NAME,
                "class" => "inp-form video",
                "type" => "text",
            ],
            "description" => [
                "label" => _BRIEF_DESCRIPTION,
                "class" => "inp-form video",
                "type" => "textarea",
            ],
            "thumbnailUrl" => [
                "label" => _THUMB_IMAGE_URL,
                "class" => "inp-form video",
                "type" => "text",
            ],
            "uploadDate" => [
                "label" => _PUBLISH_DATE,
                "class" => "inp-form video",
                "type" => "text",
                "bubble" =>
                    "" .
                    _PUBLISH_DATE_FORMAT .
                    " <span dir=\"ltr\">yyyy-mm-dd</span>",
            ],
            "duration" => [
                "label" => _DURATION,
                "class" => "inp-form video",
                "type" => "text",
                "bubble" => _DURATION_FORMAT,
            ],
            "contentUrl" => [
                "label" => _VIDEO_URL,
                "class" => "inp-form video",
                "type" => "text",
            ],
            "ratingValue" => [
                "label" => _POST_RATE,
                "class" => "inp-form video",
                "type" => "number",
                "min" => 1,
                "max" => 5,
                "bubble" => _POST_RATE_DESCRIPTION,
            ],
        ],
        //Video
    ];

    if (is_array($micro_data) && !empty($micro_data)) {
        foreach ($micro_data as $key => $values) {
            if ($key == "_pnmm_type") {
                continue;
            }
            if (
                isset($micro_data['_pnmm_type']) &&
                $key != $micro_data['_pnmm_type']
            ) {
                unset($micro_data[$key]);
            }
        }
    }
    $sel1 = isset($micro_data['article']) ? "selected" : "";
    $sel2 = isset($micro_data['organization']) ? "selected" : "";
    $sel3 = isset($micro_data['event']) ? "selected" : "";
    $sel4 = isset($micro_data['person']) ? "selected" : "";
    $sel5 = isset($micro_data['review']) ? "selected" : "";
    $sel6 = isset($micro_data['product']) ? "selected" : "";
    $sel7 = isset($micro_data['software']) ? "selected" : "";
    $sel8 = isset($micro_data['video']) ? "selected" : "";

    $micro_data_inputs =
        "<tr>
		<td colspan=\"2\">
			<select class=\"styledselect-select\" name=\"micro_data[_pnmm_type]\" id=\"_pnmm_type\">
				<option value=\"0\">" .
        _SPECIFY_POST_ABOUT .
        "</option>
				<option value=\"article\" $sel1>" .
        _ARTICLES .
        "</option>
				<option value=\"organization\" $sel2>" .
        _ORGANIZATION .
        "</option>
				<option value=\"event\" $sel3>" .
        _EVENT .
        "</option>
				<option value=\"person\" $sel4>" .
        _PERSON .
        "</option>
				<option value=\"review\" $sel5>" .
        _REVIEW .
        "</option>
				<option value=\"product\" $sel6>" .
        _PRODUCT .
        "</option>
				<option value=\"software\" $sel7>" .
        _SOFTWARE .
        "</option>
				<option value=\"video\" $sel8>" .
        _VIDEO .
        "</option>
			</select>
		</td>
	</tr>";

    foreach ($micro_data_box as $schema => $data) {
        foreach ($data as $key => $value) {
            $tag_value = isset($micro_data[$schema][$key])
                ? $micro_data[$schema][$key]
                : "";
            $bubble_show = isset($micro_data_box[$schema][$key]['bubble'])
                ? bubble_show($micro_data_box[$schema][$key]['bubble'])
                : "";
            $min = isset($value['min']) ? $value['min'] : 1;
            $max = isset($value['max']) ? $value['max'] : 2;

            $html_tag = str_replace(
                [
                    "{PNM_CLASS_NAME}",
                    "{PNM_SCHEMA}",
                    "{PNM_KEY}",
                    "{VALUE}",
                    "{PNM_MIN}",
                    "{PNM_MAX}",
                ],
                [$value['class'], $schema, $key, $tag_value, $min, $max],
                $html_tags[$value['type']]
            );

            $micro_data_inputs .=
                "<tr class=\"$schema\">
				<th style=\"width:18%\">
					<label for=\"_pnmm_" .
                $schema .
                "_" .
                $key .
                "\">" .
                $value['label'] .
                "</label>
				</th>
				<td>
					$html_tag
					$bubble_show
				</td>
			</tr>";
        }
    }
    return $micro_data_inputs;
}

?>
