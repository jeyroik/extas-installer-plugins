<?php
namespace extas\interfaces\plugins;

use extas\interfaces\IHasName;
use extas\interfaces\IHasRepository;
use extas\interfaces\IHasSection;
use extas\interfaces\IItem;

/**
 * Interface IPluginInstall
 *
 * @package extas\interfaces\plugins
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IPluginInstall extends IItem, IHasName, IHasSection, IHasRepository
{
    public const SUBJECT = 'extas.plugin.install';
}
