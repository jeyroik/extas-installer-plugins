<?php
namespace extas\components\plugins\construct;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStagePluginInstallConstruct;

/**
 * Class PluginInstallConstruct
 *
 * @package extas\components\plugins\construct
 * @author jeyroik <jeyroik@gmail.com>
 */
abstract class PluginInstallConstruct extends Plugin implements IStagePluginInstallConstruct
{
    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->config[static::FIELD__SECTION] ?? '';
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->config[static::FIELD__PARAMS] ?? [];
    }

    /**
     * @return array
     */
    public function getItem(): array
    {
        return $this->config[static::FIELD__ITEM] ?? [];
    }
}
