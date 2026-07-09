<?php

declare(strict_types=1);

namespace Dg\FlashAlert\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Provides typed access to Flash Alert system configuration values.
 */
class Config
{
    private const XML_PATH_ENABLED = 'flashalert/general/enabled';
    private const XML_PATH_BACKGROUND_COLOR = 'flashalert/design/background_color';
    private const XML_PATH_TEXT_COLOR = 'flashalert/design/text_color';
    private const XML_PATH_FONT_SIZE = 'flashalert/design/font_size';
    private const XML_PATH_BORDER_RADIUS = 'flashalert/design/border_radius';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
    }

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getBackgroundColor(?int $storeId = null): string
    {
        return $this->getValue(self::XML_PATH_BACKGROUND_COLOR, $storeId, '#1F2937');
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getTextColor(?int $storeId = null): string
    {
        return $this->getValue(self::XML_PATH_TEXT_COLOR, $storeId, '#FFFFFF');
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getFontSize(?int $storeId = null): string
    {
        $value = $this->getValue(self::XML_PATH_FONT_SIZE, $storeId, '16');

        return is_numeric($value) ? $value . 'px' : $value;
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getBorderRadius(?int $storeId = null): string
    {
        $value = $this->getValue(self::XML_PATH_BORDER_RADIUS, $storeId, '0');

        return is_numeric($value) ? $value . 'px' : $value;
    }

    /**
     * @param string $path
     * @param int|null $storeId
     * @param string $default
     * @return string
     */
    private function getValue(string $path, ?int $storeId, string $default = ''): string
    {
        $value = (string)$this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
        return $value !== '' ? $value : $default;
    }
}
