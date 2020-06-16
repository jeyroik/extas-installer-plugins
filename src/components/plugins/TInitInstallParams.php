<?php
namespace extas\components\plugins;

use extas\interfaces\IHasClass;
use extas\interfaces\IHasName;
use extas\interfaces\IHasRepository;
use extas\interfaces\IHasSection;
use extas\interfaces\IHasUid;

/**
 * Trait TInitInstallParams
 *
 * @package extas\components\plugins
 * @author jeyroik <jeyroik@gmail.com>
 */
trait TInitInstallParams
{
    protected function initInstallParams(): void
    {
        $this->selfSection = (string) $this->getParameterValue(IHasSection::FIELD__SECTION, '');
        $this->selfName = (string) $this->getParameterValue(IHasName::FIELD__NAME, '');
        $this->selfRepositoryClass = (string) $this->getParameterValue(
            IHasRepository::FIELD__REPOSITORY,
            ''
        );
        $this->selfUID = (string) $this->getParameterValue(IHasUid::FIELD__UID, '');
        $this->selfItemClass = (string) $this->getParameterValue(IHasClass::FIELD__CLASS, '');
    }
}
