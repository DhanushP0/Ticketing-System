<?php
namespace App\Controllers;
use App\Models\AdminModel;
use App\Models\TicketModel;
use CodeIgniter\Controller;
use App\Models\TicketStatus;

class SuperadminController extends Controller
{
    protected $ticketStatusModel;
    protected $ticketModel;
    protected $adminModel;

    public function __construct()
    {
        helper(['url', 'form']);
        session(); // Start session
        $this->ticketStatusModel = new TicketStatus();
        $this->ticketModel = new TicketModel();
        $this->adminModel = new AdminModel();
    }
    public function allView()
    {
        $session = session();

        // 🚫 Redirect logged-in superadmins to the superadmin dashboard
        if ($session->get('superadmin_id')) {
            return redirect()->to(base_url('superadmin/dashboard'));
        }

        return view('all_view'); // ✅ Load only for non-logged-in users
    }

    public function login()
    {
        return view('superadmin/login');
    }

    public function authenticate()
    {
        $session = session();
        $adminModel = new AdminModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $superadmin = $adminModel->where('email', $email)->where('role', 'superadmin')->first();

        if ($superadmin && password_verify($password, $superadmin['password'])) {
            $session->set([
                'superadmin_id' => $superadmin['id'],
                'superadmin_name' => $superadmin['name'],
                'superadmin_email' => $superadmin['email'],
                'role' => 'superadmin',
                'is_logged_in' => true
            ]);

            return redirect()->to(base_url('superadmin/dashboard'))->with('success', 'Login successful!');
        } else {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Invalid credentials!');
        }
    }
    public function index()
    {
        $session = session();

        // Ensure only superadmins can access this page
        if ($session->get('role') !== 'superadmin') {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Unauthorized Access');
        }

        $ticketModel = new TicketModel();
        $adminModel = new AdminModel();

        // Fetch all tickets
        $tickets = $ticketModel->findAll();

        // Get admin names for assigned tickets
        foreach ($tickets as &$ticket) {
            if (!empty($ticket['assigned_admin_id']) && $ticket['assigned_admin_id'] != null) {
                $admin = $adminModel->find($ticket['assigned_admin_id']);
                $ticket['assigned_admin_name'] = $admin ? $admin['name'] : 'Unassigned';
            } else {
                $ticket['assigned_admin_name'] = 'Unassigned';
            }
        }

        return view('superadmin/superadmin_dashboard', ['tickets' => $tickets]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('superadmin/login'))->with('success', 'Logged out successfully!');
    }
    public function dashboard()
    {
        $session = session();

        // Ensure only superadmins can access
        if ($session->get('role') !== 'superadmin') {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Unauthorized Access');
        }

        $ticketModel = new TicketModel();
        $adminModel = new AdminModel();

        // Fetch ticket stats
        $totalTickets = $ticketModel->countAllResults();
        $admins = $adminModel->findAll();
        $openTickets = $ticketModel->where('status', 'Open')->countAllResults();
        $resolvedTickets = $ticketModel->where('status', 'Resolved')->countAllResults();
        $inProgressTickets = $ticketModel->where('status', 'In Progress')->countAllResults();
        $closedTickets = $ticketModel->where('status', 'Closed')->countAllResults();

        // Urgency Overview
        $lowUrgency = $ticketModel->where('urgency', 'Low')->countAllResults();
        $mediumUrgency = $ticketModel->where('urgency', 'Medium')->countAllResults();
        $highUrgency = $ticketModel->where('urgency', 'High')->countAllResults();

        // Fetch category-wise ticket distribution
        $ticketsByCategory = $ticketModel
            ->select('category, COUNT(*) as count')
            ->groupBy('category')
            ->findAll();

        $ticketsByCategoryArray = [];
        foreach ($ticketsByCategory as $row) {
            $ticketsByCategoryArray[$row['category']] = $row['count'];
        }

        // Fetch average response time safely
        $query = $ticketModel->selectAvg('response_time')->get();
        $row = ($query && $query->getNumRows() > 0) ? $query->getRow() : null;
        $avgResponseTime = $row ? round($row->response_time, 2) : 0;

        // Fetch total admins
        $totalAdmins = $adminModel->countAllResults();

        // Fetch recent tickets (limit to 5)
        $recentTickets = $ticketModel->orderBy('created_at', 'DESC')->limit(5)->find();

        // Prepare data for view
        $data = [
            'totalTickets' => $totalTickets,
            'resolvedTickets' => $resolvedTickets,
            'openTickets' => $openTickets,
            'inProgressTickets' => $inProgressTickets,
            'closedTickets' => $closedTickets,
            'lowUrgency' => $lowUrgency,
            'mediumUrgency' => $mediumUrgency,
            'highUrgency' => $highUrgency,
            'avgResponseTime' => $avgResponseTime,
            'totalAdmins' => $totalAdmins,
            'admins' => $admins,
            'ticketsByCategory' => $ticketsByCategoryArray,
            'recentTickets' => $recentTickets
        ];

        return view('superadmin/superadmin_dashboard', $data);
    }

    public function ticket()
    {
        $session = session();

        // Ensure only superadmins can access
        if (!$session->get('is_logged_in') || $session->get('role') !== 'superadmin') {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Unauthorized Access');
        }

        $ticketModel = new TicketModel();
        $adminModel = new AdminModel();

        // Fetch all tickets
        $tickets = $ticketModel->findAll();

        // Fetch all admins
        $admins = $adminModel->where('role', 'admin')->findAll();

        // Attach assigned admin names to tickets
        foreach ($tickets as &$ticket) {
            if (!empty($ticket['assigned_admin_id'])) {
                $admin = $adminModel->find($ticket['assigned_admin_id']);
                $ticket['assigned_admin_name'] = $admin ? $admin['name'] : 'Unassigned';
            } else {
                $ticket['assigned_admin_name'] = 'Unassigned';
            }
        }

        // Pass both tickets and admins to the view
        return view('superadmin/ticket', ['tickets' => $tickets, 'admins' => $admins]);
    }

    public function profile()
    {
        $session = session();
        $superadminId = $session->get('superadmin_id');

        if (!$superadminId) {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Please log in first.');
        }

        $adminModel = new AdminModel();
        $superadmin = $adminModel->find($superadminId);

        return view('superadmin/profile', ['superadmin' => $superadmin]);
    }

    public function updateProfile()
    {
        $session = session();
        $superadminId = $session->get('superadmin_id');

        if (!$superadminId) {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Please log in first.');
        }

        $adminModel = new AdminModel();
        $superadmin = $adminModel->find($superadminId);

        if ($this->request->getMethod() === 'post') {
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');

            if (empty($name) || empty($email)) {
                return redirect()->to(base_url('superadmin/profile'))->with('error', 'Name and Email cannot be empty.');
            }

            $data = [
                'name' => $name,
                'email' => $email,
            ];

            // Handle profile picture upload
            $image = $this->request->getFile('profile_picture');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move('uploads/profile_pictures', $newName);
                $data['profile_picture'] = 'uploads/profile_pictures/' . $newName;
                $session->set('profile_picture', base_url($data['profile_picture']));
            }

            if ($adminModel->update($superadminId, $data)) {
                $session->set($data);
                return redirect()->to(base_url('superadmin/profile'))->with('success', 'Profile updated successfully!');
            } else {
                return redirect()->to(base_url('superadmin/profile'))->with('error', 'Failed to update profile!');
            }
        }

        return redirect()->to(base_url('superadmin/profile'))->with('error', 'Invalid request.');
    }

    public function listAdmins()
    {
        $session = session();

        // Ensure only superadmins can access
        if (!$session->get('is_logged_in') || $session->get('role') !== 'superadmin') {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Unauthorized Access');
        }

        $adminModel = new \App\Models\AdminModel(); // Load the Admin model
        $admins = $adminModel->findAll(); // Fetch all admins from the database

        return view('superadmin/manageAdmins', [
            'admins' => $admins,
            'session' => $session // Pass session to the view
        ]);
    }

    public function addAdmin()
    {
        $session = session();

        // Ensure only superadmins can access
        if (!$session->get('is_logged_in') || $session->get('role') !== 'superadmin') {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Unauthorized Access');
        }

        $adminModel = new \App\Models\AdminModel();

        // Validate input
        $this->validate([
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[admins.email]',
            'category' => 'required',
            'password' => 'required|min_length[6]',
        ]);

        // Get form data
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'category' => $this->request->getPost('category'),
            'role' => 'admin', // Default role
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash password
        ];

        // Insert into database
        if ($adminModel->insert($data)) {
            return redirect()->to(base_url('superadmin/manageAdmins'))->with('success', 'Admin added successfully');
        } else {
            return redirect()->to(base_url('superadmin/manageAdmins'))->with('error', 'Failed to add admin');
        }
    }
    public function editAdmin()
    {
        $session = session();
        $adminModel = new \App\Models\AdminModel();

        // Ensure only superadmins can access
        if (!$session->get('is_logged_in') || $session->get('role') !== 'superadmin') {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Unauthorized Access');
        }

        $id = $this->request->getPost('id'); // Get admin ID from form
        $admin = $adminModel->find($id);

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found');
        }

        // Validate input
        $validationRules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'category' => 'required',
            'role' => 'required|in_list[admin,superadmin]', // Validate role
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'category' => $this->request->getPost('category'),
            'role' => $this->request->getPost('role'),
        ];

        // If password is provided, update it
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Update admin details
        if ($adminModel->update($id, $data)) {
            return redirect()->back()->with('success', 'Admin updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update admin');
        }
    }
    public function deleteAdmin()
    {
        $adminModel = new \App\Models\AdminModel();
        $id = $this->request->getPost('id'); // Get the admin ID from POST data
    
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid Admin ID.');
        }
    
        $admin = $adminModel->find($id);
        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found.');
        }
        if ($admin['role'] === 'superadmin') {
            return redirect()->back()->with('error', 'Cannot delete Superadmin.');
        }
    
        if ($adminModel->delete($id)) {
            return redirect()->back()->with('success', 'Admin deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete admin.');
        }
    }    
    public function assignAdmin()
    {
        $ticketModel = new TicketModel();
        $adminModel = new AdminModel();
        $ticketStatusModel = new TicketStatus(); // Ticket Status Log Model
        $request = $this->request;
    
        // Get data from POST request
        $ticketId = $request->getPost('ticket_id');
        $newAdminId = $request->getPost('admin_id');
    
        log_message('debug', 'Received Ticket ID: ' . json_encode($ticketId));
        log_message('debug', 'Received Admin ID: ' . json_encode($newAdminId));
    
        // Validate input
        if (!$ticketId || !$newAdminId) {
            log_message('error', 'Invalid input: Ticket ID or Admin ID is missing');
            return redirect()->back()->with('error', 'Invalid input.');
        }
    
        // Get ticket and admin details
        $ticket = $ticketModel->find($ticketId);
        $newAdmin = $adminModel->find($newAdminId);
    
        log_message('debug', 'Ticket Data: ' . json_encode($ticket));
        log_message('debug', 'New Admin Data: ' . json_encode($newAdmin));
    
        if (!$ticket) {
            log_message('error', 'Ticket not found in the database.');
            return redirect()->back()->with('error', 'Ticket not found.');
        }
    
        if (!$newAdmin) {
            log_message('error', 'Admin not found in the database.');
            return redirect()->back()->with('error', 'Admin not found.');
        }
    
        log_message('debug', 'Ticket Category: ' . json_encode($ticket['category']));
        log_message('debug', 'Admin Category: ' . json_encode($newAdmin['category']));
    
        // Fix category comparison
        if (trim((string) $newAdmin['category']) !== trim((string) $ticket['category'])) {
            log_message('error', 'Category mismatch: Ticket Category (' . $ticket['category'] . ') does not match Admin Category (' . $newAdmin['category'] . ')');
            return redirect()->back()->with('error', 'Invalid Admin Selection.');
        }
    
        log_message('debug', 'Admin successfully validated, proceeding to assignment.');
    
        // Get the old status before updating
        $oldStatus = $ticket['status'];
    
        // Update assigned admin
        if (!$ticketModel->update($ticketId, ['assigned_admin_id' => $newAdminId])) {
            log_message('error', 'Failed to assign admin in database.');
            return redirect()->back()->with('error', 'Failed to assign admin.');
        }
    
        log_message('debug', 'Admin assigned successfully.');
    
        $adminName = session()->get('superadmin_name'); // Use the correct key

        if (!$adminName) {
            log_message('error', 'Session superadmin_name is missing.');
            return redirect()->back()->with('error', 'Session expired. Please log in again.');
        }        
        
        log_message('debug', 'Session Admin Name: ' . json_encode($adminName));
        
        $adminData = $adminModel->where('LOWER(name)', strtolower($adminName))->first();
        
        if (!$adminData) {
            log_message('error', 'No matching admin found in the database for name: ' . json_encode($adminName));
            return redirect()->back()->with('error', 'Admin not found in system.');
        }
        
        $adminId = $adminData['id'];  // Correct admin ID
        
    
        // Fetch admin ID by name
        $adminData = $adminModel->where('name', $adminName)->first();
        if (!$adminData) {
            log_message('error', 'Logged-in Admin not found in database.');
            return redirect()->back()->with('error', 'Admin not found.');
        }
    
        $adminId = $adminData['id']; // Correctly fetched admin ID
        log_message('debug', 'Logged-in Admin ID: ' . json_encode($adminId));
    
        // Insert into `ticket_status_log`
        $statusLogData = [
            'ticket_id' => $ticketId,
            'admin_id' => $adminId,  // Admin ID fetched by name
            'old_status' => $oldStatus,
            'new_status' => $ticket['status'], // Ensure status is updated
            'changed_at' => date('Y-m-d H:i:s')
        ];
    
        if (!$ticketStatusModel->insert($statusLogData)) {
            log_message('error', 'Failed to log status change.');
            return redirect()->back()->with('error', 'Failed to log status change.');
        }
    
        log_message('debug', 'Status log inserted successfully.');
    
        return redirect()->back()->with('success', 'Admin assigned successfully.');
    }    
    public function getAdminActivityLog()
    {
        $db = \Config\Database::connect();

        $adminActivities = $db->table('admin_log')
            ->select('admin_log.*, admins.name AS admin_name')
            ->join('admins', 'admin_log.admin_id = admins.id')
            ->orderBy('admin_log.timestamp', 'DESC')
            ->limit(20) // Fetch the latest 20 logs
            ->get()
            ->getResultArray();

        return $this->response->setJSON($adminActivities);
    }
    public function getTicketHistory($ticketId)
    {
        $ticketModel = new TicketStatus();
        $history = $ticketModel->getTicketHistory($ticketId);

        if (!empty($history)) {
            return $this->response->setJSON($history);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'No history found']);
        }
    }
    public function getTicketStats()
    {
        $ticketModel = new TicketModel();

        // Fetch ticket stats
        $totalTickets = $ticketModel->countAllResults();
        $openTickets = $ticketModel->where('status', 'Open')->countAllResults();
        $resolvedTickets = $ticketModel->where('status', 'Resolved')->countAllResults();
        $inProgressTickets = $ticketModel->where('status', 'In Progress')->countAllResults();
        $closedTickets = $ticketModel->where('status', 'Closed')->countAllResults();

        // Urgency Overview
        $lowUrgency = $ticketModel->where('urgency', 'Low')->countAllResults();
        $mediumUrgency = $ticketModel->where('urgency', 'Medium')->countAllResults();
        $highUrgency = $ticketModel->where('urgency', 'High')->countAllResults();

        // Fetch category-wise ticket distribution
        $ticketsByCategory = $ticketModel
            ->select('category, COUNT(*) as count')
            ->groupBy('category')
            ->findAll();

        $ticketsByCategoryArray = [];
        foreach ($ticketsByCategory as $row) {
            $ticketsByCategoryArray[] = [
                'category' => $row['category'],
                'count' => (int) $row['count']
            ];
        }

        // Fetch average response time safely
        $query = $ticketModel->selectAvg('response_time')->get();
        $row = ($query && $query->getNumRows() > 0) ? $query->getRow() : null;
        $avgResponseTime = $row ? round($row->response_time, 2) : 0;

        // ✅ Fetch New & Resolved Tickets for the Last 7 Days
        $dates = [];
        $newTicketsData = [];
        $resolvedTicketsData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dates[] = $date;

            // ✅ Correct date filtering for ticket creation
            $newTickets = $ticketModel
                ->where("DATE(created_at)", $date)
                ->countAllResults();

            // ✅ Corrected query to count resolved tickets per day
            $resolvedTickets = $ticketModel
                ->where("DATE(updated_at)", $date)
                ->where('status', 'Resolved')
                ->countAllResults(); // ✅ This now returns an integer count

            $newTicketsData[] = $newTickets;
            $resolvedTicketsData[] = $resolvedTickets; // ✅ Store the count properly
        }

        // Prepare JSON response
        $stats = [
            'total_tickets' => $totalTickets,
            'open_tickets' => $openTickets,
            'in_progress_tickets' => $inProgressTickets,
            'resolved_tickets' => $resolvedTickets,
            'closed_tickets' => $closedTickets,
            'low_urgency' => $lowUrgency,
            'medium_urgency' => $mediumUrgency,
            'high_urgency' => $highUrgency,
            'tickets_by_category' => $ticketsByCategoryArray,
            'avg_response_time' => $avgResponseTime,
            'dates' => $dates,
            'new_tickets' => $newTicketsData,
            'resolved_tickets_per_day' => $resolvedTicketsData
        ];

        return $this->response->setJSON($stats);
    }


}


