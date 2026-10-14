document.addEventListener('DOMContentLoaded', () => {
    const card = document.getElementById('attendanceCard');
    const form = document.getElementById('attendanceForm');

    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('photoCanvas');
    const photo = document.getElementById('photo');
    const preview = document.getElementById('photoPreview');

    const captureButton = document.getElementById('captureButton');
    const retryButton = document.getElementById('retryCameraButton');
    const fallbackButton = document.getElementById('fallbackCameraButton');
    const fallbackPhoto = document.getElementById('fallbackPhoto');
    const cameraStatus = document.getElementById('cameraStatus');

    const reasonGroup = document.getElementById('reasonGroup');
    const reason = document.getElementById('reason');
    const button = document.getElementById('submitButton');
    const locationStatus = document.getElementById('locationStatus');

    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const accuracyInput = document.getElementById('accuracyMeters');

    const officeLat = Number(card.dataset.officeLat);
    const officeLng = Number(card.dataset.officeLng);
    const radius = Number(card.dataset.radius || 100);

    const officeConfigured =
        card.dataset.officeLat.trim() !== '' &&
        card.dataset.officeLng.trim() !== '' &&
        Number.isFinite(officeLat) &&
        Number.isFinite(officeLng);

    let stream = null;
    let submitting = false;

    // Kamera
    async function startCamera() {
        stopCamera();

        if (!navigator.mediaDevices?.getUserMedia) {
            showCameraFallback('Brauzer kamerani qo‘llab-quvvatlamaydi.');
            return;
        }

        cameraStatus.textContent = 'Kamera ishga tushirilmoqda...';
        captureButton.hidden = false;
        captureButton.textContent = 'Selfie olish';

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: { ideal: 'user' },
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            });

            video.srcObject = stream;
            video.hidden = false;
            preview.hidden = true;

            await video.play();

            captureButton.hidden = false;
            retryButton.hidden = true;
            fallbackButton.hidden = true;

            cameraStatus.textContent = 'Selfie olish uchun tugmani bosing.';
        } catch (error) {
            showCameraFallback(
                'Kamerani ochib bo‘lmadi. Standart kameradan foydalaning.'
            );
        }
    }

    function showCameraFallback(message) {
        stopCamera();

        video.hidden = true;
        captureButton.hidden = true;
        retryButton.hidden = false;
        fallbackButton.hidden = false;

        cameraStatus.textContent = message;
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }

        if (video) {
            video.srcObject = null;
        }
    }

    // Kameradan selfie olish
    captureButton.addEventListener('click', async () => {
        // Olingan suratni qayta olish
        if (photo.value && video.hidden) {
            photo.value = '';
            preview.hidden = true;
            captureButton.textContent = 'Selfie olish';

            await startCamera();
            return;
        }

        if (!video.videoWidth || !video.videoHeight) {
            cameraStatus.textContent = 'Kamera hali tayyor emas.';
            return;
        }

        const maxWidth = 720;
        const maxHeight = 960;

        const scale = Math.min(
            maxWidth / video.videoWidth,
            maxHeight / video.videoHeight,
            1
        );

        canvas.width = Math.round(video.videoWidth * scale);
        canvas.height = Math.round(video.videoHeight * scale);

        const context = canvas.getContext('2d');

        context.drawImage(
            video,
            0,
            0,
            canvas.width,
            canvas.height
        );

        photo.value = canvas.toDataURL('image/jpeg', 0.75);

        preview.src = photo.value;
        preview.hidden = false;

        stopCamera();
        video.hidden = true;

        captureButton.hidden = false;
        captureButton.textContent = 'Qayta suratga olish';

        retryButton.hidden = true;
        fallbackButton.hidden = true;

        cameraStatus.textContent = 'Selfie olindi.';
    });

    retryButton.addEventListener('click', startCamera);

    // Kamera ishlamasa, qurilmaning standart kamerasi
    fallbackButton.addEventListener('click', () => {
        fallbackPhoto.click();
    });

    fallbackPhoto.addEventListener('change', () => {
        const file = fallbackPhoto.files?.[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Rasm faylini tanlang.');
            fallbackPhoto.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = () => {
            photo.value = reader.result;
            preview.src = reader.result;
            preview.hidden = false;

            video.hidden = true;
            captureButton.hidden = true;

            cameraStatus.textContent = 'Selfie rasmi tayyor.';
        };

        reader.onerror = () => {
            cameraStatus.textContent = 'Rasmni o‘qib bo‘lmadi.';
        };

        reader.readAsDataURL(file);
    });

    function distanceInMeters(lat1, lng1, lat2, lng2) {
        const toRadians = value => value * Math.PI / 180;
        const earthRadius = 6371000;

        const dLat = toRadians(lat2 - lat1);
        const dLng = toRadians(lng2 - lng1);

        const a =
            Math.sin(dLat / 2) ** 2 +
            Math.cos(toRadians(lat1)) *
            Math.cos(toRadians(lat2)) *
            Math.sin(dLng / 2) ** 2;

        return earthRadius * 2 * Math.atan2(
            Math.sqrt(a),
            Math.sqrt(Math.max(0, 1 - a))
        );
    }

    function showReason(required) {
        reasonGroup.hidden = !required;
        reason.required = required;

        if (!required) {
            reason.setCustomValidity('');
        }
    }

    function setLocationStatus(message) {
        locationStatus.querySelector('span:last-child').textContent = message;
    }

    // Davomatni yuborish
    form.addEventListener('submit', event => {
        event.preventDefault();

        if (submitting) return;

        if (!photo.value) {
            alert('Avval selfie oling.');
            captureButton.hidden = video.hidden;
            cameraStatus.textContent = 'Davom etish uchun selfie oling.';
            return;
        }

        if (!navigator.geolocation) {
            setLocationStatus('Brauzer lokatsiyani aniqlashni qo‘llab-quvvatlamaydi.');
            alert('Lokatsiyani aniqlab bo‘lmadi.');
            return;
        }

        button.disabled = true;
        button.textContent = 'Lokatsiya aniqlanmoqda...';
        setLocationStatus('GPS ma’lumoti olinmoqda...');

        navigator.geolocation.getCurrentPosition(
            position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;

                latitudeInput.value = lat;
                longitudeInput.value = lng;
                accuracyInput.value = accuracy;

                let distance = null;

                if (officeConfigured) {
                    distance = distanceInMeters(
                        lat,
                        lng,
                        officeLat,
                        officeLng
                    );
                }

                const inside = distance !== null && distance <= radius;
                const mustExplain = !officeConfigured || !inside;

                showReason(mustExplain);

                if (mustExplain && !reason.value.trim()) {
                    setLocationStatus(
                        !officeConfigured
                            ? 'Ofis koordinatalari sozlanmagan. Sababini yozing.'
                            : `Ofis radiusidan tashqaridasiz (${Math.round(distance)} m). Sababini yozing.`
                    );

                    button.disabled = false;
                    button.textContent = 'Ishga kelishni tasdiqlash';
                    reason.focus();
                    return;
                }

                setLocationStatus(
                    `Lokatsiya olindi. GPS aniqligi: taxminan ${Math.round(accuracy)} metr.`
                );

                submitting = true;
                button.textContent = 'Ma’lumotlar saqlanmoqda...';

                stopCamera();
                HTMLFormElement.prototype.submit.call(form);
            },
            error => {
                button.disabled = false;
                button.textContent = 'Ishga kelishni tasdiqlash';

                const messages = {
                    1: 'Lokatsiyaga ruxsat bering.',
                    2: 'Lokatsiyani aniqlab bo‘lmadi. GPSni yoqing.',
                    3: 'GPS olish vaqti tugadi. Qayta urinib ko‘ring.'
                };

                setLocationStatus(
                    messages[error.code] || 'Lokatsiya olishda xatolik.'
                );
            },
            {
                enableHighAccuracy: true,
                timeout: 20000,
                maximumAge: 0
            }
        );
    });

    if (reason.value.trim()) {
        showReason(true);
    }

    window.addEventListener('pagehide', stopCamera);

    startCamera();
});