<?php


namespace  App\Actions;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class userRegistrationAction
{

    //we are overloading the class
    //two different method for same purpose
    //as we are using this class for userRegisration 
    //and as well using it in reservation controller
    //in AuthController we need request Object which is UserRegisationRequest
    //while from reservationController we are not going to pass whole request object as it contains
    //un necessary data so thats why we done overloading

    public function executeFromRequest(UserRegistrationRequest $request)
    {
        // dd($request->all());
        $validatedData = $request->validated();

        // dd($validatedData);
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
            //id of role "User"
            $userRole = 1;
            $role = $user->roles()->attach($userRole);
            $roles = $user->roles()->get();
        }
        if ($user) {
            // Log the user in


            Auth::login($user);

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
        } else {

            return response()->json(['errors' => 'Provide proper details']);
        }
    }

    public function executeFromArray(array $registrationData)
    {




        //dd($registrationData);
        // $userRoles = $registrationData['userRoles'];

        // dd($userRoles);

        $user = User::create([
            'name' => $registrationData[0],
            'email' => $registrationData[1],
            'password' => Hash::make($registrationData[2])
        ]);



        //id of role "User"
        $userRole = 1;
        $role = $user->roles()->attach($userRole);
        $roles = $user->roles()->get();

        if ($user) {

            return $user;
        } else {

            return response()->json(['errors' => 'Provide proper details']);
        }
    }
}
