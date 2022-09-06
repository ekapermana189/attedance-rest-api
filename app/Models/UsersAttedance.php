<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersAttedance extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tbl_m_user_attedance';
    protected $primaryKey       = 'id_mua';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_mra',
        'id_mcad',
        'name_mua',
        'place_born_mua',
        'date_born_mua',
        'address_mua',
        'phone_mua',
        'email_mua',
        'gender_mua',
        'img_name_mua',
        'img_path_mua',
        'username_mua',
        'password_mua',
        'status_deactive_mua',
        'status_delete_mua',
        'created_by_mua',
        'created_update_mua',
        'updated_by_mua',
        'updated_date_mua',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
