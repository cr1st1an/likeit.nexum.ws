<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => '404',
        'exception_template'       => 'error',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout.phtml',
            '404'               => __DIR__ . '/../view/404.phtml',
            'error'             => __DIR__ . '/../view/error.phtml',
        ),
        
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);