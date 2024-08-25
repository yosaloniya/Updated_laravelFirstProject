<!DOCTYPE html>
<html>

<head>
    <title>THECOZYCREATIONS@GMAIL.COM</title>
</head>

<body>
    <h1>{{ $mailData['title'] }}</h1>
    <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
        <div style="margin:50px auto;width:70%;padding:20px 0">
            <div style="border-bottom:1px solid #eee">
                <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">THE
                    COZYCREATIONS</a>
            </div>
            <p style="font-size:1.1em">Hi,</p>
            <p>Thank you for choosing THE COZYCREATIONS. Use the following OTP to change your Password. OTP is valid for
                5 minutes</p>
            <h2
                style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">
                {{ $mailData['otp'] }}</h2>
            <p style="font-size:0.9em;">Regards,<br />THE COZYCREATIONS</p>
            <hr style="border:none;border-top:1px solid #eee" />
            <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
                <p>THE COZYCREATIONS Inc</p>
                <p>shop no 3, Newta, Near Mahindra Sez, Jaipur, 302029</p>
                <p>India</p>
            </div>
        </div>
    </div>
</body>

</html>
