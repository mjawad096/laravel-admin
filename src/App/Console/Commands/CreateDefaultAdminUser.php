<?php

namespace Dotlogics\Admin\App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateDefaultAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-admin:default-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a Default Admin User in table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->getEmail();
        $password = $this->getPassword();

        if ($user = User::where('email', $email)->first()) {
            $this->info('This User already exist in database');

            if (! $this->confirm('Override ?')) {
                return;
            }
        }
        $name = $this->getOutput()->ask('Enter Name ', 'admin');

        $already = ! empty($user);

        if (! $already) {
            $user = new User;
        }

        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();

        $this->info($already ? 'User Updated' : 'User Created');
    }

    private function getEmail()
    {
        $email = $this->getOutput()->ask('Enter Email ', 'admin@admin.com');
        $validator = \Validator::make(['email' => $email], ['email' => ['bail', 'required', 'email']]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return $this->getEmail();
        }

        return $email;
    }

    private function getPassword()
    {
        $defualt_password = '123456789';
        $password = $this->getOutput()->ask('Enter Password', $defualt_password);
        if (! $password) {
            return $defualt_password;
        }

        $validator = \Validator::make(['password' => $password], ['password' => ['bail', 'required', 'min:8']]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return $this->getPassword();
        }

        return $password;
    }
}
