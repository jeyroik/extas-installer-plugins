<?php
namespace extas\interfaces\stages;

use extas\interfaces\plugins\IPlugin;

/**
 * Interface IStagePluginInstallConstruct
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStagePluginInstallConstruct
{
    public const NAME = 'extas.plugin.install.construct';

    public const FIELD__SECTION = 'section';
    public const FIELD__PARAMS = 'params';
    public const FIELD__ITEM = 'item';

    /**
     * @param IPlugin $plugin
     * @param string $stage
     * @return IPlugin
     */
    public function __invoke(IPlugin $plugin, string $stage): IPlugin;

    /**
     * @return string
     */
    public function getSection(): string;

    /**
     * @return array
     */
    public function getParams(): array;

    /**
     * @return array
     */
    public function getItem(): array;
}
