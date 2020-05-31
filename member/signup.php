<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Register</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 offset-md-4 form-div">
                    <form action="signup.php" method="post">
                        <h3 class="text-center">Register</h3>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control form-control-lg">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg">
                        </div>

                        <div class="form-group">
                            <label for="passwordConf">Confirm Password</label>
                            <input type="password" name="passwordConf" class="form-control form-control-lg">
                        </div>

                        <div class="form-group">
                            <button type="submit" name="signup-btn" class="btn btn-primary btn-block btn-lg">Sign Up</button>
                        </div>
                        <p class="text-center">Already a member? <a href="login.php">Sign in</a></p>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>