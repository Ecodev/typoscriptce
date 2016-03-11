<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

if (TYPO3_MODE == 'BE') {
    $TCA['tt_content']['types'][$_EXTKEY . '_pi1']['showitem'] = 'CType;;4;button;1-1-1, header;;3;;1-1-1,bodytext;LLL:EXT:typoscriptce/locallang_db.xml:typoscript,imageborder;LLL:EXT:typoscriptce/locallang_db.xml:no_cache,tx_typoscriptce_tsbrowser,2-2-2';
    $TCA['tt_content']['columns']['bodytext']['defaultExtras'] = 'fixed-font : enable-tab';
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:typoscriptce/locallang_db.xml:tt_content.CType_pi1',
        $_EXTKEY . '_pi1',
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'ext_icon.gif'
    ],
    'CType'
);
