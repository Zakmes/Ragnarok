<?php

namespace App\Support\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseService
 *
 *
 * @see https://github.com/rappasoft/laravel-boilerplate/blob/master/app/Services/BaseService.php
 * @package App\Support\Services
 */
abstract class BaseService
{
    /**
     * The repository model.
     */
    protected Model $model;

    /**
     * The query builder.
     */
    protected Builder $query;

    /**
     * Alias for the query limit.
     */
    protected ?int $take;

    /**
     * Array of models to eager load.
     */
    protected array $with = [];

    /**
     * Array of one or more where clause parameters.
     */
    protected array $wheres = [];

    /**
     * Array of one or more where in clause parameters.
     */
    protected array $whereIns = [];

    /**
     * Array of one or more ORDER BY column/value pairs.
     */
    protected array $orderBys = [];

    /**
     * Array of scope methods to call on the model.
     */
    protected array $scopes = [];

    /**
     * Get all the model records in the database.
     */
    public function all(): Collection
    {
        $this->newQuery()->eagerLoad();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    /**
     * Count the number of specified model records in the database.
     */
    public function count(): int
    {
        return $this->get()->count();
    }

    /**
     * Get the first specified model record from the database.
     */
    public function first(): Model
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->first();

        $this->unsetClauses();

        return $model;
    }

    /**
     * Get the first specified model record from the database or throw an exception if not found.
     */
    public function firstOrFail(): Model
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->firstOrFail();

        $this->unsetClauses();

        return $model;
    }

    /**
     * Get all the specified model records in the database.
     */
    public function get(): Collection
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    /**
     * Get the specified model record from the database.
     *
     * @param  mixed $id The unique identifier from the database record
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        $this->unsetClauses();
        $this->newQuery()->eagerLoad();

        return $this->query->findOrFail($id);
    }

    /**
     * Get the first record from an database table based on a WHERE statement.
     *
     * @param  mixed  $item     The item value where u want to search for in the table column.
     * @param  string $column   The name of the column in the database table.
     * @param  array  $columns  The column u want to select for the further usage.
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByColumn($item, string $column, array $columns = ['*'])
    {
        $this->unsetClauses();
        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $item)->first($columns);
    }

    /**
     * Delete the specified model record from the database.
     *
     * @param  mixed $id The unique identifier from the database record in the table.
     * @return bool|null
     *
     * @throws \Exception
     */
    public function deleteById($id): ?bool
    {
        $this->unsetClauses();

        return $this->getById($id)->delete();
    }

    /**
     * Set the query limit.
     */
    public function limit(int $limit): self
    {
        $this->take = $limit;

        return $this;
    }

    /**
     * Set an ORDER BY clause.
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    /**
     * @param int    $limit
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
    }

    /**
     * Add a simple where clause to the query.
     */
    public function where(string $column, string $value, string $operator = '='): self
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    /**
     * Add a simple where in clause to the query.
     *
     * @param  string $column The column name that u want to use in your WHERE in statement.
     * @param  mixed  $values The values where u want to select on.
     * @return $this
     */
    public function whereIn($column, $values): self
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param  string|array $relations The database relation(s) u want to use in your query.
     * @return $this
     */
    public function with($relations): self
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    /**
     * Create a new instance of the model's query builder.
     */
    protected function newQuery(): self
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Add relationships to the query builder to eager load.
     */
    protected function eagerLoad(): self
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        return $this;
    }

    /**
     * Set clauses on the query builder.
     */
    protected function setClauses(): self
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        if (isset($this->take) and ! is_null($this->take)) {
            $this->query->take($this->take);
        }

        return $this;
    }

    /**
     * Set query scopes.
     */
    protected function setScopes(): self
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(implode(', ', $args));
        }

        return $this;
    }

    /**
     * Reset the query clause parameter arrays.
     */
    protected function unsetClauses(): self
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }
}
