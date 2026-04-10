<?php
// Quick debug script to check if per_room properties exist
require_once 'bootstrap/app.php';

$app = new \Illuminate\Foundation\Application(dirname(__FILE__));
$app->bind('path.base', __DIR__);

$kernel =  $app->make(\Illuminate\Contracts\Http\Kernel::class);

\Illuminate\Support\Facades\DB::useDefaultConnection('mysql');

// Check recent properties
$properties = \DB::table('properties')
    ->where('rental_mode', 'per_room')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

echo "Per-room properties in database:\n";
echo "Count: " . count($properties) . "\n\n";

foreach ($properties as $p) {
    echo "ID: {$p->id}, Title: {$p->title}, Created: {$p->created_at}, Owner: {$p->owner_id}\n";
    
    // Count rooms for this property
    $roomCount = \DB::table('rooms')->where('property_id', $p->id)->count();
    echo "  -> Rooms: {$roomCount}\n\n";
}

// Check all properties with creation time in last 10 minutes
echo "\n\nAll properties created in last 30 minutes:\n";
$recent = \DB::table('properties')
    ->where('created_at', '>', \Carbon\Carbon::now()->subMinutes(30))
    ->orderBy('created_at', 'desc')
    ->get();

echo "Count: " . count($recent) . "\n\n";
foreach ($recent as $p) {
    echo "ID: {$p->id}, Title: {$p->title}, Mode: {$p->rental_mode}, Created: {$p->created_at}\n";
}
