<?php

$contents = '';

if(isset($pn_credits_config['credits_direct_msg']) && $pn_credits_config['credits_direct_msg'] != '')
{
	$contents .= OpenTable();
	$contents .= $pn_credits_config['credits_list_msg'];
	$contents .= CloseTable();
}

$contents .= OpenTable(_CREDITS_ADMIN.' - '.$page_title);

$search_query = (isset($search_data) && !empty($search_data)) ? $search_query:"";
$transaction_from_date = (isset($search_data) && !empty($search_data)) ? $transaction_from_date:"";
$transaction_to_date = (isset($search_data) && !empty($search_data)) ? $transaction_to_date:"";

$search_type_sel0 = (isset($search_data) && !empty($search_data) && $search_type == 0) ? "selected":"";
$search_type_sel1 = (isset($search_data) && !empty($search_data) && $search_type == 1) ? "selected":"";
$search_type_sel2 = (isset($search_data) && !empty($search_data) && $search_type == 2) ? "selected":"";
$search_type_sel3 = (isset($search_data) && !empty($search_data) && $search_type == 3) ? "selected":"";
$search_type_sel4 = (isset($search_data) && !empty($search_data) && $search_type == 4) ? "selected":"";
$search_type_sel5 = (isset($search_data) && !empty($search_data) && $search_type == 5) ? "selected":"";

$search_status0 = (isset($search_data) && !empty($search_data) && $search_status == 0) ? "selected":"";
$search_status1 = (isset($search_data) && !empty($search_data) && $search_status == 1) ? "selected":"";
$search_status2 = (isset($search_data) && !empty($search_data) && $search_status == 2) ? "selected":"";
$search_status3 = (isset($search_data) && !empty($search_data) && $search_status == 3) ? "selected":"";

$transaction_type_sel0 = (isset($search_data) && !empty($search_data) && $transaction_type == 0) ? "selected":"";
$transaction_type_sel1 = (isset($search_data) && !empty($search_data) && $transaction_type == 1) ? "selected":"";
$transaction_type_sel2 = (isset($search_data) && !empty($search_data) && $transaction_type == 2) ? "selected":"";
$transaction_type_sel3 = (isset($search_data) && !empty($search_data) && $transaction_type == 3) ? "selected":"";
$transaction_type_sel4 = (isset($search_data) && !empty($search_data) && $transaction_type == 4) ? "selected":"";
$transaction_type_sel5 = (isset($search_data) && !empty($search_data) && $transaction_type == 5) ? "selected":"";


$contents .= "
<style>
.credit_list_box {min-height:400px;}
.add-on .input-group-btn > .btn {
  border-left-width:0;left:-2px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}
/* stop the glowing blue shadow */
.add-on .form-control:focus {
 box-shadow:none;
 -webkit-box-shadow:none; 
 border-color:#cccccc; 
}

/* enable absolute positioning */
.inner-addon {
  position: relative;
}

/* style glyph */
.inner-addon .glyphicon {
  position: absolute;
  padding: 10px;
}

/* align glyph */
.left-addon .glyphicon  { right:  0px;cursor:pointer}

/* align input */
.left-addon .form-control  { padding-right:  30px;}

/* add padding  */
.left-addon input  { padding-left:  30px; }
.credit-search-box{position:relative;width:100%;}
#credit-search-box{position:absolute;width:97%;margin-right:10px;z-index:1;}


.credit_list a.arrow_desc	{
	background: url(admin/template/images/table/table_sort_arrow_desc.gif) right no-repeat;
	color: #94b52c;
}
.credit_list a.arrow_desc:hover	{
	background: url(admin/template/images/table/table_sort_arrow_asc.gif) right no-repeat;
}

.credit_list a.arrow_asc	{
	background: url(admin/template/images/table/table_sort_arrow_asc.gif) right no-repeat;
	color: #94b52c;
}
.credit_list a.arrow_asc:hover	{
	background: url(admin/template/images/table/table_sort_arrow_desc.gif) right no-repeat;
}
.credit_list_box a.del-filters	{
	color:#fff !important;
	margin:0;
}

</style>
<div class=\"col-xs-12 credit_list_box\">
	<form class=\"form-horizontal\" action=\"".LinkToGT("index.php?modname=Credits&op=credits_list")."\" method=\"post\">
		<div class=\"credit-search-box form-group\">
			<div class=\"inner-addon left-addon\">
				<i class=\"glyphicon glyphicon-menu-down\" data-toggle=\"collapse\" data-target=\"#credit-search-box\"></i>
				<input type=\"text\" class=\"form-control\" name=\"search_data[search_query]\" value=\"$search_query\" placeholder=\""._SEARCH."\" />
			</div>
			<div class=\"collapse panel panel-default\" id=\"credit-search-box\">
				<div class=\"panel-body\">
					<div class=\"form-group\">
						<label class=\"control-label col-sm-3\" for=\"search_type\">"._SEARCH_IN.":</label>
						<div class=\"col-sm-9\">
							<select name=\"search_data[search_type]\" id=\"search_type\" style=\"width:100%\" class=\"selectpicker\">
								<option value=\"0\" $search_type_sel0>"._CREDITS_SEARCH_IN_ALL."</option>
								<option value=\"1\" $search_type_sel1>"._CREDITS_SEARCH_IN_TRANSACTION_ID."</option>
								<option value=\"2\" $search_type_sel2>"._CREDITS_SEARCH_IN_ORDER_ID."</option>
								<option value=\"3\" $search_type_sel3>"._CREDITS_SEARCH_IN_TITLE."</option>
								<option value=\"4\" $search_type_sel4>"._CREDITS_SEARCH_IN_DESC."</option>
								<option value=\"5\" $search_type_sel5>"._CREDITS_SEARCH_IN_GATEWAY."</option>
							</select>
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-3\" for=\"search_status\">"._STATUS.":</label>
						<div class=\"col-sm-9\">
							<select name=\"search_data[search_status]\" id=\"search_status\" style=\"width:100%\" class=\"selectpicker\">
								<option value=\"0\" $search_status0>"._CREDITS_SEARCH_IN_ALL."</option>
								<option value=\"1\" $search_status1>"._CREDITS_NORMAL."</option>
								<option value=\"2\" $search_status2>"._CREDITS_PENDING."</option>
								<option value=\"3\" $search_status3>"._CREDITS_CANCELED."</option>
							</select>
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-3\" for=\"transaction_type\">"._CREDIT_DETAILS_TYPE.":</label>
						<div class=\"col-sm-9\">
							<select name=\"search_data[transaction_type]\" id=\"transaction_type\" style=\"width:100%\" class=\"selectpicker\">
								<option value=\"0\" $transaction_type_sel0>"._CREDITS_SEARCH_IN_ALL."</option>
								<option value=\"1\" $transaction_type_sel1>"._CREDITS_DEPOSIT."</option>
								<option value=\"2\" $transaction_type_sel2>"._CREDITS_WITHDRAW."</option>
								<option value=\"3\" $transaction_type_sel3>"._CREDITS_TRANSFER_D."</option>
								<option value=\"4\" $transaction_type_sel4>"._CREDITS_TRANSFER_W."</option>
								<option value=\"5\" $transaction_type_sel5>"._CREDITS_SUSPEND."</option>
							</select>
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-3\">"._CREDIT_DETAILS_DATETIME.":</label>
						<div class=\"col-sm-9\">
							<input type=\"text\" class=\"form-control calendar\" placeholder=\""._FROM."\" name=\"search_data[transaction_from_date]\" value=\"$transaction_from_date\">
							<input type=\"text\" class=\"form-control calendar\" placeholder=\""._TO."\" name=\"search_data[transaction_to_date]\" value=\"$transaction_to_date\"><br />
						</div>
					</div>
					<div class=\"form-group\">        
						<div class=\"col-sm-offset-2 col-sm-9\">
							<button type=\"submit\" class=\"btn btn-default\">"._SEARCH."</button> &nbsp; 
							<a href=\"".LinkToGT("index.php?modname=Credits&op=delete_all_filters")."\" class=\"btn btn-info del-filters\">"._CREDITS_DELETE_ALL_FILTERS."</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
	</form>
	<div class=\"table-responsive credit_list\">
	<table class=\"table table-hover table-striped\">
		<thead>
			<tr>
				<th class=\"text-center\"><a href=\"".LinkToGT("index.php?modname=Credits&op=credits_list&order_by=tid&sort=".$sort_reverse."")."\">"._CODE."".(($order_by == 'tid') ? " <i class=\"glyphicon glyphicon-chevron-$sort_icon\"></i>":"")."</a></th>
				<th class=\"text-center\"><a href=\"".LinkToGT("index.php?modname=Credits&op=credits_list&order_by=type&sort=".$sort_reverse."")."\">"._CREDIT_DETAILS_TYPE."".(($order_by == 'type') ? " <i class=\"glyphicon glyphicon-chevron-$sort_icon\"></i>":"")."</a></th>
				<th class=\"text-center\"><a href=\"".LinkToGT("index.php?modname=Credits&op=credits_list&order_by=amount&sort=".$sort_reverse."")."\">"._CREDIT_DETAILS_AMOUNT."".(($order_by == 'amount') ? " <i class=\"glyphicon glyphicon-chevron-$sort_icon\"></i>":"")."</a></th>
				<th class=\"text-center\"><a href=\"".LinkToGT("index.php?modname=Credits&op=credits_list&order_by=create_time&sort=".$sort_reverse."")."\">"._CREATIONDATE."".(($order_by == 'create_time') ? " <i class=\"glyphicon glyphicon-chevron-$sort_icon\"></i>":"")."</a></th>
				<th class=\"text-center\"><a href=\"".LinkToGT("index.php?modname=Credits&op=credits_list&order_by=status&sort=".$sort_reverse."")."\">"._STATUS."".(($order_by == 'status') ? " <i class=\"glyphicon glyphicon-chevron-$sort_icon\"></i>":"")."</a></th>
			</tr>
		</thead>
		<tbody>";
			foreach($rows as $row)
			{
				$tid = $row['tid'];
				$amount = number_format($row['amount'], 0);
				$create_time = nuketimes($row['create_time'], true, true, false, 1);
				$status = $row['status'];
				$type = $row['type'];
				$title = $row['title'];
				$description = $row['description'];
				$order_id = $row['order_id'];
				$order_link = $row['order_link'];
				$order_data = ($row['order_data'] != '') ? phpnuke_unserialize($row['order_data']):"";
				
				$amount = ($amount == 0) ? "-":"<span style=\"color:".credits_get_type_color($type).";\">$amount".credits_get_type_icon($type)."</span>";
				$status_desc = '';
				switch($status)
				{
					case _CREDIT_STATUS_NORMAL:
						$status_desc = "<i class=\"glyphicon glyphicon-remove\" style=\"color:red;\"></i>";
					break;
					case _CREDIT_STATUS_OK:
						$status_desc = "<i class=\"glyphicon glyphicon-ok\" style=\"color:green;\"></i>";
					break;
					case _CREDIT_STATUS_PENDING:
						$status_desc = "<i class=\"glyphicon glyphicon-time\" style=\"color:pink;\"></i>";
					break;
					case _CREDIT_STATUS_CANCELED:
						$status_desc = "<i class=\"glyphicon glyphicon-remove\" style=\"color:orange;\"></i>";
					break;
					case _CREDIT_STATUS_FAILED:
						$status_desc = "<i class=\"glyphicon glyphicon-remove\" style=\"color:red;\"></i>";
					break;
				}

				$type_desc = credits_get_type_desc($type);
				
				if($order_id != 0 && $order_link != '' & !empty($order_data))
				{
					$order_desc = "<br />".$order_data['part_desc']." - "._CODE." : <a href=\"$order_link\" target=\"_blank\">$order_id</a><br />$description";
				}
				else
				{
					$order_desc = "<br />$description";
				}
				
				$contents .="<tr>
					<td class=\"text-center\"><a href=\"".LinkToGT("index.php?modname=Credits&op=credit_view&tid=$tid")."\" data-toggle=\"modal\" data-target=\"#sitemodal\">$tid</a></td>
					<td>$type_desc - ".$title."".$order_desc."</td>
					<td class=\"text-center\">$amount</td>
					<td class=\"text-center\">$create_time</td>
					<td class=\"text-center\">$status_desc</td>
				</tr>";
			}
		$contents .="</tbody>
	</table>
	</div>
	<div class=\"text-center\">$pagination</div>
</div>
<script>
	$(document).ready(function(){
		$(\".credit-search-box i\").on('click',function(){
			$(this).toggleClass(function() {
			  if ( $( this ).hasClass( \".glyphicon-menu-up\" ) ) {
				return \"glyphicon-menu-down\";
			  } else {
				return \"glyphicon-menu-up\";
			  }
			});
		});
	});
</script>";
$contents .= CloseTable();
?>