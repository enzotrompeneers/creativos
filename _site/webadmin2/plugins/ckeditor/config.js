/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'en';
	config.width = '95%';
	config.height = '260px';
	config.allowedContent = true; 
	
	
	config.toolbar = 
 [
		{ name: 'undo',	   items: [ 'Undo', 'Redo','Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ] },	
 		{ name: 'basicstyless',   items: [ 'Bold','Italic','Underline','Subscript', 'Superscript', '-', 'RemoveFormat'] },	
		{ name: 'paragraph', items : [ 'BulletedList','NumberedList'] },
		{ name: 'links', items: [ 'Link','Unlink','Anchor' ] },	
 																	
 		{ name: 'styles', items: [ 'Format'] },
 		{ name: 'extras', items: [ 'Table', 'SpecialChar'] },
 		{ name: 'source', items: [ 'Source' ] }
		
	];
	config.width = '90%';

};
