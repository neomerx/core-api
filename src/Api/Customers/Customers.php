<?php namespace Neomerx\CoreApi\Api\Customers;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Customer;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\CustomerRisk;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Customers\CustomerRepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Customers\CustomerRiskRepositoryInterface;
use \Neomerx\Core\Repositories\Customers\CustomerTypeRepositoryInterface;

class Customers extends SingleResourceApi implements CustomersInterface
{
    const EVENT_PREFIX = 'Api.Customer.';

    /**
     * @var CustomerTypeRepositoryInterface
     */
    private $typeRepo;

    /**
     * @var CustomerRiskRepositoryInterface
     */
    private $riskRepo;

    /**
     * @var LanguageRepositoryInterface
     */
    private $languageRepo;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @param CustomerRepositoryInterface     $customerRepo
     * @param CustomerTypeRepositoryInterface $typeRepo
     * @param CustomerRiskRepositoryInterface $riskRepo
     * @param LanguageRepositoryInterface     $languageRepo
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepo,
        CustomerTypeRepositoryInterface $typeRepo,
        CustomerRiskRepositoryInterface $riskRepo,
        LanguageRepositoryInterface $languageRepo
    ) {
        $this->customerRepo = $customerRepo;
        $this->typeRepo     = $typeRepo;
        $this->riskRepo     = $riskRepo;
        $this->languageRepo = $languageRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->customerRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            Customer::withRisk(),
            Customer::withType(),
            Customer::withLanguage(),
            Customer::withDefaultBillingAddress(),
            Customer::withDefaultShippingAddress(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Customer::FIELD_ID         => SearchGrammar::TYPE_INT,
            Customer::FIELD_FIRST_NAME => SearchGrammar::TYPE_STRING,
            Customer::FIELD_LAST_NAME  => SearchGrammar::TYPE_STRING,
            Customer::FIELD_EMAIL      => SearchGrammar::TYPE_STRING,
            Customer::FIELD_MOBILE     => SearchGrammar::TYPE_STRING,
            Customer::FIELD_GENDER     => SearchGrammar::TYPE_STRING,
            'created'                  => [SearchGrammar::TYPE_DATE, Customer::FIELD_CREATED_AT],
            'updated'                  => [SearchGrammar::TYPE_DATE, Customer::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP  => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE  => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Customer $resource */
        return new CustomerArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var CustomerType $type */
        $type = $this->keyToModelEx($input, self::PARAM_TYPE_CODE, $this->typeRepo);

        /** @var Language $language */
        $language = $this->keyToModelEx($input, self::PARAM_LANGUAGE_CODE, $this->languageRepo);

        /** @var CustomerRisk $risk */
        $risk = $this->keyToModel($input, self::PARAM_RISK_CODE, $this->riskRepo);

        return $this->customerRepo->instance($type, $language, $input, $risk);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var CustomerType $type */
        $type = $this->keyToModel($input, self::PARAM_TYPE_CODE, $this->typeRepo);

        /** @var Language $language */
        $language = $this->keyToModel($input, self::PARAM_LANGUAGE_CODE, $this->languageRepo);

        /** @var CustomerRisk $risk */
        $risk = $this->keyToModel($input, self::PARAM_RISK_CODE, $this->riskRepo);

        /** @var Customer $resource */
        $this->customerRepo->fill($resource, $type, $language, $input, $risk);

        return $resource;
    }
}
