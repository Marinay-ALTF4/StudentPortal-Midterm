<?php

namespace App\Controllers;

use App\Models\UserModel; 
use App\Models\EnrollmentModel;
use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                 'name'              => 'required|min_length[3]',
                 'email'             => 'required|valid_email|is_unique[users.email]',
                 'password'          => 'required|min_length[6]',
                 'password_confirm'  => 'matches[password]'
            ];

            if ($this->validate($rules)) {
                $userModel = new UserModel();
                $userModel->save([
                    'name'     => $this->request->getVar('name'),
                    'email'    => $this->request->getVar('email'),
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                    'role'     => 'student' // Default role
                ]);

                return redirect()->to('/login')->with('success', 'Registration Success. Proceed to login.');
            } else {
                return view('auth/register', ['validation' => $this->validator]);
            }
        }

        return view('auth/register');
    }

    public function login()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $session   = session();
            $userModel = new UserModel();

            $rules = [
                'email'    => 'required|valid_email',
                'password' => 'required|min_length[6]'
            ];

            if (!$this->validate($rules)) {
                return view('auth/login', ['validation' => $this->validator]);
            }

            $user = $userModel->where('email', $this->request->getVar('email'))->first();
            if ($user && password_verify($this->request->getVar('password'), $user['password'])) {
                $session->set([
                    'userID'    => $user['id'],
                    'name'      => $user['name'],
                    'email'     => $user['email'],
                    'role'      => $user['role'],
                    'isLoggedIn'=> true
                ]);
                $session->setFlashdata('success', 'Welcome ' . $user['name']);
                return redirect()->to('dashboard');
            }

            $session->setFlashdata('error', 'Invalid login credentials');
            return redirect()->back();
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }

    // ---------------- Dashboard with Announcements ----------------
    public function dashboard()
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('login_error', 'Please log in first.');
        }

        $role = strtolower((string) $session->get('role'));
        $userModel = new UserModel();
        $announcementModel = new AnnouncementModel();
        $data = [];

        // Fetch announcements ordered by newest first
        $announcements = $announcementModel->orderBy('created_at', 'DESC')->findAll();

        switch ($role) {
            case 'admin':
                $data['usersCount']   = $userModel->countAllResults();
                $data['recentUsers']  = $userModel->orderBy('id', 'DESC')->limit(5)->findAll();
                break;

            case 'teacher':
                $data['students']     = $userModel->where('role', 'student')->findAll();
                break;

            case 'student':
            default:
                $data['profile']      = $userModel->find((int) $session->get('userID'));
                break;
        }

        return view('auth/dashboard', [
            'role'          => $role,
            'data'          => $data,
            'announcements' => $announcements
        ]);
    }

    // ---------------- Post Announcement (Admin Only) ----------------
    public function postAnnouncement()
    {
        $session = session();
        $role = strtolower((string) $session->get('role'));

        if ($role !== 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Access denied.');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'title'    => 'required|min_length[3]',
                'content'  => 'required|min_length[5]',
                'audience' => 'required|in_list[student,teacher]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Please fill all fields correctly.');
            }

            $announcementModel = new AnnouncementModel();
            $announcementModel->save([
                'title'      => $this->request->getPost('title'),
                'content'    => $this->request->getPost('content'),
                'audience'   => $this->request->getPost('audience'),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to(base_url('dashboard'))->with('success', 'Announcement posted successfully!');
        }

        return redirect()->back();
    }

    // ---------------- Student Courses ----------------
    public function studentCourse()
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('login_error', 'Please log in first.');
        }

        $role = strtolower((string) $session->get('role'));

        if ($role !== 'student') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Access denied.');
        }

        $enrollmentModel = new EnrollmentModel();
        $enrolled = $enrollmentModel->getUserEnrollments((int) $session->get('userID'));
        $available = $enrollmentModel->getAvailableCoursesForUser((int) $session->get('userID'));

        $data = [
            'enrolledCourses'  => $enrolled,
            'availableCourses' => $available
        ];

        return view('auth/studentCourse', [
            'role' => $role,
            'data' => $data
        ]);
    }
}
