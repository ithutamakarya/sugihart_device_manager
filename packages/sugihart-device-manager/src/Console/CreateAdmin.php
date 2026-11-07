<?php

namespace Sugihart\DeviceManager\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Sugihart\DeviceManager\Models\User;

class CreateAdmin extends Command
{
    protected $signature = 'sdm:admin {email} {--name=Administrator}';

    protected $description = 'Create a device-manager administrator with an interactively entered password';

    public function handle(): int
    {
        $data = ['email' => $this->argument('email'), 'name' => $this->option('name'), 'password' => $this->secret('Password (minimal 8 karakter)')];
        $validator = Validator::make($data, [
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)],
            'name' => 'required|string|max:255', 'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        $this->info('Administrator device manager dibuat.');

        return self::SUCCESS;
    }
}
