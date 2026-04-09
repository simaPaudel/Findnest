<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['name' => 'WiFi', 'description' => 'High-speed wireless internet'],
            ['name' => 'AC', 'description' => 'Air conditioning'],
            ['name' => 'Heater', 'description' => 'Heating system'],
            ['name' => 'TV', 'description' => 'Television'],
            ['name' => 'Refrigerator', 'description' => 'Kitchen refrigerator'],
            ['name' => 'Washing Machine', 'description' => 'Automatic washing machine'],
            ['name' => 'Microwave', 'description' => 'Microwave oven'],
            ['name' => 'Stove', 'description' => 'Gas or electric stove'],
            ['name' => 'Geyser', 'description' => 'Hot water heater'],
            ['name' => 'Balcony', 'description' => 'Private or shared balcony'],
            ['name' => 'Parking', 'description' => 'Parking space available'],
            ['name' => 'Garden', 'description' => 'Garden space'],
            ['name' => 'Security', 'description' => '24/7 security system'],
            ['name' => 'Backup Power', 'description' => 'Generator or UPS backup'],
            ['name' => 'Lift', 'description' => 'Elevator access'],
            ['name' => 'CCTV', 'description' => 'Closed-circuit television security'],
            ['name' => 'Common Area', 'description' => 'Shared living or recreation area'],
            ['name' => 'Kitchen Utilities', 'description' => 'Shared kitchen with utensils'],
            ['name' => 'Furnished', 'description' => 'Furniture provided'],
            ['name' => 'Pet Friendly', 'description' => 'Pets are allowed'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($amenity['name'])],
                $amenity
            );
        }
    }
}
