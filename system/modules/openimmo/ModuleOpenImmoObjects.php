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

 
class ModuleOpenImmoObjects extends BackendModule {


	protected $strTemplate = 'be_oi_objects';

    
	/**
	 * Parse the template
	 * @return string
	 */
	public function generate() {

		$this->Template = new BackendTemplate($this->strTemplate);
		$this->compile();

        // replace insert tags
        $sTpl = $this->Template->parse();
        $sTpl = $this->replaceInsertTags($sTpl);

		return $sTpl;
	}


	/**
	 * Generate module
	 */
	protected function compile() {

        $poolID = NULL;
        $poolID = $this->Input->get('id');
        
        if( empty($poolID) )
            $this->redirect('contao/main.php?do=oi_files');
        
        $oMOI = NULL;
        $oMOI = new ModuleOpenImmo;
        
        $aEntries = array();
        $aEntries = $oMOI->getEntriesByPoolId( $poolID );
        $this->Template->entries = $aEntries;
        
        $aPool = array();
        $aPool = $oMOI->getPoolData( $poolID );
        $this->Template->pool = $aPool;
        
		$this->Template->request = ampersand($this->Environment->request, true);
	}
}

?>