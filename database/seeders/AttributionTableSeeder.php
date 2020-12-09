<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attribution')->insert(array(
            array(
              'idEtab' => '0350785N',
              'idEquipe' => 'e001',
              'nombreChambres' => 11,
            ),
            array(
                'idEtab' => '0350785N',
                'idEquipe' => 'e002',
                'nombreChambres' => 9,
            ),
            array(
                'idEtab' => '0350123A',
                'idEquipe' => 'e004',
                'nombreChambres' => 13,
            ),
            array(
                'idEtab' => '0350123A',
                'idEquipe' => 'e005',
                'nombreChambres' => 8,
            ),
            array(
                'idEtab' => '0351234W',
                'idEquipe' => 'e001',
                'nombreChambres' => 3,
            ),
            array(
                'idEtab' => '0351234W',
                'idEquipe' => 'e006',
                'nombreChambres' => 10,
            ),
            array(
                'idEtab' => '0351234W',
                'idEquipe' => 'e007',
                'nombreChambres' => 7,
            ),
          ));
    }
}
