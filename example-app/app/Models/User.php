<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
use HasFactory, Notifiable;

protected $fillable = [
'name',
'email',
'password',
'user_group',
];

protected $hidden = [
'password',
'remember_token',
];

protected $casts = [
'email_verified_at' => 'datetime',
];

public function register($data)
{
$this->validateRegistrationData($data);

$data['password'] = bcrypt($data['password']);
$data['user_group'] = 1;

return $this->create($data);
}

public function login($email, $password)
{
$user = $this->where('email', $email)->first();

if ($user && password_verify($password, $user->password)) {
return $user;
}

return null;
}

    public function assignUserGroup($assignedGroup)
    {
        $this->update(['user_group' => $assignedGroup]);
    }


public function validateRegistrationData($data)
{
if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password'])) {
throw new \Exception("Registration failed: All fields are required");
}

if ($data['password'] !== $data['confirm_password']) {
throw new \Exception("Registration failed: Passwords do not match");
}

}
}
