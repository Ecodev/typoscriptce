<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types'][$_EXTKEY.'_pi1']['showitem']='CType;;4;button;1-1-1, header;;3;;1-1-1,bodytext;LLL:EXT:typoscriptce/locallang_db.xml:typoscript,imageborder;LLL:EXT:typoscriptce/locallang_db.xml:no_cache,2-2-2';
$TCA['tt_content']['columns']['bodytext']['defaultExtras'] = 'fixed-font : enable-tab';

t3lib_extMgm::addPlugin(array('LLL:EXT:typoscriptce/locallang_db.xml:tt_content.CType_pi1', $_EXTKEY.'_pi1', t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif'),'CType');
?>