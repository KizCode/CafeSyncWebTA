<?php

namespace Database\Seeders;

use App\Models\QueueSetting;
use Illuminate\Database\Seeder;

class QueueSettingSeeder extends Seeder
{
    public function run(): void
    {
        QueueSetting::current();
    }
}
