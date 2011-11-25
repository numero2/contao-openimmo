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


class ModuleOpenImmoCEObjectsMap extends Module {


	protected $strTemplate = 'ce_oi_objects_map';
    protected $strMarkerCacheFile = 'ce_oi_objects_map_makers';
    
    protected $maxCacheTime = 86400;


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

        $aMarkers = $this->_getCachedMarkers();
        
        // get pools
        $aPools = array();
        $aPools = $this->oMOI->getEnabledPools();
        
        // find our currently selected object
        if( empty($aMarkers) && !empty($aPools) ) {
        
            foreach( $aPools as $pool ) {

                $aEntries = $this->oMOI->getEntriesByPoolId($pool['id']);
                
                if( !empty($aEntries['anbieter']) ) {
                    foreach( $aEntries['anbieter'] as $anbieter ) {
                        foreach( $anbieter['immobilie'] as $immobilie ) {
                        
                            if( !empty($immobilie['geo'][0]['plz'][0]['_data']) && !empty($immobilie['geo'][0]['ort'][0]['_data']) ) {
                        
                                $q = sprintf(
                                    "%s %s"
                                ,   $immobilie['geo'][0]['plz'][0]['_data']
                                ,   $immobilie['geo'][0]['ort'][0]['_data']
                                );
                        
                        
                                if( !empty($immobilie['geo'][0]['geokoordinaten'][0]['_attributes']['breitengrad']) && !empty($immobilie['geo'][0]['geokoordinaten'][0]['_attributes']['laengengrad']) ) {
                                
                                        $aMarkers[] = array(
                                            'lat'   => $immobilie['geo'][0]['geokoordinaten'][0]['_attributes']['laengengrad']
                                        ,   'lon'   => $immobilie['geo'][0]['geokoordinaten'][0]['_attributes']['breitengrad']
                                        ,   'name'  => $immobilie['freitexte'][0]['objekttitel'][0]['_data']
                                        ,   'addr'  => $immobilie['geo'][0]['plz'][0]['_data'].' '.$immobilie['geo'][0]['ort'][0]['_data']
                                        ,   'img'   => $immobilie['__contao__']['mainImage']
                                        ,   'link'  => $immobilie['__contao__']['detailLink']
                                        );
                                
                                } else{
                                
                                    // send geo request
                                    $sContents = "";
                                    $sContents = file_get_contents('http://maps.google.com/maps/geo?q='.rawurlencode($q).'&output=json&oe=utf8&sensor=false&key=your_api_key&hl=de');
                                    
                                    if( !empty($sContents) ) {
                                    
                                        $aData = json_decode($sContents,1);
                                        
                                        if( !empty($aData['Status']) && $aData['Status']['code'] == 200 && in_array((int)$aData['Placemark'][0]['AddressDetails']['Accuracy'],array(4,5,6)) )
                                            $aMarkers[] = array(
                                                'lat'   => $aData['Placemark'][0]['Point']['coordinates'][0]
                                            ,   'lon'   => $aData['Placemark'][0]['Point']['coordinates'][1]
                                            ,   'name'  => $immobilie['freitexte'][0]['objekttitel'][0]['_data']
                                            ,   'addr'  => $immobilie['geo'][0]['plz'][0]['_data'].' '.$immobilie['geo'][0]['ort'][0]['_data']
                                            ,   'img'   => $immobilie['__contao__']['mainImage']
                                            ,   'link'  => $immobilie['__contao__']['detailLink']
                                            );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // store marker data in cache
        $this->_cacheMarkers($aMarkers);
        
        // create marker string for mapurl
        $sMarkerParams = "";
        if( !empty($aMarkers) ) {
            
            foreach( $aMarkers as $p ) {
                $sMarkerParams .= '&amp;markers=color:red|color:red|'.$p['lon'].','.$p['lat'];
            }
        }

        $this->Template->mapMakers = !empty($sMarkerParams) ? $sMarkerParams : NULL;
        $this->Template->aMapMakers = !empty($aMarkers) ? $aMarkers : NULL;
        $this->Template->cropSize = $this->oi_objects_map_size_crop;
        $this->Template->fullSize = $this->oi_objects_map_size_full;
	}


    /**
    * ModuleOpenImmoCEObjectsMap::_getCachedMarkers
    *
    * Loads the markers from our cache file (if available and still valid)
    * @returns array
    **/
    protected function _getCachedMarkers() {

        $objFile = new File('system/tmp/'.$this->strMarkerCacheFile);
        
        $sContents = "";
        $sContents = $objFile->getContent();
        
        $aContents = array();
        $aContents = @unserialize($sContents);
        
        // check if cachetime expired
        if( (int)(time()-$objFile->ctime) > (int)$this->maxCacheTime ) {

            $b= $objFile->delete();
            return array();
        }

        $objFile->close();        

        return !empty($aContents) ? $aContents : array();
    }


    /**
    * ModuleOpenImmoCEObjectsMap::_cacheMarkers
    *
    * Writes the markers to our cache file
    * @returns void
    **/
    protected function _cacheMarkers( $aMarkers ) {
    
        $objFile = new File('system/tmp/'.$this->strMarkerCacheFile);
        $objFile->write( serialize($aMarkers) );
        $objFile->close();    
    }
}

?>