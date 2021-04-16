<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use App\Jobs\ProcessUser;
use App\Models\Country;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyUserMail;
use Validator;

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
        $rules = [
            'api_token'   => ['required', 'array'],
            'api_token.*' => ['distinct:strict', 'min:20', 'max:20'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $i = 0;
            foreach ($request['api_token'] as $value) {
                $user = User::where('api_token', $request['api_token'][$i])
                    ->first();
                if ($user != null) {
                    $user->projects()->detach();
                    $user->delete();
                }

                $i++;
            }
            return response()->json(null, 204);
        }else {
            return($validator->errors()->all());
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'name'       => ['required', 'array'],
            'email'      => ['required', 'array'],
            'password'   => ['required', 'array'],
            'country'    => ['required', 'array'],
            'name.*'     => ['distinct:strict', 'string', 'unique:users,name', 'min :3', 'max:255'],
            'email.*'    => ['distinct:strict', 'string', 'unique:users,email', 'min:5', 'max:255', 'email'],
            'password.*' => ['min:5', 'max:255'],
            'country.*'  => ['string', 'min:2', 'max:2', 'exists:countries,country_code'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $request = [
                'email'    => $request['email'],
                'name'     => $request['name'],
                'password' => $request['password'],
                'country'  => $request['country'],
            ];

            ProcessUser::dispatch($request)
                ->onQueue('process_user');

            return response()->json(null, 204);
        }else {
            return($validator->errors()->all());
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'api_token'   => ['required', 'array'],
            'name'        => ['required', 'array'],
            'email'       => ['required', 'array'],
            'password'    => ['required', 'array'],
            'country'     => ['required', 'array'],
            'api_token.*' => ['required', 'distinct:strict', 'min:20', 'max:20'],
            'name.*'      => ['distinct:strict', 'string', 'unique:users,name', 'min :3', 'max:255'],
            'email.*'     => ['distinct:strict', 'string', 'unique:users,email', 'min:5', 'max:255', 'email'],
            'password.*'  => ['min:5', 'max:255'],
            'country.*'   => ['string', 'min:2', 'max:2', 'exists:countries,country_code'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $i = 0;
            foreach ($request['api_token'] as $value) {
                $user = User::where('api_token', $request['api_token'][$i])
                    ->first();
                if ($user != null) {
                    $country_id = Country::where('country_code', '=', $request['country'][$i])
                        ->first()->id;
                    $user ->country_id = $country_id;
                    $user ->email      = $request['email'][$i];
                    $user ->name       = $request['name'][$i];
                    $user ->password   = Hash::make($request['password'][$i]);
                    $user ->save();
                }

                $i++;
            }
            return response()->json(null, 204);
        }else {
            return($validator->errors()->all());
        }
    }

    public function verify(Request $request)
    {
        $user = User::where('name', $request['name'])->first();
        if ($user == null) {
            return response()->json(null, 404);
        }

        Mail::to($user->email)->send(new VerifyUserMail($user->name, $user->api_token));

        $user ->verified  = 1;
        $user ->save();

        return response()->json(null, 204);
    }
}
