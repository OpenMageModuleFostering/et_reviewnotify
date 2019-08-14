<?php
/**
 * NOTICE OF LICENSE
 *
 * You may not sell, sub-license, rent or lease
 * any portion of the Software or Documentation to anyone.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   ET
 * @package    ET_Reviewnotify
 * @copyright  Copyright (c) 2016 ET Web Solutions (http://etwebsolutions.com)
 * @contacts   support@etwebsolutions.com
 * @license    http://shop.etwebsolutions.com/etws-license-free-v1/   ETWS Free License (EFL1)
 */

/**
 * Class ET_Reviewnotify_Block_Review_Helper
 */
class ET_Reviewnotify_Block_Review_Helper extends Mage_Review_Block_Helper
{
    /**
     * Set correct count of reviews
     *
     * Rewrite: Mage_Review_Block_Helper
     * @return int
     */
    public function getReviewsCount()
    {
        /** @var ET_Reviewnotify_Helper_Data $helper */
        $helper = Mage::helper('reviewnotify');

        $productId = $this->getProduct()->getId();
        $collection = $helper->getReviewsCollection($productId);
        $collection = $helper->addReviewsVisibilityForAuthor($collection);

        return $collection->count();
    }

}