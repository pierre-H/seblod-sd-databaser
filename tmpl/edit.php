<?php
/**
* @package			SD Databaser V2.5.0 for SEBLOD 3.x - www.seblod.com
* @license 			GNU General Public License version 2 or later
* @author       	Simon Dowdles - http://www.simondowdles.com
* @copyright		Copyright (C) 2013 Simon Dowdles New Media Holdings (Pty) Ltd. All Rights Reserved.
**/
// No Direct Access
defined( '_JEXEC' ) or die;

JCckDev::initScript( 'typo', $this->item );
// Add my own CSS
$options2 =	JCckDev::fromJSON( $this->item->options2 );
?>

<div class="seblod">
	<?php echo JCckDev::renderLegend( JText::_( 'COM_CCK_CONSTRUCTION' ), JText::_( 'PLG_CCK_FIELD_TYPO_'.$this->item->name.'_DESC' ) ); ?>
    <ul class="adminformlist adminformlist-2cols">
		<?php
			// Get the main SQL text area
			echo JCckDev::renderForm( 'sd_databaser_sql', @$options2['sd_databaser_sql'], $config, array( 'label'=>'SD_SQL', 'storage_field'=>'sd_databaser_sql_query') );
			// Get the field that allows you to specify a column name to use as value
			echo JCckDev::renderForm( 'sd_databaser_column_as_value', @$options2['sd_databaser_column_as_value'], $config, array( 'label'=>'SD_COLUMN_AS_VALUE', 'storage_field'=>'sd_databaser_column_as_value', 'style' => 'border:2px solid #666;float:left;clear:none;' ) );
			// Get the field that determines the separator
			echo JCckDev::renderForm( 'sd_databaser_separator', @$options2['sd_databaser_separator'], $config, array( 'label'=>'SD_SEPARATOR', 'storage_field'=>'sd_databaser_separator', 'style' => 'border:2px solid #666;float:left;clear:none;' ));
			// Get the field that determines the separator
			echo JCckDev::renderForm( 'sd_databaser_record_separator', @$options2['sd_databaser_record_separator'], $config, array( 'label'=>'SD_RECORD_SEPARATOR', 'storage_field'=>'sd_databaser_record_separator', 'style' => 'border:2px solid #666;float:left;clear:none;' ));
		?>
    </ul>
</div>