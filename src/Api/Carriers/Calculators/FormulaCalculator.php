<?php namespace Neomerx\CoreApi\Api\Carriers\Calculators;

use \Closure;
use \Neomerx\Core\Models\Tax;
use \Neomerx\Core\Models\Carrier;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Carriers\Tariff;
use \Neomerx\CoreApi\Converters\CartConverterGeneric;
use \Neomerx\CoreApi\Api\Carriers\TariffCalculatorData;
use \Neomerx\CoreApi\Converters\AddressConverterGeneric;
use \Neomerx\CoreApi\Converters\CustomerConverterGeneric;
use \Neomerx\CoreApi\Api\Carriers\TariffCalculatorInterface;
use \Symfony\Component\ExpressionLanguage\ParsedExpression;
use \Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class FormulaCalculator implements TariffCalculatorInterface
{
    const BIND_NAME = __CLASS__;
    const EXPRESSION_LANGUAGE_CLASS = ExpressionLanguage::class;

    const PARAM_CART         = 'cart';
    const PARAM_CUSTOMER     = Tax::PARAM_CUSTOMER;
    const PARAM_ADDRESS_TO   = Tax::PARAM_ADDRESS_TO;
    const PARAM_ADDRESS_FROM = Tax::PARAM_ADDRESS_FROM;

    /**
     * Allowed names in formula
     *
     * @var array
     */
    protected static $allowedNames = [
        self::PARAM_CART,
        self::PARAM_CUSTOMER,
        self::PARAM_ADDRESS_TO,
        self::PARAM_ADDRESS_FROM,
    ];

    /**
     * @var Closure
     */
    private $calculateFunction;

    /**
     * @param Carrier $carrier
     */
    public function init(Carrier $carrier)
    {
        $data = $carrier->data;
        if ($this->isFormula($data)) {
            /** @var ExpressionLanguage $expLang */
            /** @noinspection PhpUndefinedMethodInspection */
            $expLang = App::make(self::EXPRESSION_LANGUAGE_CLASS);

            // TODO think through if execution of arbitrary code from DB is a security issue
            $cache = $carrier->cache;
            $expression = empty($cache) ? $this->parseFormula($expLang, $data) : unserialize($cache);

            $this->calculateFunction = function (array $values) use ($expLang, $expression) {
                return $expLang->evaluate($expression, $values);
            };
        } else {
            $flatTariff = floatval($data);
            $this->calculateFunction = function () use ($flatTariff) {
                return $flatTariff;
            };
        }
    }

    /**
     * @param TariffCalculatorData $data
     *
     * @return Tariff
     */
    public function calculate(TariffCalculatorData $data)
    {
        $cart        = $data->getCart();
        $customer    = $data->getCustomer();
        $addressTo   = $data->getAddressTo();
        $addressFrom = $data->getAddressFrom();
        /** @noinspection PhpUndefinedMethodInspection */
        $parameters  = [
            self::PARAM_CART         => (object)App::make(CartConverterGeneric::class)->convert($cart),
            self::PARAM_CUSTOMER     => (object)App::make(CustomerConverterGeneric::class)->convert($customer),
            self::PARAM_ADDRESS_TO   => (object)App::make(AddressConverterGeneric::class)->convert($addressTo),
            self::PARAM_ADDRESS_FROM => (object)App::make(AddressConverterGeneric::class)->convert($addressFrom),
        ];

        /** @var Closure $closure */
        $closure = $this->calculateFunction;
        return new Tariff($closure($parameters));
    }
    /**
     * @param string $value
     *
     * @return bool
     */
    private function isFormula($value)
    {
        return (!empty($value) and trim($value)[0] === '=');
    }

    /**
     * @param ExpressionLanguage $expLang
     * @param string             $data
     *
     * @return ParsedExpression
     */
    private function parseFormula(ExpressionLanguage $expLang, $data)
    {
        $formula = substr(trim($data), 1);
        return $expLang->parse($formula, static::$allowedNames);
    }
}
