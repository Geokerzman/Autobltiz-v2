<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    private $postModel;

    public function __construct(Post $postModel)
    {
        $this->postModel = $postModel;
    }

    public function getPostsWithFilters($filters, $page, $postsPerPage, &$totalPosts, &$totalPages)
    {
        if ($filters['brand'] || $filters['model'] || $filters['year']) {
            $posts = $this->postModel->getFilteredPosts(
                $filters['brand'],
                $filters['model'],
                $filters['year'],
                $page,
                $postsPerPage
            );
            $totalPosts = $this->postModel->getTotalFilteredPosts($filters['brand'], $filters['model'], $filters['year']);
            $totalPages = ceil($totalPosts / $postsPerPage);
        } else {
            $posts = $this->postModel->paginate($postsPerPage);
            $totalPosts = $posts->total();
            $totalPages = $posts->lastPage();
        }

        return $posts;
    }

    public function addPost($data)
    {
        return $this->postModel->create($data);
    }

    public function updatePost($data)
    {
        return $this->postModel->where('id', $data['id'])->update([
            'title' => $data['title'],
            'brand' => $data['brand'],
            'model' => $data['model'],
            'description' => $data['description'],
            'year' => $data['year'],
        ]);
    }

    public function getPostById($id)
    {
        return $this->postModel->find($id);
    }

    public function deletePost($id)
    {
        return $this->postModel->where('id', $id)->delete();
    }

    public function getBrands()
    {
        return $this->postModel->getBrands();
    }

    public function getModels()
    {
        return $this->postModel->getModels();
    }

    public function getYears()
    {
        return $this->postModel->getYears();
    }

    public function getImages($user_id)
    {
        return $this->postModel->where('user_id', $user_id)->pluck('image_path')->toArray();
    }

    public function uploadImage($file)
    {
        $imagePath = $file->store('public/images');
        return str_replace('public/', '/storage/', $imagePath);
    }
}
