<?php
/**
 * Tipos - General CRUD manager for CMS
 *
 * Processes URI requests
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Tipos;


class InputType
{
	public static function getType($metadata)
	{
		$field 		= $metadata['field'];
		$comment 	= $metadata['comment'];
		
		$aType 		= explode('(', $metadata['dataType']);
		$type		= $aType[0];
		
		// Date
		if ($comment == 'fecha') {
			return 'date';
		}
		
		// File
		if ($comment == 'file') {
			return 'file';
		}
		
		// Textarea, editable
		if ($comment == 'noedit') {
			return 'textarea-noedit';
		}
		
		// Textarea, editable
		if ($type == 'text') {
			return 'textarea';
		}
		
		// Checkbox
		if ($comment == 'checkbox') {
			return 'checkbox';
		}
		
		// Foreign key
		if (strpos($field, '_id')) {
			return 'select';
		}
		
		// ENUM select
		if (strpos($comment, 'options:') !== false) {
			return 'enum';
		}
		
		// Number
		if ($type == 'int') {
			return 'number';
		}
		
		// Default text
		return 'text';
		
	}
}







// End of file