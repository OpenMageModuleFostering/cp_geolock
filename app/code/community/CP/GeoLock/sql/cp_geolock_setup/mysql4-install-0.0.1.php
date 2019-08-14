<?php
/**
 * GeoLock extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   CP
 * @package    CP_GeoLock
 * @author     Commercepundit<magento@commercepundit.com>
 */   
$installer = $this;

$connection = $installer->getConnection();

$installer->startSetup();

/**
 * Create Restricted cms page
 */
 
 $connection->insert($installer->getTable('cms/page'), array(
    'title'             => 'Geolock Restricted',  
    'identifier'        => 'why-not-available',
    'content'           => '<p><img src="{{skin url="images/logo.gif"}}" /></p>
<p><h2>{{config path="general/store_information/name"}} is currently not available in your country.If you find this is an error or have any questions, please contact us at {{config path="trans_email/ident_general/email"}}</h2></p>',
    'root_template'    => 'empty',
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 0
));


$installer->endSetup();
