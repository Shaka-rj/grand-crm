<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    public function show()
    {
        return view('employee.attendance.checkin', [
            'officeLatitude' => config('attendance.office_latitude'),
            'officeLongitude' => config('attendance.office_longitude'),
            'radius' => config('attendance.radius_meters', 100),
        ]);
    }

    public function list()
    {
        $attendances = AttendanceRecord::where('user_id', auth()->id())
            ->latest()
            ->paginate(50);

        return view('employee.attendance.list', compact('attendances'));
    }

    public function store(Request $request)
    {

         // 60 kundan eski davomat rasmlarini o'chirish
        \App\Models\AttendanceRecord::where('created_at', '<=', now()->subDays(60))
            ->whereNotNull('photo_path')
            ->chunkById(200, function ($records) {
                $storage = \Illuminate\Support\Facades\Storage::disk('public');

                foreach ($records as $record) {
                    if ($storage->exists($record->photo_path)) {
                        $storage->delete($record->photo_path);
                    }

                    $record->update([
                        'photo_path' => null,
                    ]);
                }
            });

    
        $data = $request->validate([
            'photo' => ['required', 'string', 'max:7000000'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy_meters' => ['nullable', 'numeric', 'min:0'],
            'reason' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = $request->user();

    // Oxirgi davomatdan kamida 30 daqiqa o'tishi kerak
        $interval = 1;//(int) config('attendance.interval_minutes', 30);

        $lastAttendance = \App\Models\AttendanceRecord::where('user_id', $user->id)->latest('created_at')->first();

        if (
            $lastAttendance &&
            $lastAttendance->created_at->gt(now()->subMinutes($interval))
        ) {
            $nextTime = $lastAttendance->created_at->copy()
            ->addMinutes($interval)
            ->format('H:i');

            return back()->withErrors([
                'attendance' => "Keyingi davomatni soat {$nextTime} dan keyin yuborishingiz mumkin.",
            ])->withInput($request->except('photo'));
        }

    // Ofis masofasini server tomonida hisoblash
        $officeLat = config('attendance.office_latitude');
        $officeLng = config('attendance.office_longitude');
        $radius = (int) config('attendance.radius_meters', 100);

        $officeConfigured = is_numeric($officeLat) && is_numeric($officeLng);

        $distance = null;
        $isWithinOffice = null;

        if ($officeConfigured) {
            $distance = $this->distanceInMeters(
                (float) $data['latitude'],
                (float) $data['longitude'],
                (float) $officeLat,
                (float) $officeLng
            );

            $isWithinOffice = $distance <= $radius;
        }

        // Selfie ma'lumotini tekshirish
        if (!preg_match(
            '/^data:image\/(jpeg|jpg|png|webp);base64,([A-Za-z0-9+\/=]+)$/',
            $data['photo'],
            $matches
        )) {
            return back()->withErrors([
                'photo' => 'Selfie rasmi noto‘g‘ri formatda.',
            ])->withInput($request->except('photo'));
        }

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $photoBytes = base64_decode($matches[2], true);

        if (
            $photoBytes === false ||
            strlen($photoBytes) === 0 ||
            strlen($photoBytes) > 5 * 1024 * 1024 ||
            getimagesizefromstring($photoBytes) === false
        ) {
            return back()->withErrors([
                'photo' => 'Rasm noto‘g‘ri yoki hajmi 5 MB dan oshgan.',
            ])->withInput($request->except('photo'));
        }

        // Rasmni public storage ichida saqlash
        $photoPath = 'attendance/' . \Illuminate\Support\Str::uuid()
            . '.' . $extension;

        $storage = \Illuminate\Support\Facades\Storage::disk('public');

        if (!$storage->put($photoPath, $photoBytes)) {
            return back()->withErrors([
                'photo' => 'Rasmni saqlashda xatolik yuz berdi.',
            ])->withInput($request->except('photo'));
        }

        try {
            \App\Models\AttendanceRecord::create([
                'user_id' => $user->id,
                'photo_path' => $photoPath,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'accuracy_meters' => $data['accuracy_meters'] ?? null,
                'distance_meters' => $distance === null
                    ? null
                    : (int) round($distance),
                'is_within_office' => $isWithinOffice,
                'reason' => $isWithinOffice ? null : trim($data['reason']),
            ]);
        } catch (\Throwable $e) {
            $storage->delete($photoPath);
            throw $e;
        }

        return redirect()->route('employee.attendance.list')
        ->with('success', 'Davomat muvaffaqiyatli qayd etildi.');
    }

    private function distanceInMeters(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
        + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * sin($dLng / 2) ** 2;

        return $earthRadius * 2 * atan2(
            sqrt($a),
            sqrt(max(0, 1 - $a))
        );
    }
}