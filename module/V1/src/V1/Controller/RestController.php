<?php

namespace V1\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class RestController extends AbstractRestfulController {

    public function getList() {
        # code...
        return new JsonModel(array());
    }

    public function get($id) {
        # code...
        return new JsonModel(array());
    }

    public function create($data) {
        # code...
        return new JsonModel(array());
    }

    public function update($id, $data) {
        # code...
        return new JsonModel(array());
    }

    public function delete($id) {
        # code...
        return new JsonModel(array());
    }

}