<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Input;
use \Neomerx\Core\Models\Employee;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\Employees;
use \Neomerx\CoreApi\Converters\EmployeeConverterGeneric;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class EmployeesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Employees::INTERFACE_BIND_NAME, App::make(EmployeeConverterGeneric::class));
    }

    /**
     * Search roles in the system.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        return $this->tryAndCatchWrapper('searchImpl', [Input::all()]);
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function createResource(array $input)
    {
        $employee = $this->getApiFacade()->create($input);
        return [['id' => $employee->{Employee::FIELD_ID}], SymfonyResponse::HTTP_CREATED];
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        /** @var EmployeeConverterGeneric $converter */
        $converter = $this->getConverter();

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }
}
