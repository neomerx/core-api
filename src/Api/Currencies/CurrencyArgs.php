<?php namespace Neomerx\CoreApi\Api\Currencies;

use \Neomerx\Core\Models\Currency;
use \Neomerx\CoreApi\Events\EventArgs;

/**
 * @package Neomerx\CoreApi
 */
class CurrencyArgs extends EventArgs
{
    /**
     * @var Currency
     */
    private $currency;

    /**
     * @param string    $name
     * @param Currency  $currency
     * @param EventArgs $args
     */
    public function __construct($name, Currency $currency, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->currency = $currency;
    }

    /**
     * @return Currency
     */
    public function getModel()
    {
        return $this->currency;
    }
}
