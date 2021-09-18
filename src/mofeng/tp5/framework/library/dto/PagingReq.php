<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/23
 * Time: 11:06
 */

namespace mofeng\tp5\dto;


class PagingReq extends BaseDto {
    private $page = 1;
    private $listRows = 15;

    /**
     * @return mixed
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page) {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getListRows() {
        return $this->listRows;
    }

    /**
     * @param mixed $listRows
     */
    public function setListRows($listRows) {
        $this->listRows = $listRows;
    }


}