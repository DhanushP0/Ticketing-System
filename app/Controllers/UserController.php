<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TicketModel;
use CodeIgniter\Log\Logger;
use App\Models\AdminModel;
use CodeIgniter\Controller;
use Config\Database;
use CodeIgniter\Exceptions\PageNotFoundException;
use App\Models\MessageModel;


class UserController extends Controller
{
    protected $db;

    public function __construct()
    {
        // Load the database
        $this->db = Database::connect();
    }
//     public function allView()
// {
//     $session = session();

//     // 🚫 Redirect logged-in users to the user dashboard
//     if ($session->get('user_id')) {
//         return redirect()->to(base_url('user/dashboard'));
//     }

//     return view('all_view'); // ✅ Load only for non-logged-in users
// }

    public function logout()
    {
        $session = session();
        $session->destroy();
        helper('cookie');
        delete_cookie('ci_session');
        return redirect()->to(base_url('user/login'))->with('success', 'Logged out successfully!');
    }

    public function login()
    {
        return view('user/login');  // Make sure this matches your view path
    }

    public function loginPost()
    {
        $session = session();
        $userModel = new UserModel();
        $ticketModel = new TicketModel();

        $email = trim($this->request->getPost('email'));
        $ticket_id = trim($this->request->getPost('ticket_id'));

        if (empty($email) || empty($ticket_id)) {
            return redirect()->back()->with('error', 'Invalid Email or Ticket ID');
        }

        // Check if user exists
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Check if the ticket exists for the user
        $ticket = $ticketModel->where('user_id', $user['id'])->where('ticket_id', $ticket_id)->first();

        if (!$ticket) {
            return redirect()->back()->with('error', 'Invalid Ticket ID.');
        }

        // ✅ Set session
        $session->set([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'ticket_id' => $ticket['ticket_id'],
            'isLoggedIn' => true,
            'role' => 'user'
        ]);

        // ✅ Redirect to ticket status WITH the ticket_id
        return redirect()->to(site_url('user/ticket_status/' . $ticket['ticket_id']));
    }

    public function ticketStatus($ticketId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'user') {
            return redirect()->to('user/login');
        }

        // Redirect to session ticket_id if no ID is provided
        if (!$ticketId) {
            $ticketId = session()->get('ticket_id');
            if (!$ticketId) {
                return redirect()->to('user/dashboard')->with('error', 'No ticket ID found!');
            }
            return redirect()->to('user/ticket_status/' . $ticketId);
        }

        $ticketModel = new TicketModel();
        $ticket = $ticketModel
            ->select('tickets.*, admins.name as assigned_admin')
            ->join('admins', 'tickets.assigned_admin_id = admins.id', 'left')
            ->where('tickets.ticket_id', $ticketId)
            ->first();

        if (!$ticket) {
            return redirect()->to('user/dashboard')->with('error', 'Ticket not found!');
        }

        return view('user/ticket_status', ['ticket' => $ticket]);
    }


    public function authenticate()
    {
        $session = session();
        $email = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'], // Assuming role is stored in the database
                'is_logged_in' => true
            ]);

            return redirect()->to(base_url('user/dashboard'));
        } else {
            return redirect()->to(base_url('user/login'))->with('error', 'Invalid email or password.');
        }
    }

    public function submit_ticket()
    {
        return view('user/submit_ticket');
    }

    public function submitticket()
    {
        $ticketModel = new TicketModel();
        $adminModel = new AdminModel();
        $userModel = new UserModel();

        $email = trim($this->request->getPost('email'));
        $title = trim($this->request->getPost('title'));
        $description = trim($this->request->getPost('description'));
        $urgency = trim($this->request->getPost('urgency'));
        $category = trim($this->request->getPost('category'));
        $attachments = $this->request->getFiles();

        // Validate Required Fields
        if (empty($email) || empty($title) || empty($description) || empty($urgency) || empty($category)) {
            return redirect()->back()->with('error', 'Enter all fields.');
        }

        // Check if user exists, else create a new user
        $user = $userModel->where('email', $email)->first();
        if (!$user) {
            $userModel->insert(['email' => $email]);
            $userId = $userModel->insertID();
        } else {
            $userId = $user['id'];
        }

        // Generate a Unique Ticket ID
        do {
            $ticketId = 'TICKET-' . strtoupper(bin2hex(random_bytes(4)));
        } while ($ticketModel->where('ticket_id', $ticketId)->first());

        // Find an available admin
        $availableAdmin = $adminModel->select('id')
            ->where('category', $category)
            ->whereNotIn('id', function ($builder) use ($category) {
                return $builder->select('assigned_admin_id')->from('tickets')->where('category', $category)->where('status', 'Open');
            })
            ->orderBy('id', 'ASC')
            ->first();

        $assignedAdminId = $availableAdmin ? $availableAdmin['id'] : null;

        // Insert Ticket Data
// Insert Ticket Data (Including Email)
        $ticketModel->insert([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'email' => $email,  // ✅ Ensure email is saved in the tickets table
            'title' => $title,
            'description' => $description,
            'urgency' => $urgency,
            'category' => $category,
            'assigned_admin_id' => $assignedAdminId,
            'status' => 'Open'
        ]);


        $newTicketId = $ticketModel->insertID();

        // Handle File Uploads
        $savedFiles = [];
        if ($attachments && isset($attachments['attachments'])) {
            foreach ($attachments['attachments'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move('uploads/tickets/', $newName);
                    $savedFiles[] = 'uploads/tickets/' . $newName;
                }
            }
        }

        // Update Attachments
        if (!empty($savedFiles)) {
            $ticketModel->update($newTicketId, ['attachments' => json_encode($savedFiles)]);
        }

        return redirect()->to(base_url('user/ticket_success/' . $ticketId))->with('success', 'Ticket submitted successfully! Your Ticket ID is: ' . $ticketId);
    }

    public function checkTicketStatus()
    {
        $email = trim($this->request->getPost('email'));
        $ticket_id = trim($this->request->getPost('ticket_id'));

        $ticketModel = new TicketModel();
        $ticket = $ticketModel->where('ticket_id', $ticket_id)
            ->where('user_id', function ($builder) use ($email) {
                return $builder->select('id')->from('users')->where('email', $email);
            })
            ->first();

        if ($ticket) {
            return redirect()->to(base_url("ticket_status/{$ticket_id}"));
        } else {
            return redirect()->to(base_url('user/login'))->with('error', 'Invalid Ticket ID or Email');
        }
    }

    public function ticketSuccess($ticketId)
    {
        return view('user/ticket_success', ['ticketId' => $ticketId]);
    }
    public function message($ticket_id, $admin_id)
    {
        $session = session();

        if (!$session->get('isLoggedIn') || $session->get('role') !== 'user') {
            return redirect()->to(site_url('user/login'));
        }

        $ticketModel = new TicketModel();
        $adminModel = new AdminModel();
        $messageModel = new MessageModel();

        $ticket = $ticketModel->where('ticket_id', $ticket_id)->first();
        $admin = $adminModel->where('id', $admin_id)->first();

        if (!$ticket || !$admin) {
            return view('errors/html/error_404'); // Show 404 page if invalid ticket/admin
        }

        // Fetch messages
        $messages = $messageModel->where('ticket_id', $ticket['id'])->orderBy('created_at', 'ASC')->findAll();

        return view('user/message', [
            'ticket' => $ticket,
            'admin' => $admin,
            'messages' => $messages
        ]);
    }

    public function sendMessage()
    {
        $ticketId = $this->request->getPost('ticket_id');
        $userId = session()->get('user_id'); // Get user_id from session
        $adminId = $this->request->getPost('admin_id');
        $message = $this->request->getPost('message');
    
        $attachment = $this->request->getFile('attachment');
        $attachmentName = null;
    
        if ($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
            $newName = $attachment->getRandomName();
            $attachment->move('upload/messages', $newName);
            $attachmentName = 'upload/messages/' . $newName;
        }
    
        $data = [
            'ticket_id' => $ticketId,
            'user_id' => $userId, // Ensure user_id is stored
            'admin_id' => $adminId,
            'message' => $message,
            'attachment' => $attachmentName,
            'sender' => 'user',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    
        $messageModel = new MessageModel();
        if ($messageModel->insert($data)) {
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Message sending failed']);
        }
    }
    
    public function getMessages()
    {
        $ticketId = $this->request->getGet('ticket_id');
        $messages = $this->db->table('messages')
                             ->where('ticket_id', $ticketId)
                             ->orderBy('created_at', 'ASC')
                             ->get()
                             ->getResultArray();
    
        return $this->response->setJSON($messages);
    }
    
}

