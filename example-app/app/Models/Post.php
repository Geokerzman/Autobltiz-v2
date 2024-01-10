<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'brand',
        'model',
        'description',
        'year',
        'image_path',
    ];

    // Отношение к пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Метод для получения общего числа всех постов
    public function getTotalPosts()
    {
        return $this->count();
    }

    // Метод для получения уникальных брендов
    public function getBrands()
    {
        return $this->distinct()->pluck('brand')->toArray();
    }

    // Метод для получения уникальных моделей
    public function getModels()
    {
        $models = $this->distinct()->pluck('model')->toArray();
        $modelArray = [];

        foreach ($models as $model) {
            $modelValues = explode(',', $model);
            $modelArray = array_merge($modelArray, $modelValues);
        }

        return array_unique($modelArray);
    }

    // Метод для получения уникальных лет
    public function getYears()
    {
        return $this->distinct()->pluck('year')->toArray();
    }

    // Метод для получения изображений по пользователю
    public function getImages($user_id)
    {
        return $this->where('user_id', $user_id)->pluck('image_path')->toArray();
    }

    // Метод для добавления нового поста
    public function addPost($data)
    {
        return $this->create($data);
    }

    // Метод для обновления поста
    public function updatePost($data)
    {
        return $this->where('id', $data['id'])->update(['title' => $data['title'], 'body' => $data['body']]);
    }

    // Метод для получения поста по идентификатору
    public function getPostById($id)
    {
        return $this->find($id);
    }

    // Метод для удаления поста
    public function deletePost($id)
    {
        return $this->where('id', $id)->delete();
    }
}
