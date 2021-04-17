<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Validator;

class ProjectController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function listAllAndFilter(Request $request)
    {
        if (auth()->user()) {
            $query = DB::table('projects')
                ->join('project_user', 'projects.id', '=', 'project_user.project_id')
                ->where('project_user.user_id', '=', auth()->user()->id)
                ->select('projects.*');

            if ($request->filled(['email'])) {
                $query->join('users', 'projects.user_id', '=','users.id')
                    ->where('email', '=', $request->input()['email']);
            }

            if ($request->filled(['labels'])) {
                abort_if(!is_array($request->get('labels')), 400, 'MUST BE AN ARRAY');

                $query->join('label_project', 'projects.id', '=','label_project.project_id')
                    ->whereIn('label_id', $request->get('labels'));
            }

            if ($request->filled(['continent'])) {
                if (!$request->filled(['email'])) {
                    $query->join('users', 'projects.user_id', '=','users.id');
                }
                $query->join('countries', 'countries.id', '=','users.country_id')
                    ->join('continents', 'continents.id', '=','countries.continent_id')
                    ->where('continent_code', $request->input()['continent']);
            }

            return new ProjectCollection($query->distinct()->get());
        }
        return response()->json(null, 400);
    }

    public function delete(Request $request)
    {
        if (auth()->user()) {
            $rules = [
                'id_of_a_project'   => ['required', 'array'],
                'id_of_a_project.*' => ['distinct:strict', 'exists:projects,id'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->passes()) {

                $i = 0;
                foreach ($request->input()['id_of_a_project'] as $value) {
                    $project = Project::find($request->input()['id_of_a_project'][$i]);
                    abort_if(auth()->user()
                        ->cannot('delete', $project), 401, 'UNAUTHORIZED, PLEASE GO AWAY');
                    $project->labels()->detach();
                    $project->users()->detach();
                    $project->delete();

                    $i++;
                }
                return response()->json(null, 204);
            }else {
                return($validator->errors()->all());
            }
        }
    }

    public function store(Request $request)
    {
        if (auth()->user()) {
            $rules = [
                'project_name'   => ['required', 'array'],
                'project_name.*' => ['distinct:strict', 'unique:projects,name', 'min :3', 'max:255', 'string'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->passes()) {
                $i = 0;
                foreach ($request['project_name'] as $value) {
                    $project = new Project;
                    $project ->name    = $request['project_name'][$i];
                    $project ->user_id = auth()->user()->id;
                    $project ->save();
                    $project ->users()->attach(auth()->user()->id);

                    $i++;
                }
                return response()->json(null, 204);
            }else {
                return($validator->errors()->all());
            }
        }
    }

    public function link(Request $request)
    {
        $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'user_id'    => ['required', 'exists:users,id'],
        ]);
        foreach ($request['project_id'] as $key => $project_id) {
            $project = Project::find($project_id);
            if ($project == null) {
                return response()->json(null, 404);
            }

            foreach ($request['user_id'] as $key => $user) {
                $project->users()->attach($user);
            }
        }
        return response()->json(null, 204);
    }
}
