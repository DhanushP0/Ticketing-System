<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function logout()
    {
        // Destroy session
        session()->destroy();
        {
            // Forcefully delete the session cookie
            helper('cookie');
            delete_cookie('ci_session');
        
            // Redirect to home (http://localhost:8080/)
            return redirect()->to(base_url('/'))->with('success', 'You have been logged out.');
        }
            }
            public function login()
            {
                $session = session();
                $db = \Config\Database::connect();
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
        
                $query = $db->query("SELECT * FROM admins WHERE email = ?", [$email]);
                $admin = $query->getRow();
        
                if ($admin && password_verify($password, $admin->password)) {
                    $session->set([
                        'admin_id' => $admin->id,
                        'admin_email' => $admin->email,
                        'admin_category' => $admin->category, // Store admin's assigned category
                        'isLoggedIn' => true
                    ]);
                    return redirect()->to('/admin/dashboard');
                } else {
                    $session->setFlashdata('error', 'Invalid login credentials');
                    return redirect()->to('/login');
                }
            }


}
