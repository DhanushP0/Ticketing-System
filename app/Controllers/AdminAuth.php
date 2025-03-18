<?php

namespace App\Controllers;
use App\Models\AdminModel;
use CodeIgniter\Controller;

class AdminAuth extends Controller
{
    public function login()
    {
        $adminModel = new \App\Models\AdminModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
        $admin = $adminModel->where('email', $email)->first();
    
        if ($admin && password_verify($password, $admin['password'])) {
            session()->set([
                'admin_id' => $admin['id'],
                'admin_name' => $admin['name'],
                'admin_email' => $admin['email'],
                'category' => $admin['category'],
                'role' => $admin['role'],
                'is_logged_in' => true,
                'profile_picture' => !empty($admin['profile_picture']) ? base_url($admin['profile_picture']) : base_url('uploads/default.png')
            ]);
    
            return redirect()->to('admin/profile');
        } else {
            return redirect()->to('admin/login')->with('error', 'Invalid email or password.');
        }
    }
    

    
    public function authenticate()
    {
        $adminModel = new AdminModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $admin = $adminModel->where('email', $email)->first();

        if ($admin && password_verify($password, $admin['password'])) {
            session()->set('admin_id', $admin['id']);
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/admin/login')->with('error', 'Invalid credentials');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('');
    }
}
