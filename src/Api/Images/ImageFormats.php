<?php namespace Neomerx\CoreApi\Api\Images;

use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\ImageFormat;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Images\ImageFormatRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 */
class ImageFormats extends SingleResourceApi implements ImageFormatsInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.ImageFormat.';

    /**
     * @var ImageFormatRepositoryInterface
     */
    private $imageRepo;

    /**
     * Constructor.
     *
     * @param ImageFormatRepositoryInterface $imageRepo
     */
    public function __construct(ImageFormatRepositoryInterface $imageRepo)
    {
        $this->imageRepo = $imageRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            ImageFormat::FIELD_CODE   => SearchGrammar::TYPE_STRING,
            ImageFormat::FIELD_WIDTH  => SearchGrammar::TYPE_INT,
            ImageFormat::FIELD_HEIGHT => SearchGrammar::TYPE_INT,
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
        return $this->imageRepo;
    }

    /**
     * @inheritdoc
     * @return ImageFormat
     */
    protected function getInstance(array $input)
    {
        return $this->imageRepo->instance($input);
    }

    /**
     * @inheritdoc
     * @return ImageFormat
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var ImageFormat $resource */
        $this->imageRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var ImageFormat $resource */
        return new ImageFormatArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }
}
