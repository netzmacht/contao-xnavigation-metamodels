<?php


namespace Netzmacht\Contao\XNavigation\MetaModels\Provider;

use Bit3\Contao\XNavigation\XNavigationEvents;
use Bit3\FlexiTree\Event\CollectItemsEvent;
use Bit3\FlexiTree\Event\CreateItemEvent;
use MetaModels\Filter\Setting\ICollection as MetaModelsFilterCollection;
use MetaModels\IItem;
use MetaModels\IMetaModel;
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
     * @var string
     */
    private $labelPattern = '%s';

    /**
     * @var array
     */
    private $titleAttributes = array();

    /**
     * @var string
     */
    private $titlePattern = '%s';

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
    private $sortDirection = 'asc';

    /**
     * @var array
     */
    private $filterParams = array();


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
    public function setSorting($sortBy, $sortDirection='asc')
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
            $factory->createItem('metamodels', $model, $item);
        }
    }


    /**
     * @param CreateItemEvent $event
     */
    public function createItem(CreateItemEvent $event)
    {
        $item = $event->getItem();

        if($item->getType() != 'metamodels') {
            return;
        }

        /** @var IItem $model */
        $model = $item->getName();
        $name  = sprintf('%s:%s', $model->getMetaModel()->getTableName(), $model->get('id'));

        $item
            ->setName($name)
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

        return $this->metaModel->findByFilter($filter);
    }


    /**
     * @param $model
     * @return string
     */
    private function generateLabel(IItem $model)
    {
        $value = array();

        foreach($this->labelAttributes as $attribute) {
            $value[] = $model->get($attribute);
        }

        $label = vsprintf($this->labelPattern, $value);
        $label = $this->replaceInsertTags($label);

        return $label;
    }


    /**
     * @param IItem $model
     * @return string
     */
    private function generateTitle(IItem $model)
    {
        $value = array();

        if ($this->titleAttributes) {
            foreach($this->titleAttributes as $attribute) {
                $value[] = $model->get($attribute);
            }

            $label = vsprintf($this->titlePattern, $value);
            $label = $this->replaceInsertTags($label);
        }
        else {
            $label = $this->generateLabel($model);
        }

        return specialchars($label);
    }

} 