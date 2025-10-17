<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the Portal',
                'content' => 'This is your online student portal. Stay updated!',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Midterm Exams Schedule',
                'content' => 'Midterm exams will begin next week.',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('announcements')->insertBatch($data);
    }
}
