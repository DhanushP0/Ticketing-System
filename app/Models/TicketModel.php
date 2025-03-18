<?php
namespace App\Models;
use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'ticket_id', 'user_id', 'email', 'title', 'description', 'category', 
        'status', 'urgency', 'assigned_admin_id', 'attachments', 'updated_at' 
    ];
    protected $useTimestamps = true;
protected $createdField = 'created_at';
protected $updatedField = 'updated_at';
protected $beforeUpdate = ['updateTimestamp'];

protected function updateTimestamp(array $data) {
    $data['data']['updated_at'] = date('Y-m-d H:i:s');
    return $data;
}



    public function getTotalTickets()
    {
        return $this->countAll();
    }

    public function getPendingTickets()
    {
        return $this->where('status', 'Open')->countAllResults();
    }

    public function getResolvedTickets()
    {
        return $this->where('status', 'Resolved')->countAllResults();
    }

    public function getAvgResponseTime()
    {
        return $this->select("AVG(NULLIF(TIMESTAMPDIFF(HOUR, created_at, updated_at), 0)) as avg_time")
                    ->where("status !=", "Open") // ✅ Exclude Open tickets
                    ->first()['avg_time'] ?? 0;
    }
    

    public function getStatusCounts()
    {
        return $this->select("status, COUNT(*) as count")
                    ->groupBy("status")
                    ->findAll();
    }

    public function getUrgencyCounts()
    {
        return $this->select("urgency, COUNT(*) as count")
                    ->groupBy("urgency")
                    ->findAll();
    }
}
