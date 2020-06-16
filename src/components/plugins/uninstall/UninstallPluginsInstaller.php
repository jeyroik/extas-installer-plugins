<?php
namespace extas\components\plugins\uninstall;

use extas\components\plugins\TInitInstallParams;

/**
 * Class UninstallPluginsInstaller
 *
 * @package extas\components\plugins\uninstall
 * @author jeyroik <jeyroik@gmail.com>
 */
class UninstallPluginsInstaller extends UninstallSection
{
    use TInitInstallParams;

    /**
     * InstallPluginsInstaller constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->initInstallParams();
    }
}
