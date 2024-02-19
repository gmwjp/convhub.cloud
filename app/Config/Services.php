<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Core\BaseRequest;
use App\Views\_MyView;
use Config\View as ViewConfig;
/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */
    public static function renderer($path = null, $options = null, $getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('renderer', $path, $options);
        }

        // Ensure that we have a Config\View object
        $config = is_null($options) ? new ViewConfig() : $options;

        // Replace the default View class with your custom class
        return new _MyView($config, $path);
    }
    public static function request(\Config\App $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('request', $config);
        }

        if (! is_object($config)) {
            $config = config('App');
        }

        $uri = static::uri();

        // Notice we are returning the instance of MyRequest here
        return new BaseRequest(
            $config,
            $uri,
            'php://input',
            new \CodeIgniter\HTTP\UserAgent()
        );
    }
}
