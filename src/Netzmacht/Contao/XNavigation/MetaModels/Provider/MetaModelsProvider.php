<?php


namespace Netzmacht\Contao\XNavigation\MetaModels\Provider;

use Bit3\Contao\XNavigation\XNavigationEvents;
use Bit3\FlexiTree\Event\CollectItemsEvent;
use Bit3\FlexiTree\Event\CreateItemEvent;
use MetaModels\Filter\Setting\ICollection as MetaModelsFilterCollection;
use MetaModels\IItem;
use MetaModels\IMetaModel;
use MetaModels\Render\Setting\ICollection as MetaModelsRenderSetting;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MetaModelsProvider extends \Controller implements EventSubscriberInterface
{
    /**
     * @var IMetaModel
     */
    private $metaModel;

    /**
     * @var MetaModelsFilterCollection
     */
    private $filter;

    /**
     * @var array
     */
    private $labelAttributes = array();

    /**
     * @var string|bool
     */
    private $labelPattern = false;

    /**
     * @var array
     */
    private $titleAttributes = array();

    /**
     * @var string|bool
     */
    private $titlePattern = false;

    /**
     * @var string
     */
    private $sortBy;

    /**
     * @var string
     */
    private $parentType;

    /**
     * @var int|string
     */
    private $parentName;

    /**
     * @var string
     */
    private $sortDirection = 'ASC';

    /**
     * @var array
     */
    private $filterParams = array();

    /**
     * @var array|IItem[]
     */
    protected static $cache = array();

    /**
     * @var MetaModelsRenderSetting
     */
    private $renderSetting;


    /**
     *
     */
    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @param IMetaModel $metaModel
     * @return MetaModelsProvider
     */
    public static function create(IMetaModel $metaModel)
    {
        $provider = new static();
        $provider->setMetaModel($metaModel);

        return $provider;
    }



    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            XNavigationEvents::CREATE_ITEM   => 'createItem',
            XNavigationEvents::COLLECT_ITEMS => array('collectItems', 100),
        );
    }

    /**
     * @return MetaModelsFilterCollection
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param MetaModelsFilterCollection $filter
     * @param $params
     * @return $this
     */
    public function setFilter(MetaModelsFilterCollection $filter, $params=array())
    {
        $this->filter       = $filter;
        $this->filterParams = $params;

        return $this;
    }

    /**
     * @return array
     */
    public function getLabelAttributes()
    {
        return $this->labelAttributes;
    }

    /**
     * @param array $attributes
     * @param $pattern
     * @return $this
     */
    public function setLabel(array $attributes, $pattern='%s')
    {
        $this->labelAttributes = $attributes;
        $this->labelPattern    = $pattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelPattern()
    {
        return $this->labelPattern;
    }

    /**
     * @return IMetaModel
     */
    public function getMetaModel()
    {
        return $this->metaModel;
    }

    /**
     * @param IMetaModel $metaModel
     * @return $this
     */
    public function setMetaModel($metaModel)
    {
        $this->metaModel = $metaModel;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }


    /**
     * @return string
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }


    /**
     * @param $sortBy
     * @param string $sortDirection
     * @return $this
     */
    public function setSorting($sortBy, $sortDirection='ASC')
    {
        $this->sortBy        = $sortBy;
        $this->sortDirection = $sortDirection;

        return $this;
    }

    /**
     * @return array
     */
    public function getTitleAttributes()
    {
        return $this->titleAttributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setTitle($attributes, $pattern='%s')
    {
        $this->titleAttributes = $attributes;
        $this->titlePattern    = $pattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitlePattern()
    {
        return $this->titlePattern;
    }


    /**
     * @param int|string $type
     * @param $name
     * @return $this
     */
    public function setParent($type, $name)
    {
        $this->parentType = $type;
        $this->parentName = $name;

        return $this;
    }

    /**
     * @return int|string
     */
    public function getParentName()
    {
        return $this->parentName;
    }


    /**
     * @return string
     */
    public function getParentType()
    {
        return $this->parentType;
    }

    /**
     * @return MetaModelsRenderSetting
     */
    public function getRenderSetting()
    {
        return $this->renderSetting;
    }

    /**
     * @param MetaModelsRenderSetting $renderSetting
     * @return $this
     */
    public function setRenderSetting(MetaModelsRenderSetting $renderSetting)
    {
        $this->renderSetting = $renderSetting;

        return $this;
    }

    /**
     * @param CollectItemsEvent $event
     */
    public function collectItems(CollectItemsEvent $event)
    {
        $item = $event->getParentItem();

        // match pointing point
        if($item->getType() != $this->getParentType() || $item->getName() != $this->getParentName()) {
            return;
        }

        $collection = $this->fetchMetaModelsItems();
        $factory    = $event->getFactory();

        foreach($collection as $model) {
            $name  = sprintf('%s:%s', $model->getMetaModel()->getTableName(), $model->get('id'));

            static::$cache[$name] = $model;
            $factory->createItem('metamodels', $name, $item);
        }
    }


    /**
     * @param CreateItemEvent $event
     */
    public function createItem(CreateItemEvent $event)
    {
        $item = $event->getItem();
        $name = $item->getName();

        if($item->getType() != 'metamodels') {
            return;
        }

        $model = $this->loadModel($name);

        if(!$model) {
            return;
        }

        $value = $model->parseValue('text', $this->renderSetting);
        var_dump($value);

        $item
            ->setLabel($this->generateLabel($model))
            ->setAttribute('title', $this->generateTitle($model))
            ->setExtra('model', $model);

        // TODO: set current and trail
    }


    /**
     * @return \MetaModels\IItems
     */
    public function fetchMetaModelsItems()
    {
        $filter = $this->metaModel->getEmptyFilter();

        if ($this->filter) {
            $filter->addFilterRule($this->filter, $this->filterParams);
        }

        $sortBy = '';

        if($this->sortBy) {
            $attribute = $this->metaModel->getAttributeById($this->sortBy);

            if($attribute) {
                $sortBy = $attribute->getColName();
            }
        }

        return $this->metaModel->findByFilter($filter, $sortBy, 0, 0, $this->sortDirection);
    }


    /**
     * @param $model
     * @return string
     */
    private function generateLabel(IItem $model)
    {
        $values = array();

        foreach ($this->labelAttributes as $config) {
            $attribute = $this->metaModel->getAttributeById($config['id']);

            if(!$attribute) {
                continue;
            }

            $values[]  = $model->get($attribute->getColName());
        }

        if ($this->labelPattern) {
            $label = vsprintf($this->labelPattern, $values);
        }
        else {
            $label = implode(' ', $values);
        }

        $label = $this->replaceInsertTags($label);

        return $label;
    }


    /**
     * @param IItem $model
     * @return string
     */
    private function generateTitle(IItem $model)
    {
        $values = array();

        if ($this->titleAttributes) {
            foreach($this->titleAttributes as $config) {
                $attribute = $this->metaModel->getAttributeById($config['id']);

                if (!$attribute) {
                    continue;
                }

                $parsed = $model->parseAttribute($attribute->getColName(), $config['format'], $this->renderSetting);

                if (isset($parsed[$config['format']])) {
                    $values[] = isset($parsed[$config['format']]);
                }
                else {
                    $values[] = $model->get($attribute->getColName());
                }
            }

            if ($this->titlePattern) {
                $label = vsprintf($this->titlePattern, $values);
            }
            else {
                $label = implode(' ', $values);
            }

            $label = $this->replaceInsertTags($label);
        }
        else {
            $label = $this->generateLabel($model);
        }

        return specialchars($label);
    }


    /**
     * @param $name
     * @return IItem|null
     */
    private function loadModel($name)
    {
        if(isset(static::$cache[$name])) {
            return static::$cache[$name];
        }

        list($table, $id) = explode(':', $name, 2);

        if($table != $this->metaModel->getTableName()) {
            return null;
        }

        return $this->metaModel->findById($id);
    }

} 