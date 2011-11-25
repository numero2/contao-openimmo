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

 
$GLOBALS['TL_LANG']['MOD']['openimmo']  = '<span style="color:#a50042;">Open</span>Immo';
 
 
/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['oi_files']  = array('Datenpools', 'Importdaten verwalten');
$GLOBALS['TL_LANG']['MOD']['oi_objects']= array('Objekte', 'Alle Objekte die in einem Datenpool enthalten sind');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['openimmo']  = 'OpenImmo';
$GLOBALS['TL_LANG']['FMD']['oi_list']   = array('Auflistung', 'Eine Seite mit einer Liste aller OpenImmo Objekten');
$GLOBALS['TL_LANG']['FMD']['oi_reader'] = array('Details', 'Detailliere Ansicht zu einem Objekt');

$GLOBALS['TL_LANG']['tl_module']['oi_reader_rewrite_title'] = array('Ersetze Seitentitel mit Objektname?','Bestimmt ob der Titel der Seite mit dem Namen und der Anschrift des ausgewählten Objekts ersetzt werden soll.');

?>