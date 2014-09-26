<?php

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['metapalettes']['metamodels extends default'] = array(
    'metamodels' => array('mm_metamodel'),
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['palettes']['__selector'][] = 'type';
$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['type']['eval']['submitOnChange'] = true;

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['metasubselectpalettes']['mm_metamodel'] = array(
    '!' => array(
        'mm_filter',
        'mm_sort_by',
        'mm_sort_direction',
        'mm_title_attributes',
        'mm_title_pattern',
        'mm_label_attributes',
        'mm_label_pattern'
    ),
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_metamodel'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_metamodel'],
    'inputType'        => 'select',
    'filter'           => true,
    'options_callback' => array(
        'Netzmacht\Contao\XNavigation\MetaModels\DataContainer\XNavigationProviderDataContainer',
        'getMetaModels'
    ),
    'reference'        => &$GLOBALS['TL_LANG']['xnavigation_provider'],
    'eval'             => array(
        'mandatory'          => true,
        'chosen'             => true,
        'submitOnChange'     => true,
        'tl_class'           => 'w50'
    ),
    'sql'              => "varchar(64) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_filter'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_filter'],
    'inputType'        => 'select',
    'filter'           => true,
    'options_callback' => array(
        'Netzmacht\Contao\XNavigation\MetaModels\DataContainer\XNavigationProviderDataContainer',
        'getMetaModels'
    ),
    'reference'        => &$GLOBALS['TL_LANG']['mm_filter'],
    'eval'             => array(
        'chosen'             => true,
        'includeBlankOption' => true,
        'submitOnChange'     => true,
        'tl_class'           => 'w50'
    ),
    'sql'              => "int(11) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_sort_by'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_sort_by'],
    'inputType'        => 'select',
    'filter'           => true,
    'options_callback' => array(
        'Netzmacht\Contao\XNavigation\MetaModels\DataContainer\XNavigationProviderDataContainer',
        'getAttributeNames'
    ),
    'eval'             => array(
        'chosen'             => true,
        'includeBlankOption' => true,
        'submitOnChange'     => true,
        'tl_class'           => 'w50'
    ),
    'sql'              => "int(11) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_sort_direction'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_sort_direction'],
    'inputType'        => 'select',
    'filter'           => true,
    'options'          => array('ASC', 'DESC'),
    'default'          => 'ASC',
    'reference'        => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_sorting'],
    'eval'             => array(
        'submitOnChange'     => true,
        'tl_class'           => 'w50'
    ),
    'sql'              => "varchar(4) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_label_attributes'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_label_attributes'],
    'inputType'        => 'listWizard',
    'filter'           => true,
    'options_callback' => array(
        'Netzmacht\Contao\XNavigation\MetaModels\DataContainer\XNavigationProviderDataContainer',
        'getAttributeNames'
    ),
    'eval'             => array(
        'submitOnChange'     => true,
        'tl_class'           => 'clr'
    ),
    'sql'              => "mediumblob NULL"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_label_pattern'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_label_pattern'],
    'inputType'        => 'text',
    'filter'           => true,
    'default'          => '%s',
    'eval'             => array(
        'submitOnChange'     => true,
        'tl_class'           => 'w50'
    ),
    'sql'              => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_title_attributes'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_title_attributes'],
    'inputType'        => 'listWizard',
    'filter'           => true,
    'options_callback' => array(
        'Netzmacht\Contao\XNavigation\MetaModels\DataContainer\XNavigationProviderDataContainer',
        'getAttributeNames'
    ),
    'eval'             => array(
        'submitOnChange'     => true,
        'tl_class'           => 'clr'
    ),
    'sql'              => "mediumblob NULL"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_title_pattern'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_title_pattern'],
    'inputType'        => 'text',
    'filter'           => true,
    'default'          => '%s',
    'eval'             => array(
        'submitOnChange'     => true,
        'tl_class'           => 'w50'
    ),
    'sql'              => "varchar(255) NOT NULL default ''"
);