<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE=='BE') {
    require_once(t3lib_extMgm::extPath($_EXTKEY) . 'class.user_tsbrowser.php');
	$tempColumns = array (
	    'tx_typoscriptce_tsbrowser' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:typoscriptce/locallang_db.xml:tt_content.ts_browser',
			'config' => Array (
				'type' => 'user',
				'userFunc' => 'user_tsbrowser->renderTSbrowser',
			)
		),
	);

	t3lib_div::loadTCA('tt_content');
	t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);

	$TCA['tt_content']['types'][$_EXTKEY.'_pi1']['showitem']='CType;;4;button;1-1-1, header;;3;;1-1-1,bodytext;LLL:EXT:typoscriptce/locallang_db.xml:typoscript,imageborder;LLL:EXT:typoscriptce/locallang_db.xml:no_cache,tx_typoscriptce_tsbrowser,2-2-2';
	$TCA['tt_content']['columns']['bodytext']['defaultExtras'] = 'fixed-font : enable-tab';
}

t3lib_extMgm::addPlugin(array('LLL:EXT:typoscriptce/locallang_db.xml:tt_content.CType_pi1', $_EXTKEY.'_pi1', t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif'),'CType');

if (TYPO3_MODE=='BE') {
	$TYPO3_CONF_VARS['BE']['AJAX']['TSbrowser::TSobject'] = t3lib_extMgm::extPath($_EXTKEY) . 'class.user_tsbrowser.php:user_TSbrowser->renderAjaxTSobject';
	$TYPO3_CONF_VARS['BE']['AJAX']['TSbrowser::TSshowFlag'] = t3lib_extMgm::extPath($_EXTKEY) . 'class.user_tsbrowser.php:user_TSbrowser->renderAjaxTStree';
}
?>