<?php
declare(strict_types=1);

namespace PmnGarage\Application\Settings;

interface SettingsInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key = ''): mixed;
}
