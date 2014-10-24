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
 * Plugin 'rdt_accordion' for the 'ameos_formidable' extension.
 *
 * @author	Jerome Schneider <typo3dev@ameos.com>
 */


class tx_mkforms_widgets_accordion_Main extends formidable_mainrenderlet {
	
	var $aLibs = array(
		'rdt_accordion_lib' => 'res/js/accordion-fixed.js',
		'rdt_accordion_class' => 'res/js/accordion.js',
	);

	var $sMajixClass = 'Accordion';
	var $bCustomIncludeScript = TRUE;

	var $aPossibleCustomEvents = array(
		'ontabopen',
		'ontabclose',
		'ontabchange',
	);

	function _render() {
		
		$aConf = array();
		if(($sSpeed = $this->_navConf('/speed')) !== FALSE) {
			$aConf['resizeSpeed'] = intval($sSpeed);
		}

		if(($sClassToggle = $this->_navConf('/classtoggle')) !== FALSE) {
			$aConf['classNames']['toggle'] = $sClassToggle;
		} else {
			$aConf['classNames']['toggle'] = 'accordion_toggle';
		}

		if(($sClassToggleActive = $this->_navConf('/classtoggleactive')) !== FALSE) {
			$aConf['classNames']['toggleActive'] = $sClassToggleActive;
		} else {
			$aConf['classNames']['toggleActive'] = 'accordion_toggle_active';
		}
		
		if(($sClassContent = $this->_navConf('/classcontent')) !== FALSE) {
			$aConf['classNames']['content'] = $sClassContent;
		} else {
			$aConf['classNames']['content'] = 'accordion_content';
		}

		if(($sWidth = $this->_navConf('/width')) !== FALSE) {
			$aConf['defaultSize']['width'] = $sWidth;
		}

		if(($sHeight = $this->_navConf('/height')) !== FALSE) {
			$aConf['defaultSize']['height'] = $sHeight;
		}

		if(($sDirection = $this->_navConf('/direction')) !== FALSE) {
			$aConf['direction'] = $sDirection;
		}

		// Gibt an, ob alle geöffneten Accordionelemende geschlossen werden sollen, befor das geklickte geöffnet wird.
		$aConf['closeactive'] = $this->defaultTrue('/closeactive');
		
		if(($sEvent = $this->_navConf('/event')) !== FALSE) {
			$sEvent = strtolower(trim($sEvent));
			if(t3lib_div::isFirstPartOfStr($sEvent, 'on')) {
				$sEvent = substr($sEvent, 2);
			}

			$aConf['onEvent'] = $sEvent;
		}

		reset($this->aChilds);
		$aKeys = array_keys($this->aChilds);
		reset($aKeys);
		while(list(, $sChild) = each($aKeys)) {
			// even is toggle
			$aConf['accordions'][] = $this->aChilds[$sChild]->_getElementHtmlId();
			
			if($aConf['direction'] === 'horizontal') {
				$this->aChilds[$sChild]->addCssClass('rdtaccordion_toggle_horizontal');
			} else {
				$this->aChilds[$sChild]->addCssClass('rdtaccordion_toggle');
			}

			$this->aChilds[$sChild]->addCssClass($aConf['classNames']['toggle']);

			// odd is content
			list(, $sChild) = each($aKeys);
			$this->aChilds[$sChild]->addCssClass('rdtaccordion_content');
			if($aConf['direction'] === 'horizontal') {
				$this->aChilds[$sChild]->addCssClass('rdtaccordion_content_horizontal');
			} else {
				$this->aChilds[$sChild]->addCssClass('rdtaccordion_content');
			}
		}

		if($aConf['direction'] === 'horizontal') {
			$sStyle =<<<STYLE
.rdtaccordion_toggle_horizontal {
	/* REQUIRED */
	float: left;	/* This make sure it stays horizontal */
	/* REQUIRED */
}
.rdtaccordion_content_horizontal {
	overflow: hidden;
	/* REQUIRED */
	float: left;	/* This make sure it stays horizontal */
	/* REQUIRED */
}
STYLE;
		} else {
			$sStyle =<<<STYLE
.rdtaccordion_toggle {}
.rdtaccordion_content { overflow: hidden;}
STYLE;
		}

		$this->oForm->additionalHeaderData(
			$this->oForm->inline2TempFile(
				$sStyle,
				'css',
				'CSS required by rdt_accordion'
			),
			'rdt_accordion_' . $aConf['direction'] . ' required_css'
		);

		$this->oForm->getJSLoader()->loadScriptaculous();

		$this->includeScripts(array(
			'libconf' => $aConf
		));

		$sAddInputParams = $this->_getAddInputParams();

		$aChilds = $this->renderChildsBag();
		$sCompiledChilds = $this->renderChildsCompiled($aChilds);

		return array(
			'__compiled' => '<div id="' . $this->_getElementHtmlId() . '" '.$sAddInputParams.'>' . $sCompiledChilds . '</div>',
			'childs' => $aChilds,
		);
	}

	function majixSetActiveTab($sTab) {
		return $this->buildMajixExecuter(
			'setActiveTab',
			$sTab
		);
	}

	function majixCloseTab($sTab) {
		return $this->buildMajixExecuter(
			'close',
			$sTab
		);
	}

	function majixNextTab() {
		return $this->buildMajixExecuter(
			'next'
		);
	}

	function majixPreviousTab() {
		return $this->buildMajixExecuter(
			'previous'
		);
	}

	function majixFirstTab() {
		return $this->buildMajixExecuter(
			'first'
		);
	}

	function majixLastTab() {
		return $this->buildMajixExecuter(
			'last'
		);
	}

	function renderOnly() {
		return TRUE;
	}

	function _renderReadOnly() {
		return $this->_render();
	}

	function mayHaveChilds() {
		return TRUE;
	}
	
	function shouldAutowrap() {
		return $this->oForm->_defaultFalse('/childs/autowrap/');
	}
}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/ameos_formidable/api/base/rdt_accordion/api/class.tx_rdtaccordion.php'])	{
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/ameos_formidable/api/base/rdt_accordion/api/class.tx_rdtaccordion.php']);
	}
?>