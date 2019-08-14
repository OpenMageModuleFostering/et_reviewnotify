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
 * @copyright  Copyright (c) 2012 ET Web Solutions (http://etwebsolutions.com)
 * @contacts   support@etwebsolutions.com
 * @license    http://shop.etwebsolutions.com/etws-license-free-v1/   ETWS Free License (EFL1)
 */

/**
 * Class ET_Reviewnotify_Model_Observer
 */
class ET_Reviewnotify_Model_Observer
{

    const ANTI_SPAM = 'default/catalog/review/antispam';

    /**
     * Send notification email about now product review
     *
     * @param Varien_Event_Observer $observer
     * @throws Zend_Date_Exception
     */
    public function sendNotificationEmail($observer)
    {
        $needSend = Mage::getStoreConfig('catalog/review/need_send');
        $mailTo = Mage::getStoreConfig('catalog/review/email_to');
        $emailTemplate = Mage::getStoreConfig('catalog/review/email_template');
        $emailIdentity = Mage::getStoreConfig('catalog/review/email_identity');

        /** @var Mage_Review_Model_Review $review */
        $review = $observer->getData('object');

        if (($needSend) && (strlen(trim($mailTo)) > 0)) {
            /** @var Mage_Catalog_Model_Product $product */
            $product = Mage::getModel('catalog/product')->load($review->getEntityPkValue());
            /** @var Mage_Core_Model_Email_Template $templateModel */
            $templateModel = Mage::getModel('core/email_template')->setDesignConfig(array('area' => 'backend'));

            $recipients = explode(",", $mailTo);
            foreach ($recipients as $recipient) {
                $datetime = Zend_Date::now();
                $datetime->setLocale(Mage::getStoreConfig(
                    Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE))
                    ->setTimezone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));

                $templateModel->sendTransactional(
                    $emailTemplate,
                    $emailIdentity,
                    trim($recipient),
                    trim($recipient),
                    array(
                        "product" => $product->getName() . " (sku: " . $product->getSku() . ")",
                        "title" => $review->getData('title'),
                        "nickname" => $review->getData('nickname'),
                        "details" => $review->getData('detail'),
                        "id" => $review->getId(),
                        'date' => $datetime->get(Zend_Date::DATETIME_MEDIUM)
                    )
                );
            }
        }
    }

    /**
     * Review model rewrite for old Magento version.
     *
     * Event: review_save_after
     * @param Varien_Event_Observer $observer
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function rewriteReviewModel(Varien_Event_Observer $observer)
    {
        if (version_compare(Mage::getVersion(), '1.4', '<')) {
            Mage::getConfig()->setNode(
                'global/models/review/rewrite/review',
                'ET_Reviewnotify_Model_Review'
            );
        }
    }

    /**
     * Dynamic controller rewrite Mage_Review_ProductController
     *
     * @param Varien_Event_Observer $observer
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function rewriteReviewProductController(Varien_Event_Observer $observer)
    {
        $isFixEnabled = (int)Mage::getConfig()->getNode(self::ANTI_SPAM);

        if ($isFixEnabled) {
            Mage::getConfig()->setNode('global/rewrite/review/from',
                '#^/review/product/#');

            Mage::getConfig()->setNode('global/rewrite/review/to',
                '/etreviewnotify/product/');

        }

    }

    /**
     * Dynamic rewrite
     *
     * Event: controller_front_init_before
     * @param Varien_Event_Observer $observer
     */
    public function rewriteReviewProductBlockViewList(Varien_Event_Observer $observer)
    {
        /** @var ET_Reviewnotify_Helper_Data $helper */
        $helper = Mage::helper('reviewnotify');

        $isEnable = $helper->isEnableReviewVisibilityForAuthor();

        if ($isEnable) {
            Mage::getConfig()->setNode('global/blocks/review/rewrite/product_view',
                'ET_Reviewnotify_Block_Review_Product_View');
            Mage::getConfig()->setNode('global/blocks/review/rewrite/product_view_list',
                'ET_Reviewnotify_Block_Review_Product_View_List');
            Mage::getConfig()->setNode('global/blocks/review/rewrite/helper',
                'ET_Reviewnotify_Block_Review_Helper');
        }
    }
}