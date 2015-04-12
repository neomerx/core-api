<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Employee;

class EmployeeConverterGeneric implements ConverterInterface
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param Employee $employee
     *
     * @return array
     */
    public function convert($employee = null)
    {
        if ($employee === null) {
            return null;
        }

        assert('$employee instanceof '.Employee::class);

        $result = $employee->attributesToArray();

        return $result;
    }
}
