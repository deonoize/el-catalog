<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="shadow"></div>
<div class="container d-flex align-items-center vh-100 text-white">
    <div class="col-md-6 col-12 offset-md-3">
        <form method="post">
            <?
            if (isset($error)) {
                ?>
                <div class="alert alert-danger" role="alert">
                    <?=$error?>
                </div>
                <?
            }
            ?>
            Please enter your login and password to registration
            <div class="form-group">
                <label for="inputEmail1">Email address</label>
                <input type="email" required name="login" class="form-control" id="inputEmail1"
                       aria-describedby="emailHelp">
            </div>
            <div class="form-group">
                <label for="inputPassword1">Password</label>
                <div class="position-relative">
                    <input type="password" minlength="6" required name="password1" class="form-control"
                           id="inputPassword1">
                    <a href="#" class="password-control"></a>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword2">Check Password</label>
                <div class="position-relative">
                    <input type="password" minlength="6" required name="password2" class="form-control"
                           id="inputPassword2">
                    <a href="#" class="password-control"></a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Registration</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
<script src="/js/script.js"></script>
</body>
</html>
