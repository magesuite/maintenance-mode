<?php

declare(strict_types=1);

namespace MageSuite\MaintenanceMode\Plugin\Framework\App\MaintenanceMode;

class UseDbFlagInsteadOfFile
{
    protected \MageSuite\MaintenanceMode\Helper\Configuration $configuration;

    public function __construct(
        \MageSuite\MaintenanceMode\Helper\Configuration $configuration
    ) {
        $this->configuration = $configuration;
    }

    public function afterIsOn(\Magento\Framework\App\MaintenanceMode $subject, bool $result): bool
    {
        return $this->configuration->isMaintenanceModeOn();
    }

    public function aroundSet(\Magento\Framework\App\MaintenanceMode $subject, callable $proceed, bool $isOn)
    {
        $this->configuration->setMaintenanceModeFlag($isOn);
    }
}
