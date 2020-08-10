<?php
namespace Absolute\AdvancedSlider\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        //START table setup
        $this->createSlidesTable($installer);
        $this->createSlidersTable($installer);
        $this->addForeignKeys($installer);
        //END   table setup
        $installer->endSetup();
    }

    public function createSlidesTable($installer)
    {
        $table = $installer->getConnection()->newTable(
            $installer->getTable('absolute_advancedslider_slides')
        )
            ->addColumn('slide_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Slide ID'
            )
            ->addColumn('slider_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => false, 'nullable' => false],
                'Slider Id'
            )
            ->addColumn('store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => 0],
                'Store Id'
            )
            ->addColumn('title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => true, 'default' => null],
                'Title'
            )
            ->addColumn('content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => true, 'default' => null],
                'Content'
            )
            ->addColumn('content_colour',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                254,
                [],
                'Content Background Colour'
            )
            ->addColumn('image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                [],
                'Image'
            )
            ->addColumn('mobile_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => true, 'default' => null],
                'Mobile Image'
            )
            ->addColumn('image_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                10,
                ['nullable' => false, 'unsigned' => true, 'default' => 'full'],
                'Image position'
            )
            ->addColumn('background_colour',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                20,
                [],
                'Slide Background Colour'
            )
            ->addColumn('link',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => true, 'default' => null],
                'Link'
            )
            ->addColumn('order',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                'Order'
            )
            ->addColumn('pagination_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => true, 'default' => null],
                'Link'
            )
            ->addColumn('active_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Active from'
            )
            ->addColumn('active_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Active to'
            )
            ->addColumn('created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At Time'
            )
            ->addColumn('updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update At Time'
            );

        $installer->getConnection()->createTable($table);
    }

    public function createSlidersTable($installer)
    {
        $table = $installer->getConnection()->newTable(
            $installer->getTable('absolute_advancedslider_sliders')
        )
            ->addColumn(
                'slider_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'identity' => true,
                    'primary' => true
                ],
                'Slider ID'
            )->addColumn('store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => 0],
                'Store Id'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                264,
                ['nullable' => true, 'default' => null],
                'Name'
            )
            ->addColumn('delay',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Delay'
            )
            ->addColumn('autoplay',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Autoplay'
            )
            ->addColumn('transition',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                30,
                ['nullable' => true, 'unsigned' => true],
                'Transition'
            )
            ->addColumn('pagination',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Pagination'
            )
            ->addColumn('arrows',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Arrows'
            )
            ->addColumn('retina_image_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Retina Image Size'
            )
            ->addColumn('retina_mobile_image_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Retina Mobile Image Size'
            )
            ->addColumn('created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->addColumn('updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            );

        $installer->getConnection()->createTable($table);
    }

    public function addForeignKeys($installer)
    {
        $installer->getConnection()->addForeignKey(
            $installer->getFkName('absolute_advancedslider_slides', 'slider_id', 'absolute_advancedslider_sliders', 'slider_id'),
            $installer->getTable('absolute_advancedslider_slides'),
            'slider_id',
            $installer->getTable('absolute_advancedslider_sliders'),
            'slider_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );

        $installer->getConnection()->addForeignKey(
            $installer->getFkName('absolute_advancedslider_slides', 'store_id', 'store', 'store_id'),
            $installer->getTable('absolute_advancedslider_slides'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );

        $installer->getConnection()->addForeignKey(
            $installer->getFkName('absolute_advancedslider_sliders', 'store_id', 'store', 'store_id'),
            $installer->getTable('absolute_advancedslider_sliders'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );
    }

}
