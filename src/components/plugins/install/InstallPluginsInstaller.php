<?php
namespace extas\components\plugins\install;

use extas\components\plugins\TInitInstallParams;

/**
 * Class InstallPluginsInstaller
 *
 * @package extas\components\plugins\install
 * @author jeyroik <jeyroik@gmail.com>
 */
class InstallPluginsInstaller extends InstallSection
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
