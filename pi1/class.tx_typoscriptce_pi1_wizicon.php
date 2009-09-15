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
 * Class that adds the wizard icon.
 *
 * @author    Steffen Kamper <info@sk-typo3.de>
 */
 

class tx_typoscriptce_pi1_wizicon {
	function proc($wizardItems)	{
		global $LANG;

		$LL = $this->includeLocalLang();

		$wizardItems["plugins_tx_typoscriptce_pi1"] = array(
			'icon'			=> t3lib_extMgm::extRelPath('typoscriptce') . 'ce_wiz.gif',
			'title'			=> $LANG->getLLL('tt_content.CType_pi1', $LL),
			'description'	=> $LANG->getLLL('pi1_plus_wiz_description_typoscriptce', $LL),
			'params'		=> '&defVals[tt_content][CType]=typoscriptce_pi1&defVals[tt_content][bodytext]=10 = TEXT
10.value = Hello world'
		);

		return $wizardItems;
	}
	
	/**
	 * Reads the [extDir]/locallang.xml and returns the \$LOCAL_LANG array found in that file.
	 *
	 * @return	The array with language labels
	 */
	function includeLocalLang()	{
		$llFile = t3lib_extMgm::extPath('typoscriptce') . 'locallang_db.xml';
		$LOCAL_LANG = t3lib_div::readLLXMLfile($llFile, $GLOBALS['LANG']->lang);
		
		return $LOCAL_LANG;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/typoscriptce/pi1/class.tx_typoscriptce_pi1_wizicon.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/typoscriptce/pi1/class.tx_typoscriptce_pi1_wizicon.php']);
}

?>
