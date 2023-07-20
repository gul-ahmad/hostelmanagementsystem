<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\LazyCollection;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //gul here 
        //used response json to remove the data wrapper from api structure
        $roles = RoleResource::collection(Role::with('permissions')->get());

        // $lazyRoles = LazyCollection::make(function () use ($roles) {
        //     foreach ($roles as $role) {
        //         yield $role;
        //     }
        // });
        // Create a lazy collection using the fromCollection() method
        // $lazyRoles = LazyCollection::fromCollection($roles);

        return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $role = $request->validate([
            'name' => 'required|unique:roles',
            'permissions' => 'nullable|array|',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $roleCreate = Role::create($role);
        if ($request->has('permissions')) {
            $roleCreate->permissions()->attach($role['permissions']);
        }
        return response()->json($role, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function update(Request $request, Role $role)
    {

        $requestData = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array|',
            'permissions.*' => 'exists:permissions,id'

        ]);

        $role->update(['name' => $requestData['name']]);

        // Get the selected permissions from the request
        $selectedPermissions = $requestData['permissions'];

        // Sync the permissions without detaching existing permissions
        if ($selectedPermissions) {
            $role->permissions()->syncWithoutDetaching($selectedPermissions);
        }

        return response()->json($role, 200);
    }


    public function destroy(Role $role)
    {
        $role->permissions()->detach(); // Detach all associated permissions
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}
