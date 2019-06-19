<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .wrapper {
            align-items: center;
            display: flex;
            justify-content: center;
            position: relative;
            height: 100vh;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content">
            <div class="title">
                {{ $message ?? 'Redirecting to payment...' }}
            </div>
        </div>
    </div>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ config('services.stripe.key') }}");
        stripe.redirectToCheckout({
            sessionId: '{{ $session_id }}'
        }).then((result) => {
            console.log(result);
        });
    </script>
</body>
</html>