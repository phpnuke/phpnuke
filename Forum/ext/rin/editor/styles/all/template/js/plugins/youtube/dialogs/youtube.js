/*
 Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
*/
CKEDITOR.dialog.add("youtube",function(c){return{title:c.lang.youtube.title,minWidth:270,minHeight:120,contents:[{id:"youtube",label:c.lang.youtube.title,elements:[{type:"text",id:"youtube",label:RinEditor["Video URL:"],"default":""}]}],onOk:function(){var a=this.getValueOf("youtube","youtube"),b="[youtube]";if(a)var d=a.match(/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/),a=d&&11==d[2].length?d[2]:a.replace(/^[^v]+v.(.{11}).*/,"$1"),b=b+a+"[/youtube]";else b+="[/youtube]";MyBBEditor.insertText(b,
"",""+c.name+"_2")}}});