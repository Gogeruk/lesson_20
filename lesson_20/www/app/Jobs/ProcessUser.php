<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Country;
use App\Models\User;

class ProcessUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $i = 0;
        foreach ($this->request['name'] as $key => $value) {
            $country_id = Country::where('country_code', '=', $this->request['country'][$i])
                ->first()->id;

            $user = new User;
            $user ->name       = $this->request['name'][$i];
            $user ->email      = $this->request['email'][$i];
            $user ->country_id = $country_id;
            $user ->password   = Hash::make($this->request['password'][$i]);
            $user ->api_token  = Str ::random(20);
            $user ->setRememberToken($token = Str::random(10));
            $user ->save();

            $i++;
        }
    }
}
