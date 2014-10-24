<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2014 DMK E-BUSINESS GmbH (dev@dmk-ebusiness.de)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/** 
 * Plugin 'rdt_dewplayer' for the 'ameos_formidable' extension.
 *
 * @author	Jerome Schneider <typo3dev@ameos.com>
 */


class tx_mkforms_widgets_dewplayer_Main extends formidable_mainrenderlet {
	
	function _render() {

		$sLabel = $this->getLabel();

		$sPath = $this->_getPath();
		$sHtmlId = $this->_getElementHtmlId();
		$sHtmlName = $this->_getElementHtmlName();
		$sAddParams = $this->_getAddInputParams();

		$bAutoStart = $this->oForm->_defaultFalse("/autostart", $this->aElement);
		$bAutoReplay = $this->oForm->_defaultFalse("/autoreplay", $this->aElement);
		$sBgColor = (($sTempColor = $this->_navConf("/bgcolor")) !== FALSE) ? $sTempColor : "FFFFFF";

		$sMoviePath = $this->sExtWebPath . "res/dewplayer.swf";

		$sColor = str_replace("#", "", $sBgColor);
		$sMoviePath .= "?bgcolor=" . $sColor;
		$sFlashParams .= '<param name="bgcolor" value="' . $sColor . '" />';

		$sFlashParams = "";
		
		if($bAutoStart) {
			$sMoviePath .= "&autostart=1";
			$sFlashParams .= '<param name="autostart" value="1" />';
		}
		
		if($bAutoReplay) {
			$sMoviePath .= "&autoreplay=1";
			$sFlashParams .= '<param name="autoreplay" value="1" />';
		}



		$sMoviePath .= "&mp3=" . rawurlencode($sPath);

		$sInput =<<< FLASHOBJECT
			
			<object
				name=		"{$sHtmlName}"
				id=			"{$sHtmlId}"
				codebase=	"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0"
				type=		"application/x-shockwave-flash"
				data=		"{$sMoviePath}"
				width=		"200"
				height=		"20"
				align=		"middle"
				{$sAddParams}>

				<param name="allowScriptAccess" value="sameDomain" />
				<param name="movie" value="{$sMoviePath}" />
				<param name="quality" value="high" />
				{$sFlashParams}

			</object>

FLASHOBJECT;

		

		$aHtmlBag = array(
			"__compiled" => $this->_displayLabel($sLabel) . $sInput,
			"input" => $sInput,
			"mp3." => array(
				"file" => $sPath,
			)
		);
		
		return $aHtmlBag;
	}

	function _renderOnly() {
		return true;
	}
	
	function _getPath() {
		
		if(($sPath = $this->_navConf("/path")) !== FALSE) {
			
			if($this->oForm->isRunneable($sPath)) {
				$sPath = $this->getForm()->getRunnable()->callRunnableWidget($this, $sPath);
			}

			if(t3lib_div::isFirstPartOfStr($sPath, "EXT:")) {
				
				$sPath = t3lib_div::getIndpEnv("TYPO3_SITE_URL") .
					str_replace(
						t3lib_div::getIndpEnv("TYPO3_DOCUMENT_ROOT"),
						"",
						t3lib_div::getFileAbsFileName($sPath)
					);
			}
		}
		
		return $sPath;
	}
}


	if (defined("TYPO3_MODE") && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]["XCLASS"]["ext/ameos_formidable/api/base/rdt_dewplayer/api/class.tx_rdtdewplayer.php"])	{
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]["XCLASS"]["ext/ameos_formidable/api/base/rdt_dewplayer/api/class.tx_rdtdewplayer.php"]);
	}
?>