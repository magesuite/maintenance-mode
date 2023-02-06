<?php

declare(strict_types=1);

namespace MageSuite\MaintenanceMode\Helper;

class Configuration
{
    protected const XML_PATH_MAINTENANCE_MODE_FLAG = 'system/maintenance_mode/enabled';

    protected \Magento\Framework\App\Config\ConfigResource\ConfigInterface $resourceConfig;
    protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig;
    protected \Magento\Framework\App\Cache\Manager $cacheManager;

    public function __construct(
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $resourceConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Cache\Manager $cacheManager
    ) {
        $this->resourceConfig = $resourceConfig;
        $this->scopeConfig = $scopeConfig;
        $this->cacheManager = $cacheManager;
    }

    public function isMaintenanceModeOn(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_MAINTENANCE_MODE_FLAG);
    }

    public function setMaintenanceModeFlag(bool $isOn): void
    {
        $this->resourceConfig->saveConfig(self::XML_PATH_MAINTENANCE_MODE_FLAG, $isOn ? 1 : 0);
        $this->cacheManager->flush([\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER]);
    }
}
