<?php

use \Neomerx\CoreApi\Support\Config as C;
use \Neomerx\Limoncello\Config\Config as JAC;
use \Neomerx\CoreApi\Server\Http\Router as CoreApiRouter;
use \Neomerx\CoreApi\Api\Carriers\Calculators\FormulaCalculator;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Models\Image;
use \Neomerx\Core\Models\Region;
use \Neomerx\Core\Models\Address;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\Country;
use \Neomerx\Core\Models\Employee;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\Supplier;
use \Neomerx\Core\Models\ObjectType;
use \Neomerx\Core\Models\ImageFormat;
use \Neomerx\Core\Models\Manufacturer;
use \Neomerx\Core\Models\RoleObjectType;
use \Neomerx\CoreApi\Api\Carriers\Calculators\Calculator;

use \Neomerx\CoreApi\Schemas\RoleSchema;
use \Neomerx\CoreApi\Schemas\ImageSchema;
use \Neomerx\CoreApi\Schemas\RegionSchema;
use \Neomerx\CoreApi\Schemas\AddressSchema;
use \Neomerx\CoreApi\Schemas\CarrierSchema;
use \Neomerx\CoreApi\Schemas\CountrySchema;
use \Neomerx\CoreApi\Schemas\EmployeeSchema;
use \Neomerx\CoreApi\Schemas\LanguageSchema;
use \Neomerx\CoreApi\Schemas\SupplierSchema;
use \Neomerx\CoreApi\Schemas\CalculatorSchema;
use \Neomerx\CoreApi\Schemas\ObjectTypeSchema;
use \Neomerx\CoreApi\Schemas\RoleObjectSchema;
use \Neomerx\CoreApi\Schemas\ImageFormatSchema;
use \Neomerx\CoreApi\Schemas\ManufacturerSchema;

return [

    /**
     * Carrier settings
     */
    C::KEY_CARRIERS => [

        // Set up tariff calculators
        C::KEY_CARRIERS_TARIFF_CALCULATORS => [

            // formula-based calculator
            'formula' => [
                C::PARAM_CALCULATOR_FACTORY     => FormulaCalculator::class,   // implementation class
                C::PARAM_CALCULATOR_NAME        => 'Formula',                  // could be in translatable format
                C::PARAM_CALCULATOR_DESCRIPTION => 'Formula based calculator', // could be in translatable format
            ],

        ],

    ],

    C::KEY_AUTH => [

        C::PARAM_AUTH_CACHE_ACL_KEY           => 'employeeAcl',
        C::PARAM_AUTH_CACHE_ACL_IN_MINUTES    => 5,
        C::PARAM_AUTH_CACHE_TOKENS_PREFIX     => 'employeeToken_',
        C::PARAM_AUTH_CACHE_TOKENS_IN_MINUTES => 5,

    ],

    C::JSON_API => [
        JAC::SCHEMAS => [
            Address::class                => AddressSchema::class,
            Calculator::class             => CalculatorSchema::class,
            Carrier::class                => CarrierSchema::class,
            Country::class                => CountrySchema::class,
            Employee::class               => EmployeeSchema::class,
            Image::class                  => ImageSchema::class,
            ImageFormat::class            => ImageFormatSchema::class,
            Language::class               => LanguageSchema::class,
            Manufacturer::class           => ManufacturerSchema::class,
            ObjectType::class             => ObjectTypeSchema::class,
            Region::class                 => RegionSchema::class,
            Role::class                   => RoleSchema::class,
            RoleObjectType::class         => RoleObjectSchema::class,
            Supplier::class               => SupplierSchema::class,
        ],
        JAC::JSON    => [
            JAC::JSON_OPTIONS    => JSON_PRETTY_PRINT,
            JAC::JSON_DEPTH      => JAC::JSON_DEPTH_DEFAULT,
            JAC::JSON_URL_PREFIX => \Request::getUri() . CoreApiRouter::VERSION_PREFIX,
        ],
    ],

];
