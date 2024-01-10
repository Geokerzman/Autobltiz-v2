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

    // Регистрация пользователя
    public function register($data)
    {
        // Валидация данных
        if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
            // Хеширование пароля
            $data['password'] = bcrypt($data['password']);

            // Установка группы пользователя 1
            $data['user_group'] = 1;

            // Создание нового пользователя
            return self::create($data);
        } else {
            // Обработка ошибок при регистрации
            return false;
        }
    }

    // Аутентификация пользователя
    public function login($email, $password)
    {
        $user = self::where('email', $email)->first();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }

    // Назначение группы пользователю
    public function assignUserGroup($userId, $assignedGroup)
    {
        return self::where('id', $userId)->update(['user_group' => $assignedGroup]);
    }

    // Поиск пользователя по электронной почте
    public function findUserByEmail($email)
    {
        return (bool)self::where('email', $email)->first();
    }

    // Получение пользователя по идентификатору
    public function getUserById($id)
    {
        return self::select('id', 'name')->where('id', $id)->first();
    }
}
