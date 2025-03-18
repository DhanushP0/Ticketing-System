<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table = 'admin_activity';
    protected $primaryKey = 'id';
    protected $allowedFields = ['admin_name', 'action', 'status', 'timestamp'];
}
