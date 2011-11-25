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


class ModuleOpenImmoCESearchForm extends Module {


	protected $strTemplate = 'ce_oi_searchform';
    protected $aRadians = array(10,25,50,100);

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

        $oi_sf_plz = array();
        
        // get pools
        $aPools = array();
        $aPools = $this->oMOI->getEnabledPools();
        
        if( !empty($aPools) ) {
        
            foreach( $aPools as $pool ) {

                $aEntries = $this->oMOI->getEntriesByPoolId($pool['id']);
                
                if( !empty($aEntries['anbieter']) ) {
                    foreach( $aEntries['anbieter'] as $anbieter ) {
                        foreach( $anbieter['immobilie'] as $immobilie ) {

                            $oi_sf_plz[ $immobilie['geo'][0]['plz'][0]['_data'] ] = $immobilie['geo'][0]['ort'][0]['_data'];
                        }
                    }
                }
            }
        }

        // set post url
        $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->oi_searchform_jumpto);
        
        $sPostURL = NULL;
        $sPostURL = $this->oMOI->generatePageURLWithParams($objPage,"objekt=");

        $this->Template->postURL = $sPostURL;
        $this->Template->aRadians = $this->aRadians;

        $this->Template->oi_sf_plz_vals = $oi_sf_plz;
        $this->Template->oi_sf_plz_selected = $this->Input->post('oi_sf_plz');
        $this->Template->oi_sf_radius = $this->Input->post('oi_sf_radius');
        $this->Template->oi_sf_radius_km = $this->Input->post('oi_sf_radius_km');
	}
}

?>