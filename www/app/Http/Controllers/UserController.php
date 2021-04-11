<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Jobs\ProcessUser;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function listAllAndFilter(Request $request)
    {
        $query = DB::table('users')->select('users.*');

        if ($request->filled(['country'])) {

            $query->join('countries', 'countries.id', '=','users.country_id')
                    ->where('country_code', '=', $request->input()['country']);
        }

        if (   $request->filled(['verified'])
            or $request->filled(['email'])
            or $request->filled(['name'])) {

            $whitelist = [
                'verified',
                'email',
                'name',
            ];

            $parameters = array_intersect_key($request->input(), array_flip($whitelist));

            foreach ($parameters as $name => $parameter) {
                    $query->where($name, $parameter);
            }
        }
        return new UserCollection($query->get());
    }

    public function delete(Request $request)
    {
        if (auth()->user()) {
            $user = User::find(auth()->user()->id);

            $user->projects()->detach();
            $user->delete();

            return response()->json(null, 204);
        }
        return response()->json(null, 400);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'unique:users,name', 'min :3', 'max     :255', 'string'],
            'email'    => ['required', 'unique:users,email', 'min:5', 'max     :255', 'string'],
            'password' => ['required', 'min:5', 'max:255'],
            'country'  => ['required', 'min:2', 'max:255', 'exists:countries,country_code'],
        ]);

        $request = [
            'email'    => $request['email'],
            'name'     => $request['name'],
            'password' => $request['password'],
            'country'  => $request['country'],
        ];

        ProcessUser::dispatch($request)
            ->onQueue('process_user');

        return response()->json(null, 204);
    }

    public function update(Request $request)
    {
        if (auth()->user()) {
            $request->validate([
                'name'     => ['required', 'unique:users,name', 'min :3', 'max     :255', 'string'],
                'email'    => ['required', 'unique:users,email', 'min:5', 'max     :255', 'string'],
                'password' => ['required', 'min:5', 'max:255'],
                'country'  => ['required', 'min:2', 'max:255', 'exists:countries,country_code'],
            ]);

            $user = User::find(auth()->user()->id);
            if ( $request['country'] != null) {
                $country_id = Country::where('country_code', '=', $request['country'])
                    ->first()->id;
                $user ->country_id = $country_id;
            }

            $user ->email      = $request['email'];
            $user ->name       = $request['name'];
            $user ->password   = Hash::make($request['password']);
            $user ->save();

            return new UserResource($user);
        }
        return response()->json(null, 400);
    }

    public function verify(Request $request)
    {
        $user = User::where('name', $request['name'])->first();
        if ($user == null) {
            return response()->json(null, 404);
        }

        $user ->verified  = 1;
        $user ->save();

        return new UserResource($user);
    }
}
