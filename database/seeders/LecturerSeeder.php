<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'user_type' => 'Lecturer',
            'is_head' => false,
            'email' => 'test.one3245@st.lasu.edu.ng',
            'password'=> Hash::make('password')
        ]);
        DB::table('lecturers')->insert([
            'staff_id' => '3245',
            'user_id' => $user->id,
            'department_id' => '0591',
            'level_assigned' => 'Final',
        ]);
        $user2 = User::create([
            'user_type' => 'Lecturer',
            'is_head' => true,
            'email' => 'test.two3245@st.lasu.edu.ng',
            'password'=> Hash::make('password')
        ]);
        DB::table('lecturers')->insert([
            'staff_id' => '3235',
            'department_id' => '0591',
            'user_id' => $user2->id,
            'level_assigned' => '300',
        ]);
        $user2 = User::create([
            'user_type' => 'Admin',
            'is_head' => false,
            'email' => 'admin@st.lasucs.edu.ng',
            'password'=> Hash::make('password')
        ]);
    }
}
