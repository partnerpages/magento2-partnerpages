<?php
/**
 * Copyright 2016 Henrik Hedelund
 *
 * This file is part of Partnerpages_Piwik.
 *
 * Partnerpages_Piwik is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Partnerpages_Piwik is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Partnerpages_Piwik.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Partnerpages\Piwik\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Observer for `catalog_controller_product_view'
 *
 */
class ProductViewObserver implements ObserverInterface
{

    /**
     * Piwik tracker instance
     *
     * @var \Partnerpages\Piwik\Model\Tracker
     */
    protected $_piwikTracker;

    /**
     * Piwik data helper
     *
     * @var \Partnerpages\Piwik\Helper\Data $_dataHelper
     */
    protected $_dataHelper;

    /**
     * Constructor
     *
     * @param \Partnerpages\Piwik\Model\Tracker $piwikTracker
     * @param \Partnerpages\Piwik\Helper\Data $dataHelper
     */
    public function __construct(
        \Partnerpages\Piwik\Model\Tracker $piwikTracker,
        \Partnerpages\Piwik\Helper\Data $dataHelper
    ) {
        $this->_piwikTracker = $piwikTracker;
        $this->_dataHelper = $dataHelper;
    }

    /**
     * Push EcommerceView to tracker on product view page
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Partnerpages\Piwik\Observer\ProductViewObserver
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_dataHelper->isTrackingEnabled()) {
            return $this;
        }

        $product = $observer->getEvent()->getProduct();
        /* @var $product \Magento\Catalog\Model\Product */

        $category = $product->getCategory();
        $this->_piwikTracker->setEcommerceView(
            $product->getSku(),
            $product->getName(),
            $category
                ? $category->getName()
                : false,
            $product->getFinalPrice()
        );

        return $this;
    }
}
