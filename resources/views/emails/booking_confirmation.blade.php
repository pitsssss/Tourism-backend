<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flight Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 150px; }
        .booking-ref {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        .section { margin-bottom: 25px; }
        .section-title {
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            color: #2c3e50;
        }
        .flight-info { display: flex; justify-content: space-between; }
        .traveler-list { margin-top: 15px; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Tourism Company" class="logo">
            <h1>Your Flight Booking is Confirmed</h1>
        </div>

        <div class="booking-ref">
            <h2>Booking Reference: <strong>{{ $booking->reference_number }}</strong></h2>
            <p>Booking Date: {{ $booking->created_at->format('d M Y - h:i A') }}</p>
        </div>

        <div class="section">
            <h2 class="section-title">Flight Details</h2>
            <div class="flight-info">
                <div>
                    <h3>Departure</h3>
                    <p>{{ $flightDetails['departure']['airport'] }}</p>
                    <p>{{ $flightDetails['departure']['date'] }}</p>
                    <p>{{ $flightDetails['departure']['time'] }}</p>
                    @if($flightDetails['departure']['terminal'])
                        <p>Terminal: {{ $flightDetails['departure']['terminal'] }}</p>
                    @endif
                </div>
                <div>
                    <h3>Arrival</h3>
                    <p>{{ $flightDetails['arrival']['airport'] }}</p>
                    <p>{{ $flightDetails['arrival']['date'] }}</p>
                    <p>{{ $flightDetails['arrival']['time'] }}</p>
                    @if($flightDetails['arrival']['terminal'])
                        <p>Terminal: {{ $flightDetails['arrival']['terminal'] }}</p>
                    @endif
                </div>
            </div>
            <p>Flight Duration: {{ $flightDetails['duration'] }}</p>
            <p>Airline: {{ $flightDetails['airline'] }} (Flight Number: {{ $flightDetails['flightNumber'] }})</p>
            <p>Class: {{ $flightDetails['class'] }}</p>
        </div>

        <div class="section">
            <h2 class="section-title">Passengers</h2>
            <div class="traveler-list">
                @foreach($travelers as $traveler)
                <div style="margin-bottom: 15px;">
                    <h3>{{ $traveler['name'] }}</h3>
                    <p>Passport Number: {{ $traveler['passport'] }}</p>
                    <p>Nationality: {{ $traveler['nationality'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Payment Information</h2>
            <p>Amount Paid: {{ $booking->payment_amount }} {{ $booking->payment_currency }}</p>
            <p>Payment Status: Paid Successfully</p>
            <p>Payment Method: Credit Card</p>
        </div>

        <div class="section">
            <h2 class="section-title">Contact Information</h2>
            <p>Email: {{ $contact['email'] }}</p>
            <p>Phone: +{{ $contact['countryCode'] }} {{ $contact['phone'] }}</p>
        </div>

        <div class="footer">
            <p>Thank you for choosing Syrian Tourism Company</p>
            <p>For any inquiries, please contact: +963 11 1234567</p>
            <p>Or email us at: info@syriatourism.com</p>
        </div>
    </div>
</body>
</html>
