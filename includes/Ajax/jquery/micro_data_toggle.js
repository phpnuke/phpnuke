$(document).ready(function(){var selected=$("#_pnmm_type").val();if(selected=="0")
hidden();else expand_default(selected);function hidden(){$(".article").hide();$(".organization").hide();$(".event").hide();$(".person").hide();$(".review").hide();$(".product").hide();$(".software").hide();$(".video").hide()}
function expand_default(selected){hidden();var micometa_class='';switch(selected)
{case"article":micometa_class='.article';break;case"organization":micometa_class='.organization';break;case"event":micometa_class='.event';break;case"person":micometa_class='.person';break;case"review":micometa_class='.review';break;case"product":micometa_class='.product';break;case"software":micometa_class='.software';break;case"video":micometa_class='.video';break}
$(micometa_class).show(500)}
$("#_pnmm_type").change(function(){hidden();var type=$(this).val();var micometa_class='';switch(type)
{case"article":micometa_class='.article';break;case"organization":micometa_class='.organization';break;case"event":micometa_class='.event';break;case"person":micometa_class='.person';break;case"review":micometa_class='.review';break;case"product":micometa_class='.product';break;case"software":micometa_class='.software';break;case"video":micometa_class='.video';break}
$(micometa_class).show(500)})})