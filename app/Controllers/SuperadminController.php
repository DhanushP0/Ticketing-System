<?php
namespace App\Controllers;
use App\Models\AdminModel;
use App\Models\TicketModel;
use CodeIgniter\Controller;
use App\Models\TicketStatus;

class SuperadminController extends Controller
{
    protected $ticketStatusModel;

    public function __construct()
    {
        helper(['url', 'form']);
        session(); // Start session
        $this->ticketStatusModel = new TicketStatus();
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

    public function superadminDashboard()
    {
        $session = session();

        // Ensure only superadmins can access this page
        if (!$session->get('is_logged_in') || $session->get('role') !== 'superadmin') {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Unauthorized Access');
        }

        $adminModel = new AdminModel();
        $ticketModel = new TicketModel();

        // Fetch superadmin details
        $superadmin = $adminModel->find($session->get('superadmin_id'));

        // Get total tickets count
        $totalTickets = $ticketModel->countAll();

        return view('superadmin/superadmin_dashboard', [
            'superadmin' => $superadmin,
            'totalTickets' => $totalTickets
        ]);
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
    public function assignAdmin()
    {
        $ticketModel = new TicketModel();
        $adminModel = new AdminModel();
        $ticketStatusModel = new TicketStatus(); // Ticket Status Log Model
        $request = $this->request;

        // Get data from POST request
        $ticketId = $request->getPost('ticket_id');
        $newAdminId = $request->getPost('admin_id');

        // Validate input
        if (!$ticketId || !$newAdminId) {
            return redirect()->back()->with('error', 'Invalid input.');
        }

        // Get ticket and admin details
        $ticket = $ticketModel->find($ticketId);
        $newAdmin = $adminModel->find($newAdminId);

        if (!$ticket || !$newAdmin || $newAdmin['category'] !== $ticket['category']) {
            return redirect()->back()->with('error', 'Invalid Admin Selection.');
        }

        // Get the old status before updating
        $oldStatus = $ticket['status'];

        // Update assigned admin
        if (!$ticketModel->update($ticketId, ['assigned_admin_id' => $newAdminId])) {
            return redirect()->back()->with('error', 'Failed to assign admin.');
        }

        // Get logged-in admin's name from session
        $adminName = session()->get('admin_name');

        // Fetch admin ID by name
        $adminData = $adminModel->where('name', $adminName)->first();
        if (!$adminData) {
            return redirect()->back()->with('error', 'Admin not found.');
        }

        $adminId = $adminData['id']; // Correctly fetched admin ID

        // Insert into `ticket_status_log`
        $statusLogData = [
            'ticket_id' => $ticketId,
            'admin_id' => $adminId,  // Admin ID fetched by name
            'old_status' => $oldStatus,
            'new_status' => $ticket['status'], // Ensure status is updated
            'changed_at' => date('Y-m-d H:i:s')
        ];

        if (!$ticketStatusModel->insert($statusLogData)) {
            return redirect()->back()->with('error', 'Failed to log status change.');
        }

        return redirect()->back()->with('success', 'Admin assigned successfully.');
    }
    public function dashboard()
    {
        $ticketModel = new TicketModel();
        $userModel = new UserModel();
        $adminModel = new AdminModel();
        $activityModel = new ActivityModel();

        // Fetch data from database
        $data['tickets'] = $ticketModel->findAll(); // Get all tickets
        $data['totalUsers'] = $userModel->countAll(); // Get total users
        $data['activeAdmins'] = $adminModel->where('role', 'admin')->countAllResults(); // Count active admins

        // Count resolved tickets
        $data['resolvedTickets'] = $ticketModel->where('status', 'Resolved')->countAllResults();

        // Ticket Growth Example (Last 7 days)
        $lastWeekTickets = $ticketModel->where('created_at >=', date('Y-m-d', strtotime('-7 days')))->countAllResults();
        $data['ticketGrowth'] = $lastWeekTickets > 0 ? round(($lastWeekTickets / max(count($data['tickets']), 1)) * 100) : 0;

        // User Growth Example
        $lastWeekUsers = $userModel->where('created_at >=', date('Y-m-d', strtotime('-7 days')))->countAllResults();
        $data['userGrowth'] = $lastWeekUsers > 0 ? round(($lastWeekUsers / max($data['totalUsers'], 1)) * 100) : 0;

        // Resolution Rate Example
        $data['resolutionRate'] = count($data['tickets']) > 0 ? round(($data['resolvedTickets'] / count($data['tickets'])) * 100) : 0;

        // Fetch Admin Activity Logs
        $data['adminActivities'] = $activityModel->orderBy('timestamp', 'DESC')->findAll(5); // Get last 5 admin activities

        return view('superadmin/dashboard', $data);
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
}


