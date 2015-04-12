<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\Core\Models\Carrier;

interface TariffCalculatorInterface
{
    /**
     * @param Carrier $carrier
     *
     * @return void
     */
    public function init(Carrier $carrier);

    /**
     * @param TariffCalculatorData $data
     *
     * @return Tariff
     */
    public function calculate(TariffCalculatorData $data);
}
