<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketStatus extends Model
{
    protected $table = 'ticket_status_log';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ticket_id', 'admin_id', 'old_status', 'new_status', 'changed_at'];

    public function getTicketHistory($ticketId)
    {
        return $this->select('ticket_status_log.*, admins.name AS admin_name')
            ->join('admins', 'admins.id = ticket_status_log.admin_id', 'left')
            ->where('ticket_status_log.ticket_id', $ticketId)
            ->orderBy('ticket_status_log.changed_at', 'DESC')
            ->findAll();
    }
}


