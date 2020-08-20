<?php
namespace extas\components\plugins\construct;

use extas\components\plugins\Plugin;
use extas\interfaces\IHasClass;
use extas\interfaces\IHasName;
use extas\interfaces\IHasRepository;
use extas\interfaces\IHasSection;
use extas\interfaces\IHasUid;
use extas\interfaces\plugins\IPlugin;
use extas\interfaces\plugins\IPluginInstall;
use extas\interfaces\samples\parameters\ISampleParameter;

/**
 * Class PluginInstallConstructDefault
 *
 * @package extas\components\plugins\construct
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginInstallConstructDefault extends PluginInstallConstruct
{
    /**
     * @param IPlugin $plugin
     * @param string $stage
     * @return IPlugin
     */
    public function __invoke(IPlugin $plugin, string $stage): IPlugin
    {
        if ($plugin->getClass()) {
            return $plugin;
        }

        $section = $this->getSection();
        $params = $this->getParams();
        $item = $this->getItem();

        $plugin->setClass('extas\\components\\plugins\\'.$stage.'\\'.ucfirst($stage).'PluginsInstaller')
            ->setStage('extas.' . $stage . '.section.' . $section)
            ->addParametersByValues([
                IHasRepository::FIELD__REPOSITORY => $item[IPluginInstall::FIELD__REPOSITORY],
                IHasUid::FIELD__UID => $params['pk'],
                IHasSection::FIELD__SECTION => $section,
                IHasName::FIELD__NAME => $item[IPluginInstall::FIELD__NAME],
                IHasClass::FIELD__CLASS => $params['itemClass']
            ]);

        return $plugin;
    }
}
