<?php
namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegistrationSeeder extends Seeder
{
    public function run()
    {
        // Obtener usuarios no administradores
        $users = User::where('is_admin', false)->get();
        $typesRegistration = ['virtual', 'presential', 'student'];
        $typesPayment = ['pending', 'completed', 'failed'];
        foreach ($users as $user) {
            Registration::create([
                'user_id' => $user->id,
                'registration_type' => $user->registration_type ?? $typesRegistration[array_rand($typesRegistration)],
                'total_amount' => rand(0, 100),
                'payment_status' => $typesPayment[array_rand($typesPayment)],
                'ticket_code' => Str::random(10)
            ]);
        }
    }
}