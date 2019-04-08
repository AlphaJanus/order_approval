<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-03-25
 * Time: 16:23
 */

namespace Netzexpert\OrderApproval\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('quote'),
            'is_order',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'nullable' => true,
                'comment'  => 'Check if order is approved'
            ]
        );
        $setup->endSetup();
    }
}