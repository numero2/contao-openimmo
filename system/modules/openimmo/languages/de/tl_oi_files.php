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
 * Fields 
 */ 
$GLOBALS['TL_LANG']['tl_oi_files']['name'] = array('Bezeichnung', 'Bitte eine Bezeichnung für den Datenpool eingeben (z.B. Luxusvillen in Miami)'); 
$GLOBALS['TL_LANG']['tl_oi_files']['path'] = array('XML Datei', 'Bitte wählen Sie die entsprechende XML Datei aus'); 
$GLOBALS['TL_LANG']['tl_oi_files']['jumpTo'] = array('Weiterleitungsseite', 'Bitte wählen Sie auf welche Seite der Benutzer weitergeleitet werden soll');
$GLOBALS['TL_LANG']['tl_oi_files']['enabled'] = array('Datenpool veröffentlichen', 'Klicken Sie hier um die Daten aus diesem Pool öffentlich sichtbar zu machen');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_oi_files']['common_settings'] = 'Allgemeine Einstellungen';
$GLOBALS['TL_LANG']['tl_oi_files']['publish_legend'] = 'Veröffentlichung';


/** 
 * Buttons 
 */ 
$GLOBALS['TL_LANG']['tl_oi_files']['new'] = array('Neuer Datenpool', 'Einen neuen Datenpool anlegen'); 
$GLOBALS['TL_LANG']['tl_oi_files']['edit'] = array('Editieren', 'Datenpool ID %s editieren'); 
$GLOBALS['TL_LANG']['tl_oi_files']['copy'] = array('Datenpool kopieren', 'Den Datenpool in die Zwischenablage kopieren'); 
$GLOBALS['TL_LANG']['tl_oi_files']['delete']= array('Datenpool löschen', 'Den Datenpool aus der Liste entfernen'); 
$GLOBALS['TL_LANG']['tl_oi_files']['show'] = array('Details', 'Details des Datenpools ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_oi_files']['toggle'] = array('Veröffentlichen', 'Diesen Datenpool veröffentlichen / nicht veröffentlichen');
$GLOBALS['TL_LANG']['tl_oi_files']['objects'] = array('Objekte', 'Alle Objekte in diesem Datenpool anzeigen');

$GLOBALS['TL_LANG']['tl_oi_files']['numRecords']  = '<span style="color:#AAA;">[%d Einträge]</span> %s';
$GLOBALS['TL_LANG']['tl_oi_files']['numRecordsError']  = '<span style="color:#AAA;">[%d Einträge]</span> %s - <span style="color:#cc5555;">Fehler beim Lesen der XML Datei!</span>';


/** 
 * Error Messages
 */ 
$GLOBALS['TL_LANG']['ERR']['tl_oi_files']['noOpenImmoXML'] = "Die Datei beinhaltet keinerlei OpenImmo XML Daten";
 
?>