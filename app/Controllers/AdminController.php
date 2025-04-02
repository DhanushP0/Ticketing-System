<?php

namespace App\Controllers;
use App\Models\AdminModel;
use App\Models\TicketModel;
use App\Models\MessageModel;
use CodeIgniter\Controller;

class AdminController extends BaseController
{
    public function allView()
{
    $session = session();

    // 🚫 Redirect logged-in admins to the dashboard
    if ($session->get('admin_id')) {
        return redirect()->to(base_url('admin/dashboard'));
    }

    return view('all_view'); // ✅ Load only for non-logged-in users
}

    public function login()
    {
        return view('admin/login');
    }

    public function authenticate()
    {
        $session = session(); // No need for session_start()
        $adminModel = new AdminModel();
    
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
        $admin = $adminModel->where('email', $email)->first();
    
        if ($admin && password_verify($password, $admin['password'])) {
            $session->regenerate(); // Secure session handling
    
            $session->set([
                'admin_id' => $admin['id'],
                'admin_name' => $admin['name'],
                'admin_email' => $admin['email'],
                'category' => $admin['category'],
                'role' => 'admin',
                'is_logged_in' => true
            ]);
    
            return redirect()->to(base_url('admin/dashboard'))->with('success', 'Login successful!');
        }
    
        return redirect()->to(base_url('admin/login'))->with('error', 'Invalid email or password.');
    }
    
    //     public function index()
// {
//     $db = \Config\Database::connect();
//     $builder = $db->table('tickets');

    //     if (!empty($_GET['ticket_id'])) {
//         $builder->where('ticket_id', $_GET['ticket_id']);
//     }

    //     if (!empty($_GET['title'])) {
//         $builder->like('title', $_GET['title']);
//     }

    //     if (!empty($_GET['urgency']) && $_GET['urgency'] !== 'all') {
//         $builder->where('urgency', $_GET['urgency']);
//     }

    //     if (!empty($_GET['status']) && $_GET['status'] !== 'all') {
//         $builder->where('status', $_GET['status']);
//     }

    //     $data['tickets'] = $builder->get()->getResult();

    //     return view('tickets/index', $data);
// }


    // public function dashboard()
    // {
    //     if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
    //         return redirect()->to(base_url('admin/login'))->with('error', 'Unauthorized access.');
    //     }

    //     $ticketModel = new \App\Models\TicketModel();
    //     $adminModel = new \App\Models\AdminModel();

    //     $adminId = session()->get('admin_id');

    //     // Get admin's category
    //     $admin = $adminModel->find($adminId);
    //     $adminCategory = $admin ? $admin['category'] : null;

    //     if (!$adminCategory) {
    //         return redirect()->to(base_url('admin/login'))->with('error', 'Your account has no assigned category.');
    //     }

    //     // Fetch tickets for admin's category
    //     $tickets = $ticketModel->where('category', $adminCategory)->findAll();

    //     return view('admin/dashboard', ['tickets' => $tickets]);
    // }

    public function update_ticket()
    {
        $session = session();
        $db = \Config\Database::connect();
        $ticketModel = new TicketModel();
        $adminModel = new AdminModel();
    
        $ticketId = $this->request->getPost('ticket_id');
        $status = $this->request->getPost('status');
        $adminId = $session->get('admin_id');
    
        if (!$adminId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        if (!$ticketId || !$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
    
        // Validate status input
        $validStatuses = ['Open', 'In Progress', 'Resolved'];
        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }
    
        // Get current status
        $ticket = $ticketModel->find($ticketId);
        if (!$ticket) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ticket not found']);
        }
    
        $oldStatus = $ticket['status'];
    
        // Update ticket status
        $ticketModel->update($ticketId, ['status' => $status]);
    
        // Log status change
        $db->table('ticket_status_log')->insert([
            'ticket_id'  => $ticketId,
            'admin_id'   => $adminId,
            'old_status' => $oldStatus,
            'new_status' => $status,
            'changed_at' => date('Y-m-d H:i:s')
        ]);
    
        $message = 'Ticket updated successfully.';
    
        // If ticket is resolved, assign a new one
        if ($status === 'Resolved') {
            $admin = $adminModel->find($adminId);
            $adminCategory = $admin ? $admin['category'] : null;
    
            if ($adminCategory) {
                $nextTicket = $ticketModel->where('category', $adminCategory)
                    ->where('assigned_admin_id', null)
                    ->orWhere('assigned_admin_id', '') // Handles NULL or empty values
                    ->orderBy("FIELD(urgency, 'High', 'Medium', 'Low')")
                    ->orderBy('created_at', 'ASC')
                    ->first();
    
                if ($nextTicket) {
                    $ticketModel->update($nextTicket['id'], ['assigned_admin_id' => $adminId]);
                    $message = 'Ticket updated! You have been assigned a new ticket.';
                } else {
                    $message = 'Ticket updated! No more pending tickets.';
                }
            }
        }
    
        return $this->response->setJSON([
            'success' => true,
            'new_status' => $status,
            'message' => $message
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/login'));
    }
    public function ticket()
    {
        $ticketModel = new TicketModel();
        $adminId = session()->get('admin_id');

        // Get filter values from GET request
        $ticket_id = $this->request->getGet('ticket_id');
        $title = $this->request->getGet('title'); // Title filter
        $urgency = $this->request->getGet('urgency');
        $status = $this->request->getGet('status');
        $perPage = $this->request->getGet('perPage') ?? 10; // Default 10 tickets per page

        // Query builder
        $query = $ticketModel->where('assigned_admin_id', $adminId);

        // Apply filters
        if (!empty($ticket_id)) {
            $query->where('ticket_id', $ticket_id); // Use 'ticket_id' instead of 'id'
        }

        if (!empty($title)) {
            $query->like('title', $title); // Search for partial matches in title
        }

        if (!empty($urgency) && $urgency !== 'all') {
            $query->where('urgency', $urgency);
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        // Paginate results
        $data['tickets'] = $query->paginate($perPage);
        $data['pager'] = $ticketModel->pager; // Pagination links

        return view('admin/ticket', $data);
    }

    public function updateProfile()
    {
        $adminModel = new AdminModel();
        $adminId = session('admin_id');

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');

        $data = [
            'name' => $name,
            'email' => $email
        ];

        // Handling the profile picture upload
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            $newName = $profilePicture->getRandomName();
            $profilePicture->move('uploads/profile_pictures/', $newName);

            // ✅ Store only the relative path in the database
            $data['profile_picture'] = 'uploads/profile_pictures/' . $newName;

            // ✅ Update session so the image appears after logout/login
            session()->set('profile_picture', $data['profile_picture']);
        }

        $adminModel->update($adminId, $data);

        session()->set('admin_name', $name);
        session()->set('admin_email', $email);
        session()->setFlashdata('profile_updated', 'Profile updated successfully.');

        return redirect()->to('admin/profile');
    }

    public function profile()
    {
        return view('admin/profile'); // Ensure 'profile.php' is inside 'app/Views/admin/'
    }
    public function resolveTicket($ticketId)
    {
        $session = session();
        $ticketModel = new TicketModel();
        $adminModel = new AdminModel();
    
        $adminId = $session->get('admin_id');
    
        // Check if the ticket exists and is assigned to the logged-in admin
        $ticket = $ticketModel->find($ticketId);
        if (!$ticket || $ticket['assigned_admin_id'] != $adminId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid ticket or not assigned to you.']);
        }
    
        // Update the ticket status to 'Resolved' and set resolved_at timestamp
        $ticketModel->update($ticketId, [
            'status' => 'Resolved',
            'resolved_at' => date('Y-m-d H:i:s') //  Set resolved_at to current timestamp
        ]);
    
        // Get the admin's category
        $admin = $adminModel->find($adminId);
        $adminCategory = $admin ? $admin['category'] : null;
    
        if (!$adminCategory) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Ticket resolved! No category found for admin.'
            ]);
        }
    
        // Find the next unassigned ticket **within the same category**
        $nextTicket = $ticketModel->where('category', $adminCategory)
            ->where('assigned_admin_id', null) // NULL check
            ->orWhere('assigned_admin_id', '') // Empty string check
            ->orderBy("FIELD(urgency, 'High', 'Medium', 'Low')")
            ->orderBy('created_at', 'ASC')
            ->first();
    
        if ($nextTicket) {
            // Assign the next ticket to the current admin
            $ticketModel->update($nextTicket['id'], ['assigned_admin_id' => $adminId]);
    
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Ticket resolved! You have been assigned a new ticket.',
                'next_ticket_id' => $nextTicket['id'],
                'next_ticket_title' => $nextTicket['title']
            ]);
        }
    
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Ticket resolved! No more pending tickets to assign in your category.'
        ]);
    }

    /**
     * Assigns the next available unassigned ticket to the admin.
     */
    private function assignNextUnassignedTicket($adminId, $category)
    {
        $ticketModel = new TicketModel();

        // Find the first unassigned ticket in the same category
        $nextTicket = $ticketModel
            ->where('category', $category)
            ->where('assigned_admin_id', null) // Ensure NULL filtering works
            ->orWhere('assigned_admin_id', '') // Also check if stored as empty string
            ->orderBy('id', 'ASC')
            ->first();

        if ($nextTicket) {
            // Assign the ticket to the admin
            $ticketModel->update($nextTicket['id'], ['assigned_admin_id' => $adminId]);

            log_message('debug', "✅ Unassigned ticket ID {$nextTicket['id']} is now assigned to Admin ID {$adminId}");
        } else {
            log_message('debug', "❌ No unassigned tickets available for category: $category");
        }
    }
    public function viewMessages($ticketId)
    {
        $ticketModel = new TicketModel();
        $messageModel = new MessageModel();

        // Get ticket details
        $ticket = $ticketModel->where('ticket_id', $ticketId)->first();
        if (!$ticket) {
            return view('errors/custom_error', ['message' => "Ticket not found for ID: " . esc($ticketId)]);
        }

        // Get messages related to the ticket
        $messages = $messageModel->where('ticket_id', $ticketId)->orderBy('created_at', 'ASC')->findAll();

        // Load the admin/messages view
        return view('admin/messages', [
            'ticket' => $ticket,
            'messages' => $messages
        ]);
    }
    public function sendMessage()
    {
        $db = \Config\Database::connect();
        $session = session();

        $ticket_id = $this->request->getPost('ticket_id');
        $message = $this->request->getPost('message');

        $admin_id = $session->get('admin_id') ?? 0;
        $user_id = $session->get('user_id') ?? null;

        if (!$admin_id && !$user_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized action!']);
        }

        $sender = ($admin_id > 0) ? 'admin' : 'user';

        $attachmentPath = '';
        $attachment = $this->request->getFile('attachment');

        if ($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'video/mp4', 'video/avi', 'video/mov', 'application/pdf'];
            $maxSize = 5 * 1024 * 1024; // 5MB Limit

            if (!in_array($attachment->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid file type!']);
            }

            if ($attachment->getSize() > $maxSize) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'File size exceeds 5MB limit!']);
            }

            $newName = $attachment->getRandomName();
            $attachment->move('uploads/messages', $newName);
            $attachmentPath = 'uploads/messages/' . $newName;
        }

        $data = [
            'ticket_id' => $ticket_id,
            'admin_id' => $admin_id,
            'user_id' => $user_id,
            'sender' => $sender,
            'message' => $message,
            'attachment' => $attachmentPath,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if (!$db->table('messages')->insert($data)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save message!']);
        }

        $attachmentUrl = !empty($attachmentPath) ? base_url($attachmentPath) : '';

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'attachment' => $attachmentUrl
        ]);
    }

    public function getMessages($ticket_id)
    {
        $messageModel = new MessageModel();
        $messages = $messageModel->where('ticket_id', $ticket_id)->orderBy('created_at', 'ASC')->findAll();

        if (!$messages) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No messages found']);
        }

        return $this->response->setJSON(['status' => 'success', 'messages' => $messages]);
    }

    public function dashboard()
    {
        $session = session();

        $adminId = $session->get('admin_id');

        $ticketModel = new TicketModel();

        // dd($ticketModel->getAvgResponseTime());
        $tickets = $ticketModel->where('assigned_admin_id', $adminId)->findAll();
        $data['tickets'] = $tickets;



        // Fetch ticket counts for this admin
        $data['totalTickets'] = $ticketModel->where('assigned_admin_id', $adminId)->countAllResults();
        $data['openTickets'] = $ticketModel->where(['assigned_admin_id' => $adminId, 'status' => 'Open'])->countAllResults();
        $data['resolvedTickets'] = $ticketModel->where(['assigned_admin_id' => $adminId, 'status' => 'Resolved'])->countAllResults();
        $data['highPriorityTickets'] = $ticketModel->where(['assigned_admin_id' => $adminId, 'urgency' => 'High'])->countAllResults();

        // Chart data
        $data['statusCounts'] = [
            $ticketModel->where(['assigned_admin_id' => $adminId, 'status' => 'Open'])->countAllResults(),
            $ticketModel->where(['assigned_admin_id' => $adminId, 'status' => 'In Progress'])->countAllResults(),
            $ticketModel->where(['assigned_admin_id' => $adminId, 'status' => 'Resolved'])->countAllResults(),
        ];

        $data['urgencyCounts'] = [
            $ticketModel->where(['assigned_admin_id' => $adminId, 'urgency' => 'Low'])->countAllResults(),
            $ticketModel->where(['assigned_admin_id' => $adminId, 'urgency' => 'Medium'])->countAllResults(),
            $ticketModel->where(['assigned_admin_id' => $adminId, 'urgency' => 'High'])->countAllResults(),
        ];

        // Ticket trends (last 7 days)
        $ticketTrend = $ticketModel->select("DATE(created_at) as date, COUNT(*) as count")
            ->where('assigned_admin_id', $adminId)
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->limit(7)
            ->findAll();

        $data['ticketTrendDates'] = array_column($ticketTrend, 'date');
        $data['ticketTrendCounts'] = array_column($ticketTrend, 'count');

        // Average Response Time
        $responseTimes = $ticketModel->select("
    COALESCE(MIN(TIMESTAMPDIFF(HOUR, created_at, updated_at)), 0) as min_time, 
    COALESCE(AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)), 0) as avg_time, 
    COALESCE(MAX(TIMESTAMPDIFF(HOUR, created_at, updated_at)), 0) as max_time
")
            ->where('assigned_admin_id', $adminId)
            ->where('updated_at IS NOT NULL', null, false)
            ->first();

        // Ensure data exists
        $responseTimes = $responseTimes ?? ['min_time' => 0, 'avg_time' => 0, 'max_time' => 0];
        $data['responseTimeLabels'] = ['Min Time', 'Avg Time', 'Max Time'];


        $data['responseTimes'] = array_map('intval', [
            $responseTimes['min_time'] ?? 0,
            $responseTimes['avg_time'] ?? 0,
            $responseTimes['max_time'] ?? 0
        ]);
        // print_r($responseTimes);exit();


        return view('admin/dashboard', $data);
    }
//     public function filterTickets()
// {
//     $page = $this->request->getGet('page') ?? 1;
//     $rowsPerPage = $this->request->getGet('rows') ?? 5;
//     $urgency = $this->request->getGet('urgency');
//     $status = $this->request->getGet('status');

    //     $query = $this->db->table('tickets');

    //     if ($urgency !== "all") {
//         $query->where('urgency', $urgency);
//     }
//     if ($status !== "all") {
//         $query->where('status', $status);
//     }

    //     $totalTickets = $query->countAllResults(false);
//     $totalPages = ceil($totalTickets / $rowsPerPage);

    //     $tickets = $query->limit($rowsPerPage, ($page - 1) * $rowsPerPage)->get()->getResultArray();

    //     $html = '';
//     foreach ($tickets as $ticket) {
//         $html .= '<tr>
//                     <td>' . esc($ticket['id']) . '</td>
//                     <td>' . esc($ticket['title']) . '</td>
//                     <td>' . esc($ticket['description']) . '</td>
//                     <td><span class="badge bg-' . ($ticket['urgency'] === 'High' ? 'danger' : ($ticket['urgency'] === 'Medium' ? 'warning' : 'success')) . '">' . esc($ticket['urgency']) . '</span></td>
//                     <td><span class="badge bg-' . ($ticket['status'] === 'Open' ? 'info' : ($ticket['status'] === 'In Progress' ? 'warning' : 'success')) . '">' . esc($ticket['status']) . '</span></td>
//                     <td><a href="' . base_url('admin/ticket/' . esc($ticket['id'])) . '" class="btn btn-primary">View</a></td>
//                   </tr>';
//     }

    //     return $this->response->setJSON(['tickets' => $html, 'totalPages' => $totalPages]);
// }


    public function getTicketActivity()
    {
        $ticketModel = new TicketModel();
        $adminId = session()->get('admin_id'); // Assuming admin ID is stored in session

        // Fetch new tickets count per day for the last 7 days
        $newTickets = $ticketModel->select("DATE(created_at) as date, COUNT(id) as count")
            ->where('assigned_admin_id', $adminId)
            ->where('created_at >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->findAll();

        // Fetch resolved tickets count per day for the last 7 days
        $resolvedTickets = $ticketModel->select("DATE(updated_at) as date, COUNT(id) as count")
            ->where('assigned_admin_id', $adminId)
            ->where('status', 'Resolved')
            ->where('updated_at >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->findAll();

        // Generate labels for the last 7 days
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = date('Y-m-d', strtotime("-$i days"));
        }

        // Format the data to match chart labels
        $newData = array_fill(0, 7, 0);
        $resolvedData = array_fill(0, 7, 0);

        foreach ($newTickets as $ticket) {
            $index = array_search($ticket['date'], $labels);
            if ($index !== false) {
                $newData[$index] = (int) $ticket['count'];
            }
        }

        foreach ($resolvedTickets as $ticket) {
            $index = array_search($ticket['date'], $labels);
            if ($index !== false) {
                $resolvedData[$index] = (int) $ticket['count'];
            }
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'newTickets' => $newData,
            'resolvedTickets' => $resolvedData
        ]);
    }
    // public function updateTicketStatus($ticket_id, $new_status)
    // {
    //     $db = \Config\Database::connect();
    //     $session = session();
    //     $admin_id = $session->get('admin_id');
    
    //     if (!$admin_id) {
    //         return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
    //     }
    
    //     // Validate status input
    //     $valid_statuses = ['Open', 'In Progress', 'Resolved'];
    //     if (!in_array($new_status, $valid_statuses)) {
    //         return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid status']);
    //     }
    
    //     // Get current status
    //     $ticket = $db->table('tickets')->where('id', $ticket_id)->get()->getRow();
    //     if (!$ticket) {
    //         return $this->response->setJSON(['status' => 'error', 'message' => 'Ticket not found']);
    //     }
    
    //     $old_status = $ticket->status;
    
    //     // Update status in tickets table
    //     $db->table('tickets')->where('id', $ticket_id)->update(['status' => $new_status]);
    
    //     // Log status change in ticket_status_log
    //     $db->table('ticket_status_log')->insert([
    //         'ticket_id'  => $ticket_id,
    //         'admin_id'   => $admin_id,
    //         'old_status' => $old_status,
    //         'new_status' => $new_status,
    //         'changed_at' => date('Y-m-d H:i:s') // Ensure timestamp is recorded
    //     ]);
    
    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'message' => 'Status updated successfully',
    //         'new_status' => $new_status
    //     ]);
    // }
    
    public function adminLogin()
    {
        $db = \Config\Database::connect();
        $session = session();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $admin = $db->table('admins')->where('email', $email)->get()->getRow();

        if ($admin && password_verify($password, $admin->password)) {
            $session->set([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'category' => $admin->category,
                'role' => $admin->role,
                'is_logged_in' => true
            ]);

            // ✅ Log admin login activity
            $db->table('admin_logs')->insert([
                'admin_id' => $admin->id,
                'action' => 'Login',
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()
            ]);

            return redirect()->to('/admin/dashboard')->with('success', 'Login successful!');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }
    public function adminLogout()
    {
        $db = \Config\Database::connect();
        $session = session();
        $admin_id = $session->get('admin_id');

        if ($admin_id) {
            // ✅ Log admin logout activity
            $db->table('admin_logs')->insert([
                'admin_id' => $admin_id,
                'action' => 'Logout',
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()
            ]);

            $session->destroy();
        }

        return redirect()->to('/admin/login');
    }

    public function getLogs()
    {
        $db = \Config\Database::connect();

        // Fetch admin logs (Login/Logout)
        $admin_logs = $db->table('admin_logs')
            ->select('admin_logs.*, admins.name AS admin_name')
            ->join('admins', 'admin_logs.admin_id = admins.id', 'left')
            ->orderBy('admin_logs.timestamp', 'DESC')
            ->get()
            ->getResultArray();

        // Fetch ticket logs (Status Changes)
        $ticket_logs = $db->table('ticket_logs')
            ->select('ticket_logs.*, admins.name AS admin_name')
            ->join('admins', 'ticket_logs.admin_id = admins.id', 'left')
            ->orderBy('ticket_logs.changed_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'admin_logs' => $admin_logs,
            'ticket_logs' => $ticket_logs
        ]);
    }
    public function get_ticket_logs()
{
    $db = \Config\Database::connect();

    $ticket_logs = $db->table('ticket_logs')
        ->select('ticket_logs.*, admins.name AS admin_name, tickets.title AS ticket_title')
        ->join('admins', 'ticket_logs.admin_id = admins.id', 'left')
        ->join('tickets', 'ticket_logs.ticket_id = tickets.id', 'left')
        ->orderBy('ticket_logs.changed_at', 'DESC')
        ->get()
        ->getResultArray();

    return $this->response->setJSON(['ticket_logs' => $ticket_logs]);
}

}


