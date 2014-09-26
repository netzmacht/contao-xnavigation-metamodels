<?php

namespace Netzmacht\Contao\XNavigation\MetaModels\DataContainer;

use MetaModels\Factory;

class XNavigationProviderDataContainer
{

    /**
     * @var Factory
     */
    protected $metaModelsFactory;


    /**
     * Construct
     */
    public function __construct()
    {
        $this->metaModelsFactory = new Factory();
    }


    /**
     * Get all Metamodels table names
     *
     * @return array|\string[]
     */
    public function getMetaModels()
    {
        return $this->metaModelsFactory->getAllTables();
    }


    /**
     * @param \DataContainer $dataContainer
     * @return array
     */
    public function getAttributeNames(\DataContainer $dataContainer)
    {
        $options = array();

        if ($dataContainer->activeRecord->mm_metamodel) {
            $metaModel  = $this->metaModelsFactory->byTableName($dataContainer->mm_metamodel);
            $attributes = $metaModel->getAttributes();

            foreach($attributes as $name => $attribute) {
                $options[$name] = $attribute->getName();
            }
        }

        return $options;
    }


    /**
     * @param \DataContainer $dataContainer
     * @return array
     */
    public function getFilterNames(\DataContainer $dataContainer)
    {
        $database       = \Database::getInstance();
        $values         = array();
        $filterSettings = $database
            ->prepare('SELECT * FROM tl_metamodel_filter WHERE pid=? ORDER BY name')
            ->execute($dataContainer->activeRecord->mm_metamodel);

        while ($filterSettings->next()) {
            $values[$filterSettings->id] = $filterSettings->name;
        }

        return $values;
    }

} 