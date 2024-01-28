<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PostService;

class PostsController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->middleware('auth');
        $this->postService = $postService;
    }

    public function index(Request $request, $page = 1)
    {
        $postsPerPage = 10;

        $filters = [
            'brand' => $request->input('brand'),
            'model' => $request->input('model'),
            'year' => $request->input('year'),
        ];

        $posts = $this->postService->getPostsWithFilters($filters, $page, $postsPerPage, $totalPosts, $totalPages);

        $data = [
            'filters' => $filters,
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'brands' => $this->postService->getBrands(),
            'years' => $this->postService->getYears(),
            'models' => $this->postService->getModels(),
            'images' => $this->postService->getImages(Auth::id()),
        ];

        return view('posts.index', $data);
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validatePostData($request);

            $imagePath = $this->postService->uploadImage($request->file('fileToUpload'));

            $data = [
                'title' => $request->input('title'),
                'user_id' => Auth::id(),
                'brand' => $request->input('brand'),
                'model' => $request->input('model'),
                'description' => $request->input('description'),
                'year' => $request->input('year'),
                'image_path' => $imagePath,
            ];

            if ($this->postService->addPost($data)) {
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
                'brands' => $this->postService->getBrands(),
                'years' => $this->postService->getYears(),
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
        $this->postService->createPost($data);

        // После успешного сохранения перенаправление на страницу с постами
        return redirect()->route('posts.index')->with('success', 'Post Created');
    }

    public function edit(Request $request, $id)
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
            if ($this->postService->updatePost($data)) {
                return true;
            }
        }

        return false;
    }

    private function getPostDataForEdit($id)
    {
        $post = $this->postService->getPostById($id);

        if ($this->isPostOwner($post)) {
            $data = [
                'id' => $id,
                'title' => $post->title,
                'brand' => $post->brand,
                'model' => $post->model,
                'description' => $post->description,
                'year' => $post->year,
                'brands' => $this->postService->getBrands(),
                'years' => $this->postService->getYears(),
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
        $post = $this->postService->getPostById($id);

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

    public function delete(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $post = $this->postService->getPostById($id);

            if ($this->isPostOwner($post) && $this->hasDeletePermission()) {
                if ($this->postService->deletePost($id)) {
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
}
