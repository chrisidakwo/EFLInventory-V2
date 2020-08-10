<html lang="en">
    <head>
        <meta charset="utf-8">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/theme/colors/blue.css') }}" id="theme" rel="stylesheet">
        <title>Purchase Receipt</title>
    </head>

    <body style="padding: 0 15px;">
        <h2 class="text-center text-uppercase">EmiFoodLovers Market</h2>
        <address class="text-center">
            21 Akpajo Road, Eleme<br>Port-Harcourt, Rivers
        </address>
        <hr>
        <p>
            <label>Date:&nbsp;</label><span><?php use Carbon\Carbon;use Ramsey\Uuid\Uuid;echo Carbon::now("Africa/Lagos")->toDayDateTimeString(); ?></span>
            <label>Transaction #:&nbsp;</label><?php echo Uuid::uuid4()->toString(); ?>
        </p>
        <hr>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price * $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
