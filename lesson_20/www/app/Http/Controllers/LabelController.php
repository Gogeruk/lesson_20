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
use Validator;

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
                abort_if(!is_array($request->get('projects')), 400, 'MUST BE AN ARRAY');

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
            $rules = [
                'id_of_a_label'   => ['required', 'array'],
                'id_of_a_label.*' => ['distinct:strict', 'exists:labels,id'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->passes()) {

                $i = 0;
                foreach ($request->input()['id_of_a_label'] as $value) {
                    $label = Label::find($request->input()['id_of_a_label'][$i]);
                    abort_if(auth()->user()
                        ->cannot('delete', $label), 401, 'UNAUTHORIZED, PLEASE GO AWAY');
                    $label->projects()->detach();
                    $label->delete();

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
                'label_name'   => ['required', 'array'],
                'label_name.*' => ['distinct:strict', 'unique:labels,name', 'min :3', 'max:255', 'string'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->passes()) {
                $i = 0;
                foreach ($request['label_name'] as $value) {
                    $label = new Label;
                    $label ->name    = $request['label_name'][$i];
                    $label ->user_id = auth()->user()->id;
                    $label ->save();

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
