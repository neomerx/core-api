<?php

use \Neomerx\CoreApi\Support\Config as C;
use \Neomerx\CoreApi\Api\Carriers\Calculators\FormulaCalculator;

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

];
