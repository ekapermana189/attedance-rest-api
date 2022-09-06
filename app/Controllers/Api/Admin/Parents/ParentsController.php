<?php

namespace App\Controllers\Api\Admin\Parents;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ParentsController extends ResourceController
{

    protected $modelName = 'App\Models\UsersAttedance';

    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
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
        $header     = $this->request->getServer('HTTP_AUTHORIZATION');
        $token      = explode(' ', $header)[1];
        $payload    = JWT::decode($token, new Key(Services::secretKey(), 'HS256'));
        $userID     = $payload->uid;


        $UserRolePar       = $this->request->getPost('UserRoleId');
        $ClassIdPar        = $this->request->getPost('ClassId');
        $FullNamePar       = $this->request->getPost('fullName');
        $PlaceBornPar      = $this->request->getPost('placeBorn');
        $DateBornPar       = $this->request->getPost('dateBorn');
        $AddressPar        = $this->request->getPost('address');
        $PhonePar          = $this->request->getPost('phoneNumber');
        $EmailPar          = $this->request->getPost('emailAdd');
        $GenderPar         = $this->request->getPost('gender');
        $username          = $this->request->getPost('usernameParent');
        $password          = $this->request->getPost('passwordParent');
        $ParentPicturePar  = $this->request->getFile('parentPicture');

        $checkUniqueEmail = $this->model
            ->where([
                'email_mua' => $EmailPar,
                'id_mra'            => 2,
                'status_delete_mua' => 0
            ])->first();
        $emailIsUnique = !empty($checkUniqueEmail) ? "|is_unique[tbl_m_user_attedance.email_mua]" : null;

        $validation = [
            "UserRoleId"  => [
                "rules" => "required"
            ],

            "ClassId"  => [
                "rules" => "required"
            ],

            "placeBorn"  => [
                "rules" => "required"
            ],
            "dateBorn"  => [
                "rules" => "required"
            ],
            "address"  => [
                "rules" => "required"
            ],
            "phoneNumber"  => [
                "rules" => "required"
            ],
            "emailAdd"  => [
                "rules" => "required|valid_email{$emailIsUnique}",
                "errors" => [
                    "is_unique" => "Email already used"
                ]
            ],
            "gender"  => [
                "rules" => "required"
            ],
            "usernameParent"  => [
                "rules" => "required"
            ],
            "passwordParent"  => [
                "rules" => "required"
            ],
            'parentPicture' => [
                'rules' => 'uploaded[parentPicture]',
            ],


        ];

        if (!$this->validate($validation)) return $this->fail($this->validator->getErrors());

        # Check input picture or not.
        if ($ParentPicturePar->getName()) {
            # Photo Path & Name
            $filePath      = "pictures/";
            $fileName      = $ParentPicturePar->getRandomName();

            # Move File
            $ParentPicturePar->move($filePath, $fileName);

            # Photo Path URL
            $ParentImagePath = $filePath . $fileName;
        }

        $ParentImagePath = isset($ParentImagePath) ? $ParentImagePath : NULL;

        $parentData = [
            "id_mra"         => $UserRolePar,
            "id_mcad"        => $ClassIdPar,
            "name_mua"       => $FullNamePar,
            "place_born_mua" => $PlaceBornPar,
            "date_born_mua"  => $DateBornPar,
            "address_mua"    => $AddressPar,
            "phone_mua"      => $PhonePar,
            "email_mua"      => $EmailPar,
            "gender_mua"     => $GenderPar,
            "img_path_mua"   => $ParentImagePath,
            "username_mua"   => $username,
            "password_mua"   => password_hash($password, PASSWORD_BCRYPT),
            "created_by_mua" => $userID,
        ];

        $this->model->insert($parentData);

        $response = [
            "status"     => $this->codes['created'],
            "error"      => $this->codes['created'],
            "messages"   => "Successfully created the Parent Data"
        ];

        return $this->respondCreated($response);
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
        $header     = $this->request->getServer('HTTP_AUTHORIZATION');
        $token      = explode(' ', $header)[1];
        $payload    = JWT::decode($token, new Key(Services::secretKey(), 'HS256'));
        $userID     = $payload->uid;
        try {
            //code...
            //
            $UserRolePar       = $this->request->getVar('UserRoleId');
            $ClassIdPar        = $this->request->getVar('ClassId');
            $FullNamePar       = $this->request->getVar('fullName');
            $PlaceBornPar      = $this->request->getVar('placeBorn');
            $DateBornPar       = $this->request->getVar('dateBorn');
            $AddressPar        = $this->request->getVar('address');
            $PhonePar          = $this->request->getVar('phoneNumber');
            $EmailPar          = $this->request->getVar('emailAdd');
            $GenderPar         = $this->request->getVar('gender');
            $username          = $this->request->getVar('usernameParent');
            $password          = $this->request->getVar('passwordParent');



            $data = [
                "id_mra"         => $UserRolePar,
                "id_mcad"        => $ClassIdPar,
                "name_mua"       => $FullNamePar,
                "place_born_mua" => $PlaceBornPar,
                "date_born_mua"  => $DateBornPar,
                "address_mua"    => $AddressPar,
                "phone_mua"      => $PhonePar,
                "email_mua"      => $EmailPar,
                "gender_mua"     => $GenderPar,
                "username_mua"   => $username,
                "password_mua"   => password_hash($password, PASSWORD_BCRYPT),
                "created_by_mua" => $userID,

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
