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
 * Class ET_Reviewnotify_Helper_Data
 */
class ET_Reviewnotify_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Generate reviews collection
     *
     * @param int $productId
     * @return mixed
     */
    public function getReviewsCollection($productId)
    {
        $collection = Mage::getModel('review/review')->getCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addEntityFilter('product', $productId)
            ->setDateOrder();
        return $collection;
    }

    /**
     * Change review collection for frontend
     * Add additional review statuses, depending on extension settings
     * Author can see Pending and Not Approved reviews, if configured.
     *
     * @param Mage_Review_Model_Resource_Review_Collection $collection
     * @return Mage_Review_Model_Resource_Review_Collection $collection
     */
    public function addReviewsVisibilityForAuthor($collection)
    {
        $enable = $this->isEnableReviewVisibilityForAuthor();
        $customerId = Mage::getModel('customer/session')->getData('id');

        if (($enable) && (null !== $customerId)) {
            $statuses = $this->getReviewVisibilityForAuthor();
            $sql = '((status_id IN (' . implode(', ', $statuses) . ')) AND (customer_id = '
                . $customerId . ')) OR (status_id = ' . Mage_Review_Model_Review::STATUS_APPROVED . ')';

            $collection->getSelect()->where($sql);
        } else {
            $collection->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED);
        }

        return $collection;
    }

    /**
     * Is Function review visibility for author enabled or not
     *
     * @return bool
     */
    public function isEnableReviewVisibilityForAuthor()
    {
        return (bool)Mage::getStoreConfig('catalog/review/is_enabled_review_visibility_for_author');
    }

    /**
     * Get review statuses that should be visible for author
     * by default only approved
     *
     * @return array
     */
    public function getReviewVisibilityForAuthor()
    {

        $statuses = Mage::getStoreConfig('catalog/review/review_visibility_for_author');
        $statuses = explode(',', $statuses);
        $statuses[] = (string)Mage_Review_Model_Review::STATUS_APPROVED;

        return $statuses;
    }
}