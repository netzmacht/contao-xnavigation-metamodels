<?php

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['metapalettes']['metamodels extends default'] = array(
    'parent'     => array('mm_parent_type'),
    'metamodels' => array('mm_metamodel'),
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['palettes']['__selector'][] = 'type';
$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['type']['eval']['submitOnChange'] = true;


$GLOBALS['TL_DCA']['tl_xnavigation_provider']['metasubselectpalettes']['mm_parent_type'] = array(
    'page' => array('mm_parent_page'),
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['metasubselectpalettes']['mm_metamodel'] = array(
    '!' => array(
        'mm_filter',
        'mm_sort_by',
        'mm_sort_direction',
        'mm_render_setting',
        'mm_label_attributes',
        'mm_label_use_pattern',
        'mm_title_attributes',
        'mm_title_use_pattern',
    ),
);


$GLOBALS['TL_DCA']['tl_xnavigation_provider']['metasubpalettes']['mm_label_use_pattern'] = array(
    'mm_label_pattern'
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['metasubpalettes']['mm_title_use_pattern'] = array(
    'mm_title_pattern'
);


$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_parent_type'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_parent_type'],
    'inputType'        => 'select',
    'filter'           => true,
    'options'          => array('page'),
    'eval'             => array(
        'includeBlankOption' => true,
        'submitOnChange'     => true,
        'tl_class'           => 'w50'
    ),
    'sql'              => "varchar(16) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_parent_page'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_parent_page'],
    'inputType'        => 'pageTree',
    'filter'           => true,
    'reference'        => &$GLOBALS['TL_LANG']['xnavigation_provider'],
    'eval'             => array(
        'mandatory'          => true,
        'chosen'             => true,
        'submitOnChange'     => true,
        'tl_class'           => 'clr'
    ),
    'sql'              => "int(64) NOT NULL default '0'"
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
        'getFilterNames'
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
    'sql'              => "varchar(11) NOT NULL default '0'"
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

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_render_setting'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_render_setting'],
    'inputType'        => 'select',
    'filter'           => true,
    'options_callback' => array(
        'Netzmacht\Contao\XNavigation\MetaModels\DataContainer\XNavigationProviderDataContainer',
        'getRenderSettings'
    ),
    'eval'             => array(
        'chosen'             => true,
        'includeBlankOption' => true,
        'tl_class'           => 'w50'
    ),
    'sql'              => "int(11) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_label_attributes'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_label_attributes'],
    'inputType'        => 'multiColumnWizard',
    'filter'           => true,
    'eval'             => array(
        'submitOnChange'     => true,
        'tl_class'           => 'clr',
        'columnFields' => array(
            'id' => array(
                'inputType' => 'select',
                'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_label_attribute_id'],
                'options_callback' => array(
                    'Netzmacht\Contao\XNavigation\MetaModels\DataContainer\XNavigationProviderDataContainer',
                    'getAttributeNames'
                ),
                'eval' => array(
                    'style' => 'width: 300px',
                ),
            ),

            'format' => array(
                'inputType' => 'select',
                'label'     => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_label_attribute_format'],
                'options'   => array('raw', 'text', 'output'),
                'eval'      => array(
                    'style' => 'width: 150px',
                )
            ),
        )
    ),
    'sql'              => "mediumblob NULL",

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

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_label_use_pattern'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_label_use_pattern'],
    'inputType'        => 'checkbox',
    'default'          => '%s',
    'eval'             => array(
        'submitOnChange'     => true,
        'tl_class'           => 'clr w50 m12'
    ),
    'sql'              => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_title_attributes'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_title_attributes'],
    'inputType'        => 'multiColumnWizard',
    'filter'           => true,
    'eval'             => array(
        'submitOnChange'     => true,
        'tl_class'           => 'clr',
        'columnFields' => array(
            'id' => array(
                'inputType' => 'select',
                'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_title_attributes'],
                'options_callback' => array(
                    'Netzmacht\Contao\XNavigation\MetaModels\DataContainer\XNavigationProviderDataContainer',
                    'getAttributeNames'
                ),
                'eval' => array(
                    'style' => 'width: 300px',
                ),
            ),

            'format' => array(
                'inputType' => 'select',
                'label'     => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_label_attribute_format'],
                'options'   => array('raw', 'text', 'output'),
                'eval'      => array(
                    'style' => 'width: 150px',
                )
            ),
        )
    ),
    'sql'              => "mediumblob NULL",
);

$GLOBALS['TL_DCA']['tl_xnavigation_provider']['fields']['mm_title_use_pattern'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_xnavigation_provider']['mm_title_use_pattern'],
    'inputType'        => 'checkbox',
    'default'          => '%s',
    'eval'             => array(
        'submitOnChange'     => true,
        'tl_class'           => 'clr w50 m12'
    ),
    'sql'              => "char(1) NOT NULL default ''"
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