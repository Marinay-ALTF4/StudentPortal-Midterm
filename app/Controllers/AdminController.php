<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AdminController extends BaseController
{
    public function registerUser()
{
    $data = [
        'fullname' => $this->request->getPost('fullname'),
        'email'    => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'role'     => $this->request->getPost('role')
    ];

    $userModel = new UserModel();

    //  Check if email or student ID already exists
    $existingUser = $userModel->where('email', $data['email'])->first();

    if ($existingUser) {
        // If found, return with error message
        return redirect()->back()->with('error', 'Email/Student ID already exists!');
    }

    // Save the user if not duplicate
    $userModel->save($data);

    return redirect()->back()->with('success', ucfirst($data['role']) . ' registered successfully!');
}
}
