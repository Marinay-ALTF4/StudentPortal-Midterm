<?php
namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    public function index()
    {
        $model = new AnnouncementModel();

        // Fetch announcements (newest first)
        $data['announcements'] = $model->orderBy('created_at', 'DESC')->findAll();

        // Also pass role/session info if needed
        $data['role'] = session()->get('role');
        $data['name'] = session()->get('name');
        $data['email'] = session()->get('email');

        return view('dashboard', $data); // <-- your big dashboard file name
    }
}
