<?php

//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//
//class PostsController extends Controller
//{
//    private $postModel;
//
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->postModel = app()->make('App\Models\Post');
//    }
//
//    public function index($page = 1)
//    {
//        $postsPerPage = 10;
//        $totalPosts = $this->postModel->getTotalPosts();
//        $totalPages = ceil($totalPosts / $postsPerPage);
//
//        $page = max(1, min($page, $totalPages));
//
//        $filters = [
//            'brand' => request()->input('brand'),
//            'model' => request()->input('model'),
//            'year' => request()->input('year'),
//        ];
//
//        $posts = $this->getPostsWithFilters($filters, $page, $postsPerPage, $totalPosts, $totalPages);
//
//        $data = [
//            'filters' => $filters,
//            'posts' => $posts,
//            'currentPage' => $page,
//            'totalPages' => $totalPages,
//            'totalPosts' => $totalPosts,
//            'brands' => $this->postModel->getBrands(),
//            'years' => $this->postModel->getYears(),
//            'models' => $this->postModel->getModels(),
//            'images' => $this->postModel->getImages(Auth::id()),
//        ];
//
//        return view('posts.index', $data);
//    }
//
//    public function add(Request $request)
//    {
//        if ($request->isMethod('post')) {
//            $this->validate($request, [
//                'title' => 'required',
//                'brand' => 'required',
//                'model' => 'required',
//                'description' => 'required',
//                'year' => 'required',
//                'fileToUpload' => 'required|image|mimes:jpeg,png,jpg,gif|max:500000',
//            ]);
//
//            $imagePath = $this->uploadImage($request->file('fileToUpload'));
//
//            $data = [
//                'title' => $request->input('title'),
//                'user_id' => Auth::id(),
//                'brand' => $request->input('brand'),
//                'model' => $request->input('model'),
//                'description' => $request->input('description'),
//                'year' => $request->input('year'),
//                'image_path' => $imagePath,
//            ];
//
//            if ($this->postModel->addPost($data)) {
//                return redirect()->route('posts.index')->with('success', 'Post Added');
//            } else {
//                return redirect()->back()->with('error', 'Something went wrong while adding the post');
//            }
//        } else {
//            $data = [
//                'title' => '',
//                'brand' => '',
//                'model' => '',
//                'description' => '',
//                'year' => '',
//                'brands' => $this->postModel->getBrands(),
//                'years' => $this->postModel->getYears(),
//            ];
//
//            return view('posts.add', $data);
//        }
//    }
//
//    public function edit($id)
//    {
//        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//            // Sanitize POST array
//            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
//
//            $data = [
//                'id' => $id,
//                'title' => trim($_POST['title']),
//                'user_id' => Auth::id(),
//                'brand' => trim($_POST['brand']),
//                'model' => trim($_POST['model']),
//                'description' => trim($_POST['description']),
//                'year' => trim($_POST['year']),
//            ];
//
//            $this->validatePostData($data);
//
//            // Make sure no errors
//            if (empty($data['title_err']) && empty($data['brand_err']) && empty($data['model_err']) && empty($data['description_err']) && empty($data['year_err'])) {
//                // Validated
//                if ($this->postModel->updatePost($data)) {
//                    return redirect()->route('posts.index')->with('success', 'Post Updated');
//                } else {
//                    return redirect()->back()->with('error', 'Something went wrong');
//                }
//            } else {
//                // Load view with errors
//                return redirect()->back()->withErrors($data);
//            }
//
//        } else {
//            // Get existing post from model
//            $post = $this->postModel->getPostById($id);
//
//            // Check for owner
//            if ($post->user_id != Auth::id()) {
//                return redirect()->route('posts.index');
//            }
//
//            $data = [
//                'id' => $id,
//                'title' => $post->title,
//                'brand' => $post->brand,
//                'model' => $post->model,
//                'description' => $post->description,
//                'year' => $post->year,
//                'brands' => $this->postModel->getBrands(),
//                'years' => $this->postModel->getYears(),
//            ];
//
//            return view('posts.edit', $data);
//        }
//    }
//
//    private function validatePostData(&$data)
//    {
//        $isError = false;
//
//        if (empty($data['title'])) {
//            $data['title_err'] = 'Please enter title';
//            $isError = true;
//        }
//
//        if (empty($data['brand'])) {
//            $data['brand_err'] = 'Please select a brand';
//            $isError = true;
//        }
//
//        if (empty($data['model'])) {
//            $data['model_err'] = 'Please enter a model';
//            $isError = true;
//        }
//
//        if (empty($data['description'])) {
//            $data['description_err'] = 'Please enter a description';
//            $isError = true;
//        }
//
//        if (empty($data['year'])) {
//            $data['year_err'] = 'Please select a year';
//            $isError = true;
//        }
//
//        return $isError;
//    }
//
//    public function show($id)
//    {
//        $post = $this->postModel->getPostById($id);
//        $user = Auth::user();  // Assuming you have the User model imported
//
//        $data = [
//            'post' => $post,
//            'user' => $user,
//        ];
//
//        return view('posts.show', $data);
//    }
//
//    public function delete($id)
//    {
//        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//            // Get existing post from model
//            $post = $this->postModel->getPostById($id);
//
//            // Check for owner
//            if ($post->user_id != Auth::id()) {
//                return redirect()->route('posts.index');
//            }
//
//            // Check for user_group
//            $userGroup = Auth::user()->user_group ?? null;
//            if ($userGroup !== 2) {
//                // User does not have the required user_group
//                return redirect()->route('posts.index');
//            }
//
//            if ($this->postModel->deletePost($id)) {
//                return redirect()->route('posts.index')->with('success', 'Post Removed');
//            } else {
//                return redirect()->back()->with('error', 'Something went wrong');
//            }
//        } else {

            namespace App\Http\Controllers;

            use Illuminate\Http\Request;
            use Illuminate\Support\Facades\Auth;

            class PostsController extends Controller
            {
                private $postModel;

                public function __construct()
                {
                    $this->middleware('auth');
                    $this->postModel = app()->make('App\Models\Post');
                }

                public function index($page = 1)
                {
                    $postsPerPage = 10;
                    $totalPosts = $this->postModel->getTotalPosts();
                    $totalPages = ceil($totalPosts / $postsPerPage);

                    $page = max(1, min($page, $totalPages));

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
                        $this->validate($request, [
                            'title' => 'required',
                            'brand' => 'required',
                            'model' => 'required',
                            'description' => 'required',
                            'year' => 'required',
                            'fileToUpload' => 'required|image|mimes:jpeg,png,jpg,gif|max:500000',
                        ]);

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

                public function edit($id)
                {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Sanitize POST array
                        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                        $data = [
                            'id' => $id,
                            'title' => trim($_POST['title']),
                            'user_id' => Auth::id(),
                            'brand' => trim($_POST['brand']),
                            'model' => trim($_POST['model']),
                            'description' => trim($_POST['description']),
                            'year' => trim($_POST['year']),
                        ];

                        $this->validatePostData($data);

                        // Make sure no errors
                        if (empty($data['title_err']) && empty($data['brand_err']) && empty($data['model_err']) && empty($data['description_err']) && empty($data['year_err'])) {
                            // Validated
                            if ($this->postModel->updatePost($data)) {
                                return redirect()->route('posts.index')->with('success', 'Post Updated');
                            } else {
                                return redirect()->back()->with('error', 'Something went wrong');
                            }
                        } else {
                            // Load view with errors
                            return redirect()->back()->withErrors($data);
                        }

                    } else {
                        // Get existing post from model
                        $post = $this->postModel->getPostById($id);

                        // Check for owner
                        if ($post->user_id != Auth::id()) {
                            return redirect()->route('posts.index');
                        }

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

                        return view('posts.edit', $data);
                    }
                }

                private function validatePostData(&$data)
                {
                    $isError = false;

                    if (empty($data['title'])) {
                        $data['title_err'] = 'Please enter title';
                        $isError = true;
                    }

                    if (empty($data['brand'])) {
                        $data['brand_err'] = 'Please select a brand';
                        $isError = true;
                    }

                    if (empty($data['model'])) {
                        $data['model_err'] = 'Please enter a model';
                        $isError = true;
                    }

                    if (empty($data['description'])) {
                        $data['description_err'] = 'Please enter a description';
                        $isError = true;
                    }

                    if (empty($data['year'])) {
                        $data['year_err'] = 'Please select a year';
                        $isError = true;
                    }

                    return $isError;
                }

                public function show($id)
                {
                    $post = $this->postModel->getPostById($id);
                    $user = Auth::user();  // Assuming you have the User model imported

                    $data = [
                        'post' => $post,
                        'user' => $user,
                    ];

                    return view('posts.show', $data);
                }

                public function delete($id)
                {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Get existing post from model
                        $post = $this->postModel->getPostById($id);

                        // Check for owner
                        if ($post->user_id != Auth::id()) {
                            return redirect()->route('posts.index');
                        }

                        // Check for user_group
                        $userGroup = Auth::user()->user_group ?? null;
                        if ($userGroup !== 2) {
                            // User does not have the required user_group
                            return redirect()->route('posts.index');
                        }

                        if ($this->postModel->deletePost($id)) {
                            return redirect()->route('posts.index')->with('success', 'Post Removed');
                        } else {
                            return redirect()->back()->with('error', 'Something went wrong');
                        }
                    } else {
                        return redirect()->route('posts.index');
                    }
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
                        $posts = $this->postModel->getPosts($page, $postsPerPage);
                    }

                    return $posts;
                }

                private function uploadImage($file)
                {
                    $imagePath = $file->store('public/images');
                    return str_replace('public/', '/storage/', $imagePath);
                }
            }

            return redirect()->route('posts.index');
        }
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
            $posts = $this->postModel->getPosts($page, $postsPerPage);
        }

        return $posts;
    }

    private function uploadImage($file)
    {
        $imagePath = $file->store('public/images');
        return str_replace('public/', '/storage/', $imagePath);
    }
}
