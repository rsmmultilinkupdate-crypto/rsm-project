<?php

use Illuminate\Database\Seeder;
use App\EmailSetting;

class EmailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing entries
        EmailSetting::truncate();
        
        // Add default email settings
        EmailSetting::create([
            'email' => 'rsmmultilinkenquiry@gmail.com',
            'label' => 'Primary Enquiry Email',
            'type' => 'both',
            'is_active' => 1,
        ]);
        
        EmailSetting::create([
            'email' => 'kumarshivam827@gmail.com',
            'label' => 'Admin Email',
            'type' => 'both',
            'is_active' => 1,
        ]);
        
        echo "Email settings seeded successfully!\n";
    }
}
