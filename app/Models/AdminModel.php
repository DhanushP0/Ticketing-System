<?php

namespace App\Models;
use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'admins';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'email', 'password', 'category', 'role', 'created_at','profile_picture'];

    protected $useTimestamps = false;  

    protected $createdField  = 'created_at';
    public function getAdminName($adminId)
{
    $admin = $this->find($adminId);
    return $admin ? $admin['name'] : 'Unassigned';
}

}
