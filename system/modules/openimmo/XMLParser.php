<?php

/**
* XMLParser.class.php
*
* A simple xml parser
* @author Eric Binek
* @modified Benny Born <benny@bennyborn.de>
* @version 2.0
*/

class XMLParser {

	private $aPointer			= array();
	private $numPointers		= 0;
	private $aReturn			= array();
	private $aCurrent			= array();
	private $sReturn			= '';
	private $hParser			= NULL;
	private $sEncoding 			= 'ISO-8859-1';
	private $aAllowedCharsets 	= array(
		'ISO-8859-1'
	,	'US-ASCII'
	,	'UTF-8'
	);

	public function __construct() {
	
	}

	public function __destruct() {
	
	}
	
	private function setStartElement( $parser , $tag , $param ) {
	
		$int 				= 0;
		$this->aPointer[]	= $tag;
		
		$this->numPointers++;
		
		if( !isset($this->aCurrent[$tag]) ) {
		
			$this->aCurrent[$tag]	= array();
		}
		
		$int = count($this->aCurrent[$tag]);
		
		if( !empty($param) ) {
		
			$this->aCurrent[$tag][$int]['_attributes']	= $param;
		}
		
		$this->aCurrent = &$this->aCurrent[$tag][$int];
	}

	private function setCharacterData($parser, $data) {
	
		$sData	= trim( $data );
		
		if( $sData != '' ) {
		
			if( empty($this->aCurrent['_data']) ) {
				$this->aCurrent['_data']	 = $sData;
			} else {
				$this->aCurrent['_data']	.= $sData;
			}
		}
	}

	private function setEndElement($parser, $tag) {
	
		$path_str	= '';
		
		for( $i=0; $i < $this->numPointers; $i++ ) {
		
			$int = 0;
			
			if( ( $i + 1 ) < $this->numPointers ) {
			
				$path_str .= "['".$this->aPointer[$i]."']";	
				eval( "\$int = count( \$this->aReturn".$path_str.");" );

				if( $int ) {
					$path_str .= "[".($int-1)."]";
				}
			}
		}
		
		eval( "\$this->aCurrent	=	&	\$this->aReturn".$path_str.";" );
		reset($this->aPointer);
		array_pop ($this->aPointer);
		
		$this->numPointers--;
	}	
	
	public function xmlToArray( $sXMLDocument = "" ) {
	
		$aEncoding 			= array();
		
		if( preg_match('/encoding=["|]([\w-]+)["|]/', $sXMLDocument, $aEncoding) ) {
		
			if( !empty($aEncoding[1]) ) {
			
				// check if charset is allowed
				if( in_array( strtoupper($aEncoding[1]), $this->aAllowedCharsets ) ) {
					$this->sEncoding = $aEncoding[1];					
				}
			}
		}
	
		if( ($this->hParser = xml_parser_create()) && !empty($sXMLDocument) ) {
		
			$this->aReturn	=	array();
			$this->aCurrent	=	&$this->aReturn;
			
			xml_set_object($this->hParser, $this);
			xml_parser_set_option ($this->hParser, XML_OPTION_SKIP_WHITE, 1);
			xml_parser_set_option ($this->hParser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option ($this->hParser, XML_OPTION_TARGET_ENCODING, $this->sEncoding);
			xml_set_element_handler($this->hParser, "setStartElement", "setEndElement");
			xml_set_character_data_handler($this->hParser, "setCharacterData"); 
			xml_parse($this->hParser, $sXMLDocument );
			xml_parser_free($this->hParser);
			
			$this->aCurrent	=	&$this->aReturn;
		}
		
		return $this->aReturn;
	}
	
	public function arrayToXml( $args , $level = 0 ) {
	
		if( !$level ) {
		
			$this->sReturn = '';	
		}
		
		if( !is_array($args) ) {
		
			return '';
		}
		
		foreach( $args AS $sTag => $aIndex ) {
		
			if( $sTag == '_attributes' || $sTag == '_data' ) {
				continue;	
			}
			
			for( $i=0; $i<count($aIndex); $i++ ) {
			
				$this->sReturn .= '<'.$sTag;

				if( isset( $aIndex[$i]['_attributes'] ) ) {
				
					foreach( $aIndex[$i]['_attributes'] AS $sParam => $sValue ) {

                        if( $sValue != '' )
						    $this->sReturn .= ' '.$sParam.'="'.$sValue.'"';
					}
				}
				
				$this->sReturn .= '>';
				$this->arrayToXml( $aIndex[$i] , $level+1 );
				
				if( isset( $aIndex[$i]['_data'] ) ) {
					$this->sReturn .= '<![CDATA['.$aIndex[$i]['_data'].']]>';
				}
				
				$this->sReturn .= '</'.$sTag.'>';
				$this->sReturn .= "\n";
			}
		}
		
		return $this->sReturn;
	}		
}
?>