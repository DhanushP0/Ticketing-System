<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ticket_id', 'admin_id', 'user_id', 'sender', 'message', 'attachment', 'created_at'];
}
