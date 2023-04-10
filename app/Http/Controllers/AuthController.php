<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register the User in the sytem
     * 
     * @param [string] name
     * @param [string] email
     * @param [string] password
     * @param [stirng] confirm_password
     */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'confirm_password' => 'required|same:password',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user) {
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'message' => 'User crated Successfully',
                'user' => $user,
                'token' => $token,
            ], 201);
        } else {

            return response()->json(['error' => 'Provide proper details']);
        }
    }

    /**
     * Login user and create token there
     * 
     * @param [string] email
     * @param  [string] password
     * 
     */

    public function login(Request $request)
    {

        $credentials = $request->validate([

            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([

                'email' => ['The provided credentials are incorrect'],

            ]);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['token' => $token, 'token_type' => 'Bearer']);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    // public function user()
    // {
    //     $users = User::paginate(10);

    //     return response()->json([
    //         'users' => $users->items(),
    //         'totalPage' => $users->lastPage(),
    //         'totalUsers' => $users->total(),
    //     ]);

    //     return response()->json(['users' => $users]);
    // }
    public function user(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->has('q')) {
            $query->where('name', 'LIKE', '%' . $request->input('q') . '%');
        }

        // Apply pagination
        $perPage = $request->input('perPage', 10);
        $page = $request->input('currentPage', 1);
        $users = $query->paginate($perPage, ['*'], 'currentPage', $page);

        return response()->json([
            'users' => $users->items(),
            'totalPage' => $users->lastPage(),
            'totalUsers' => $users->total(),
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
