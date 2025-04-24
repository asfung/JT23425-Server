<?php

namespace Database\Seeders;

use Ramsey\Uuid\Uuid;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // $faker = Faker::create();

        // $unitKerjas = UnitKerja::all(); 
        // $jabatans = Jabatan::all(); 

        // for ($i = 0; $i < 10; $i++) {
        //     $unitKerja = $unitKerjas->random();
        //     $jabatan = $jabatans->where('unit_kerja_id', $unitKerja->id)->random();

        //     Pegawai::create([
        //         // 'no' => $faker->randomNumber(), 
        //         'nip' => '12130' . $faker->unique()->randomNumber(9), 
        //         'nama' => $faker->name, 
        //         'tempat_lahir' => $faker->city, 
        //         'tgl_lahir' => $faker->date('Y-m-d', '1968-03-15'),
        //         'alamat' => $faker->address,
        //         'jenis_kelamin' => $faker->randomElement(['L', 'P']),
        //         'gol' => $faker->randomElement(['A', 'B', 'AB', 'O']), 
        //         'eselon' => 'Eselon ' . $faker->numberBetween(1, 3), 
        //         'jabatan' => $jabatan->name, 
        //         'tempat_tugas' => $faker->city,
        //         'agama' => $faker->randomElement(['Islam', 'Christian', 'Buddhist', 'Hindu']),
        //         'unit_kerja_id' => $unitKerja->id, 
        //         'no_hp' => $faker->phoneNumber, 
        //         'npwp' => 'NPWP' . $faker->unique()->randomNumber(9), 
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }


        $faker = Faker::create('id_ID');

        $jabatans = [
            1 => ['Software Engineer', 'Senior Network Test Engineer', 'Senior Network Engineer - Deployments'], 
            2 => ['Manager Accounting', 'Manager Finance'],
            3 => ['Software Engineer', 'Senior Network Test Engineer', 'Senior Network Engineer - Deployments'], 
            4 => ['Senior Staff Production', 'Production Engineer Specialist'],
            5 => [],
            6 => ['Senior Software Engineer', 'Manager, Software Engineering', 'Software Advanced Developer'], 
            7 => ['Solutions Architect, Energy', 'Senior Solutions Architect, Retail', 'Senior Solutions Architect, OEM AI', 'Solutions Architect, Financial Services'], // Sales
            8 => ['Senior Benefits Specialist', 'HR Manager', 'Learning and Development Specialist', 'HR Business Partner'], 
            9 => ['Global Business Development Lead, Healthcare and Life Sciences Ecosystem'],
        ];

        $employees = [];
        for ($i = 1; $i <= 10; $i++) {
            $unit_kerja_id = $faker->randomElement([1, 2, 3, 4, 6, 7, 8, 9]); 
            $available_jabatans = $jabatans[$unit_kerja_id] ?? ['Staff'];
            $jabatan = $faker->randomElement($available_jabatans);

            $employees[] = [
                'id' => Uuid::uuid4()->toString(),
                // 'no' => $i,
                'nip' => $faker->unique()->numerify('198############'), 
                'nama' => $faker->name,
                'tempat_lahir' => $faker->city,
                'tgl_lahir' => $faker->dateTimeBetween('-50 years', '-25 years')->format('Y-m-d'),
                'alamat' => $faker->address,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'gol' => $faker->randomElement(['I/a', 'II/b', 'III/c', 'IV/d', null]),
                // 'eselon' => $faker->randomElement(['Eselon I', 'Eselon II', 'Eselon III', null]),
                'eselon' => $faker->randomElement(['I', 'II', 'III', null]),
                'jabatan' => $jabatan,
                'tempat_tugas' => $faker->city,
                'agama' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', null]),
                'unit_kerja_id' => $unit_kerja_id,
                'no_hp' => $faker->phoneNumber,
                'npwp' => $faker->unique()->numerify('##.###.###.#-###.###'), 
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert data into pegawais table
        DB::table('pegawais')->insert($employees);

    }
}
