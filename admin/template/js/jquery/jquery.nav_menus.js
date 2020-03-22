$(document).ready(function()
{
	// Open single
	$('.nav_select').beefup({
		openSingle: true,
		animation: 'fade',
		openSpeed: 400,
		closeSpeed: 400,
	});
	
	$('ul.categories_select').checktree();
	
	var nestable_output = $('#nestable').data('output', $('#nestable-output'));
	
	var updateOutput = function(e)
	{
		var list   = e.length ? e : $(e.target),
			output = list.data('output');
		if (window.JSON) {
			output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
		} else {
			output.val('JSON browser support required for this demo.');
		}
	};
	
	var saveOutput = function(e)
	{
		echo_message('', true, false);
		var final_items = $("#nestable-output").val();
		var general_title = $("#nav_title").val();
		var nav_location = $("#nav_location").val();
		var nav_status = 0;
		$(".nav_status").each(function(){
			if($(this).is(":checked"))
				nav_status = $(this).val();
		});
		
		var lang_titles = {};
		$(".nav_titles").each(function(){
			var lang_name = $(this).data('language');
			var lang_value = $(this).val();
			lang_titles[lang_name] = lang_value;
		});
		
		final_items = typeof final_items === 'object' ? final_items : JSON.parse(final_items);
		$.post(admin_file+".php",
		{
			op				: "nav_menus_admin",
			nav_id			: nav_id,
			update			: "ok",
			nav_fields		: {nav_location, general_title, lang_titles, nav_status, final_items},
			mode			: 'save_nav',
			csrf_token		: pn_csrf_token
		},
		function(response, status)
		{
			response = typeof response === 'object' ? response : JSON.parse(response);
			echo_message(response, false, false);
		});
	};
	
	// activate Nestable for list
	$('#nestable').nestable({
		group: 1,
		itemUniqueId: 'nid',
		itemOptionsHtml: item_options_html,
		maxDepth: max_depth
	})
	.on('change', updateOutput);
	
	// output initial serialised data
	updateOutput(nestable_output);

	$('#nestable-menu').on('click', function(e)
	{
		var target = $(e.target),
			action = target.data('action');
			
		if (action === 'expand-all')
			$('.dd').nestable('expandAll');
			
		if (action === 'collapse-all')
			$('.dd').nestable('collapseAll');

		if (action === 'save-nav')
			saveOutput(nestable_output);
	});
	
	$('.menu-actions').on('click', function(e)
	{
		var target = $(e.target),
			action = target.data('action');

		if (action === 'remove')
			$('.dd').nestable('remove', {"id": 4});
	});
	
	$('.add_menu').on('click', function(e)
	{
		var target = $(e.target),
			action = target.data('action');

		var nav_menu_fields = {};
		
		if(action == 'custom')
		{
			var custom_menu_title = $("#custom_menu_title").val();
			
			nav_menu_fields["type"]		= 'custom';
			nav_menu_fields["url"]		= $("#custom_menu_url").val();
			nav_menu_fields["title"]	= custom_menu_title;
		}
		
		if(action == 'categories')
		{
			var categories_ids = {};
			var categories_module = $(this).closest(".beefup__body").find('ul.categories_select').data('module');
			var categories_list = $(this).closest(".beefup__body").find('.categories_select input');
			$.each(categories_list, function(key, value){
				if($(this).is(":checked"))
					categories_ids[$(this).val()] = $(this).data('label');
			});
			nav_menu_fields["type"]			= 'categories';
			nav_menu_fields["module"]		= categories_module;
			nav_menu_fields["categories"]	= categories_ids;
		}
		
		$.post(""+admin_file+".php?op=nav_menus_admin",
		{
			mode	: 'add_nav_menu',
			nav_id	: nav_id,
			nav_menu_fields	: nav_menu_fields,
			csrf_token	: pn_csrf_token
		},
		function(response, status)
		{
			response = typeof response === 'object' ? response : JSON.parse(response);
			$.each(response.message, function(key, value){
				$('.dd').nestable('add', {"id": key, "text": value.title, "title": value.title, "text": value.title, "url": value.url, "type": value.type, "module": value.module, "part_id": value.part_id, "status": value.status, "attributes": value.attributes});
			});
			updateOutput($('#nestable').data('output', $('#nestable-output')));
			e.preventDefault();
		});
	});

});