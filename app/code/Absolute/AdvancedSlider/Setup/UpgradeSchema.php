<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Absolute\AdvancedSlider\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /* var \Magento\Framework\Setup\SetupInterface */
        $installer = $setup->startSetup();
        /* var \Magento\Framework\DB\Adapter\AdapterInterface */
        $connection = $setup->getConnection();

        if (version_compare($context->getVersion(), '0.0.2') < 0) {
            $this->addSliderAliasColumn($connection, $installer);
        }
        $setup->endSetup();
    }

    /**
     * @param $connection \Magento\Framework\DB\Adapter\AdapterInterface
     * @param $installer \Magento\Framework\Setup\SetupInterface
     */
    private function addSliderAliasColumn($connection, $installer)
    {
        $table = $installer->getTable('absolute_advancedslider_sliders');
        $connection->addColumn($table, 'alias',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 254,
                'nullable' => false,
                'comment' => 'Alias',
                'after' => 'name'
            ],
            null
        );
    }
}
