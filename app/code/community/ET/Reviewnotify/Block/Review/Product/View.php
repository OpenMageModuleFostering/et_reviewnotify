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
 * Class ET_Reviewnotify_Block_Review_Product_View
 */
class ET_Reviewnotify_Block_Review_Product_View extends Mage_Review_Block_Product_View
{
    /**
     * Create reviews collection
     *
     * Rewrite Mage_Review_Block_Product_View
     * @return Mage_Review_Model_Resource_Review_Collection
     */
    public function getReviewsCollection()
    {
        /** @var ET_Reviewnotify_Helper_Data $helper */
        $helper = Mage::helper('reviewnotify');

        if (null === $this->_reviewsCollection) {
            $productId = $this->getProduct()->getId();
            $this->_reviewsCollection = $helper->getReviewsCollection($productId);
            $this->_reviewsCollection = $helper->addReviewsVisibilityForAuthor($this->_reviewsCollection);
        }

        return $this->_reviewsCollection;
    }
}