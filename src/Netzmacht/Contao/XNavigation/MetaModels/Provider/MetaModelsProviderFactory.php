<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 26.09.14
 * Time: 19:55
 */

namespace Netzmacht\Contao\XNavigation\MetaModels\Provider;


use Bit3\Contao\XNavigation\Event\CreateProviderEvent;
use MetaModels\Factory as MetaModelsFactory;
use MetaModels\Filter\Setting\Factory as MetaModelsFilterFactory;
use MetaModels\Filter\Setting\ICollection as MetaModelsFilterCollection;
use MetaModels\Render\Setting\Factory;
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

        $metaModel = MetaModelsFactory::byId($model->mm_metamodel);

        // metamodel does not exists. break it here
        if (!$metaModel) {
            return;
        }

        $labelPattern = $model->mm_label_use_pattern ? $model->mm_label_pattern : false;
        $titlePattern = $model->mm_title_use_pattern ? $model->mm_title_pattern : false;

        $provider = MetaModelsProvider::create($metaModel)
            ->setParent($model->mm_parent_type, $model->mm_parent_page)
            ->setLabel(deserialize($model->mm_label_attributes, true), $labelPattern)
            ->setTitle(deserialize($model->mm_title_attributes, true), $titlePattern);

        if ($model->mm_filter) {
            $filter = MetaModelsFilterFactory::byId($model->mm_filter);
            $params = $this->createFilterParams($filter);

            if($filter) {
                $provider->setFilter($filter, $params);
            }
        }

        if($model->mm_render_setting) {
            $renderSetting = Factory::byId($metaModel, $model->mm_render_setting);
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