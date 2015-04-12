<?php namespace Neomerx\CoreApi\Server\Http;

use \Route;
use \Neomerx\CoreApi\Server\Http\Controllers\EmployeeLoginControllerJson;

class Router
{
    /**
     * @return callable
     */
    public static function getRoutes()
    {
        return function () {
            include __DIR__ . '/Routes/auth.php';
            include __DIR__ . '/Routes/carriers.php';
            include __DIR__ . '/Routes/categories.php';
            include __DIR__ . '/Routes/countries.php';
            include __DIR__ . '/Routes/currencies.php';
            include __DIR__ . '/Routes/customers.php';
            include __DIR__ . '/Routes/employees.php';
            include __DIR__ . '/Routes/features.php';
            include __DIR__ . '/Routes/image-formats.php';
            include __DIR__ . '/Routes/inventory.php';
            include __DIR__ . '/Routes/languages.php';
            include __DIR__ . '/Routes/manufacturers.php';
            include __DIR__ . '/Routes/orders.php';
            include __DIR__ . '/Routes/products.php';
            include __DIR__ . '/Routes/regions.php';
            include __DIR__ . '/Routes/suppliers.php';
            include __DIR__ . '/Routes/supply-orders.php';
            include __DIR__ . '/Routes/taxes.php';

            Route::get('logout', ['uses' => EmployeeLoginControllerJson::class.'@logout']);
        };
    }
}
