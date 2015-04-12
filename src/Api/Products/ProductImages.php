<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Image;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\ProductImage;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\CoreApi\Api\Images\ImagesInterface;
use \Symfony\Component\HttpFoundation\File\UploadedFile;
use \Neomerx\Core\Repositories\Products\ProductRepositoryInterface;
use \Neomerx\Core\Repositories\Products\VariantRepositoryInterface;
use \Neomerx\Core\Repositories\Products\ProductImageRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductImages extends SingleResourceApi implements ProductImagesInterface
{
    const EVENT_PREFIX = 'Api.ProductImage.';

    /**
     * @var ProductImageRepositoryInterface
     */
    private $productImageRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var VariantRepositoryInterface
     */
    private $variantRepo;

    /**
     * @var ImagesInterface
     */
    private $imagesApi;

//    /**
//     * Read product images.
//     *
//     * @param Product $product
//     *
//     * @return Collection
//     */
//    public function showProductImages(Product $product)
//    {
//        /** @noinspection PhpUndefinedMethodInspection */
//        $productImages = $product->productImages()
//            ->with('image.properties.language', 'image.paths.format')
//            ->get();
//
//        return $productImages;
//    }
//
//    /**
//     * Add product images.
//     *
//     * @param Product $product
//     * @param array $descriptions
//     * @param array $files
//     *
//     * @return void
//     */
//    public function storeProductImages(Product $product, array $descriptions, array $files)
//    {
//        Permissions::check($product, Permission::edit());
//        $this->saveImages($descriptions, $files, $this->languageModel, $this->productImageModel, $product);
//    }
//
//    /**
//     * Set product image as cover.
//     *
//     * @param Product $product
//     * @param int   $imageId
//     *
//     * @return void
//     */
//    public function setDefaultProductImage(Product $product, $imageId)
//    {
//        Permissions::check($product, Permission::edit());
//
//        /** @noinspection PhpUndefinedMethodInspection */
//        /** @var \Neomerx\Core\Models\ProductImage $productImage */
//        $productImage = $product->productImages()->where(ProductImage::FIELD_ID, '=', $imageId)->firstOrFail();
//        $productImage->setAsCover();
//
//        Event::fire(new ProductArgs(Products::EVENT_PREFIX . 'defaultImageChanged', $product));
//    }
//
//    /**
//     * Remove product images.
//     *
//     * @param Product $product
//     * @param int   $imageId
//     *
//     * @return void
//     */
//    public function destroyProductImage(Product $product, $imageId)
//    {
//        Permissions::check($product, Permission::edit());
//
//        // re-select them to check that they do belong to $product
//        /** @noinspection PhpUndefinedMethodInspection */
//        $image = $product->productImages()
//            ->where(ProductImage::FIELD_ID, $imageId)->lists(ProductImage::FIELD_ID);
//
//        $this->productImageModel->destroy($image);
//
//        Event::fire(new ProductArgs(Products::EVENT_PREFIX . 'deletedImage', $product));
//    }

    /**
     * @param ProductImageRepositoryInterface $productImageRepo
     * @param ProductRepositoryInterface      $productRepo
     * @param VariantRepositoryInterface      $variantRepo
     * @param ImagesInterface                 $imagesApi
     */
    public function __construct(
        ProductImageRepositoryInterface $productImageRepo,
        ProductRepositoryInterface $productRepo,
        VariantRepositoryInterface $variantRepo,
        ImagesInterface $imagesApi
    ) {
        $this->productImageRepo = $productImageRepo;
        $this->productRepo      = $productRepo;
        $this->variantRepo      = $variantRepo;
        $this->imagesApi        = $imagesApi;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->productImageRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            ProductImage::withProduct(),
            ProductImage::withVariant(),
            ProductImage::withImage(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var ProductImage $resource */
        return new ProductImageArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Product $product */
        $product = $this->keyToModelEx($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        /** @var Variant $variant */
        $variant = $this->keyToModel($input, self::PARAM_VARIANT_SKU, $this->variantRepo);

        // read or create image
        if (isset($input[self::PARAM_ID_IMAGE]) === true) {
            $image = $this->imagesApi->read($input[self::PARAM_ID_IMAGE]);
        } else {
            /** @var UploadedFile $file */
            $file = S\arrayGetValue($input, self::PARAM_ORIGINAL_FILE_DATA);
            $name = $variant !== null ? $variant->{Variant::FIELD_SKU} : $product->{Product::FIELD_SKU};
            $name .= '.'.$file->getClientOriginalExtension();
            $image = $this->imagesApi->create([
                ImagesInterface::PARAM_ORIGINAL_FILE_DATA => $file,
                ImagesInterface::PARAM_ORIGINAL_FILE_NAME => $name,
                ImagesInterface::PARAM_PROPERTIES         => S\arrayGetValue($input, self::PARAM_PROPERTIES),
            ]);
        }

        return $this->productImageRepo->instance($product, $image, $input, $variant);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var ProductImage $resource */

        /** @var Product $product */
        $product = $this->keyToModel($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        /** @var Variant $variant */
        $variant = $this->keyToModel($input, self::PARAM_VARIANT_SKU, $this->variantRepo);

        /** @var Image $image */
        if (isset($input[self::PARAM_ID_IMAGE]) === true) {
            $image =  $this->imagesApi->read($input[self::PARAM_ID_IMAGE]);
        } else {
            $image = null;
        }

        $this->productImageRepo->fill($resource, $product, $image, $input, $variant);

        return $resource;
    }
}
