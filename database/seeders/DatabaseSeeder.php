<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Doctor;
use App\Models\Patient;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $permissions = [
        'view patients',
        'create patients',
        'edit patients',
        'delete patients',
        'view doctors',
        'create doctors',
        'edit doctors',
        'delete doctors',
        'view appointments',
        'create appointments',
        'edit appointments',
        'delete appointments',
        'view records',
        'create records',
        'edit records',
        'delete records',
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
$adminAccount = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'hms_admin2024@gmail.com',
            'password' => Hash::make('2024_hm$'),
            'type' => 1
        ]);

        // get each item in permissions array and create permissions
        foreach ($this->permissions as $p) {
            Permission::create(['name' => $p]);
        }

        // creating and assigning permission of admin role
        $admin = Role::create(['name' => 'admin']);

        $admin->givePermissionTo(
            'view patients',
            'create patients',
            'edit patients',
            'delete patients',
            'view doctors',
            'create doctors',
            'edit doctors',
            'delete doctors',
            'view appointments',
            'view records',
        );

        $adminAccount->assignRole($admin);

        $doctor = Role::create(['name' => 'doctor']);
        $doctor->givePermissionTo(
            'view patients',
            'edit patients',
            'view records',
            'create records',
            'edit records',
            'edit doctors',
            'view doctors',
            'view appointments',
            'edit appointments',
            'delete appointments',
        );

        $patient = Role::create(['name' => 'patient']);
        $patient->givePermissionTo(
            'view doctors',
            'view records',
            'create patients',
            'create appointments',
            'view appointments',
            'delete appointments',
        );

        // //creating dummy patient account and patient details
        // Patient::create([
        //     "name" => "Test patient",
        //     "email" => "dolorcharlee55@gmail.com",
        //     "address" => "B6 L6 Phase 3 Andrea Paz Barandal Calamba City",
        //     "birthday" => "2000-01-01",
        //     "age" => 24,
        //     "marital_status" => "Single",
        //     "contact_number" => "09666807016",
        //     "blood_type" => "B+",
        //     "weight" => 57,
        //     "height" => 170
        // ]);

        // $patient1account = User::create([
        //     "name" => "Test patient",
        //     "email" => "dolorcharlee55@gmail.com",
        //     "password" => Hash::make("hms_password2024"),
        //     "type" => 3,
        //     "details_id" => 1
        // ]);

        // $patient1account->assignRole('patient');

        // // creating dummy doctor account and doctor details
        // Doctor::create([
        //     "name" => "Dr. Test Testing",
        //     "email" => "testtesting@gmail.com",
        //     "address" => "testing address",
        //     "phone_number" => "09666807016",
        //     "medical_license" => "mdr",
        //     "gender" => "male",
        //     "medical_school_graduated" => "Test med school",
        //     "year_graduated" => "2000",
        //     "specialties" => "test test test",
        //     "career_summary" => "Buffalo buffalo Buffalo buffalo buffalo buffalo Buffalo buffalo",
        // ]);

        // $doctorAccount = User::create([
        //     "name" => "Dr. Test Testing",
        //     "email" => "testtesting@gmail.com",
        //     "password" => Hash::make("doc2024_hmspassword"),
        //     "type" => 2,
        //     "details_id" => 1
        // ]);

        // $doctorAccount->assignRole('doctor');

    }
}
