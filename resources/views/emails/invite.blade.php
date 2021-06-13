<table width="100%" style="max-width: 640px; margin: auto;padding-bottom: 30px;">
    <tbody>
      
        <tr>
            <td>
                <h3 style="font-size:30px; font-weight:600; color: #393a3c; font-family:Arial,sans-serif;text-align:center;margin-bottom: 20px;">{{ config('app.name') }}</h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="font-size:17px; font-weight:600; color:#393a3c; font-family:Arial,sans-serif;margin: 0 0 10px;">Hello Dear,</h4>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-size:17px; color:#393a3c; font-family:Arial,sans-serif;">{{$text}}</p>
            </td>
        </tr>
        <tr>
            <td>
                <br>
                <br>
                <br>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; font-weight:400; font-size:17px; color:#393a3c; font-family:Arial,sans-serif; margin-bottom:40px;">
                Regards,<br>
                <strong>{{ config('app.name') }}</strong>
            </td>
        </tr>
    </tbody>
</table>