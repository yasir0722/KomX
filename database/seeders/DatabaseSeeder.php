<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Admin User ──────────────────────────────────────────────
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@komx.app',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        Member::create([
            'user_id' => $admin->id,
            'phone' => '+60123456789',
            'membership_number' => 'KMX-0001',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // ─── Committee User ─────────────────────────────────────────
        $committee = User::create([
            'name' => 'Committee User',
            'email' => 'committee@komx.app',
            'password' => Hash::make('password'),
            'role' => 'committee',
            'email_verified_at' => now(),
        ]);

        Member::create([
            'user_id' => $committee->id,
            'phone' => '+60123456780',
            'membership_number' => 'KMX-0002',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // ─── Regular Member ─────────────────────────────────────────
        $member = User::create([
            'name' => 'Regular Member',
            'email' => 'member@komx.app',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        Member::create([
            'user_id' => $member->id,
            'phone' => '+60123456781',
            'membership_number' => 'KMX-0003',
            'status' => 'active',
            'joined_at' => now(),
        ]);
    }
}
