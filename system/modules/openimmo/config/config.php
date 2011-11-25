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
 * Back end modules
 */
if( TL_MODE == 'BE' ) {

    array_insert($GLOBALS['BE_MOD']['openimmo'], 1, array(

        'oi_files'      => array(
            'tables'    => array('tl_oi_files')
        ,   'icon'      => 'system/modules/openimmo/html/icon_oi.png'
        )
    ,   'oi_objects'    => array(
            'callback'  => 'ModuleOpenImmoObjects'
        ,   'icon'      => 'system/modules/openimmo/html/icon_building.png'
        )
    ));	
	
	$GLOBALS['TL_CSS'][] = 'system/modules/openimmo/html/openimmo_be.css';
}
 
 
/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD'], 2, array(
	'openimmo' => array (
		'oi_list'   => 'ModuleOpenImmoList'
    ,   'oi_reader' => 'ModuleOpenImmoReader'
	)
));


/**
 * Content elements
 */
array_insert($GLOBALS['TL_CTE']['openimmo'], 3, array(
	'oi_object'         => 'ModuleOpenImmoCEObject'
,   'oi_objects_map'    => 'ModuleOpenImmoCEObjectsMap'
,   'oi_searchform'     => 'ModuleOpenImmoCESearchForm'
));


/**
 * Register hooks
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('ModuleOpenImmo', 'replaceInsertTags');
?>