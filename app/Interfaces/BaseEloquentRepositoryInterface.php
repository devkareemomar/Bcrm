<?php

namespace App\Interfaces;

interface BaseEloquentRepositoryInterface
{
    /**
     * Return all items
     *
     * @param string $orderBy
     * @param array $relations
     * @param array $parameters
     * @return mixed
     */
    public function getAll($orderBy = 'id', array $relations = [], $relationCountWhere= '', $relationWhere = '' ,array $condition=[],  array $relationsCount = [], array $parameters = [], $fields = ['*']);


    /**
     * Paginate items
     *
     * @param string $orderBy
     * @param array $relations
     * @param integer $paginate
     * @param array $parameters
     * @return mixed
     */
    public function paginate($orderBy = 'id', array $relations = [], array $relationsCount = [], $paginate = 25, array $parameters = [], $fields = ['*']);


    /**
     * Get all items by a field
     *
     * @param array $parameters
     * @param array $relations
     * @return mixed
     */
    public function getAllBy(array $parameters, array $relations = [], array $relationsCount = [], $fields = ['*']);


    /**
     * List all items
     *
     * @param string $fieldName
     * @param string $fieldId
     * @return mixed
     */
    public function pluck($listFieldName, $listFieldId = null);


    /**
     * List records limited by a certain field
     *
     * @param string $field
     * @param string|array $value
     * @param string $listFieldName
     * @param string $listFieldId
     * @return mixed
     */
    public function pluckBy($field, $value, $listFieldName, $listFieldId = null);


    /**
     * return table colmuns
     * @param array $except
     * @return array
     * @throws \Exception
     */
    public function getTableColumns(array $except = []);

    /**
     * Find a single item
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     */
    public function findById($id, array $relations = [], array $relationsCount = [], $fields = ['*']);


    /**
     * Find a single item by a field
     *
     * @param string $field
     * @param string $value
     * @param array $relations
     * @return mixed
     */
    public function findBy($field, $value, array $relations = [], array $relationsCount = [], $fields = ['*']);


    /**
     * Find a single record by multiple fields
     *
     * @param array $data
     * @param array $relations
     * @return mixed
     */
    public function findByMany(array $data, array $relations = [], array $relationsCount = [], $fields = ['*']);


    /**
     * Find multiple models
     *
     * @param array $ids
     * @param array $relations
     * @return object
     */
    public function getAllWhereIn(array $ids, $whereInField = 'id', array $relations = [], array $relationsCount = [], array $parameters = [], $fields = ['*']);


    /**
     * Insert bulk record
     *
     * @param array $records array of records data
     * @throws \Exception
     */
    public function insert(array $records);

    /**
     * Store a newly created item
     *
     * @param array $data
     * @return mixed
     */
    public function store(array $data);


    /**
     * find and update the item
     *
     * @param int $id
     * @param array $data
     * @return object
     */
    public function updateById($id, array $data);


    /**
     * update item instance
     *
     * @param object $instance
     * @param array $data
     * @return object
     */
    public function updateByInstance($instance, array $data);


    /**
     * Permanently remove an item from storage
     *
     * @param integer $id
     * @return mixed
     */
    public function destroy($id);


    /**
     * Permanently remove many items from storage
     *
     * @param array $ids
     * @return mixed
     */
    public function destroyAll($ids);


    /**
     *  Permanently remove many items from storage by a field
     *
     * @param array $parameters
     * @param array $relations
     * @return mixed
     */
    public function destroyAllBy(array $parameters, $ids = null);


    /**
     * Get count of records
     *
     * @param null
     * @return integer
     */
    public function count($parameters, $ids = null);
}
