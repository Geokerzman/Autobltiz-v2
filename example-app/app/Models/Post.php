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

public function user()
{
return $this->belongsTo(User::class);
}

public function getTotalPosts()
{
return $this->count();
}

public function getBrands()
{
return $this->distinct()->pluck('brand')->toArray();
}

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

public function getYears()
{
return $this->distinct()->pluck('year')->toArray();
}

public function getImages($user_id)
{
return $this->where('user_id', $user_id)->pluck('image_path')->toArray();
}

public function addPost($data)
{
return $this->create($data);
}

public function updatePost($data)
{
return $this->where('id', $data['id'])->update([
'title' => $data['title'],
'brand' => $data['brand'],
'model' => $data['model'],
'description' => $data['description'],
'year' => $data['year'],
]);
}

public function getPostById($id)
{
return $this->find($id);
}

public function deletePost($id)
{
return $this->where('id', $id)->delete();
}

public function getFilteredPosts($brand, $model, $year, $page, $perPage)
{
return $this->when($brand, function ($query) use ($brand) {
return $query->where('brand', $brand);
})
->when($model, function ($query) use ($model) {
return $query->where('model', 'like', '%' . $model . '%');
})
->when($year, function ($query) use ($year) {
return $query->where('year', $year);
})
->orderBy('created_at', 'desc')
->skip(($page - 1) * $perPage)
->take($perPage)
->get();
}

public function getTotalFilteredPosts($brand, $model, $year)
{
return $this->when($brand, function ($query) use ($brand) {
return $query->where('brand', $brand);
})
->when($model, function ($query) use ($model) {
return $query->where('model', 'like', '%' . $model . '%');
})
->when($year, function ($query) use ($year) {
return $query->where('year', $year);
})
->count();
}
}
