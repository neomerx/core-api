<?php namespace Neomerx\CoreApi\Api;

use \DB;
use \Neomerx\Core\Auth\Permission;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Auth\Facades\Permissions;

/**
 * @package Neomerx\CoreApi
 */
abstract class SingleResourceApi extends BaseApi
{
    /**
     * Get unsaved resource instance.
     *
     * @param array $input Raw input data.
     *
     * @return BaseModel
     */
    abstract protected function getInstance(array $input);

    /**
     * Fill resource with data.
     *
     * @param BaseModel $resource Resource to be updated.
     * @param array     $input    Raw input data.
     *
     * @return BaseModel
     */
    abstract protected function fillResource(BaseModel $resource, array $input);

    /**
     * Create resource.
     *
     * @param array $input
     *
     * @return BaseModel
     */
    public function create(array $input)
    {
        $resource = $this->getInstance($input);
        $this->createResource($resource, $input);
        return $resource;
    }

    /**
     * Update resource.
     *
     * @param string|int $key
     * @param array $input
     *
     * @return void
     */
    public function update($key, array $input)
    {
        if (empty($input) === false) {
            $resource = $this->readResource($key);
            $this->updateResource($resource, $input);
        }
    }

    /**
     * @param BaseModel $resource
     * @param array     $originalInput
     */
    protected function createResource(BaseModel $resource, array $originalInput = [])
    {
        DB::beginTransaction();
        try {
            $resource->saveOrFail();
            Permissions::check($resource, Permission::create());
            $this->onCreating($resource, $originalInput);
            $this->fireEvent($this->getEventArgsOnCreating($resource));

            $allExecutedOk = true;

        } finally {
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }

        $this->onCreated($resource, $originalInput);
        $this->fireEvent($this->getEventArgsOnCreated($resource));
    }

    /**
     * @param BaseModel $resource
     * @param array     $originalInput
     */
    protected function updateResource(BaseModel $resource, array $originalInput)
    {
        Permissions::check($resource, Permission::edit());
        $resource = $this->fillResource($resource, $originalInput);

        /** @noinspection PhpUndefinedMethodInspection */
        DB::beginTransaction();
        try {
            $resource->saveOrFail();
            $this->onUpdating($resource, $originalInput);
            $this->fireEvent($this->getEventArgsOnUpdating($resource));

            $allExecutedOk = true;

        } finally {
            /** @noinspection PhpUndefinedMethodInspection */
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }

        $this->onUpdated($resource, $originalInput);
        $this->fireEvent($this->getEventArgsOnUpdated($resource));
    }

    /**
     * This method will be invoked within transaction with parameters
     *
     * @param BaseModel $resource
     * @param array     $input
     *
     * @return void
     */
    protected function onCreating(BaseModel $resource, array $input)
    {
        // avoid unused parameter warning
        isset($input) === true ?: null;
        isset($resource) === true ?: null;
    }

    /**
     * This method will be invoked on transaction completion with parameters
     *
     * @param BaseModel $resource
     * @param array     $input
     *
     * @return void
     */
    protected function onCreated(BaseModel $resource, array $input)
    {
        // avoid unused parameter warning
        isset($input) === true ?: null;
        isset($resource) === true ?: null;
    }

    /**
     * This method will be invoked within transaction with parameters
     *
     * @param BaseModel $resource
     * @param array     $input
     *
     * @return void
     */
    protected function onUpdating(BaseModel $resource, array $input)
    {
        // avoid unused parameter warning
        isset($input) === true ?: null;
        isset($resource) === true ?: null;
    }

    /**
     * This method will be invoked on transaction completion with parameters
     *
     * @param BaseModel $resource
     * @param array     $input
     *
     * @return void
     */
    protected function onUpdated(BaseModel $resource, array $input)
    {
        // avoid unused parameter warning
        isset($input) === true ?: null;
        isset($resource) === true ?: null;
    }
}
