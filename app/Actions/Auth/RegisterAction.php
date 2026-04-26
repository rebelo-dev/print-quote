<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterAction
{
    public function execute(array $data): array
    {

        $slug = $this->generateSlug($data['name']);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'slug'     => $slug,
            'role'     => 'printer',
        ]);

        $user->printerSetting()->create([
            'cost_per_hour'     => 0,
            'mm3_per_hour'      => 0,
            'margin_percentage' => 0,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    private function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $count = 1;

        while (User::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
