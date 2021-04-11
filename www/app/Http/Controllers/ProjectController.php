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
                if (!is_array($request->get('labels'))) {
                    return response()->json('ARE YOU TRYING TO BREAK ME? HOW DARE YOU!?!?!?', 400);
                }

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
            $project = Project::find($request->input()['id_of_a_project']);
            if ($project == null) {
                return response()->json(null, 404);
            }
            if ($project->user_id != auth()->user()->id) {
                return response()->json(null, 401);
            }

            $project->labels()->detach();
            $project->users()->detach();
            $project->delete();

            return response()->json(null, 204);
        }
        return response()->json(null, 400);
    }

    public function store(Request $request)
    {
        if (auth()->user()) {
            $request->validate([
                'project_name'  => ['required', 'unique:projects,name', 'min :3', 'max:255', 'string'],
            ]);

            $project = new Project;
            $project ->name    = $request['project_name'];
            $project ->user_id = auth()->user()->id;
            $project ->save();
            $project ->users()->attach(auth()->user()->id);


            return new ProjectResource($project);
        }
        return response()->json(null, 400);
    }

    public function link(Request $request)
    {
        $request->validate([
            'project_id' => ['required', 'exists:project_user,project_id'],
            'user_id'    => ['required', 'exists:project_user,user_id'],
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
