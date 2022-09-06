<?php

namespace App\Controllers\Api\Admin\Profile;

use CodeIgniter\API\ResponseTrait;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class ProfileController extends ResourceController
{
    use ResponseTrait;


    protected $modelName  = 'App\Models\UsersAttedance';
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $header      = $this->request->getServer('HTTP_AUTHORIZATION');
        $token       = explode(' ', $header)[1];
        $payload     = JWT::decode($token, new Key(Services::secretKey(), 'HS256'));

        $data = $this->model
            ->select('tbl_m_user_attedance.*')
            ->where('status_delete_mua', 0)->find($payload->uid);

        $response = [
            "status"        => 200,
            "error"         => 200,
            "messages"      => "Success",
            "data"          => $data
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
        //
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
