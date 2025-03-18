<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('url'); // Ensure URL helper is loaded
        $session = session();

        // Check if logged in
        if (!$session->has('user_id')) { // Ensure user is logged in
            return redirect()->to(base_url('/'))->with('error', 'You must log in first.');
        }

        // Get user role from session
        $role = $session->get('role'); // Assuming role is stored in session

        // Ensure $arguments is an array before using in_array()
        if (!is_array($arguments)) {
            $arguments = []; // Default to an empty array to prevent errors
        }

        // Role-based access control
        if (in_array('admin', $arguments) && !in_array($role, ['admin', 'superadmin'])) {
            return redirect()->to(base_url('/'))->with('error', 'Admins only.');
        }

        if (in_array('superadmin', $arguments) && $role !== 'superadmin') {
            return redirect()->to(base_url('/'))->with('error', 'Superadmins only.');
        }
         // Check if superadmin is logged in
         if (!session()->get('is_logged_in') || session()->get('role') !== 'superadmin') {
            return redirect()->to(base_url('superadmin/login'))->with('error', 'Please login first!');
        }
        
        }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
