<?php

namespace App\Controllers\Api\Admin\Auth;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AdminAuthController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\UsersAttedance';

    public function login()
    {
        $rules = [
            'email'         => 'required|valid_email',
            'password'      => 'required|min_length[6]'
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $email      = $this->request->getVar('email');
        $password   = $this->request->getVar('password');

        $account = $this->model->where([
            "id_mra"                 => 1,
            "email_mua"              => $email,
            "status_deactive_mua"    => 0,
            "status_delete_mua"      => 0,
        ])->first();

        $passwordVerify = $account ? password_verify($password, $account['password_mua']) : false;

        if (!$account) return $this->fail('Account Not Found');
        if (!$passwordVerify) return $this->fail('Wrong Password');

        try {
            $issuedatClaim       = time();
            $notbeforeClaim      = $issuedatClaim;
            $expireClaim         = $issuedatClaim + 3600;

            $payloadAccessToken = [
                "iat"           => $issuedatClaim,
                "nbf"           => $notbeforeClaim,
                "exp"           => $expireClaim,
                "uid"           => $account['id_mua'],
                "userRoleID"    => $account['id_mra'],
                "guard"         => "admin"
            ];

            $payloadRefreshToken = [
                "iat"   => $issuedatClaim,
                "nbf"   => $notbeforeClaim,
                "guard" => "admin",
                "data"  => [
                    "email"         => $account['email_mua'],
                    "password"      => $account['password_mua'],
                    "userRoleID"    => $account['id_mra'],
                ]
            ];

            $accessToken    = JWT::encode($payloadAccessToken, Services::secretKey(), 'HS256');
            $refreshToken   = JWT::encode($payloadRefreshToken, Services::secretRefreshKey(), 'HS256');

            $response = [
                "status"                => $this->codes['created'],
                "error"                 => $this->codes['created'],
                "messages"              => "Success",
                "expireAt"              => date("Y-m-d H:i:s", $expireClaim),
                "accessToken"           => $accessToken,
                "refreshToken"          => $refreshToken,
            ];

            return $this->respondCreated($response);
        } catch (\Throwable $th) {
            return $this->fail("Failed login.");
        }
    }

    public function register()
    {
        $fullName   = $this->request->getPost('fullName');
        $email      = $this->request->getPost('email');
        $password   = $this->request->getPost('password');
        $createdBy  = $this->request->getPost('createdBy');

        $checkUniqueEmail = $this->model
            ->orWhere(['email_mua' => $email])
            ->where([
                'id_mra'            => 1,
                'status_delete_mua' => 0
            ])
            ->first();
        $emailIsUnique = !empty($checkUniqueEmail) ? "|is_unique[tbl_m_user_attedance.email_mua]" : null;

        $rules = [
            "fullName"  => [
                "rules" => "required"
            ],
            "email" => [
                "rules" => "required|valid_email{$emailIsUnique}",
                "errors" => [
                    "is_unique" => "Email already used"
                ]
            ],
            "password"  => [
                "rules" => "required|min_length[6]"
            ],
            "passwordConfirmed" => [
                "rules" => "matches[password]",
                "errors" => [
                    'matches'  => 'The Password Confirmed field does not match the Password field.'
                ]
            ],

        ];

        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        try {
            $waliUsersData = [
                "id_mra"                => 1,
                "name_mua"              => $fullName,
                "email_mua"             => $email,
                "password_mua"          => password_hash($password, PASSWORD_BCRYPT),
                "created_by_mua"        => $createdBy
            ];

            $this->model->insert($waliUsersData);

            $response = [
                "status"     => $this->codes['created'],
                "error"      => $this->codes['created'],
                "messages"   => "Successfully updated your account."
            ];

            return $this->respondCreated($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->fail("Failed to create your account.");
        }
    }

    public function refresh()
    {
        $header   = $this->request->getServer('HTTP_AUTHORIZATION');

        try {
            $token      = explode(' ', $header)[1];
            $payload    = JWT::decode($token, new Key(Services::secretRefreshKey(), 'HS256'));

            $payload    = $payload->data;

            $account    = $this->model->where([
                "email_mua"                 => $payload->email,
                "password_mua"              => $payload->password,
                "id_mra"                    => $payload->userRoleID,
                "status_deactive_mua"       => 0,
                "status_delete_mua"         => 0,
            ])->first();

            if (!$account) return $this->failForbidden('Tokens can no longer be used');

            $issuedatClaim       = time();
            $notbeforeClaim      = $issuedatClaim;
            $expireClaim         = $issuedatClaim + Services::tokenExpirationTime();

            $payloadAccessToken = [
                "iat"           => $issuedatClaim,
                "nbf"           => $notbeforeClaim,
                "exp"           => $expireClaim,
                "uid"           => $account['id_mua'],
                "userRoleID"    => $account['id_mra'],
                "guard"         => "admin"
            ];

            $accessToken    = JWT::encode($payloadAccessToken, Services::secretKey(), 'HS256');

            $response = [
                "status"        => $this->codes['created'],
                "error"         => $this->codes['created'],
                "messages"      => "Success",
                "expireAt"      => date("Y-m-d H:i:s", $expireClaim),
                "accessToken"   => $accessToken,
            ];

            return $this->respondCreated($response);
        } catch (\Throwable $th) {
            return $this->failUnauthorized('Invalid Token');
        }
    }
}
