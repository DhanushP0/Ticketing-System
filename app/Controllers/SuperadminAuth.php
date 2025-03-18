<?php

namespace App\Controllers;
use App\Models\AdminModel;
use CodeIgniter\Controller;

class SuperadminAuth extends Controller
{
    public function login()
    {
        $session = session();
        $adminModel = new AdminModel();
    
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
        $superadmin = $adminModel->where('email', $email)->where('role', 'superadmin')->first();
    
        if ($superadmin && password_verify($password, $superadmin['password'])) {
            $session->set([
                'admin_id' => $superadmin['id'],
                'admin_role' => 'superadmin',
                'is_logged_in' => true
            ]);
            return redirect()->to(base_url('superadmin/dashboard'))->with('success', '✅ Superadmin login successful!');
        } else {
            return redirect()->to(base_url('superadmin/login'))->with('error', '❌ Invalid superadmin credentials.');
        }
    }
    
    public function authenticate()
    {
        $adminModel = new AdminModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $superadmin = $adminModel->where(['email' => $email, 'role' => 'superadmin'])->first();

        if ($superadmin && password_verify($password, $superadmin['password'])) {
            session()->set('superadmin_id', $superadmin['id']);
            return redirect()->to('/superadmin/dashboard');
        } else {
            return redirect()->to('/superadmin/login')->with('error', 'Invalid credentials');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
