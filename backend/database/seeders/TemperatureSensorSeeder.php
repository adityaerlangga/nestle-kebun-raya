<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TemperatureSensor;
use Carbon\Carbon;

class TemperatureSensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sensorNames = ['Sensor A'];
        
        // Generate data for the last 24 hours
        $startTime = Carbon::now()->subHours(24);
        
        foreach ($sensorNames as $sensorName) {
            $currentTime = $startTime->copy();
            
            // Generate a reading every 2 hours for the last 24 hours
            for ($i = 0; $i < 12; $i++) {
                // Generate realistic temperature values between 20-35°C with some variation
                $baseTemp = 25 + ($sensorName === 'Sensor A' ? 2 : ($sensorName === 'Sensor B' ? -1 : 0));
                $variation = rand(-3, 3);
                $temperature = $baseTemp + $variation + (sin($i * 0.5) * 2); // Add some sine wave variation
                
                TemperatureSensor::create([
                    'name' => $sensorName,
                    'value' => round($temperature, 2),
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime
                ]);
                
                $currentTime->addHours(2);
            }
        }
    }
}
