<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{


    use ResponseTrait;

    protected $modelName = 'App\Models\UsersAttedance';
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
        $users = $this->model->findAll();

        $response = [
            "status"                => $this->codes['created'],
            "error"                 => $this->codes['created'],
            "messages"              => "Success",
            "data"                  => $users
        ];

        return $this->respond($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {

        $users = $this->model->find($id);

        $response = [
            "status"                => $this->codes['created'],
            "error"                 => $this->codes['created'],
            "messages"              => "Success",
            "data"                  => $users
        ];

        return $this->respond($response);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
