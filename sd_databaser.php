<?php
/**
* @package			SD Databaser V2.5.0 for SEBLOD 3.x - www.seblod.com
* @license 			GNU General Public License version 2 or later
* @author       	Simon Dowdles - http://www.simondowdles.com
* @copyright		Copyright (C) 2013 Simon Dowdles New Media Holdings (Pty) Ltd. All Rights Reserved.
**/

// No Direct Access
defined( '_JEXEC' ) or die;

// Plugin
class plgCCK_Field_TypoSd_Databaser extends JCckPluginTypo
{
	protected static $type	=	'sd_databaser';
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Prepare
		
	// onCCK_Field_TypoPrepareContent
	// Content is only PREPARED at this stage!!!
	public function onCCK_Field_TypoPrepareContent( &$field, $target = 'value', &$config = array() )
	{
		if ( self::$type != $field->typo ) {
			return;
		}
		
		// Prepare
		$typo	=	parent::g_getTypo( $field->typo_options );
		
		// Set
		if ( $typo->get( 'sd_databaser_sql_query', '' ) == 'clear' ) {
			$field->display	=	0;
			$field->type	=	'';
		} else {
			if ( $field->typo_label ) {
				$field->label	=	self::_typo( $typo, $field, '', $config );
			}
			$field->typo		=	self::_typo( $typo, $field, '', $config );
		}
	}
	
	// _typo
	// This is where the actual application of the typo value is applied
	protected static function _typo( $typo, $field, $value, &$config = array() )
	{
		$options2 = JCckDev::fromJSON( $field->typo_options );
		
		$out = "";
		$sql = @$options2['sd_databaser_sql_query'];
		$column = @$options2['sd_databaser_column_as_value'];
		$sdSeparator = @$options2['sd_databaser_separator'];
		$sdSeparater = !$sdSeparater ? '' : $sdSeparater;
		$sdRecordSeparator = @$options2['sd_databaser_record_separator'];
		$sdRecordSeparator = !$sdRecordSeparator ? '' : $sdRecordSeparator;
		

		if($column == '' || $column == NULL || empty($column)){
			return $field->value;
		}
		
		// Find and replace the term *value* with the fields value...
		$sdPattern = '/(\*value\*)/';
		$sdReplace = $field->value;
		$sql = addslashes(preg_replace($sdPattern, $sdReplace, $sql));
		
		// Is the user making use of multiple columns to get back the value?
		// pre-buffer columns, set it to empty string
		$columns = "";
		
		if(preg_match('/||/',$column)){
			$columns = explode('||',$column);
		}
		
		// Get a datbase object
		$sdDBO = JFactory::getDBO();
		$sdDBO->setQuery($sql);
		$sdDBO->execute();
		$sdDBOResult = $sdDBO->loadObjectList();
		
		if(!is_array($columns)){
			foreach($sdDBOResult as $sdResult):
				$out .= $sdResult->$column;
			endforeach;
		}elseif(is_array($columns)){ // we have more than one column being used as the return value...
			if(!function_exists('cleanColumns')){
				function cleanColumns(&$key, &$value){
					$key = strip_tags(trim(filter_var($key, FILTER_SANITIZE_STRING)));
				}
			}
			array_walk($columns, 'cleanColumns');
			foreach($sdDBOResult as $sdResult){
				foreach($columns as $theColumn){
					$out .= $sdResult->$theColumn.$sdSeparator;
				}
				if($sdSeparator > ''){
					$out = substr($out, 0, -strlen($sdSeparator));
				}
				$out = $out.$sdRecordSeparator;
			}

			if($sdRecordSeparator > ''){
				$out = substr($out, 0, -strlen($sdRecordSeparator));
			}
			
			//$out = substr($out, 0, -strlen($sdSeparator));
		}else{
			return $field->value;
		}

		return $out;
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Special Events
	
	// onCCK_Field_TypoBeforeRenderContent
	// Content is about to be RENDERED, so the above step has already happened
	public static function onCCK_Field_TypoBeforeRenderContent( $process, &$fields, &$storages, &$config = array() )
	{
		$name	=	$process['name'];
		
		if ( count( $process['matches'][1] ) ) {
			foreach ( $process['matches'][1] as $k=>$v ) {
				$fieldname				=	$process['matches'][2][$k];
				$target					=	strtolower( $v );
				$fields[$name]->typo	=	str_replace( $process['matches'][0][$k], $fields[$fieldname]->{$target}, $fields[$name]->typo );
			}
		}
	}
}
?>