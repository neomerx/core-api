<?php namespace Neomerx\CoreApi\Api\Images;

use \Neomerx\Core\Config;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Image;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\ImageProperties;
use \Neomerx\Core\Filesystem\Facades\Storage;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Symfony\Component\HttpFoundation\File\UploadedFile;
use \Neomerx\Core\Repositories\Images\ImageRepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Images\ImagePropertiesRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Images extends ResourceWithPropertiesApi implements ImagesInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.Image.';

    /**
     * @var ImageRepositoryInterface
     */
    private $imagesRepo;

    /**
     * @var ImagePropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @param ImageRepositoryInterface           $imagesRepo
     * @param ImagePropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface        $languageRepo
     */
    public function __construct(
        ImageRepositoryInterface $imagesRepo,
        ImagePropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo
    ) {
        parent::__construct($languageRepo);
        $this->imagesRepo     = $imagesRepo;
        $this->propertiesRepo = $propertiesRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->imagesRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            Image::withPaths(),
            Image::withProperties(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Image::FIELD_ORIGINAL_FILE => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP  => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE  => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Image $resource */
        return new ImageArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Image $resource */
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
        /** @var Image $resource */
        /** @var ImageProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Image $resource */
        return $resource->{Image::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return ImageProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var UploadedFile $file */
        $file = S\arrayGetValueEx($input, self::PARAM_ORIGINAL_FILE_DATA);
        unset($input[self::PARAM_ORIGINAL_FILE_DATA]);
        $fileName = S\arrayGetValue($input, self::PARAM_ORIGINAL_FILE_NAME);
        $fileName = $fileName !== null ? pathinfo($fileName, PATHINFO_BASENAME) : $file->getClientOriginalName();

        $input[self::PARAM_ORIGINAL_FILE_NAME] = $fileName;

        // TODO make drive name and folder configurable and update config accordingly
        $drive = Config::get(Config::KEY_IMAGE_DISK);
        $this->copyToDisk($file, $drive, Config::KEY_IMAGE_FOLDER_ORIGINALS.DIRECTORY_SEPARATOR.$fileName);

        return $this->imagesRepo->instance($input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Image $resource */

        $this->imagesRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * @param UploadedFile $file
     * @param string       $diskName
     * @param string       $folderAndFileName
     */
    protected function copyToDisk(UploadedFile $file, $diskName, $folderAndFileName)
    {
        if ($file->isValid()) {
            $imageDrive = Storage::disk($diskName);
            $stream     = fopen($file->getRealPath(), 'r');
            try {
                $imageDrive->writeStream($folderAndFileName, $stream);
            } finally {
                fclose($stream);
            }
        }
    }
}
