<?php
namespace tests\plugins\misc;

use extas\components\items\SnuffItem;
use extas\components\repositories\Repository;

/**
 * Class DynamicRepo
 *
 * @package tests\plugins\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
class DynamicRepo extends Repository
{
    protected string $name = 'snuff_items';
    protected string $itemClass = SnuffItem::class;
    protected string $pk = 'id';
    protected string $scope = 'extas';

    public function getDefaultProperties()
    {
        return [
            'name' => 'snuff_items',
            'itemClass' => SnuffItem::class,
            'pk' => 'id',
            'scope' => 'extas'
        ];
    }
}
