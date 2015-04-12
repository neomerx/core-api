<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Models\Tax;
use \Neomerx\CoreApi\Events\EventArgs;

class TaxArgs extends EventArgs
{
    /**
     * @var Tax
     */
    private $tax;

    /**
     * @param string    $name
     * @param Tax       $tax
     * @param EventArgs $args
     */
    public function __construct($name, Tax $tax, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->tax = $tax;
    }

    /**
     * @return Tax
     */
    public function getModel()
    {
        return $this->tax;
    }
}
