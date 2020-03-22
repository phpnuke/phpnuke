$(document).ready(function(){if(search_data!=[])
{search_data=typeof search_data==='object'?search_data:JSON.parse(search_data);search_categories(search_data,first_module,selected_category);$("#search_module").change(function()
{var module=$(this).val();search_categories(search_data,module,selected_category);if(search_data[module].time_field!='')
$("#search_time_html").fadeIn();else $("#search_time_html").fadeOut();if(search_data[module].author_field!='')
$("#search_author_html").fadeIn();else $("#search_author_html").fadeOut()})}});function search_categories(search_data,module_name,selected_category)
{if(search_data[module_name].category_field!='')
{$("#search_category_html").fadeIn();$.post(phpnuke_url+"index.php?modname=Search",{op:"search_categories",selected_category:selected_category,module:module_name,csrf_token:pn_csrf_token},function(data,status){$("#search_category").html('<option value="0">'+search_language.all_categories+'</option>'+data)})}
else $("#search_category_html").fadeOut().find('select').html('<option value="0" selected>'+search_language.all_categories+'</option>')}