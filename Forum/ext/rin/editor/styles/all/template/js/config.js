/*
 Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
*/
CKEDITOR.editorConfig=function(a){(CKEDITOR.env.mobile||CKEDITOR.env.iOS)&&parseInt(rinmobsms)&&(rinstartupmode="source");a.toolbarGroups=[{name:"basicstyles"},{name:"align"},{name:"styles"},{name:"colors",groups:["colors","cleanup"]},{name:"insert"},{name:"list"},{name:"blocks",groups:["blocks","clipboard"]},{name:"extra",groups:["extra","extradesc"]},{name:"undo"},{name:"document",groups:["tools","mode"]}];language=rinlanguage;removePlugins=rinautosave;removeButtons="Cut,Copy,Paste,Anchor,BGColor,indent,"+
rinrmvbut+"";removeDialogTabs="link:advanced";height=rinheight;fontSize_sizes=fontsizes;smiley_images=dropdownsmiliesurl.concat(dropdownsmiliesurlmore);smiley_descriptions=dropdownsmiliesname.concat(dropdownsmiliesnamemore);smiley_name=dropdownsmiliesdes.concat(dropdownsmiliesdesmore);smiley_path=smileydirectory;smiley_sc=rinsmileysc;autosave_saveDetectionSelectors='input[name*\x3d"post"],input[name*\x3d"save"],input[name*\x3d"preview"]';autosave_message=rinautosavemsg;imgurClientId=rinimgur;disableNativeSpellChecker=
!1;skin=rinskin};