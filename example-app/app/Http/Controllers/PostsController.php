<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    private $postModel;
    private $userModel;

    public function __construct()
    {
        $this->middleware('auth');

        $this->postModel = app()->make('App\Models\Post');
        $this->userModel = app()->make('App\Models\User');
    }

    public function index($page = 1)
    {
        $postsPerPage = 10;
        $totalPosts = $this->postModel->getTotalPosts();
        $totalPages = ceil($totalPosts / $postsPerPage);

        if ($page < 1 || $page > $totalPages) {
            $page = 1;
        }

        $brand = request()->input('brand');
        $model = request()->input('model');
        $year = request()->input('year');

        if ($brand !== null || $model !== null || $year !== null) {
            $posts = $this->postModel->getFilteredPosts($brand, $model, $year, $page, $postsPerPage);
            $totalPosts = $this->postModel->getTotalFilteredPosts($brand, $model, $year);
            $totalPages = ceil($totalPosts / $postsPerPage);
        } else {
            $posts = $this->postModel->getPosts($page, $postsPerPage);
        }

        $brands = $this->postModel->getBrands();
        $years = $this->postModel->getYears();
        $models = $this->postModel->getModels();
        $images = $this->postModel->getImages(auth()->id());

        $data = [
            'brand' => $brand,
            'model' => $model,
            'models' => $models,
            'year' => $year,
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'brands' => $brands,
            'years' => $years,
            'images' => $images,
            'image' => $images,
        ];

        return view('posts.index', $data);
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required',
                'brand' => 'required',
                'model' => 'required',
                'description' => 'required',
                'year' => 'required',
                'fileToUpload' => 'required|image|mimes:jpeg,png,jpg,gif|max:500000',
            ]);

            $imagePath = $request->file('fileToUpload')->store('public/images');
            $imagePath = str_replace('public/', '/storage/', $imagePath);

            $data = [
                'title' => $request->input('title'),
                'user_id' => auth()->id(),
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
            $brands = $this->postModel->getBrands();
            $years = $this->postModel->getYears();

            $data = [
                'title' => '',
                'brand' => '',
                'model' => '',
                'description' => '',
                'year' => '',
                'brands' => $brands,
                'years' => $years,
            ];

            return view('posts.add', $data);
        }
    }

    // Остальные методы оставляем без изменений
    // ...

}


