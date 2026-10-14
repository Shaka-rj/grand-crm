<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ishga kelishni tasdiqlash | Grand CRM</title>

    <link rel="stylesheet" href="{{ asset('css/employee/attendance.css') }}">
</head>
<body>

<div
    class="attendance-card"
    id="attendanceCard"
    data-office-lat="{{ $officeLatitude ?? '' }}"
    data-office-lng="{{ $officeLongitude ?? '' }}"
    data-radius="{{ $radius }}"
>
    <div class="attendance-heading">
        <h1>Ishga kelishni tasdiqlash</h1>
        <p>Rasm va joriy lokatsiyani kiriting.</p>
    </div>

    @if(session('attendance_result'))
        <div class="notice notice-warning">
            {{ session('attendance_result') }}
        </div>
    @endif

    @if($errors->any())
        <div class="notice notice-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form
        id="attendanceForm"
        method="POST"
        action="{{ route('employee.attendance.store') }}"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="camera-box">
            <video id="cameraPreview" autoplay playsinline muted></video>
            <img id="photoPreview" class="photo-preview" alt="Selfie rasmi" hidden>

            <canvas id="photoCanvas" hidden></canvas>

            <button type="button" id="captureButton">
                Selfie olish
            </button>

            <button type="button" id="retryCameraButton" hidden>
                Kamerani qayta ishga tushirish
            </button>

            <button type="button" id="fallbackCameraButton" hidden>
                Standart kamerani ochish
            </button>

            <input
                type="file"
                id="fallbackPhoto"
                accept="image/*"
                capture="user"
                hidden
            >

            <input type="hidden" name="photo" id="photo">
            <p id="cameraStatus">Kamera ishga tushirilmoqda...</p>
        </div>

        <div class="location-box" id="locationStatus">
            <span class="location-dot"></span>
            <span>Lokatsiya yuborish vaqtida olinadi.</span>
        </div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <input type="hidden" name="accuracy_meters" id="accuracyMeters">

        <div id="reasonGroup" class="reason-group" hidden>
            <label for="reason">Ofisdan tashqarida bo‘lish sababi</label>
            <textarea
                name="reason"
                id="reason"
                rows="4"
                maxlength="2000"
                placeholder="Sababini yozing..."
            >{{ old('reason') }}</textarea>
        </div>

        <button type="submit" id="submitButton">
            Ishga kelishni tasdiqlash
        </button>

        <p class="privacy-note">
            Rasm va lokatsiya ishga kelish qaydini tekshirish uchun saqlanadi.
        </p>
    </form>
</div>

<script src="{{ asset('js/employee/attendance.js') }}"></script>
</body>
</html>