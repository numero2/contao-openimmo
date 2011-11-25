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


class ModuleOpenImmoCEObject extends Module {


	protected $strTemplate = 'ce_oi_object';


    public function __construct( $oDB ) {
    
        parent::__construct($oDB);

        $this->oMOI = NULL;
        $this->oMOI = new ModuleOpenImmo();
    }


	/**
	 * Generate module
	 */
	protected function compile() {

        $this->Template = new FrontendTemplate($this->strTemplate);
        
        $objectID = NULL;
        $objectID = $this->oi_objectid;
        
        $aObject = array();
        $aPool = array();
        
        if( !empty($objectID) || $this->oi_random_object ) {
        
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
                                
                                    if( $immobilie['verwaltung_techn'][0]['objektnr_intern'][0]['_data'] == '{'.$objectID.'}' || $this->oi_random_object ) {
                                    
                                        #echo '<pre>'.print_r($immobilie,1).'</pre>';
                                        $aObject = $immobilie;
                                        $aPool = $pool;
                                        break 3;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        $this->Template->entry = $aObject;
        $this->Template->pool = $aPool;
	}
}

?>