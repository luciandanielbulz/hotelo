<?php

namespace App\Modules\Booking\Commands;

use App\Modules\Booking\Models\Room;
use Illuminate\Console\Command;

class CreateTestRoom extends Command
{
    protected $signature = 'booking:create-test-room';
    protected $description = 'Create a test room for the booking module';

    public function handle()
    {
        $room = Room::create([
            'name' => 'Zimmer 101',
            'description' => 'Gemütliches Einzelzimmer mit Blick auf die Berge',
            'price_per_night' => 89.00,
            'max_guests' => 2,
            'features' => ['WiFi', 'TV', 'Bad', 'Balkon'],
            'amenities' => [
                'Kostenloses WiFi',
                'Flachbild-TV',
                'Privates Badezimmer',
                'Balkon mit Aussicht',
            ],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->info("Test-Zimmer erstellt: {$room->name} (ID: {$room->id})");
        $this->info("Zugriff: /booking/rooms");
        
        return 0;
    }
}
