<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text|color|image|boolean|textarea
            $table->string('label');
            $table->string('group')->default('general'); // general|appearance|contact|maintenance
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default settings
        $defaults = [
            // General
            ['key' => 'app_name',        'value' => 'I Care True',        'type' => 'text',    'label' => 'Nama Aplikasi',    'group' => 'general',     'sort_order' => 1],
            ['key' => 'app_tagline',      'value' => 'Komunitas Rohani Kristen', 'type' => 'text', 'label' => 'Tagline',     'group' => 'general',     'sort_order' => 2],
            ['key' => 'app_email',        'value' => '',                   'type' => 'text',    'label' => 'Email Komunitas',  'group' => 'general',     'sort_order' => 3],

            // Appearance
            ['key' => 'logo',             'value' => null,                 'type' => 'image',   'label' => 'Logo',             'group' => 'appearance',  'sort_order' => 1],
            ['key' => 'favicon',          'value' => null,                 'type' => 'image',   'label' => 'Favicon',          'group' => 'appearance',  'sort_order' => 2],
            ['key' => 'hero_banner',      'value' => null,                 'type' => 'image',   'label' => 'Banner Utama',     'group' => 'appearance',  'sort_order' => 3],
            ['key' => 'primary_color',    'value' => '#2563eb',            'type' => 'color',   'label' => 'Warna Primer',     'group' => 'appearance',  'sort_order' => 4],
            ['key' => 'secondary_color',  'value' => '#1d4ed8',            'type' => 'color',   'label' => 'Warna Sekunder',   'group' => 'appearance',  'sort_order' => 5],
            ['key' => 'sidebar_color',    'value' => '#1e293b',            'type' => 'color',   'label' => 'Warna Sidebar',    'group' => 'appearance',  'sort_order' => 6],

            // Contact
            ['key' => 'wa_leader',        'value' => '',                   'type' => 'text',    'label' => 'WhatsApp Leader',  'group' => 'contact',     'sort_order' => 1],
            ['key' => 'wa_co_leader',     'value' => '',                   'type' => 'text',    'label' => 'WhatsApp Co-Leader','group' => 'contact',    'sort_order' => 2],
            ['key' => 'address',          'value' => '',                   'type' => 'textarea','label' => 'Alamat Gereja',    'group' => 'contact',     'sort_order' => 3],
            ['key' => 'maps_embed',       'value' => '',                   'type' => 'textarea','label' => 'Google Maps Embed URL','group' => 'contact', 'sort_order' => 4],

            // Maintenance
            ['key' => 'maintenance_mode', 'value' => '0',                  'type' => 'boolean', 'label' => 'Mode Maintenance',  'group' => 'maintenance', 'sort_order' => 1],
            ['key' => 'maintenance_msg',  'value' => 'Kami sedang melakukan pemeliharaan sistem. Silakan kembali beberapa saat lagi.', 'type' => 'textarea', 'label' => 'Pesan Maintenance', 'group' => 'maintenance', 'sort_order' => 2],
            ['key' => 'maintenance_eta',  'value' => '',                   'type' => 'text',    'label' => 'Estimasi Selesai',  'group' => 'maintenance', 'sort_order' => 3],
        ];

        foreach ($defaults as $setting) {
            \DB::table('app_settings')->insert(array_merge($setting, [
                'created_at' => now(), 'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
