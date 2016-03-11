<?php

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

/**
 * Plugin 'Typoscript Object' for the 'typoscriptce' extension.
 *
 * @author    Steffen Kamper <info@sk-typo3.de>
 */
class tx_typoscriptce_pi1 extends AbstractPlugin
{

    /**
     * @var string
     */
    public $prefixId = 'tx_typoscriptce_pi1';

    /**
     * Path to this script relative to the extension dir.
     *
     * @var string
     */
    public $scriptRelPath = 'pi1/class.tx_typoscriptce_pi1.php';

    /**
     * @var string
     */
    public $extKey = 'typoscriptce';

    /**
     * @var bool
     */
    public $pi_checkCHash = true;

    /**
     * The main method of the PlugIn
     *
     * @param string $content : The PlugIn content
     * @param array $conf : The PlugIn configuration
     * @return string
     */
    public function main($content, $conf)
    {

        // get data
        $obj = $this->cObj->data['imageborder'] ? 'COA_INT' : 'COA';
        $tsRaw = $this->cObj->data['bodytext'];

        // parse TS
        $tsParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $tsParser->parse($tsRaw);
        $result = $tsParser->setup;

        // render output
        $out = $this->cObj->cObjGetSingle($obj, $result);
        return $this->pi_wrapInBaseClass($out);
    }


}
