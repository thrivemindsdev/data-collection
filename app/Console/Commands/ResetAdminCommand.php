<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset 
                            {email=admin@ashram.in : The admin email address}
                            {password=Admin@1234 : The new admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or reset the admin user account credentials';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $password = (string) $this->argument('password');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => 'Admin — श्री आनंदम धाम',
                'password' => Hash::make($password),
            ]
        );

        $this->info("--------------------------------------------------");
        $this->info(" Admin account ready!");
        $this->info(" Email:    {$user->email}");
        $this->info(" Password: {$password}");
        $this->info(" ID:       {$user->id}");
        $this->info("--------------------------------------------------");

        return Command::SUCCESS;
    }
}
