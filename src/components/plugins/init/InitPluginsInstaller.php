<?php
namespace extas\components\plugins\init;

use extas\components\plugins\Plugin;
use extas\components\plugins\PluginRepository;
use extas\interfaces\IHasClass;
use extas\interfaces\IHasName;
use extas\interfaces\IHasRepository;
use extas\interfaces\IHasSection;
use extas\interfaces\IHasUid;
use extas\interfaces\plugins\IPlugin;
use extas\interfaces\plugins\IPluginInstall;
use extas\interfaces\samples\parameters\ISampleParameter;

/**
 * Class InitPluginsInstaller
 *
 * @package extas\components\plugins\init
 * @author jeyroik <jeyroik@gmail.com>
 */
class InitPluginsInstaller extends InitSection
{
    /**
     * @param array $item
     * @throws \ReflectionException
     */
    protected function initItem(array $item): void
    {
        $this->writeLn(['Initializing item...']);

        $repositoryClass = $item[IPluginInstall::FIELD__REPOSITORY] ?? '';
        if ($repositoryClass) {
            $params = $this->getRepoParams($repositoryClass);
            $itemSection = $item[IPluginInstall::FIELD__SECTION] ?? '';
            $section = $itemSection ?: $params['name'];
            $pluginRepository = new PluginRepository();

            $pluginRepository->create($this->createPluginInstall($section, $params, $item));
            $this->commentLn(['Created install plugin for ' . $section]);

            $pluginRepository->create($this->createPluginUninstall($section, $params, $item));
            $this->commentLn(['Created uninstall plugin for ' . $section]);
        }

        $this->writeLn(['Item initialized.']);
    }

    /**
     * @param string $repositoryClass
     * @return array
     * @throws \ReflectionException
     */
    protected function getRepoParams(string $repositoryClass): array
    {
        $repo = $this->$repositoryClass();

        if (method_exists($repo, 'getDefaultProperties')) {
            return $repo->getDefaultProperties();
        }

        $repository = new \ReflectionClass($repo);

        return $repository->getDefaultProperties();
    }

    /**
     * @param string $section
     * @param array $params
     * @param array $item
     * @return Plugin
     */
    protected function createPluginInstall(string $section, array $params, array $item): Plugin
    {
        return $this->createPluginConfig('install', $section, $params, $item);
    }

    /**
     * @param string $section
     * @param array $params
     * @param array $item
     * @return Plugin
     */
    protected function createPluginUninstall(string $section, array $params, array $item): Plugin
    {
        return $this->createPluginConfig('uninstall', $section, $params, $item);
    }

    /**
     * @param string $stage
     * @param string $section
     * @param array $params
     * @param array $item
     * @return Plugin
     */
    protected function createPluginConfig(string $stage, string $section, array $params, array $item): Plugin
    {
        return new Plugin([
            IPlugin::FIELD__CLASS => 'extas\\components\\plugins\\'.$stage.'\\'.ucfirst($stage).'PluginsInstaller',
            IPlugin::FIELD__STAGE => 'extas.' . $stage . '.section.' . $section,
            IPlugin::FIELD__PARAMETERS => [
                IHasRepository::FIELD__REPOSITORY => [
                    ISampleParameter::FIELD__NAME => IHasRepository::FIELD__REPOSITORY,
                    ISampleParameter::FIELD__VALUE => $item[IPluginInstall::FIELD__REPOSITORY]
                ],
                IHasUid::FIELD__UID => [
                    ISampleParameter::FIELD__NAME => IHasUid::FIELD__UID,
                    ISampleParameter::FIELD__VALUE => $params['pk']
                ],
                IHasSection::FIELD__SECTION => [
                    ISampleParameter::FIELD__NAME => IHasSection::FIELD__SECTION,
                    ISampleParameter::FIELD__VALUE => $section
                ],
                IHasName::FIELD__NAME => [
                    ISampleParameter::FIELD__NAME => IHasName::FIELD__NAME,
                    ISampleParameter::FIELD__VALUE => $item[IPluginInstall::FIELD__NAME] ?? ''
                ],
                IHasClass::FIELD__CLASS => [
                    ISampleParameter::FIELD__NAME => IHasClass::FIELD__CLASS,
                    ISampleParameter::FIELD__VALUE => $params['itemClass']
                ]
            ]
        ]);
    }
}
