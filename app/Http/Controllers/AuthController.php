<?php

namespace App\Http\Controllers;

use App\Actions\userRegistrationAction;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\LazyCollection;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Command\LazyCommand;

class AuthController extends Controller
{
    private $userRegistration;

    public function __construct(userRegistrationAction $userRegistration)
    {
        $this->userRegistration = $userRegistration;
    }

    public function register(UserRegistrationRequest $request)
    {

        $response = $this->userRegistration->executeFromRequest($request);

        return $response;
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
                    'email' => ['The provided email is wrong!'],
                ],
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth-token')->plainTextToken;
        $userData = $user->roles->pluck('name');
        $userDetails = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        $userAbilities = $user->roles->flatMap(function ($role) {

            return $role->permissions->pluck('name');
        });

        return response()->json(
            [
                'accessToken' => $token,
                'token_type' => 'Bearer',
                'userAbilities' => $userAbilities,
                'userData' => $userData,
                'userDetails' => $userDetails
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

    public function show(Request $request, $id): JsonResponse
    {


        $userData = User::query()
            ->where('id', $id)
            ->with('reservations')
            ->first();



        return response()->json([
            'data' => $userData,
        ]);
    }
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
