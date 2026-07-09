<?php

declare(strict_types=1);

namespace Dg\FlashAlert\ViewModel;

use Dg\FlashAlert\Model\Config;
use Dg\FlashAlert\Setup\Patch\Data\AddFlashAlertTextAttribute;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * View model exposing Flash Alert banner data to templates.
 */
final class FlashAlert implements ArgumentInterface
{
    /**
     * @param Config $config
     * @param Registry $registry
     */
    public function __construct(
        private readonly Config   $config,
        private readonly Registry $registry,
    ) {
    }

    /**
     * @return bool
     */
    public function shouldDisplay(): bool
    {
        return $this->config->isEnabled() && $this->hasAlertText();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    /**
     * @return bool
     */
    public function hasAlertText(): bool
    {
        return trim($this->getAlertText()) !== '';
    }

    /**
     * @return string
     */
    public function getAlertText(): string
    {
        return (string)($this->getCurrentProduct()?->getData(AddFlashAlertTextAttribute::ATTRIBUTE_CODE) ?? '');
    }

    /**
     * @return Product|null
     */
    public function getCurrentProduct(): ?Product
    {
        $product = $this->registry->registry('current_product');
        return $product instanceof Product ? $product : null;
    }

    /**
     * @return string
     */
    public function getBackgroundColor(): string
    {
        return $this->config->getBackgroundColor();
    }

    /**
     * @return string
     */
    public function getTextColor(): string
    {
        return $this->config->getTextColor();
    }

    /**
     * @return string
     */
    public function getFontSize(): string
    {
        return $this->config->getFontSize();
    }

    /**
     * @return string
     */
    public function getBorderRadius(): string
    {
        return $this->config->getBorderRadius();
    }
}
