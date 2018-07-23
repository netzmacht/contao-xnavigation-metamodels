<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 26.09.14
 * Time: 19:55
 */

namespace Netzmacht\Contao\XNavigation\MetaModels;


use Bit3\Contao\XNavigation\Event\CreateProviderEvent;
use MetaModels\Factory as MetaModelsFactory;
use MetaModels\Filter\Setting\ICollection as MetaModelsFilterCollection;
use MetaModels\Filter\Setting\FilterSettingFactory;
use MetaModels\Render\Setting\RenderSettingFactory;
use Netzmacht\Contao\XNavigation\MetaModels\Provider\MetaModelsProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MetaModelsProviderFactory implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            EVENT_XNAVIGATION_CREATE_PROVIDER => 'createProvider'
        );
    }


    /**
     * @param CreateProviderEvent $event
     */
    public function createProvider(CreateProviderEvent $event)
    {
        $model = $event->getProviderModel();

        if ($model->type != 'metamodels') {
            return;
        }

        $factory       = new MetaModelsFactory();
        $metaModelName = $factory->translateIdToMetaModelName($model->mm_metamodel);
        $metaModel     = $factory->getMetaModel($metaModelName);

        // metamodel does not exists. break it here
        if (!$metaModel) {
            return;
        }

        $provider = MetaModelsProvider::create($metaModel, $model->id)
            ->setParent($model->mm_parent_type, $model->mm_parent_page);

        $attributeMappings = deserialize($model->mm_attributes, true);
        foreach ($attributeMappings as $config) {
            if($config['id'] && $config['html']) {
                $provider->addAttributeMapping($config['id'], $config['html'], $config['type'], $config['format']);
            }
        }

        if ($model->mm_filter) {
            $filterFactory = new FilterSettingFactory();
            $filter        = $filterFactory->createCollection($metaModelName->mm_filter);
            $params        = $this->createFilterParams($filter);

            if($filter) {
                $provider->setFilter($filter, $params);
            }
        }

        if($model->mm_render_setting) {
            $renderFactory = new RenderSettingFactory();
            $renderSetting = $renderFactory->createCollection($metaModel, $model->mm_render_setting);
            $provider->setRenderSetting($renderSetting);
        }

        if ($model->mm_sort_by) {
            $provider->setSorting($model->mm_sort_by, $model->mm_sort_direction ?: 'ASC');
        }

        $event->setProvider($provider);
    }


    /**
     * @param MetaModelsFilterCollection $filter
     * @return array
     */
    private function createFilterParams(MetaModelsFilterCollection $filter)
    {
        $names  = $filter->getParameterFilterNames();
        $values = array();

        foreach (array_keys($names) as $name)
        {
            $varValue = \Input::get($name);
            if (is_string($varValue)) {
                $values[$name] = $varValue;
            }
        }

        return $values;
    }

} 
