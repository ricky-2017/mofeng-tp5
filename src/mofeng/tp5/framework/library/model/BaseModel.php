<?php
/**
 * Created by PhpStorm.
 * User: JerryChaox
 * Date: 2018/10/27
 * Time: 13:16
 */

namespace mofeng\tp5\model;


use mofeng\tp5\dto\PagingReq;
use think\Model;

abstract class BaseModel extends Model {
    const ABLE = 1; // 启用
    const DISABLE = -1; // 禁用

    /**
     * 只读字段，写入以后就不允许被更新
     * @var array
     */
    protected $readonly = ['id', 'create_time'];
    /**
     * 自动写入时间戳
     * @var bool
     */
    protected $autoWriteTimestamp = 'datetime';

    //protected $connection = 'local_config';

    /**
     * @param array $data
     */
    public function __construct($data = []) {
        parent::__construct($data);
    }

    protected function initialize() {
        self::extendQueryMethod();
        parent::initialize();
    }

    public function softDeleteByPk($pk, $statusFieldName = "status") {
        return $this
            ->where($this->pk, $pk)
            ->update([$statusFieldName, self::DISABLE]);
    }

    public function hardDeleteByPk($pk, $withDelete = []) {
        if (!empty($withDelete)) {
            $obj = $this->find($pk);
            if (is_string($withDelete)) {
                $withDelete = explode(',', $withDelete);
            }

            foreach ($withDelete as $key => $name) {
                $obj->$name()->delete();
            }

            $obj->delete();
            return true;
        }
        return $this->destroy($pk);
    }

    function patch($field, $value, $where) {
        return call_user_func_array(self::getPatchMethod(), [$this, $field, $value, $where]);
    }

    function upsert($data = [], $where = [], $sequence = null) {
        return call_user_func_array(self::getUpsertMethod(), [$this, $data, $where, $sequence]);
    }

    public function lists($paging = true) {
        return call_user_func_array(self::getListsMethod(), [$this, $paging]);
    }

    public function relationSaveOrUpdate($data, $relationSet = []) {
        if (empty($data[$this->pk])) {
            exception('NULL PK IN DATA WHEN RELATION UPDATE');
        }
        $object = $this->find($data[$this->pk]);

        foreach ($relationSet as $key => $value) {
            $relation = is_numeric($key) ? $value : $key;
            $relationUpdateFlag = isset($value['updateFlag']) ? $value['updateFlag'] : true;

            if (!$value['batch']) {
                $object->$relation()->allowField(true)
                    ->isUpdate($relationUpdateFlag)
                    ->save($data[$value]);
            } else {
                $object->$relation()
                    ->allowField(true)
                    ->isUpdate($relationUpdateFlag)
                    ->saveAll($data[$key]);
            }
        }
    }

    private function extendQueryMethod() {
        self::extend("lists", $this->getListsMethod());
        self::extend("patch", $this->getPatchMethod());
        self::extend("upsert", $this->getUpsertMethod());
    }

    private function getPatchMethod() {
        return function ($query, $field, $value, $where) {
          $query->update([$field => $value], $where);
        };
    }

    private function getUpsertMethod() {
        return function ($query, $data = [], $where = [], $sequence = null) {
            $result = $query->where($where)->find();
            trace($result, 'rrrrrrrrr');
            $result->save($data);
            $pk = ($result->getPk());
            return $result->$pk;
        };
    }

    private function getListsMethod() {
        return function ($query, $paging = true) {
            if($paging instanceof PagingReq) {
                $paging = $paging->toArray(true);
            }
            return $paging ? $query->paginate($paging) : $query->select();
        };
    }

    /**
     * 用作过滤字段
     * @param array $toFilter 要过滤的数组
     * @param array $filter 规定的字段
     * @return array
     */
    protected function filterData($toFilter = [], $filter = []) {
        return array_filter($toFilter, function ($v, $k) use ($filter) {
            return in_array($k, $filter) ? true : false;
        }, ARRAY_FILTER_USE_BOTH);
    }
}