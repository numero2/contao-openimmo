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


class ModuleOpenImmoReader extends Module {


	protected $strTemplate = 'mod_oi_reader';


    public function __construct( $oDB ) {
    
        parent::__construct($oDB);

        $this->oMOI = NULL;
        $this->oMOI = new ModuleOpenImmo();
        
        $this->loadLanguageFile('tl_oi_labels'); 
    }


	/**
	 * Generate module
	 */
	protected function compile() {

        $this->Template = new FrontendTemplate($this->strTemplate);
        
        $objectID = NULL;
        $objectID = $this->Input->get('objekt');
        
        $aObject = array();
        $aPool = array();
        
        if( !empty($objectID) ) {
        
            // get pools
            $aPools = array();
            $aPools = $this->oMOI->getEnabledPools();
            
            // get objects
            $aObjects = array();
            
            // find our currently selected object
            if( !empty($aPools) ) {
            
                foreach( $aPools as $pool ) {

                    $aEntries = $this->oMOI->getEntriesByPoolId($pool['id']);
                    
                    if( !empty($aEntries['anbieter']) ) {
                        foreach( $aEntries['anbieter'] as $anbieter ) {
                            foreach( $anbieter['immobilie'] as $immobilie ) {
                                
                                if( !empty($immobilie['verwaltung_techn'][0]['objektnr_intern'][0]['_data']) ) {
                                
                                    if( $immobilie['verwaltung_techn'][0]['objektnr_intern'][0]['_data'] == '{'.$objectID.'}' ) {
                                        $aObject = $immobilie;
                                        $aPool = $pool;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // object not found? throw 404
        if( empty($aObject) ) {
        
			$obj404 = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE type='error_404' AND published=1 AND pid=?")->limit(1)->execute($this->getRootIdFromUrl());
            $a404 = $obj404->fetchAssoc();

            if( !empty($a404) ) {
            
                $this->redirect( $this->generateFrontendUrl($a404), 404);            
                return;

            } else {
            
                header('HTTP/1.1 404 Not Found');
                die('Page not found');
            }        
        }
        
        //overwrite page title
        if( $this->oi_reader_rewrite_title && !empty($aObject['freitexte'][0]['objekttitel'][0]['_data']) ) {
        
            $GLOBALS['objPage']->pageTitle = $aObject['freitexte'][0]['objekttitel'][0]['_data'];
            
            if( !empty($aObject['geo'][0]['plz'][0]['_data']) )
                $GLOBALS['objPage']->pageTitle .= ' in '.$aObject['geo'][0]['plz'][0]['_data'];
                
            if( !empty($aObject['geo'][0]['ort'][0]['_data']) )
                $GLOBALS['objPage']->pageTitle .= ' in '.$aObject['geo'][0]['ort'][0]['_data'];
        }
        
        $this->Template->entry = $aObject;
        $this->Template->pool = $aPool;
        $this->Template->backLink = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
	}
}

?>