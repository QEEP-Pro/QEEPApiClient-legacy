<?php
/**
 * Created by Michael A. Sivolobov
 * Date: 19.06.13
 */

require_once dirname(__FILE__) . '/src/Controller/APIController.php';
require_once dirname(__FILE__) . '/src/Model/Config.php';
require_once dirname(__FILE__) . '/src/Model/Order.php';
require_once dirname(__FILE__) . '/src/Model/OrderStatus.php';
require_once dirname(__FILE__) . '/src/Model/Product.php';
require_once dirname(__FILE__) . '/src/Model/Purchase.php';

/** @var Config $config */
$config = Config::getInstance();
$config->loadFromFile(dirname(__FILE__) . '/src/Resources/config.php');
