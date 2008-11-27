<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Steffen Kamper <info@sk-typo3.de>
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_t3lib . 'class.t3lib_page.php');
require_once(PATH_t3lib.'class.t3lib_tsparser.php');

class user_TSbrowser {

	protected $cTypes = array();
    protected $pid = 0;
    protected $usableObjects = array('lib', 'plugin', 'tt_content');
    protected $showAllObjects = false;

    function renderTSbrowser($PA, $parentObject) {
        $this->pid = $PA['row']['pid'];
        $pageInfo = t3lib_BEfunc::getRecord('pages', $this->pid);

        $alttext = t3lib_BEfunc::getRecordIconAltText($pageInfo, 'pages');
		$iconImg = t3lib_iconWorks::getIconImage('pages', $pageInfo, $GLOBALS['BACK_PATH'], 'class="absmiddle" title="'. htmlspecialchars($alttext) . '"');
		// Make Icon:
		$theIcon = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($iconImg, 'pages', $pageRecord['uid']);
        $pageInfoDisplay = $theIcon . $pageInfo['title'] . '<em>[pid: ' . $this->pid . ']</em>';

        //load JS + CSS
        if (t3lib_div::int_from_ver(TYPO3_version) >= 4003000) {
	        $GLOBALS['SOBE']->doc->addStyleSheet('typoscriptce_tsbrowser', t3lib_extMgm::extRelPath('typoscriptce')."res/tsbrowser.css");
		} else {
        	$GLOBALS['SOBE']->doc->styleSheetFile2 = t3lib_extMgm::extRelPath('typoscriptce')."res/tsbrowser.css";
		}
        $GLOBALS['SOBE']->doc->JScodeArray['typoscriptce_tsbrowser'] = 'var T3_BACK_PATH = "' . $GLOBALS['BACK_PATH'] . '";' . chr(10);
        $parentObject->loadJavascriptLib(t3lib_extMgm::extRelPath('typoscriptce')."res/tsbrowser.js");

        $this->showAllObjects = $GLOBALS['BE_USER']->uc['tx_typoscriptce']['showAll'];

        // Get TypoScript template for current page
		$conf = $this->getConfigArray();
		// Show TS template hierarchy
		$tree = $this->showTemplate($conf);

    	return '
    	<div id="ts-browser">
              <div id="ts-tree-container">
              <h5>Browse existing Typoscript</h5>
              <input type="checkbox" id="checkAllObjectsTS" value="1" ' . ($this->showAllObjects ? ' checked="checked"' : '') . '/><label for="checkAllObjectsTS">Show all Objects</label>
              <input type="hidden" id="TSthePid" value="' . $this->pid . '" />
              
              	<div style="width: 94.7%;" class="tsce-ts-tree">
					<div id="rootpageinfo">' . $pageInfoDisplay .'</div>
				</div>
				<div id="ts-tree">' . $tree . '</div>
              </div>
              <div id="ts-preview-container">   
              <h5>Preview</h5>
              <div id="ts-preview">

              </div>
              </div>
    	</div>
    	';
	}

    /**
	 * Get TS template
	 *
	 * This function creates instances of the class needed to render
	 * the TS template, and gets it as a multi-dimensionnal array.
	 *
	 * @return		An array containing all the available TS objects
	 */
	function getConfigArray($key='') {

		// Initialize the page selector
		$this->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$this->sys_page->init(true);

		// initialize the TS template
		$this->tmpl = t3lib_div::makeInstance('t3lib_TStemplate');
		$this->tmpl->init();

		// Avoid an error
		$this->tmpl->tt_track = 0;

		// Get rootline for current PID
		$rootline = $this->sys_page->getRootLine($this->pid);

		// Start TS template
		$this->tmpl->start($rootline);
		#$this->tmpl->runThroughTemplates($rootline, 0);
		$configArray =  $this->tmpl->setup;

		if ($key) {
        	$keys = explode('.', $key);
        	foreach ($keys as $key) {
            	$configArray =& $configArray[$key . '.'];
        	}
		}

		//Return configuration array
		return $configArray;
	}

	/**
	 * Show TS template hierarchy
	 *
	 * This function displays the TS template hierarchy as HTML list
	 * elements. Each section can be expanded/collapsed.
	 *
	 * @param		$object		A section of the TS template
	 * @param		$object		The path to the current object
	 * @return
	 */
	function showTemplate($conf, $pObj = false) {

		// Storage
		$htmlCode = array();
		$curr = t3lib_div::GPvar('current');

		// Process each object of the configuration array
		foreach($conf as $key => $value) {
            $str = '';

			// TS object ID
			$id = $pObj . $key;
			$sel = (strpos($curr, $id) === 0);
			$mtc = ($curr == $id);

			$subKey = '';
			if (substr($key, -1) == '.') {
            	$subKey = substr($key, 0, -1);
			}

            if (!$pObj && $subKey && !$this->showAllObjects && !in_array($subKey, $this->usableObjects)) {
            	continue;
            }

			// Check if object is a container
			if (is_array($value)) {
				// Check if object has a content type
				if (substr($key, 0, - 1) != $lastKey) {
					// No content type - Process sub configuration
					$subArray = $this->showTemplate($value, $id);
					// Check if objects are available
					if ($subArray) {
						// Add container
						// remuve trailing point
						if ($subKey) {
                            $key = $subKey;
                        	if(!is_array($conf[$subKey])) {
                             	$str = $conf[$subKey] ? ' <em>['. t3lib_div::fixed_lgd_cs($conf[$subKey],30) . ']</em>' : '';
                        	}
						}
						$htmlCode[] = '<li class="pm ' . ($sel ? 'minus act' : 'plus') . '" ' . ($mtc ? 'id="selected"' : '') . ' name="' . $pObj . $key . '"><strong>' . $key . '</strong>' . $str . $subArray . '</li>';
					}
				}
			} else {
				$value = htmlspecialchars($value);

				if (substr($key, -1) != '.' && is_array($conf[$key . '.'])) {
                    $subArray = $this->showTemplate($conf[$key . '.'], $id);
				} else {
					// Memorize key
					$lastKey = $key;
					// TS object
					$htmlCode[] = '<li class="' . ($sel ? 'act' : '') . '" ' . ($mtc ? 'id="selected"' : '') . ' name="' . $key . '"><span>' . $key . '</span> <em>['.  t3lib_div::fixed_lgd_cs($value,30) . ']</em></li>';
				}
			}
		}

		// Check if objects have been detected
		if (count($htmlCode)) {
		//	array_push($htmlCode, str_replace('<li class="', '<li class="last ', array_pop($htmlCode)));
			array_push($htmlCode, preg_replace('/^<li class="/', '<li class="last ', array_pop($htmlCode)));

				// Return hierarchy
			return
			'<ul class="' . (!$pObj ? 'tree' : '') . '">' .
			implode(chr(10), $htmlCode) .
			'</ul>';
		}
	}

	// AJAX functions

	function renderAjaxTStree($params = array(), TYPO3AJAX &$ajaxObj = null) {

	    $flag = t3lib_div::_POST('objflag');
	    $pid = t3lib_div::_POST('pid');

	    $this->pid = intval($pid);
	    $flag = $flag == 'false' ? 0 : 1;
	    $GLOBALS['BE_USER']->uc['tx_typoscriptce']['showAll'] = $flag;
	    $GLOBALS['BE_USER']->writeUC();

	    $this->showAllObjects = $flag;

        // Get TypoScript template for current page
		$conf = $this->getConfigArray();
		// Show TS template hierarchy
		$tree = $this->showTemplate($conf);
		$ajaxObj->addContent('ts-tree', $tree);
	}

	function renderAjaxTSobject($params = array(), TYPO3AJAX &$ajaxObj = null) {


        $obj = t3lib_div::_POST('obj');
        $pid = t3lib_div::_POST('pid');

        $this->pid = intval($pid);

        $tsparser = t3lib_div::makeInstance("t3lib_TSparser");
		//$tsparser->highLightStyles = $this->highLightStyles;
	    $tsparser->lineNumberOffset=1;

        $ts = $this->getConfigArray($obj);

        if(is_array($ts)) {
	        $tsHighlight .= $tsparser->doSyntaxHighlight($this->tsArrayToText($ts), '', 0);
		} else {
        	$tsHighlight = '<em>empty</em>';
		}

        $html = '<h3>' . htmlspecialchars($obj) . '</h3>' . $tsHighlight;
        $ajaxObj->addContent('ts-preview', $html);
	}

	function tsArrayToText($arr, $text='', $depth = 0) {
                
		foreach ($arr as $key => $val) {
        	if (is_array($val)) {
        	     $tsText .= str_repeat(' ', $depth * 4) . substr($key, 0, -1) . ' {' . chr(10) . $this->tsArrayToText($val, $text, $depth + 1) . chr(10) .  str_repeat(' ', $depth * 4) . '}' . chr(10);
        	} else {
        		if (strpos($val, chr(10))) {
					$tsText .= str_repeat(' ', $depth * 4) . $key . ' (' . str_repeat(' ', ($depth + 1) * 4) . chr(10) . $val . chr(10) . str_repeat(' ', ($depth) * 4) . ')' . chr(10);        		
        		} else {
        			$tsText .= str_repeat(' ', $depth * 4) . $key . ' = ' . $val . chr(10);
				}
			}
	    }
	    return $tsText;
	}

}
?>
