<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="{{ $countdown_seconds }};url={{ $redirect_url }}">
    <title>Payment Successful</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .success-icon { color: #4CAF50; font-size: 72px; }
        .booking-ref { font-size: 24px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="success-icon">✓</div>
    <h1>Payment Successful!</h1>

    <div class="booking-ref">
        Booking Reference: <strong>{{ $booking->reference_number }}</strong>
    </div>

    <p>You will be redirected back to the app in <span id="countdown">{{ $countdown_seconds }}</span> seconds...</p>

    <p>
        <a href="{{ $redirect_url }}">Click here if you are not redirected automatically</a>
    </p>

    <script>
        // Countdown timer
        let seconds = {{ $countdown_seconds }};
        const countdownEl = document.getElementById('countdown');

        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = "{{ $redirect_url }}";
            }
        }, 1000);
    </script>
</body>
</html>
