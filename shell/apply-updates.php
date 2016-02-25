<?php

require_once 'abstract.php';

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Shell
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Mage_Shell_Updates extends Mage_Shell_Abstract
{
    /*
     * dont include mage, because of the missing options
     * different from the
     * https://github.com/magento/bugathon_march_2013/commit/734f23cbb4331fcc9a4577f35abb45e37896e979
     *
     * dont overwrite core file.
     */
    protected $_includeMage = false;

    /**
     * Initialize application options
     *
     * @var array
     */
    protected $_appOptions = array('global_ban_use_cache' => true);

    /**
     * Initialize application and parse input parameters
     *
     */
    public function __construct()
    {
        require_once $this->_getRootPath() . 'app' . DIRECTORY_SEPARATOR . 'Mage.php';
        Mage::app($this->_appCode, $this->_appType, $this->_appOptions);

        parent::__construct();
    }


    /**
     * Run script
     *
     */
    public function run()
    {
        if ($this->getArg('run')) {

            ini_set('display_errors', 1);

            // apply updates
            Mage_Core_Model_Resource_Setup::applyAllUpdates();
            Mage_Core_Model_Resource_Setup::applyAllDataUpdates();

            // now enable caching and save
            Mage::getConfig()->getOptions()->setData('global_ban_use_cache', false);

            // re-init cache
            Mage::app()->baseInit(array());
            Mage::getConfig()->loadModules()->loadDb()->saveCache();

            echo "All updates successfully applied\n";

        } elseif ($this->getArg('help')) {
            echo $this->usageHelp();
        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f apply-updates.php -- [options]

  run               Run apply updates
  help              This help

USAGE;
    }
}

$shell = new Mage_Shell_Updates();
$shell->run();
