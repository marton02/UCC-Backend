<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('What is the name of the new user?');
        $email = $this->ask('What is the email of the new user?');
        $password = $this->secret('What is the password of the new user?');

        $this->line("New user will be created with this data:");

        $this->table(["name", "email"],[[$name,$email]]);

        if ($this->confirm('Do you wish to create an account?',true)) {
            try{
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                ]);

                $this->info("New user has been created!");
            }catch (\Exception $e){
                Log::error($e->getMessage());
                $this->error($e->getMessage());
            }
        }else{
            $this->line("Aborted by the user.");
        }
    }
}
