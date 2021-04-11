<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Label;
use App\Http\Resources\LabelResource;
use App\Http\Resources\LabelCollection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class LabelController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function listAllAndFilter(Request $request)
    {
        if (auth()->user()) {
            $query = DB::table('labels')->select('labels.*');

            if ($request->filled(['email'])) {

                $query->join('users', 'labels.user_id', '=','users.id')
                        ->where('email', '=', $request->input()['email']);
            }

            if ($request->filled(['projects'])) {
                if (!is_array($request->get('projects'))) {
                    abort(400, 'ARE YOU TRYING TO BREAK ME? HOW DARE YOU!?!?!?');
                }
                $query->join('label_project', 'labels.id', '=','label_project.label_id')
                        ->whereIn('project_id', $request->get('projects'));
            }

            $query->where('labels.user_id', '=', auth()->user()->id);
            return new LabelCollection($query->distinct()->get());
        }
        return response()->json(null, 400);
    }

    public function delete(Request $request)
    {
        if (auth()->user()) {
            $label = Label::find($request->input()['id_of_a_label']);
            if ($label == null) {
                return response()->json(null, 404);
            }
            if ($label->user_id != auth()->user()->id) {
                return response()->json(null, 401);
            }

            $label->projects()->detach();
            $label->delete();

            return response()->json(null, 204);
        }
        return response()->json(null, 400);
    }

    public function store(Request $request)
    {
        if (auth()->user()) {
            $request->validate([
                'label_name'  => ['required', 'unique:labels,name', 'min :3', 'max:255', 'string'],
            ]);

            $label = new Label;
            $label ->name    = $request['label_name'];
            $label ->user_id = auth()->user()->id;
            $label ->save();

            return new LabelResource($label);
        }
        return response()->json(null, 400);
    }


    public function link(Request $request)
    {
        $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'label_id'   => ['required', 'exists:labels,id'],
        ]);
        foreach ($request['label_id'] as $key => $label_id) {
            $label = Label::find($label_id);
            if ($label == null) {
                return response()->json(null, 404);
            }

            foreach ($request['project_id'] as $key => $project) {
                $label->projects()->attach($project);
            }
        }
        return response()->json(null, 204);
    }
}
