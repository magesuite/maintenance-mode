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

    public function afterIsOn(
        \Magento\Framework\App\MaintenanceMode $subject,
        bool $result,
        string $remoteAddr = ''
    ): bool {
        if (!$this->configuration->isMaintenanceModeOn()) {
            return false;
        }

        $ipWhitelist = $this->configuration->getMaintenanceModeIpWhitelist();
        return !in_array($remoteAddr, $ipWhitelist);
    }

    public function aroundSet(\Magento\Framework\App\MaintenanceMode $subject, callable $proceed, bool $isOn): void
    {
        $this->configuration->setMaintenanceModeFlag($isOn);
    }

    public function aroundSetAddresses(
        \Magento\Framework\App\MaintenanceMode $subject,
        callable $proceed,
        ?string $addresses
    ): void {
        $this->configuration->setMaintenanceModeIpWhitelist($addresses);
    }
}
