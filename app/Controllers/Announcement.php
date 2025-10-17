<?php
namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    public function index()
    {
        $model = new AnnouncementModel();

        // Get logged-in user's role (student or teacher)
        $role = session()->get('role');

        // Fetch announcements only for this audience
        $data['announcements'] = $model
            ->where('audience', $role)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data['role']  = $role;
        $data['name']  = session()->get('name');
        $data['email'] = session()->get('email');

        return view('dashboard', $data);
    }
}
