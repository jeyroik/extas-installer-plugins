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
use extas\interfaces\stages\IStagePluginInstallConstruct;

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

            if (!empty($params)) {
                $itemSection = $item[IPluginInstall::FIELD__SECTION] ?? '';
                $section = $itemSection ?: $params['name'];
                $pluginRepository = new PluginRepository();

                $pluginRepository->create($this->createPluginInstall($section, $params, $item));
                $this->commentLn(['Created install plugin for ' . $section]);

                $pluginRepository->create($this->createPluginUninstall($section, $params, $item));
                $this->commentLn(['Created uninstall plugin for ' . $section]);
            } else {
                $this->infoLn(['Skip item, cause repository "' . $repositoryClass . '" is not initialized yet.']);
            }
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
        try {
            $repo = $this->$repositoryClass();
        } catch (\Exception $e) {
            /**
             * Repository not initialized yet.
             * Will create plugins after repository will be initialized (see AfterRepositoriesInitPlugins for details).
             */
            return [];
        }

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
        $newPlugin = new Plugin();
        $config = [
            IStagePluginInstallConstruct::FIELD__SECTION => $section,
            IStagePluginInstallConstruct::FIELD__PARAMS => $params,
            IStagePluginInstallConstruct::FIELD__ITEM => $item
        ];

        foreach ($this->getPluginsByStage(IStagePluginInstallConstruct::NAME, $config) as $plugin) {
            /**
             * @var IStagePluginInstallConstruct $plugin
             */
            $newPlugin = $plugin($newPlugin, $stage);
        }

        return $newPlugin;
    }
}
