<?php namespace Neomerx\CoreApi\Api\Currencies;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\Currency;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\CurrencyProperties;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Currencies\CurrencyRepositoryInterface;
use \Neomerx\Core\Repositories\Currencies\CurrencyPropertiesRepositoryInterface;

class Currencies extends ResourceWithPropertiesApi implements CurrenciesInterface
{
    const EVENT_PREFIX = 'Api.Currency.';
    const BIND_NAME = __CLASS__;

    /**
     * @var CurrencyRepositoryInterface
     */
    private $currencyRepo;

    /**
     * @var CurrencyPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @param CurrencyRepositoryInterface           $currencyRepo
     * @param CurrencyPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface           $languageRepo
     */
    public function __construct(
        CurrencyRepositoryInterface $currencyRepo,
        CurrencyPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo
    ) {
        parent::__construct($languageRepo);
        $this->currencyRepo   = $currencyRepo;
        $this->propertiesRepo = $propertiesRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->currencyRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Currency::withProperties()];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Currency::FIELD_ID        => SearchGrammar::TYPE_STRING,
            Currency::FIELD_CODE      => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Currency $resource */
        return new CurrencyArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Currency $resource */
        return $this->propertiesRepo->instance($resource, $language, $attributes);
    }

    /**
     * @inheritdoc
     */
    protected function fillProperty(
        BaseModel $property,
        BaseModel $resource,
        Language $language,
        array $attributes
    ) {
        /** @var Currency $resource */
        /** @var CurrencyProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Currency $resource */
        return $resource->{Currency::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return CurrencyProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        return $this->currencyRepo->instance($input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Currency $resource */

        $this->currencyRepo->fill($resource, $input);
        return $resource;
    }
}
