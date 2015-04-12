<?php namespace Neomerx\CoreApi\Api;

use \DB;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Events\Event;
use \Neomerx\Core\Auth\Permission;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Auth\Facades\Permissions;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
abstract class BaseApi
{
    const EVENT_POSTFIX_CREATED  = 'created';
    const EVENT_POSTFIX_UPDATED  = 'updated';
    const EVENT_POSTFIX_DELETED  = 'deleted';
    const EVENT_POSTFIX_CREATING = 'creating';
    const EVENT_POSTFIX_UPDATING = 'updating';
    const EVENT_POSTFIX_DELETING = 'deleting';

    /**
     * @return RepositoryInterface
     */
    abstract protected function getResourceRepository();

    /**
     * Get model relations to load on read and search.
     *
     * @return array
     */
    abstract protected function getResourceRelations();

    /**
     * Get resource search rules.
     *
     * @return array
     */
    abstract protected function getSearchRules();

    /**
     * @param BaseModel $resource
     * @param string    $eventNamePostfix
     *
     * @return EventArgs
     */
    abstract protected function getEvent(BaseModel $resource, $eventNamePostfix);

    /**
     * Read resource.
     *
     * @param string|int $key
     *
     * @return BaseModel
     */
    public function read($key)
    {
        $resource = $this->readResource($key);
        return $resource;
    }

    /**
     * Delete resource.
     *
     * @param string|int $key
     *
     * @return void
     */
    public function delete($key)
    {
        $resource = $this->readResource($key);
        $this->deleteResource($resource);
    }

    /**
     * Search resources.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = [])
    {
        $resources = $this->getResourceRepository()
            ->search($this->getResourceRelations(), $parameters, $this->getSearchRules());
        foreach ($resources as $resource) {
            /** @var \Neomerx\Core\Models\BaseModel $resource */
            Permissions::check($resource, Permission::view());
        }

        return $resources;
    }

    /**
     * @param EventArgs $eventArgs
     *
     * @return void
     */
    protected function fireEvent(EventArgs $eventArgs)
    {
        Event::fire($eventArgs);
    }

    /**
     * @param BaseModel $resource
     *
     * @return EventArgs
     */
    protected function getEventArgsOnCreated(BaseModel $resource)
    {
        return $this->getEvent($resource, self::EVENT_POSTFIX_CREATED);
    }

    /**
     * @param BaseModel $resource
     *
     * @return EventArgs
     */
    protected function getEventArgsOnCreating(BaseModel $resource)
    {
        return $this->getEvent($resource, self::EVENT_POSTFIX_CREATING);
    }

    /**
     * @param BaseModel $resource
     *
     * @return EventArgs
     */
    protected function getEventArgsOnUpdated(BaseModel $resource)
    {
        return $this->getEvent($resource, self::EVENT_POSTFIX_UPDATED);
    }

    /**
     * @param BaseModel $resource
     *
     * @return EventArgs
     */
    protected function getEventArgsOnUpdating(BaseModel $resource)
    {
        return $this->getEvent($resource, self::EVENT_POSTFIX_UPDATING);
    }

    /**
     * @param BaseModel $resource
     *
     * @return EventArgs
     */
    protected function getEventArgsOnDeleted(BaseModel $resource)
    {
        return $this->getEvent($resource, self::EVENT_POSTFIX_DELETED);
    }

    /**
     * @param BaseModel $resource
     *
     * @return EventArgs
     */
    protected function getEventArgsOnDeleting(BaseModel $resource)
    {
        return $this->getEvent($resource, self::EVENT_POSTFIX_DELETING);
    }

    /**
     * @param string|int $key
     * @param array      $columns
     *
     * @return BaseModel
     */
    protected function readResource($key, array $columns = ['*'])
    {
        return $this->readResourceFromRepository(
            $key,
            $this->getResourceRepository(),
            $this->getResourceRelations(),
            $columns
        );
    }

    /**
     * @param int|string          $resourceId
     * @param RepositoryInterface $repository
     * @param array               $relations
     * @param array               $columns
     *
     * @return BaseModel
     */
    protected function readResourceFromRepository(
        $resourceId,
        RepositoryInterface $repository,
        array $relations = [],
        array $columns = ['*']
    ) {
        $resource = $repository->read($resourceId, $relations, $columns);
        Permissions::check($resource, Permission::view());

        return $resource;
    }

    /**
     * @param BaseModel $resource
     */
    protected function deleteResource(BaseModel $resource)
    {
        Permissions::check($resource, Permission::delete());
        DB::beginTransaction();
        try {
            $resource->deleteOrFail();
            $this->onDeleting($resource);
            $this->fireEvent($this->getEventArgsOnDeleting($resource));

            $allExecutedOk = true;

        } finally {
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }

        $this->onDeleted($resource);
        $this->fireEvent($this->getEventArgsOnDeleted($resource));
    }

    /**
     * This method will be invoked within transaction with parameter
     *
     * @param BaseModel $resource
     *
     * @return void
     */
    protected function onDeleting(BaseModel $resource)
    {
        // avoid unused parameter warning
        isset($resource) === true ?: null;
    }

    /**
     * This method will be invoked on transaction completion with parameter
     *
     * @param BaseModel $resource
     *
     * @return void
     */
    protected function onDeleted(BaseModel $resource)
    {
        // avoid unused parameter warning
        isset($resource) === true ?: null;
    }

    /**
     * Replace code in input with its model.
     *
     * @param array               &$input
     * @param string              $inKey
     * @param RepositoryInterface $repository
     *
     * @return BaseModel|null
     */
    protected function keyToModel(array &$input, $inKey, RepositoryInterface $repository)
    {
        if (isset($input[$inKey]) === false) {
            return null;
        }

        $resource = $this->readResourceFromRepository($input[$inKey], $repository);

        unset($input[$inKey]);

        return $resource;
    }

    /**
     * Replace code in input with its model.
     *
     * @param array               &$input
     * @param string              $inKey
     * @param RepositoryInterface $repository
     *
     * @throws \Neomerx\Core\Exceptions\InvalidArgumentException
     *
     * @return BaseModel
     */
    protected function keyToModelEx(array &$input, $inKey, RepositoryInterface $repository)
    {
        $resource = $this->keyToModel($input, $inKey, $repository);
        $resource !== null ?: S\throwEx(new InvalidArgumentException($inKey));
        return $resource;
    }
}
