<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\JsonApi\Schema\SchemaProvider;
use \Neomerx\CoreApi\Api\Carriers\Calculators\Calculator;

/**
 * @package Neomerx\CoreApi
 */
class CalculatorSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'calculators';

    /** Resources sub-URL */
    const SUB_URL = '/calculators/';

    /** Schema attribute */
    const ATTR_CODE = 'code';

    /** Schema attribute */
    const ATTR_NAME = 'name';

    /** Schema attribute */
    const ATTR_DESCRIPTION = 'description';

    /**
     * @var string
     */
    protected $resourceType = self::TYPE;

    /**
     * @var string
     */
    protected $selfSubUrl = self::SUB_URL;

    /**
     * @inheritdoc
     */
    public function getId($calculator)
    {
        /** @var Calculator $calculator */
        return $calculator->getCode();
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($calculator)
    {
        /** @var Calculator $calculator */
        return [
            self::ATTR_NAME        => $calculator->getName(),
            self::ATTR_DESCRIPTION => $calculator->getDescription(),
        ];
    }
}
