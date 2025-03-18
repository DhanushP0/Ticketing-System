<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TicketModel;

class ChartController extends Controller
{
    public function getTicketData()
    {
        $session = session();
        if (!$session->has('admin_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $category = $session->get('admin_category'); // Get admin's assigned category
        $ticketModel = new TicketModel();

        // Ticket Status Distribution
        $ticketStatusData = $ticketModel
            ->select('status, COUNT(*) as count')
            ->where('category', $category)
            ->groupBy('status')
            ->findAll();

        // Tickets by Urgency
        $urgencyData = $ticketModel
            ->select('urgency, COUNT(*) as count')
            ->where('category', $category)
            ->groupBy('urgency')
            ->findAll();

        // Ticket Trends (Last 7 Days)
        $trendData = $ticketModel
            ->select("DATE(created_at) as date, COUNT(*) as count")
            ->where('category', $category)
            ->where('created_at >=', date('Y-m-d', strtotime('-7 days')))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->findAll();

        // Response Time Performance (Average Response Time Per Ticket)
        $responseTimeData = $ticketModel
        ->select('status, AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_time')
        ->where('category', $category)
        ->where('status !=', 'Open') // Exclude open tickets
        ->where('updated_at IS NOT NULL', null, false) // Ensure updated_at exists
        ->groupBy('status')
        ->findAll();
    

        error_log(print_r($trendData, true));
return $this->response->setJSON([
    'ticketStatus' => $ticketStatusData,
    'urgency' => $urgencyData,
    'trend' => $trendData,
    'responseTime' => $responseTimeData
]);

    }
}
