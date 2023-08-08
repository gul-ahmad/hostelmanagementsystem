<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\LazyCollection;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Command\LazyCommand;

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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'confirm_password' => 'required|same:password',
        ]);

        //  dd($request->all());
        $userRoles = $request->userRoles;

        // dd($userRoles);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);


        if ($userRoles) {

            foreach ($userRoles as $userRole) {
                $role = $user->roles()->attach($userRole);
            }
            $roles = $user->roles()->get();
        } else {
            //id of role User 
            $userRole = 6;
            $role = $user->roles()->attach($userRole);
            $roles = $user->roles()->get();
        }
        if ($user) {
            $token = $user->createToken('auth-token')->plainTextToken;
            $userAbilities = $user->roles->flatMap(function ($role) {

                return $role->permissions->pluck('name');
            });
            return response()->json([
                'message' => 'User created Successfully',
                'user' => $user,
                'token' => $token,
                'roles' => $roles,
                'userAbilities' => $userAbilities
            ], 201);
        } else {

            return response()->json(['errors' => 'Provide proper details']);
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
            return response()->json([
                'message' => 'The provided credentials are incorrect',
                'errors' => [
                    'email' => ['The provided credentials are incorrect'],
                ],
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth-token')->plainTextToken;
        $userData = $user->roles->pluck('name');

        $userAbilities = $user->roles->flatMap(function ($role) {

            return $role->permissions->pluck('name');
        });

        return response()->json(
            [
                'accessToken' => $token,
                'token_type' => 'Bearer',
                'userAbilities' => $userAbilities,
                'userData' => $userData
            ],
            200
        );
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
        // $query = User::query();

        $query = User::with('roles');


        // Apply filters
        if ($request->has('q')) {
            $query->where('name', 'LIKE', '%' . $request->input('q') . '%');
        }

        // Apply pagination
        $perPage = $request->input('perPage', 10);
        $page = $request->input('currentPage', 1);
        $users = $query->paginate($perPage, ['*'], 'currentPage', $page);
        $users = $query->paginate($perPage, ['*'], 'page', $page);


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


    public function update(Request $request, $id)
    {

        $request->validate([
            'name' =>   'sometimes|string'
        ]);

        $user = User::findOrFail($request->id);
        $user->update([
            'name' => $request->user['name'],
        ]);
        return response()->json(
            [
                'success' => 'User updated Successfully'
            ],
            200
        );
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(
            [
                'success' => 'User Deleted successfully'
            ],
            200
        );
    }
}
