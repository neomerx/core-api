<?php namespace Neomerx\CoreApi\Api\Categories;

use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Events\Event;
use \Neomerx\Core\Auth\Permission;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\Category;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Auth\Facades\Permissions;
use \Neomerx\Core\Models\CategoryProperties;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Categories\CategoryRepositoryInterface;
use \Neomerx\Core\Repositories\Categories\CategoryPropertiesRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Categories extends ResourceWithPropertiesApi implements CategoriesInterface
{
    const EVENT_PREFIX = 'Api.Category.';

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * @var CategoryPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @param CategoryRepositoryInterface           $categoryRepo
     * @param CategoryPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface           $languageRepo
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepo,
        CategoryPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo
    ) {
        parent::__construct($languageRepo);
        $this->categoryRepo   = $categoryRepo;
        $this->propertiesRepo = $propertiesRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->categoryRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Category::withProperties()];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Category::FIELD_CODE      => SearchGrammar::TYPE_STRING,
            Category::FIELD_LINK      => SearchGrammar::TYPE_STRING,
            'created'                 => [SearchGrammar::TYPE_DATE, Category::FIELD_CREATED_AT],
            'updated'                 => [SearchGrammar::TYPE_DATE, Category::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Category $resource */
        return new CategoryArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Category $resource */
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
        /** @var Category $resource */
        /** @var CategoryProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Category $resource */
        return $resource->{Category::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return CategoryProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        return $this->categoryRepo->instance($input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Category $resource */
        $this->categoryRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function readDescendantsByCategory(Category $parent)
    {
        Permissions::check($parent, Permission::view());
        return $parent->getDescendants();
    }

    /**
     * {@inheritdoc}
     */
    public function moveUpByCategory(Category $category)
    {
        Permissions::check($category, Permission::edit());
        $category->moveLeft();
        Event::fire(new CategoryArgs(self::EVENT_PREFIX . 'movedUp', $category));
    }

    /**
     * {@inheritdoc}
     */
    public function moveDownByCategory(Category $category)
    {
        Permissions::check($category, Permission::edit());
        $category->moveRight();
        Event::fire(new CategoryArgs(self::EVENT_PREFIX . 'movedDown', $category));
    }

    /**
     * {@inheritdoc}
     */
    public function attachByCategory(Category $category, Category $newParent)
    {
        Permissions::check($category, Permission::edit());
        Permissions::check($newParent, Permission::edit());
        $category->attachToCategory($newParent);
        Event::fire(new CategoryArgs(self::EVENT_PREFIX . 'attached', $category));
    }

//
//    /**
//     * {@inheritdoc}
//     */
//    public function showProducts(Category $category)
//    {
//        Permissions::check($category, Permission::view());
//
//        /** @noinspection PhpUndefinedMethodInspection */
//        $productsInCategory = $category->assignedProducts()->with('properties.language')->get();
//
//        $result = [];
//        foreach ($productsInCategory as $categoryProduct) {
//            $position = $categoryProduct->pivot->{ProductCategory::FIELD_POSITION};
//            $result[] = [$categoryProduct, $position];
//        }
//
//        return $result;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function updatePositions(Category $category, array $productPositions)
//    {
//        $categoryId = $category->{Category::FIELD_ID};
//        Permissions::check($category, Permission::edit());
//
//        /** @noinspection PhpUndefinedMethodInspection */
//        DB::beginTransaction();
//        try {
//
//            foreach ($productPositions as $sku => $position) {
//
//                /** @var \Neomerx\Core\Models\Product $product */
//                $product = $this->productModel->selectByCode($sku)->firstOrFail([Product::FIELD_ID]);
//                $productId = $product->{Product::FIELD_ID};
//                /** @noinspection PhpUndefinedMethodInspection */
//                $this->productCategoryModel->where([
//                    ProductCategory::FIELD_ID_CATEGORY => $categoryId,
//                    ProductCategory::FIELD_ID_PRODUCT  => $productId
//                ])->update([ProductCategory::FIELD_POSITION => $position]);
//
//            }
//
//            $allExecutedOk = true;
//
//        } finally {
//            /** @noinspection PhpUndefinedMethodInspection */
//            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
//        }
//
//        Event::fire(new CategoryArgs(self::EVENT_PREFIX . 'updatedPositions', $category));
//    }
    /**
     * {@inheritdoc}
     */
    public function readDescendants($parentCode)
    {
        /** @var Category $category */
        $category = $this->read($parentCode);
        return $this->readDescendantsByCategory($category);
    }

    /**
     * {@inheritdoc}
     */
    public function moveUp($categoryCode)
    {
        /** @var Category $category */
        $category = $this->read($categoryCode);
        $this->moveUpByCategory($category);
    }

    /**
     * {@inheritdoc}
     */
    public function moveDown($categoryCode)
    {
        /** @var Category $category */
        $category = $this->read($categoryCode);
        $this->moveDownByCategory($category);
    }

    /**
     * {@inheritdoc}
     */
    public function attach($categoryCode, $newParentCode)
    {
        /** @var Category $category */
        $category = $this->read($categoryCode);
        /** @var Category $parentCategory */
        $parentCategory = $this->read($newParentCode);
        $this->attachByCategory($category, $parentCategory);
    }
}
