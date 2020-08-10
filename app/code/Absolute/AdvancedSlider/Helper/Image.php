<?php

namespace Absolute\AdvancedSlider\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Image extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Custom directory relative to the "media" folder
     */
    const DIRECTORY = 'absolute_advancedslider/image';

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $_mediaDirectory;

    /**
     * @var \Magento\Framework\Image\Factory
     */
    protected $_imageFactory;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Image\Factory $imageFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_imageFactory = $imageFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * First check this file on FS
     *
     * @param string $filename
     * @return bool
     */
    protected function _fileExists($filename)
    {
        if ($this->_mediaDirectory->isFile($filename)) {
            return true;
        }
        return false;
    }

    /**
     * Resize image
     * @return string
     */
    public function resize($image, $width = null, $height = null)
    {
        $mediaFolder = self::DIRECTORY;

        $path = $mediaFolder . '/cache';
        if ($width !== null) {
            $path .= '/' . $width . 'x';
            if ($height !== null) {
                $path .= $height ;
            }
        }

        $absolutePath = $this->_mediaDirectory->getAbsolutePath($mediaFolder) . DIRECTORY_SEPARATOR . $image;
        $imageResized = $this->_mediaDirectory->getAbsolutePath($path) . DIRECTORY_SEPARATOR . $image;

        if (!$this->_fileExists($path . $image)) {

            try {
                // open the image
                $imageFactory = $this->_imageFactory->create();
                $imageFactory->open($absolutePath);

                // return the original if it's smaller than the required width
                if ($imageFactory->getOriginalWidth() < $width) {
                    return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $mediaFolder . DIRECTORY_SEPARATOR . $image;
                }

                // or create the resized version
                $imageFactory->constrainOnly(false);
                $imageFactory->keepFrame(false);
                $imageFactory->keepTransparency(true);
                $imageFactory->keepAspectRatio(true);
                $imageFactory->resize($width, $height);
                $imageFactory->save($imageResized);

            } catch (\Exception $e) {
                // if something happens above, don't return an image
                return false;
            }
        }

        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $path . DIRECTORY_SEPARATOR . $image;
    }
}