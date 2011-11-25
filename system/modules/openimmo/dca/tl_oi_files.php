<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  numero2 2010 
 * @author     numero2 
 * @package    openimmo
 * @license    GNU/LGPL  
 * @filesource
 */


/**
 * Table tl_oi_files
 */ 
$GLOBALS['TL_DCA']['tl_oi_files'] = array (

	'config' => array (
		'dataContainer'             => 'Table'
	)
,   'list' => array (
		'sorting' => array(
			'mode'                  => 0
        ,   'fields'                => array('name')
        ,   'flag'                  => 10
		)
    ,   'label' => array(
			'fields'                => array('name')
        ,   'format'                => '%s'
        ,   'label_callback'        => array('tl_oi_files','getLabel')
		)
    ,   'global_operations' => array()
    ,   'operations' => array(
			'edit' => array(
				'label'             => &$GLOBALS['TL_LANG']['tl_oi_files']['edit']
            ,   'href'              => 'act=edit'
            ,   'icon'              => 'edit.gif'
			)
        ,   'delete' => array(
				'label'             => &$GLOBALS['TL_LANG']['tl_oi_files']['delete']
            ,   'href'              => 'act=delete'
            ,   'icon'              => 'delete.gif'
            ,   'attributes'        => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			)
        ,   'show' => array(
				'label'             => &$GLOBALS['TL_LANG']['tl_oi_files']['show']
            ,   'href'              => 'act=show'
            ,   'icon'              => 'show.gif'
			)
        ,   'toggle' => array(
				'label'             => &$GLOBALS['TL_LANG']['tl_oi_files']['toggle']
            ,   'icon'              => 'visible.gif'
            ,   'attributes'        => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"'
            ,   'button_callback'   => array('tl_oi_files', 'toggleIcon')
			)
        ,   'objects' => array(
				'label'             => &$GLOBALS['TL_LANG']['tl_oi_files']['objects']
            ,   'href'              => 'do=oi_objects'
            ,   'icon'              => 'system/modules/openimmo/html/icon_building.png'
            ,   'button_callback'   => array('tl_oi_files', 'checkObjects')
			)            
		)
	)
,   'palettes' => array(
		'__selector__'              => array('')
    ,   'default'                   => '{common_settings},name,path;{publish_legend},jumpTo,enabled;'
	)
,   'subpalettes' => array(
		''                          => ''
	)
,   'fields' => array(
		'name' => array(
			'label'                 => &$GLOBALS['TL_LANG']['tl_oi_files']['name']
        ,   'exclude'               => false
        ,   'inputType'             => 'text'
        ,   'eval'                  => array('mandatory'=>true, 'maxlength'=>255)
		)
    ,   'path' => array(
			'label'                 => &$GLOBALS['TL_LANG']['tl_oi_files']['path']
        ,   'exclude'               => false
        ,   'inputType'             => 'fileTree'
        ,   'eval'                  => array('mandatory'=>true, 'files'=>true, 'fieldType'=>'radio', 'filesOnly' => true, 'extensions' => 'xml')
        ,   'save_callback'         => array( array('tl_oi_files','validateXML') )
		)
    ,   'jumpTo' => array(
			'label'                 => &$GLOBALS['TL_LANG']['tl_oi_files']['jumpTo']
        ,   'exclude'               => true
        ,   'inputType'             => 'pageTree'
        ,   'eval'                  => array('fieldType'=>'radio', 'tl_class'=>'clr')
		)
    ,   'enabled' => array(
			'label'                 => &$GLOBALS['TL_LANG']['tl_oi_files']['enabled']
        ,   'exclude'               => false
        ,   'inputType'             => 'checkbox'
		)        
	)
);


class tl_oi_files extends Backend {


	public function __construct() {

		parent::__construct();
		$this->import('BackendUser', 'User');
        
        $this->mOI = new ModuleOpenImmo();
	}


	/**
	 * Button Callback to enable / disable the objects button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */		
	public function checkObjects($row, $href, $label, $title, $icon, $attributes) {
    
        $isValid = $this->mOI->validateXML($row['path']);
  
        if( !$isValid ) {
            return $this->generateImage(preg_replace('/\.png$/i', '_.png', $icon));
        } else {
            return '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
        }
    }
    
    
    /**
    * tl_oi_files::validateXML
    * Checks if the given xml file contains valid OpenImmo XML Data
    *
    * @param fileName
    * @returns bool
    */
    public function validateXML( $fileName=NULL ) {

        if( !empty($fileName) ) {
        
            $isValid = $this->mOI->validateXML($fileName);
      
            if( !$isValid ) {
                throw new Exception( $GLOBALS['TL_LANG']['ERR']['tl_oi_files']['noOpenImmoXML'] );
            }
        }

        return $fileName;
    }

    
    /**
    * tl_oi_files::getLabel
    * Returns the label for the given entry
    *
    * @param aEntry
    * @returns string
    */    
    public function getLabel( $aEntry=array() ) {
    
        if( empty($aEntry) )
            return false;

        $isValid = $this->mOI->validateXML($aEntry['path']);
  
        if( !$isValid ) {
            $sLabel = sprintf(
                $GLOBALS['TL_LANG']['tl_oi_files']['numRecordsError']
            ,   0
            ,   $aEntry['name']
            );
        } else {
            $numRecords = 0;
            $numRecords = $this->mOI->countEntries($aEntry['path']);
            
            $sLabel = sprintf(
                $GLOBALS['TL_LANG']['tl_oi_files']['numRecords']
            ,   $numRecords
            ,   $aEntry['name']
            );
        }
        
        return $sLabel;
    }
    
    
	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes) {


		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if( !$row['enabled'] ){
            $icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
    
    
	/**
	 * Disable/enable a pool
	 * @param integer
	 * @param boolean
	 */
    public function toggleVisibility( $id, $state ) {
    
        $this->mOI->toggleEnabled( $id, $state );
    }
}
?>