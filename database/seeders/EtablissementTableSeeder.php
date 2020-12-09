<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtablissementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('etablissement')->insert(array(
            array(
              'idEtab' => '0350785N',
              'nomEtab' => 'College de Moka',
              'adresseRue' => '2 avenue Aristide Briand BP 6',
              'codePostal' => '35401',
              'ville' => 'Saint-Malo',
              'tel' => '0299206990',
              'adresseElectronique' => null,
              'type' => 1,
              'civiliteResponsable' => 'M.',
              'nomResponsable' => 'Dupont',
              'prenomResponsable' => 'Alain',
              'nombreChambresOffertes' => 20,
            ),
            array(
                'idEtab' => '0350123A',
                'nomEtab' => 'College Lamartine',
                'adresseRue' => '3 avenue des corsaires',
                'codePostal' => '35404',
                'ville' => 'Parame',
                'tel' => '0299561459',
                'adresseElectronique' => null,
                'type' => 1,
                'civiliteResponsable' => 'Mme',
                'nomResponsable' => 'Lefort',
                'prenomResponsable' => 'Anne',
                'nombreChambresOffertes' => 58,
            ),
            array(
                'idEtab' => '0351234W',
                'nomEtab' => 'College Leonard de Vinci',
                'adresseRue' => '2 rue Rabelais',
                'codePostal' => '35418',
                'ville' => 'Saint-Malo',
                'tel' => '0299117474',
                'adresseElectronique' => null,
                'type' => 1,
                'civiliteResponsable' => 'M.',
                'nomResponsable' => 'Durand',
                'prenomResponsable' => 'Pierre',
                'nombreChambresOffertes' => 60,
            ),
            array(
                'idEtab' => '11111111',
                'nomEtab' => 'Centre de rencontres internationales',
                'adresseRue' => '37 avenue du R.P. Umbricht BP 108',
                'codePostal' => '35407',
                'ville' => 'Saint-Malo',
                'tel' => '0299000000',
                'adresseElectronique' => null,
                'type' => 0,
                'civiliteResponsable' => 'M.',
                'nomResponsable' => 'Guenroc',
                'prenomResponsable' => 'Guy',
                'nombreChambresOffertes' => 200,
            ),
          ));
    }
}
