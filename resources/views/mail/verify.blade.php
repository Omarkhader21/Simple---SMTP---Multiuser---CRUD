<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Account verification</title>
</head>

<body>
    <p>
        Hallo : {{ $details['name'] }}
    </p>
    <br>
    <p>
        Here is your data
    </p>

    <table>
        <tr>
            <td>Full Name</td>
            <td>:</td>
            <td>{{ $details['name'] }}</td>
        </tr>
        <tr>
            <td>Role</td>
            <td>:</td>
            <td>{{ $details['role'] }}</td>
        </tr>
        <tr>
            <td>Website</td>
            <td>:</td>
            <td>{{ $details['website'] }}</td>
        </tr>
        <tr>
            <td>Date Registration</td>
            <td>:</td>
            <td>{{ $details['datetime'] }}</td>
        </tr>
        <br><br><br>
        <tr>
            <td>
                <h3>Click below to verify your account : </h3>
                <a href="{{ $details['url'] }}"
                    style="text-decoration: none; color: rgb(255,255,255); padding: 9px; background-color:blue; font:bold; border-radius: 20%">Verifiy</a>
            </td>
            <br><br><br>
            <p>
                Copyright @ {{ now()->year }} | OmarKhader21
            </p>
        </tr>
    </table>
</body>

</html>
