<?php

namespace Config;
use Config\ApiConfig;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Accs::top');
//$routes->get('//([a-zA-Z0-9_]+)', 'Stores::detail/$1');
//$routes->post('//([a-zA-Z0-9_]+)', 'Stores::detail/$1');

/*
[todo]
$route['api/(:any)'] = "api/$1";
$route['([a-z0-9A-Z-_]+)'] = "stores/detail/$1";
*/
//API�̒�`�����擾
$apiConfig = new ApiConfig();
$apis = $apiConfig->defines;
// �z��̃f�[�^�����Ƀ��[�g��ݒ�
foreach($apis as $moduleName => $moduleData) {
    $class = 'Api\\' . $moduleData['class'];
    foreach ($moduleData['methods'] as $method => $methodconf) {
        $parameters = [];
        $patterns = [];
        foreach ($methodconf["patterns"] as $idx => $pattern) {
            $parameters[] = '$' . ($idx + 1);
            $patterns[] = $pattern;
        }
        $path = 'api/' . $moduleName . '/' . $method;
        if(count($patterns) > 0){
            $path .= '/' . implode('/',$patterns);
        }
        $controllerMethod = $class . '::' . $method;
        if(count($parameters) > 0){
            $controllerMethod .= '/' . implode('/',$parameters);
        }
        $routes->add($path, $controllerMethod);
    }
}

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
