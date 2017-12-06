<?php

namespace WhoopsForShopware;

use Shopware\Components\Plugin;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class WhoopsForShopware extends Plugin
{
    
    public static function getSubscribedEvents ()
    {
        return [
            'Enlight_Controller_Front_RouteStartup' => 'onRouteStartup',
        ];
    }
    
    public function onRouteStartup (\Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Front $subject */
        $subject = $args->get('subject');
        
        if ($subject->getParam('noErrorHandler'))
        {
            $this->registerComposer();
            
            $requestUri = $subject->Request()->getRequestUri();
            $whoops     = new Run;

            if (
                false !== \strpos($requestUri, '/api')
                || false !== \strpos($requestUri, '/ajax')
                || false !== \strpos($requestUri, '/backend')
                || $subject->Request()->isXmlHttpRequest()
            ) {
                $whoops->pushHandler(new JsonResponseHandler());
            } else {
                $whoops->pushHandler(new PrettyPageHandler());
            }
            
            $whoops->register();
            restore_error_handler();
        }
    }
    
    private function registerComposer ()
    {
        $filename = $this->getPath() . '/vendor/autoload.php';
        
        if (file_exists($filename))
        {
            require_once $filename;
        }
    }
    
}
