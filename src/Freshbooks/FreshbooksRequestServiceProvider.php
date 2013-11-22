<?php

namespace JasonReading\Freshbooks;

use Silex\Application;
use Silex\ServiceProviderInterface;

class FreshbooksRequestServiceProvider implements ServiceProviderInterface
{
    function register(Application $app)
    {
        $app['freshbooks.options'] = array(
            'domain' => NULL,
            'token' => NULL,
        );

        $app['freshbooks'] = $app->share(function ($app, $command) {
            FreshBooksRequest::init($app['freshbooks.options']['domain'], $app['freshbooks.options']['token']);
            $freshbooks = new FreshBooksRequest($command);
            return $freshbooks;
        });

    }

    function boot(Application $app)
    {
    }
}
