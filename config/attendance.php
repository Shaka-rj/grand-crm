<?php

return [
    'office_latitude' => env('ATTENDANCE_OFFICE_LATITUDE', 41.2850370),
    'office_longitude' => env('ATTENDANCE_OFFICE_LONGITUDE', 69.2144556),

    'radius_meters' => (int) env('ATTENDANCE_RADIUS_METERS', 100),

    'interval_minutes' => (int) env('ATTENDANCE_INTERVAL_MINUTES', 30),
];