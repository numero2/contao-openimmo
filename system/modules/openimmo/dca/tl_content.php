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
 * oi_object CE
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'oi_object';
$GLOBALS['TL_DCA']['tl_content']['palettes']['oi_object'] = '{title_legend},type;{oi_legend},oi_random_object,oi_objectid;{expert_legend:hide},cssID';

$GLOBALS['TL_DCA']['tl_content']['fields']['oi_objectid'] = array (
	'label'         => $GLOBALS['TL_LANG']['tl_content']['oi_objectid']
,   'exclude'       => false
,   'inputType'     => 'text'
,   'eval'          => array('maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['oi_random_object'] = array (
	'label'         => $GLOBALS['TL_LANG']['tl_content']['oi_random_object']
,   'exclude'       => false
,   'inputType'     => 'checkbox'
);


/**
 * oi_objects_map CE
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'oi_objects_map';
$GLOBALS['TL_DCA']['tl_content']['palettes']['oi_objects_map'] = '{title_legend},type;{oi_legend},oi_objects_map_size_crop,oi_objects_map_size_full;{expert_legend:hide},cssID';

$GLOBALS['TL_DCA']['tl_content']['fields']['oi_objects_map_size_crop'] = array (
	'label'         => $GLOBALS['TL_LANG']['tl_content']['oi_objects_map_size_crop']
,   'exclude'       => false
,   'inputType'     => 'text'
,   'default'       => '130x115'
,   'eval'          => array('maxlength'=>7, 'mandatory'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['oi_objects_map_size_full'] = array (
	'label'         => $GLOBALS['TL_LANG']['tl_content']['oi_objects_map_size_full']
,   'exclude'       => false
,   'inputType'     => 'text'
,   'default'       => '512x512'
,   'eval'          => array('maxlength'=>7, 'mandatory'=>true, 'tl_class'=>'w50')
);


/**
 * oi_searchform CE
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'oi_searchform';
$GLOBALS['TL_DCA']['tl_content']['palettes']['oi_searchform'] = '{title_legend},type;{oi_legend},oi_searchform_jumpto;{expert_legend:hide},cssID';

$GLOBALS['TL_DCA']['tl_content']['fields']['oi_searchform_jumpto'] = array (
    'label'         => &$GLOBALS['TL_LANG']['tl_content']['oi_searchform_jumpto']
,   'exclude'       => true
,   'inputType'     => 'pageTree'
,   'eval'          => array('fieldType'=>'radio', 'tl_class'=>'clr')
);
?>