<?php
namespace App\Services;

use App\Models\User;

class UserService
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function register(array $data)
    {
        try {
            $user = $this->userModel->register($data);

            return ['success' => true, 'user' => $user];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    public function login($email, $password)
    {
        $user = $this->userModel->login($email, $password);

        if ($user) {
            return ['success' => true, 'user' => $user];
        } else {
            return ['success' => false, 'message' => 'No user found or password incorrect'];
        }
    }

    public function assignUserGroup($userId, $assignedGroup)
    {
        return $this->userModel->assignUserGroup($userId, $assignedGroup);
    }
}
