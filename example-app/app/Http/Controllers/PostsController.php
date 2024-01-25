<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostsController extends Controller
{
    private $postModel;

    public function __construct(Post $postModel)
    {
        $this->middleware('auth');
        $this->postModel = $postModel;
    }

    public function index($page = 1)
    {
        $postsPerPage = 10;

        $filters = [
            'brand' => request()->input('brand'),
            'model' => request()->input('model'),
            'year' => request()->input('year'),
        ];

        $posts = $this->getPostsWithFilters($filters, $page, $postsPerPage, $totalPosts, $totalPages);

        $data = [
            'filters' => $filters,
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'brands' => $this->postModel->getBrands(),
            'years' => $this->postModel->getYears(),
            'models' => $this->postModel->getModels(),
            'images' => $this->postModel->getImages(Auth::id()),
        ];

        return view('posts.index', $data);
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validatePostData($request);

            $imagePath = $this->uploadImage($request->file('fileToUpload'));

            $data = [
                'title' => $request->input('title'),
                'user_id' => Auth::id(),
                'brand' => $request->input('brand'),
                'model' => $request->input('model'),
                'description' => $request->input('description'),
                'year' => $request->input('year'),
                'image_path' => $imagePath,
            ];

            if ($this->postModel->addPost($data)) {
                return redirect()->route('posts.index')->with('success', 'Post Added');

            } else {
                return redirect()->back()->with('error', 'Something went wrong while adding the post');

            }
        } else {
            $data = [
                'title' => '',
                'brand' => '',
                'model' => '',
                'description' => '',
                'year' => '',
                'brands' => $this->postModel->getBrands(),
                'years' => $this->postModel->getYears(),
            ];

            return view('posts.add', $data);

        }
    }
    public function store(Request $request)
    {
        // Обработка данных из формы и сохранение в базу данных
        $data = $request->validate([
            'title' => 'required',
            'body' => 'nullable', // Временно сделать поле body необязательным
            'brand' => 'required',
            'model' => 'required',
            'description' => 'required',
            'year' => 'required',
            // Добавьте другие правила валидации по необходимости
        ]);

        // Создание нового поста с использованием данных из формы
        Post::create($data);

        // После успешного сохранения перенаправление на страницу с постами
        return redirect()->route('posts.index')->with('success', 'Post Created');
    }
    public function edit($id)
    {
        if ($request->isMethod('post')) {
            $data = $this->sanitizePostData($id, $request->post());

            if ($this->validateAndUpdatePost($data)) {
                return redirect()->route('posts.index')->with('success', 'Post Updated');
            } else {
                return redirect()->back()->with('error', 'Something went wrong');
            }
        } else {
            $data = $this->getPostDataForEdit($id);

            return view('posts.edit', $data);
        }
    }

    private function validateAndUpdatePost($data)
    {
        if (!$this->hasValidationErrors($data)) {
            if ($this->postModel->updatePost($data)) {
                return true;
            }
        }

        return false;
    }

    private function getPostDataForEdit($id)
    {
        $post = $this->postModel->getPostById($id);

        if ($this->isPostOwner($post)) {
            $data = [
                'id' => $id,
                'title' => $post->title,
                'brand' => $post->brand,
                'model' => $post->model,
                'description' => $post->description,
                'year' => $post->year,
                'brands' => $this->postModel->getBrands(),
                'years' => $this->postModel->getYears(),
            ];

            return $data;
        }

        return redirect()->route('posts.index');
    }

    private function sanitizePostData($id, $postData)
    {
        $postData = filter_var_array($postData, FILTER_SANITIZE_STRING);

        $data = [
            'id' => $id,
            'title' => trim($postData['title']),
            'user_id' => Auth::id(),
            'brand' => trim($postData['brand']),
            'model' => trim($postData['model']),
            'description' => trim($postData['description']),
            'year' => trim($postData['year']),
        ];

        return $data;
    }

    private function hasValidationErrors($data)
    {
        $isError = false;

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $data[$key . '_err'] = 'Please enter ' . str_replace('_', ' ', $key);
                $isError = true;
            }
        }

        return $isError;
    }

    public function show($id)
    {
        $post = $this->postModel->getPostById($id);

        if ($this->isPostOwner($post)) {
            $user = Auth::user();

            $data = [
                'post' => $post,
                'user' => $user,
            ];

            return view('posts.show', $data);
        }

        return redirect()->route('posts.index');
    }

    public function delete($id)
    {
        if ($request->isMethod('post')) {
            $post = $this->postModel->getPostById($id);

            if ($this->isPostOwner($post) && $this->hasDeletePermission()) {
                if ($this->postModel->deletePost($id)) {
                    return redirect()->route('posts.index')->with('success', 'Post Removed');
                } else {
                    return redirect()->back()->with('error', 'Something went wrong');
                }
            }
        }

        return redirect()->route('posts.index');
    }

    private function isPostOwner($post)
    {
        return $post->user_id == Auth::id();
    }

    private function hasDeletePermission()
    {
        $userGroup = Auth::user()->user_group ?? null;

        return $userGroup === 2;
    }

    private function validatePostData($request)
    {
        $this->validate($request, [
            'title' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'description' => 'required',
            'year' => 'required',
            'fileToUpload' => 'required|image|mimes:jpeg,png,jpg,gif|max:500000',
        ]);
    }

    private function getPostsWithFilters($filters, $page, $postsPerPage, &$totalPosts, &$totalPages)
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

    private function uploadImage($file)
    {
        $imagePath = $file->store('public/images');
        return str_replace('public/', '/storage/', $imagePath);
    }
}
