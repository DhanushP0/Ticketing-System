<?php
namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\Controller;

class ProfileController extends Controller
{
  public function updateProfile()
{
    $session = session();
    $adminId = $session->get('admin_id'); // Get logged-in admin ID

    // Load validation
    $validation = \Config\Services::validation();
    $validation->setRules([
        'name'  => 'required',
        'email' => 'required|valid_email',
        'profile_picture' => 'is_image[profile_picture]|max_size[profile_picture,2048]|mime_in[profile_picture,image/png,image/jpeg,image/jpg]',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->back()->withInput()->with('error', $validation->getErrors());
    }

    $name = $this->request->getPost('name');
    $email = $this->request->getPost('email');

    $profilePicture = $this->request->getFile('profile_picture');
    $newFileName = null;

    if ($profilePicture->isValid() && !$profilePicture->hasMoved()) {
        $uploadPath = 'uploads/profile_pictures/';
        $newFileName = time() . '_' . uniqid() . '.' . $profilePicture->getClientExtension();
        $profilePicture->move(FCPATH . $uploadPath, $newFileName); // Move to public/uploads/

        // Update session
        $session->set('profile_picture', $newFileName);
    }

    // Update database
    $db = \Config\Database::connect();
    $builder = $db->table('admins');
    $updateData = [
        'name'  => $name,
        'email' => $email,
    ];

    if ($newFileName) {
        $updateData['profile_picture'] = $newFileName; // Store only filename
    }

    $builder->where('id', $adminId)->update($updateData);

    return redirect()->to(base_url('admin/profile'))->with('profile_updated', 'Profile updated successfully!');
}
public function serveImage($filename)
{
    $path = WRITEPATH . 'uploads/profile_pictures/' . $filename;

    if (file_exists($path)) {
        return $this->response->setHeader('Content-Type', mime_content_type($path))
                              ->setBody(file_get_contents($path));
    } else {
        return $this->response->setStatusCode(404)->setBody('Image not found');
    }
}


  
}
