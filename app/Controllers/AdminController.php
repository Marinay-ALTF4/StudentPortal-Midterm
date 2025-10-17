<?php
namespace App\Controllers;

use App\Models\AnnouncementModel;

class AdminController extends BaseController
{
    public function postAnnouncement()
    {
        $announcementModel = new AnnouncementModel();

        $data = [
            'title'      => $this->request->getPost('title'),
            'content'    => $this->request->getPost('content'),
            'audience'   => $this->request->getPost('audience'), // student or teacher
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Validate audience
        $validAudiences = ['student', 'teacher'];
        if (!in_array($data['audience'], $validAudiences)) {
            return redirect()->back()->with('error', 'Invalid audience selected.');
        }

        if ($announcementModel->insert($data)) {
            return redirect()->back()->with('success', 'Announcement posted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to post announcement.');
        }
    }
}
