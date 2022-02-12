<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function index(Request $request)
    {
        $searchField = 'username';
        $filters = [AllowedFilter::exact('department_id')];
        $sorts = ['id', 'created_at', 'username', 'authorization_level'];
        $callback = fn ($models) => UserResource::collection($models);
        $res = requestResponseWithFilterRangeSort(User::class, $searchField, $filters, $sorts, $callback);
        return $res;
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return new UserResource($user);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $user->update($request->only('authorization_level',));
        return new UserResource($user);
    }

    public function updatePassword(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $data = request()->validate([
            'password' => ['required', 'string', 'min:4'],
        ]);
        $user->update(['password' => Hash::make($data['password'])]);
        return new UserResource($user);
    }
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        return $user->delete();
    }
}
