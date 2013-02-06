<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'V1\Controller\Rest' => 'V1\Controller\RestController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'v1' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/v1[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'V1\Controller\Rest',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);