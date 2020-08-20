<?php
namespace tests\plugins\misc;

use extas\components\plugins\construct\PluginInstallConstruct;
use extas\components\plugins\construct\PluginInstallConstructDefault;
use extas\interfaces\plugins\IPlugin;

/**
 * Class PluginConstruct
 * @package tests\plugins\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginConstruct extends PluginInstallConstruct
{
    /**
     * @param IPlugin $plugin
     * @param string $stage
     * @return IPlugin
     */
    public function __invoke(IPlugin $plugin, string $stage): IPlugin
    {
        $plugin->setClass(PluginInstallConstructDefault::class);

        return $plugin;
    }
}
