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


class ModuleOpenImmoList extends Module {


	protected $strTemplate = 'mod_oi_list';


    public function __construct( $oDB ) {
    
        parent::__construct($oDB);

        $this->oMOI = NULL;
        $this->oMOI = new ModuleOpenImmo();
    }

    protected function _getRadiusSearchPoint( $address=NULL ) {
    
        if( empty($address) )
            return false;
            
        // send geo request
        $sContents = "";
        $sContents = @file_get_contents('http://maps.google.com/maps/geo?q='.rawurlencode($address).'&output=json&oe=utf8&sensor=false&key=your_api_key&hl=de');
        
        if( !empty($sContents) ) {
        
            $aData = json_decode($sContents,1);
            
            if( !empty($aData['Status']) && $aData['Status']['code'] == 200 ) {
            
                $aReturn = array(
                    'lat' => $aData['Placemark'][0]['Point']['coordinates'][1]
                ,   'lon' => $aData['Placemark'][0]['Point']['coordinates'][0]
                ,   'name'=> $aData['Placemark'][0]['address']
                );            
            
                return $aReturn;
            }
        }
        
        return false;
    }
    

	/**
	 * Generate module
	 */
	protected function compile() {        
        
        $this->Template = new FrontendTemplate($this->strTemplate);
        
        // get pools
        $aPools = array();
        $aPools = $this->oMOI->getEnabledPools();
        $this->Template->pools = $aPools;
        
        // get objects
        $aObjects = array();
        
        $hasMatchingObjects = false;
        
        if( !empty($aPools) ) {
        
            // check if we have a radius search
            $sf_radius_point = $this->_getRadiusSearchPoint( $this->Input->post('oi_sf_radius') );
            $sf_radius_value = $this->Input->post('oi_sf_radius_km');
            
            if( !empty($sf_radius_point['name']) ) {
                $this->Input->setPost('oi_sf_radius',$sf_radius_point['name']);
            }
        
            foreach( $aPools as $pool ) {

                $aEntries = $this->oMOI->getEntriesByPoolId($pool['id']);
                
                if( !empty($aEntries) ) {

                    foreach( $aEntries['anbieter'][0]['immobilie'] as $iI => $iV ) {
                    
                        // filter entries by vermarktungsart
                        if( $this->Input->get('typ') ) {
                            foreach( $iV['objektkategorie'][0]['vermarktungsart'][0]['_attributes'] as $vaI => $vaV ) {
                            
                                $validType = false;
                            
                                switch( $this->Input->get('typ') ) {
                                
                                    case 'miete' :                                        
                                        $validType = in_array($vaI,array('MIETE_PACHT','ERBPACHT'));
                                    break;
                                    
                                    case 'kauf' :
                                        $validType = in_array($vaI,array('KAUF','LEASING'));
                                    break;
                                }
                                
                                if( !$validType ) {
                                    unset($aEntries['anbieter'][0]['immobilie'][$iI]);
                                    continue 2;
                                }
                            }
                        }
                        
                        // filter by specific city
                        if( $this->Input->post('oi_sf_plz') ) {
                        
                            foreach( $aEntries['anbieter'][0]['immobilie'] as $iI => $iV ) {
                            
                                if( $iV['geo'][0]['plz'][0]['_data'] != $this->Input->post('oi_sf_plz') ) {
                                    unset($aEntries['anbieter'][0]['immobilie'][$iI]);
                                    continue 2;
                                }
                            }
                        }
                        
                        // filter by radius
                        if( !empty($sf_radius_point) && !empty($sf_radius_value) ) {
                        
                            foreach( $aEntries['anbieter'][0]['immobilie'] as $iI => $iV ) {                               
                                
                                $geo = !empty($iV['geo'][0]['geokoordinaten'][0]['_attributes']) ? $iV['geo'][0]['geokoordinaten'][0]['_attributes'] : NULL;
                                
                                // does the object have coordinates?
                                if( !empty($geo['breitengrad']) && !empty($geo['laengengrad']) ) {
                                
                                    $fDistance = 0;
                                    $fDistance = $this->_distance(
                                        $geo['breitengrad']
                                    ,   $geo['laengengrad']
                                    ,   $sf_radius_point['lat']
                                    ,   $sf_radius_point['lon']
                                    );
                                    
                                    // is the distance within the allowed radius?
                                    if( $fDistance <= $sf_radius_value ) {
                                    
                                        // add the distance
                                        $aEntries['anbieter'][0]['immobilie'][$iI]['__contao__']['distance'] = $fDistance;
                                    } else {
                                        unset($aEntries['anbieter'][0]['immobilie'][$iI]);
                                        continue 2;
                                    }

                                } else {
                                    unset($aEntries['anbieter'][0]['immobilie'][$iI]);
                                    continue 2;
                                }
                            }
                        }
                        
                        $hasMatchingObjects = true;
                    }
                }
                
                $aObjects[] = $aEntries;
            }
        }
        
        $this->Template->typ = $this->Input->get('typ');
        $this->Template->mietURL = $this->addToUrl('typ=miete');
        $this->Template->kaufURL = $this->addToUrl('typ=kauf');
        $this->Template->entries = $aObjects;
        $this->Template->hasMatchingObjects = $hasMatchingObjects;
        $this->Template->sf_radius_point = $sf_radius_point;
	}
    
    
    /**
    * ModuleOpenImmoList::_distance
    *
    * Calculates the geographical distance between two points (in kilometers)
    * @param lat1 Latitude of point 1
    * @param lng1 Longitude of point 1
    * @param lat2 Latitude of point 2
    * @param lng2 Longitude of point 2
    * @returns float
    **/
    protected function _distance( $lat1, $lng1, $lat2, $lng2 ) {

        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return $km;
    }
}

?>