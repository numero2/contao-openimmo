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

 
class ModuleOpenImmo extends Controller {


    // list of namespaces that are compatible with OpenImmo
    protected $aAllowedNamespaces = array('imo','openimmo');

    protected $aCurrencyCodes = array(
        'USD' => '$'
    ,   'EUR' => '€'
    ,   'GBP' => '£'
    );
    
    public function __construct() {

        $this->import('Database');
        $this->import('Input');
    }

    
    /**
    * ModuleOpenImmo::_loadXML
    *
    * Returns an array containing the xml data
    * @param fileName
    * @returns array
    **/
    public function _loadXML( $fileName=NULL ) {

        // load xml file
        $sXML = NULL;
        $sXML = @file_get_contents(TL_ROOT.DIRECTORY_SEPARATOR.$fileName);
    
        if( !empty($sXML) ) {
        
            // check for known namespaces
            $knownNameSpace = false;
            
            foreach( $this->aAllowedNamespaces as $nameSpace ) {
            
                if( strpos($sXML,'<'.$nameSpace.':') !== FALSE ) {
                
                    $knownNameSpace = true;
                    
                    // strip namespace from xml
                    $sXML = str_replace( array('<'.$nameSpace.':','</'.$nameSpace.':'), array('<','</'), $sXML);
                }
            }

            // no of the known namespaces given? NOT VALID!
            if( !$knownNameSpace )
                return false;

            // parse our xml into an array
            $oXML = NULL;
            $oXML = new XMLParser();
            $aXML = array();
            $aXML = $oXML->xmlToArray($sXML);

            // strip our root node and return xml
            if( !empty($aXML) ) {
            
                $rootNodeName = array_keys($aXML);
                $rootNodeName = $rootNodeName[0];

                $aXML = $aXML[$rootNodeName][0];

                return $aXML;
            }
        }

        return false;
    }

    
    /**
    * ModuleOpenImmo::validateXML
    *
    * Validates the given xml file by trying to load it
    * @param fileName
    * @returns bool
    **/
    public function validateXML( $fileName=NULL ) {

        return (bool)$this->_loadXML($fileName);
    }
    
    
    /**
    * ModuleOpenImmo::countEntries
    *
    * Returns the number of entries in the given xml
    * @param fileName
    * @returns int
    **/
    public function countEntries( $fileName=NULL ) {

        $aXML = array();
        $aXML = $this->_loadXML($fileName);

        $numEntries = 0;
        
        if( !empty($aXML['anbieter']) ) {
        
            foreach( $aXML['anbieter'] as $anData ) {
            
                $numEntries += count($anData['immobilie']);
            }
        }

        return $numEntries;
    }
    

    /**
    * ModuleOpenImmo::getEntriesByPoolId
    *
    * Returns a list of all entries from the xml file of the given entry
    * @param poolID
    * @returns array
    **/
    public function getEntriesByPoolId( $poolID=NULL ) {
    
        if( empty($poolID) )
            return false;

        $aEntry = NULL;
        $aEntry = $this->getPoolData($poolID);

        if( !empty($aEntry) ) {
        
            if( !empty($aEntry['jumpTo']) ) {
            
                $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($aEntry['jumpTo']);
            }
        
            // get objects from xml
            $aObjects = array();
            $aObjects = $this->_loadXML($aEntry['path']);
            
            // add some metadata to each object
            if( !empty($aObjects['anbieter']) ) {
                foreach( $aObjects['anbieter'] as $anbieterId => $anbieter ) {
                
                    if( !empty($anbieter['immobilie']) ) {
                    
                        foreach( $anbieter['immobilie'] as $immobilieId => $immobilie ) {
                        
                            $imData = &$aObjects['anbieter'][$anbieterId]['immobilie'][$immobilieId]['__contao__'];
                            $imData = array(
                                'mainImage'     => ''
                            ,   'detailLink'    => ''
                            ,   'currencyCode'  => $this->aCurrencyCodes['EUR']
                            );
                        
                            // generate detail link
                            if( !empty($objPage) ) {
                            
                                $identifier = $immobilie['verwaltung_techn'][0]['objektnr_intern'][0]['_data'];
                                $identifier = str_replace( array('{','}'), '', $identifier);
                                
                                $imData['detailLink'] = $this->generatePageURLWithParams($objPage, 'objekt='.$identifier,true);
                            }
                            
                            // add shortcut for currency code
                            if( !empty($immobilie['preise'][0]['waehrung'][0]['_attributes']['iso_waehrung']) ) {
                            
                                if( !empty($this->aCurrencyCodes[$immobilie['preise'][0]['waehrung'][0]['_attributes']['iso_waehrung']]) )
                                    $imData['currencyCode'] = $this->aCurrencyCodes[$immobilie['preise'][0]['waehrung'][0]['_attributes']['iso_waehrung']];
                                else
                                    $imData['currencyCode'] = $immobilie['preise'][0]['waehrung'][0]['_attributes']['iso_waehrung'];
                            }
                            
                            // find the main image
                            if( true ) {
                                $sAltImage = "";
                                $sMainImage = "";
                                
                                if( !empty($immobilie['anhaenge'][0]['anhang']) ) {
                                
                                    foreach( $immobilie['anhaenge'][0]['anhang'] as $anhang ) {
                                    
                                        if( strpos($anhang['_attributes']['gruppe'],'BILD') === FALSE )
                                            continue;
                                    
                                        if( $anhang['_attributes']['gruppe'] == 'TITELBILD' )
                                            $sMainImage = $anhang['daten'][0]['pfad'][0]['_data'];
                                            
                                        if( empty($sAltImage) )
                                            $sAltImage = $anhang['daten'][0]['pfad'][0]['_data'];
                                    }
                                }
                                
                                $sAltImage = empty($sAltImage) ? 'system/modules/openimmo/html/noimage.png' : $aEntry['dir'].$sAltImage;
                                $sMainImage = empty($sMainImage) ? $sAltImage : $aEntry['dir'].$sMainImage;
                                
                                $imData['mainImage'] = $sMainImage;
                            }
                        }
                    }
                }
            }

            return $aObjects;
        }
        
        return false;
    }
    
    
    /**
    * ModuleOpenImmo::getPoolData
    *
    * Returns all data of the pool with the given id
    * @param poolID
    * @returns array
    **/
    public function getPoolData( $poolID=NULL ) {
    
        if( empty($poolID) )
            return false;

        // get all entries
		$objEntries = $this->Database->prepare("SELECT * FROM tl_oi_files WHERE id=? ORDER BY tstamp")->execute($poolID);

        $aPool = NULL;
        $aPool = $objEntries->fetchAssoc();
        
        if( !empty($aPool['path']) ) {
        
            $aPool['dir'] = substr($aPool['path'],0,(strrpos($aPool['path'],'/')+1));
        
            if( !empty($aPool['jumpTo']) ) {
            
                $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($aPool['jumpTo']);
                
                $aPool['jumpToUrl'] = NULL;
                $aPool['jumpToUrl'] = $this->generateFrontendUrl( $objPage->fetchAssoc() );
            }
        }
        
        return $aPool;
    }
    
    
    /**
    * ModuleOpenImmo::toggleEnabled
    *
    * Toggles the enabled status of the given pool
    * @param poolID
    * @param state
    * @returns bool
    **/
    public function toggleEnabled( $poolID=NULL, $state=0 ) {
    
        if( empty($poolID) )
            return false;

        $sQuery = NULL;
        $sQuery = "UPDATE tl_oi_files SET `enabled`=".(int)$state." WHERE id=".(int)$poolID.";";

		return $this->Database->execute($sQuery);
    }
    
    
    /**
    * ModuleOpenImmo::getEnabledPools
    *
    * Returns all enabled pools
    * @returns array
    **/
    public function getEnabledPools() {
    
		$objPools = $this->Database->execute("SELECT `id` FROM tl_oi_files WHERE `enabled`=1 ORDER BY `tstamp` DESC;");

        $aPools = array();
        $aPools = $objPools->fetchAllAssoc();
        
        if( !empty($aPools) ) {
        
            foreach( $aPools as $i => $pool ) {
            
                $aPools[$i] = $this->getPoolData($pool['id']);
            }
        }

        return $aPools;
    }
    
    
	/**
    * ModuleOpenImmo::replaceInsertTags
    *
    * Replace insert tags with their values
    * @param string
    * @return string
    */
	protected function replaceInsertTags($strBuffer, $blnCache=false) {

        $aParams = explode('::', $strBuffer);
 
        switch( $aParams[0] ) {
        
            // insert variable of current object
            case 'oi_curr_object' :

                // get all data of the current object
                $objectID = NULL;
                $objectID = $this->Input->get('objekt');
                
                $aObject = array();
                $aPool = array();
                
                if( !empty($objectID) ) {
                
                    // get pools
                    $aPools = array();
                    $aPools = $this->getEnabledPools();
                    
                    // get objects
                    $aObjects = array();
                    
                    // find our currently selected object
                    if( !empty($aPools) ) {
                    
                        foreach( $aPools as $pool ) {

                            $aEntries = $this->getEntriesByPoolId($pool['id']);
                            
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
            
                if( empty($aObject) )
                    return false;

                switch( $aParams[1] ) {
                    case 'objekttitel' :
                        return $aObject['freitexte'][0]['objekttitel'][0]['_data'];
                    break;
                    
                    case 'objektnr_extern' :
                        return $aObject['verwaltung_techn'][0]['objektnr_extern'][0]['_data'];
                    break;                    
                }
                
            break;
            
            // not our insert tag?
            default :
                return false;
            break;
        }

        return false;
    
    }
    
    
	public function generatePageURLWithParams( $objPage, $strRequest=NULL, $blnIgnoreParams=false ) {
		$arrGet = $blnIgnoreParams ? array() : $_GET;

		// Clean the $_GET values (thanks to thyon)
		foreach (array_keys($arrGet) as $key)
		{
			$arrGet[$key] = $this->Input->get($key, true);
		}

		$arrFragments = preg_split('/&(amp;)?/i', $strRequest);

		// Merge the new request string
		foreach ($arrFragments as $strFragment)
		{
			list($key, $value) = explode('=', $strFragment);

			if ($value == '')
			{
				unset($arrGet[$key]);
			}
			else
			{
				$arrGet[$key] = $value;
			}
		}

		$strParams = '';

		foreach ($arrGet as $k=>$v) {
			$strParams .= $GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' . $k . '=' . $v  : '/' . $k . '/' . $v;
		}

		// Do not use aliases
		if( $GLOBALS['TL_CONFIG']['disableAlias'] ) {
			return 'index.php?' . preg_replace('/^&(amp;)?/i', '', $strParams);
		}

		$pageId = strlen($objPage->alias) ? $objPage->alias : $objPage->id;

		// Get page ID from URL if not set
		if( empty($pageId) ) {
			$pageId = $this->getPageIdFromUrl();
		}

		return ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $pageId . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];
	}
}

?>