<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\TicketAttachmentModel;
use App\Models\AdminModel;

class TicketController extends BaseController
{
    public function show_ticket_form()
    {
        return view('user/submit_ticket');
    }

    public function submitticket()
{
    if (!session()->get('is_logged_in')) {
        return redirect()->to(base_url('user/login'))->with('error', 'Please log in first.');
    }

    $ticketModel = new TicketModel();
    $userId = session()->get('user_id');
    $title = $this->request->getPost('title');
    $description = $this->request->getPost('description');
    $urgency = $this->request->getPost('urgency');

    // ✅ Get Admin ID
    $assignedAdminId = $this->findAvailableAdmin($urgency);
    if (!$assignedAdminId) {
        $assignedAdminId = null;  // Explicitly set NULL
    }

    // ✅ Insert into DB
    $ticketData = [
        'user_id' => $userId,
        'title' => $title,
        'description' => $description,
        'urgency' => $urgency,
        'assigned_admin_id' => $assignedAdminId,
        'status' => 'Open'
    ];

    $ticketModel->insert($ticketData);
    $ticketId = $ticketModel->insertID();
    $this->assignTicketAutomatically($ticketId);

    if (!$ticketId) {
        return redirect()->back()->with('error', 'Failed to submit ticket.');
    }

    return redirect()->to(base_url('user/ticket_status'))->with('success', 'Ticket submitted successfully!');
}


private function findAvailableAdmin($urgency)
{
    $adminModel = new AdminModel();

    // ✅ Find an admin with the same category & exclude superadmins
    $availableAdmin = $adminModel
        ->select('id')
        ->where('category', $urgency) // Match urgency with admin category
        ->where('role', 'admin') // Exclude superadmins
        ->orderBy('id', 'ASC')
        ->first();

    if ($availableAdmin) {
        log_message('debug', '✅ Assigned Admin ID: ' . $availableAdmin['id']);
        return $availableAdmin['id'];
    }

    log_message('debug', '❌ No admin found for urgency: ' . $urgency);
    return null;
}


    public function assignAdmin()
    {
        $ticketModel = new TicketModel();
        
        $ticketId = $this->request->getPost('ticket_id');
        $adminId = $this->request->getPost('admin_id');
    
        if (!$ticketId || !$adminId) {
            return redirect()->back()->with('error', 'Missing ticket ID or admin ID!');
        }
    
        $ticket = $ticketModel->find($ticketId);
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found!');
        }
    
        // Perform update
        $ticketModel->update($ticketId, ['assigned_admin_id' => $adminId]);
    
        return redirect()->back()->with('success', 'Admin assigned successfully.');
    }
    
    

    public function viewTickets()
    {
        $ticketModel = new TicketModel();
        $attachmentModel = new TicketAttachmentModel();
        $userId = session()->get('user_id');

        // ✅ Fetch tickets only for the logged-in user
        $data['tickets'] = $ticketModel->where('user_id', $userId)->findAll();

        // ✅ Fetch attachments for these tickets
        foreach ($data['tickets'] as &$ticket) {
            $ticket['attachments'] = $attachmentModel->where('ticket_id', $ticket['id'])->findAll();
        }

        return view('user/tickets', $data);
    }

    public function userTickets()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tickets')
            ->select('tickets.*, admins.name AS admin_name, ticket_attachments.file_path, ticket_attachments.file_type')
            ->join('admins', 'tickets.assigned_admin_id = admins.id', 'left')
            ->join('ticket_attachments', 'ticket_attachments.ticket_id = tickets.id', 'left')
            ->where('tickets.user_id', session()->get('user_id'));

        $tickets = $builder->get()->getResultArray();

        return view('user/tickets', ['tickets' => $tickets]);
    }
    public function assignTicketAutomatically($ticketId)
    {
        $adminModel = new AdminModel();
        $ticketModel = new TicketModel();
    
        // ✅ Get ticket details
        $ticket = $ticketModel->find($ticketId);
        if (!$ticket) {
            log_message('error', '❌ Ticket not found for ID: ' . $ticketId);
            return;
        }
    
        // ✅ Find an admin based on urgency (matching their category)
        $admin = $adminModel
            ->where('category', $ticket['urgency']) // Match urgency with category
            ->where('role', 'admin') // Exclude superadmins
            ->orderBy('id', 'ASC')
            ->first();
    
        if ($admin) {
            // ✅ Assign admin to the ticket
            $ticketModel->update($ticketId, ['assigned_admin_id' => $admin['id']]);
            log_message('debug', '🎯 Ticket ID: ' . $ticketId . ' assigned to Admin ID: ' . $admin['id']);
        } else {
            log_message('debug', '⚠️ No available admin for ticket ID: ' . $ticketId);
        }
    }
    public function status($ticket_id)
    {
        $session = session();
    
        // Ensure user is logged in before showing the page
        if (!$session->get('logged_in')) {
            return redirect()->to(base_url('user/login'))->with('error', 'Please log in first.');
        }
    
        $ticketModel = new TicketModel();
        $ticket = $ticketModel->find($ticket_id);
    
        if (!$ticket) {
            // Destroy session and prevent redirect loop if ticket is not found
            $session->destroy();
            return redirect()->to(base_url('user/login'))->with('error', 'Ticket not found.');
        }
    
        return view('ticket_status', ['ticket' => $ticket]);
    }    

}
