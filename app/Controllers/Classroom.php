<?php

namespace App\Controllers;


use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Classroom extends ResourceController
{

    use ResponseTrait;

    protected $modelName = 'App\Models\ClassroomModel';
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {

        $classRooms = $this->model
            ->select('tbl_m_classroom_attedance.id_mcad')
            ->select('tbl_m_periode.name_period')
            ->select('tbl_m_level_attedance.name_mla')
            ->select('tbl_m_classroom_attedance.name_mcad')
            ->select('tbl_m_classroom_attedance.shift_mcad')
            ->select('tbl_m_classroom_attedance.from_mcad')
            ->select('tbl_m_classroom_attedance.upto_mcad')
            ->join('tbl_m_periode', 'tbl_m_periode.id_period = tbl_m_classroom_attedance.id_period')
            ->join('tbl_m_level_attedance', 'tbl_m_level_attedance.id_mla = tbl_m_classroom_attedance.id_mla')
            ->orderBy('tbl_m_classroom_attedance.id_mcad', 'ASC')
            ->findAll();

        $response = [
            "status"                => $this->codes['created'],
            "error"                 => $this->codes['created'],
            "messages"              => "Success",
            "data"                  => $classRooms
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
        $classRooms = $this->model
            ->select('tbl_m_classroom_attedance.id_mcad')
            ->select('tbl_m_periode.name_period')
            ->select('tbl_m_level_attedance.name_mla')
            ->select('tbl_m_classroom_attedance.name_mcad')
            ->select('tbl_m_classroom_attedance.shift_mcad')
            ->select('tbl_m_classroom_attedance.from_mcad')
            ->select('tbl_m_classroom_attedance.upto_mcad')
            ->join('tbl_m_periode', 'tbl_m_periode.id_period = tbl_m_classroom_attedance.id_period')
            ->join('tbl_m_level_attedance', 'tbl_m_level_attedance.id_mla = tbl_m_classroom_attedance.id_mla')
            ->find($id);

        $response = [
            "status"                => $this->codes['created'],
            "error"                 => $this->codes['created'],
            "messages"              => "Success",
            "data"                  => $classRooms
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
        $periodeCls     = $this->request->getPost('periodClass');
        $lvlCls         = $this->request->getPost('levelClass');
        $nameCls        = $this->request->getPost('nameClass');
        $shiftCls       = $this->request->getPost('shiftClass');
        $fromCls        = $this->request->getPost('fromClass');
        $uptoCls        = $this->request->getPost('uptoClass');
        $createdCls     = $this->request->getPost('createdClass');

        $rules = [
            "periodClass"  => [
                "rules" => "required"
            ],

            "levelClass"  => [
                "rules" => "required"
            ],

            "nameClass"  => [
                "rules" => "required"
            ],
            "shiftClass"  => [
                "rules" => "required"
            ],
            "fromClass"  => [
                "rules" => "required"
            ],
            "uptoClass"  => [
                "rules" => "required"
            ],
            "createdClass"  => [
                "rules" => "required"
            ],

        ];

        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());


        $data = [
            "id_period" => $periodeCls,
            "id_mla"    => $lvlCls,
            "name_mcad" => $nameCls,
            "shift_mcad" => $shiftCls,
            "from_mcad" => $fromCls,
            "upto_mcad" => $uptoCls,
            "created_by_mcad" => $createdCls,

        ];



        $this->model->insert($data);

        if ($data == true) {
            $output = [
                'status' => 201,
                'error' => null,
                'messages' => [
                    'success' => 'Data Saved'
                ]
            ];
            return $this->respondCreated($output);
        } else {
            $output = [
                'status'   => 401,
                'messages' => [
                    'success' => 'Data not Save'
                ]
            ];

            return $this->respondCreated($output);
        }
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
        try {
            //code...
            //
            $periodeCls     = $this->request->getVar('periodClass');
            $lvlCls         = $this->request->getVar('levelClass');
            $nameCls        = $this->request->getVar('nameClass');
            $shiftCls       = $this->request->getVar('shiftClass');
            $fromCls        = $this->request->getVar('fromClass');
            $uptoCls        = $this->request->getVar('uptoClass');
            $createdCls     = $this->request->getVar('createdClass');

            $data = [
                "id_period" => $periodeCls,
                "id_mla"    => $lvlCls,
                "name_mcad" => $nameCls,
                "shift_mcad" => $shiftCls,
                "from_mcad" => $fromCls,
                "upto_mcad" => $uptoCls,
                "created_by_mcad" => $createdCls,

            ];

            $this->model->update($id, $data);

            $response = [
                "status"     => $this->codes['created'],
                "error"      => $this->codes['created'],
                "messages"   => "Successfully updated the Classroom"
            ];

            return $this->respondCreated($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->fail("Failed to update the Classroom");
        }
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
