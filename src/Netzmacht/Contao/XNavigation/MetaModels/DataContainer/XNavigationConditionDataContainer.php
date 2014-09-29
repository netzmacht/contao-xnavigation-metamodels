<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 29.09.14
 * Time: 10:51
 */

namespace Netzmacht\Contao\XNavigation\MetaModels\DataContainer;


use MetaModels\Factory;

class XNavigationConditionDataContainer
{
    public function getMetaModels()
    {
        return Factory::getAllTables();
    }

} 