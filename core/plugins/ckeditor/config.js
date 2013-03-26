/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	config.uiColor = '#CCCCCC';
	
	/*
	config.extraPlugins = 'stylesheetparser';
	config.contentsCss = 'http://localhost/slash-cms/admin/templates/system/css/custom_styles.css';
	config.stylesSet = [];
	*/ 
	config.toolbar = 'SlashCMSToolbar';
	config.toolbar_SlashCMSToolbar =
	[
		{ name: 'document', items : [ 'Source','-','Save','NewPage','Preview','Print','-','Templates' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt','-','Link','Unlink','Anchor'] },
		
	'/',
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },
	'/',
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
		
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] }
		
	];
	
 
	 
};
