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
 * @license    http://shop.etwebsolutions.com/etws-license-commercial-v1/   ETWS Commercial License (ECL1)
 */

/**
 * Class ET_Reviewnotify_Model_ReviewStatuses
 */
class ET_Reviewnotify_Model_ReviewStatuses extends Mage_Review_Model_Resource_Review_Status_Collection
{
    /**
     * Options List
     */
    public function toOptionArray()
    {
        $statuses = parent::toOptionArray();

        $statusList = array();

        foreach ($statuses as $status) {
            if ($status['value'] != Mage_Review_Model_Review::STATUS_APPROVED) {
                $statusList[] = array('value' => $status['value'], 'label' => $status['label']);
            }
        };

        return $statusList;
    }
}