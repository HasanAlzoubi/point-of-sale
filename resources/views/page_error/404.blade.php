<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('error_page/css/main.css') }}">
    <link rel="stylesheet" href="{{asset('error_page/css/normalize.css')}}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,500;0,600;0,700;1,500&display=swap"
        rel="stylesheet">
    <title>Not Found</title>
</head>

<body>


    <div class="error-page">
        <div class="info">
            <div class="text">
                <p>403</p>
                <span>Sorry, you are forbidden from accessing this page</span>
                <a href="#">GO HOME</a>
            </div>

        </div>

        <img src="{{asset('error_page/images/404.svg')}}" alt="">

    </div>

</body>

</html>
