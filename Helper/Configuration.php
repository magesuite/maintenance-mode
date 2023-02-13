<?php

declare(strict_types=1);

namespace MageSuite\MaintenanceMode\Helper;

class Configuration
{
    protected const XML_PATH_MAINTENANCE_MODE_FLAG = 'system/maintenance_mode/enabled';
    protected const XML_PATH_MAINTENANCE_MODE_IP_WHITELIST = 'system/maintenance_mode/ip_whitelist';

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
        $this->setMaintenanceModeIpWhitelist(null);
        $this->cacheManager->flush([\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER]);
    }

    public function setMaintenanceModeIpWhitelist(?string $addresses): void
    {
        if (empty($addresses)) {
            $this->resourceConfig->deleteConfig(self::XML_PATH_MAINTENANCE_MODE_IP_WHITELIST);
            return;
        }

        if (!preg_match('/^[^\s,]+(,[^\s,]+)*$/', $addresses)) {
            throw new \InvalidArgumentException("One or more IP-addresses is expected (comma-separated)\n");
        }

        $this->resourceConfig->saveConfig(self::XML_PATH_MAINTENANCE_MODE_IP_WHITELIST, $addresses);
    }

    public function getMaintenanceModeIpWhitelist(): array
    {
        $whitelist = $this->scopeConfig->getValue(self::XML_PATH_MAINTENANCE_MODE_IP_WHITELIST);

        if (!$whitelist) {
            return [];
        }

        return explode(',', $whitelist);
    }
}
