<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AnnouncementModel;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $annModel = new AnnouncementModel();

        $annModel->insert([
            'title' => 'Welcome to Student Portal',
            'content' => 'Portal is now live. Check for updates.',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $annModel->insert([
            'title' => 'System Maintenance',
            'content' => 'Portal will be down tonight 10 PM - 12 AM.',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
