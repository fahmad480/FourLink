<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemParameter;

class SystemParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parameters = [
            [
                'code' => 'public_url',
                'value' => 'https://yourapp.com',
                'description' => 'Public URL for external/public link access. If use_public_url is enabled, public links will use this domain.',
            ],
            [
                'code' => 'use_public_url',
                'value' => '0',
                'description' => 'Enable public URL for external links (1) or use application URL from config (0)',
            ],
            [
                'code' => 'app_name',
                'value' => 'FourLink',
                'description' => 'Application name displayed throughout the application',
            ],
        ];

        foreach ($parameters as $parameter) {
            SystemParameter::updateOrCreate(
                ['code' => $parameter['code']],
                [
                    'value' => $parameter['value'],
                    'description' => $parameter['description'],
                ]
            );
        }
    }
}
