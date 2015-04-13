<?php namespace Neomerx\CoreApi\Api\Languages;

use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;

class Languages extends SingleResourceApi implements LanguagesInterface
{
    const EVENT_PREFIX = 'Api.Language.';

    /**
     * @var LanguageRepositoryInterface
     */
    private $languageRepo;

    /**
     * @param LanguageRepositoryInterface $languageRepo
     */
    public function __construct(LanguageRepositoryInterface $languageRepo)
    {
        $this->languageRepo = $languageRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Language::FIELD_ISO_CODE  => SearchGrammar::TYPE_STRING,
            Language::FIELD_NAME      => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->languageRepo;
    }

    /**
     * @inheritdoc
     * @return Language
     */
    protected function getInstance(array $input)
    {
        return $this->languageRepo->instance($input);
    }

    /**
     * @inheritdoc
     * @return Language
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Language $resource */
        $this->languageRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Language $resource */
        return new LanguageArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }
}
