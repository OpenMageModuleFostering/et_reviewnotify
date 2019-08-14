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
 * Class ET_Reviewnotify_Block_Adminhtml_System_Config_Form_Field_Linktoinfo
 */
class ET_Reviewnotify_Block_Adminhtml_System_Config_Form_Field_Linktoinfo
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     *
     * @inheritdoc
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        /** @var ET_Reviewnotify_Helper_Data $helper */
        $helper = Mage::helper('reviewnotify');

        $html = '<a href="' . $this->getUrl('*/system_config/edit', array('section' => 'reviewnotify')) . '">' .
            $helper->__('Extension information') . '</a>';
        return $html;
    }
}