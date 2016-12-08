<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Mediku</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mediku2.css" rel="stylesheet">


</head>
<body>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <div class="container">
        <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">

                    <a class="navbar-brand" href="#">Mediku</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <div class="mediku">
            <h1 id="margin_top">Welcome!</h1>

            <h2 id="welcome_msg">We'll Help You <br> Find Nearest Medical Facility</h2>

            <div class="container">
                <div class="row">
                    <div>
                        <div id="imaginary_container">
                            <div class="input-group stylish-input-group">
                                <form action="map.php" method="post">
                                <input type="text" class="form-control" id ="address" name="address" placeholder="Fill Your Address here...">
                                    <span class="input-group-addon">
                                        <button type="submit">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>  
                                    </span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</body>
</html>