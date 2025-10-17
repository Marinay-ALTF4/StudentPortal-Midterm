<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role');

        $uri = current_url(true)->getPath(); // Current URL path

        if (!$role) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        if ($role === 'admin' && str_starts_with($uri, 'admin')) {
            return; // allowed
        }

        if ($role === 'teacher' && str_starts_with($uri, 'teacher')) {
            return; // allowed
        }

        if ($role === 'student' && (str_starts_with($uri, 'student') || $uri === 'announcements')) {
            return; // allowed
        }

        // Unauthorized access
        return redirect()->to('/announcements')->with('error', 'Access Denied: Insufficient Permissions');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
