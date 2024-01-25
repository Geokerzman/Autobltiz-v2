<?php
//
//
//namespace App\Http\Controllers;
//
//use Illuminate\Support\Facades\Auth;
//
//class UsersController extends Controller
//{
//    private $userModel;
//
//    public function __construct()
//    {
//        $this->userModel = app()->make('App\Models\User');
//    }
//
//    public function register()
//    {
//        if (request()->isMethod('post')) {
//            $validatedData = request()->validate([
//                'name' => 'required',
//                'email' => 'required|email|unique:users,email',
//                'password' => 'required|min:6',
//                'confirm_password' => 'required|same:password',
//            ]);
//
//            $data = [
//                'name' => $validatedData['name'],
//                'email' => $validatedData['email'],
//                'password' => bcrypt($validatedData['password']),
//            ];
//
//            if ($this->userModel->register($data)) {
//                $user = $this->userModel->findUserByEmail($data['email']);
//                $this->userModel->assignUserGroup($user->id, 1);
//
//                return redirect()->route('users.login')->with('success', 'You are registered and can log in');
//            } else {
//                return redirect()->back()->with('error', 'Something went wrong');
//            }
//        } else {
//            return view('users.register');
//        }
//    }
//
//    public function login()
//    {
//        if (request()->isMethod('post')) {
//            $validatedData = request()->validate([
//                'email' => 'required|email',
//                'password' => 'required',
//            ]);
//
//            $credentials = [
//                'email' => $validatedData['email'],
//                'password' => $validatedData['password'],
//            ];
//
//            if (Auth::attempt($credentials)) {
//                return redirect()->route('posts.index');
//            } else {
//                return redirect()->back()->with('error', 'No user found or password incorrect');
//            }
//        } else {
//            return view('users.login');
//        }
//    }
//
//    public function logout()
//    {
//        Auth::logout();
//        return redirect()->route('index');
//    }
//}


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {

            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
            ]);


            $data = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
            ];


            if ($this->userModel->register($data)) {
                $user = $this->userModel->findUserByEmail($data['email']);
                $this->userModel->assignUserGroup($user->id, 1);

                $this->createUserSession($user);

                return redirect()->route('users.login')->with('success', 'You are registered and can log in');
            } else {

                return redirect()->back()->with('error', 'Something went wrong');
            }
        } else {
            return view('users.register');
        }
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = [
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $this->createUserSession($user);

                return redirect()->route('posts.index');
            } else {
                return redirect()->back()->with('error', 'No user found or password incorrect');
            }
        } else {
            return view('users.login');
        }
    }

    public function createUserSession($user)
    {
        session(['user_id' => $user->id, 'user_email' => $user->email, 'user_name' => $user->name, 'user_group' => $user->user_group]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
