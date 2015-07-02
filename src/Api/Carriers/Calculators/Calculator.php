<?php namespace Neomerx\CoreApi\Api\Carriers\Calculators;

/**
 * @package Neomerx\CoreApi
 */
class Calculator
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @param string $code
     * @param string $class
     * @param string $name
     * @param string $description
     */
    public function __construct($code, $class, $name, $description)
    {
        $this->code        = $code;
        $this->name        = $name;
        $this->class       = $class;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
