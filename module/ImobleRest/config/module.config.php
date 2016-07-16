<?php

namespace ImobleRest;

return array(
    'controllers' => array(
        'invokables' => array(
            'ImobleRest\Controller\ImmobileRest' => 'ImobleRest\Controller\ImmobileRestController',
            'ImobleRest\Controller\ImmobileItemRest' => 'ImobleRest\Controller\ImmobileItemRestController',
        ),
    ),
    'service_manager' => array (
        'dao_services' => array(
            'ImobleRest\Service\ImmobileService' => array(
                'service' => 'ImobleRest\Service\ImmobileService',
                'model' => 'ImobleRest\Model\Doctrine\ImmobileDAODoctrine',
            ),
            'ImobleRest\Service\ImmobileItemService' => array(
                'service' => 'ImobleRest\Service\ImmobileItemService',
                'model' => 'ImobleRest\Model\Doctrine\ImmobileItemDAODoctrine',
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'immobile-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/immobile[/:id]',
                    'constraints' => array(
                        'id'     => '.+',
                    ),
                    'defaults' => array(
                        'controller' => 'ImobleRest\Controller\ImmobileRest',
                    ),
                ),
            ),
            'immobile-item-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/immobile/:immobile/items[/:id]',
                    'constraints' => array(
                        'id'     => '.+',
                    ),
                    'defaults' => array(
                        'controller' => 'ImobleRest\Controller\ImmobileItemRest',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array( //Add this config
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    // ...
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);