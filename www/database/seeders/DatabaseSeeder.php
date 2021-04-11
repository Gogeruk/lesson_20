<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\Continent;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $response = Http::get('http://country.io/continent.json');

        foreach ($response->json() as $key => $value) {
            Continent::create([
                'country_code'   => $key,
                'continent_code' => $value
            ]);
        }

        $response = Http::get('http://country.io/names.json');

        foreach ($response->json() as $key => $value) {
            $id = DB::table('continents')
                ->select('id')->where('country_code', $key)
                    ->first();

            Country::create([
                    'country_code' => $key,
                    'country_name' => $value,
                    'continent_id' => $id->id
                ]);
        }

        $users     = \App\Models\User     ::factory(20)->create();
        $labels    = \App\Models\Label    ::factory(60)->create();
        $projects  = \App\Models\Project  ::factory(40)->create();
        $country   = \App\Models\Country  ::factory(0)->create();
        $continent = \App\Models\Continent::factory(0)->create();

        // label_project
        foreach ($projects as $key => $value) {
            $value->labels()
                ->attach($labels->random(rand(2, 4))
                    ->pluck('id'));
        }

        // project_user
        foreach ($projects as $key => $project) {
            $project->users()
                ->attach($users->random(rand(2, 4))
                    ->pluck('id'));
            foreach ($projects as $key => $linked_user) {
                if ($project->user_id != $linked_user->id) {
                    $project->users()
                        ->attach($project->user_id);
                    break;
                }
            }
        }
    }
}
