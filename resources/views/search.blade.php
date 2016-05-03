<!DOCTYPE html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.min.css">
        <link rel="stylesheet" href="css/jquery-ui.theme.min.css">
        <script src="js/jquery-ui.min.js"></script>

        <script src="js/moment.js"></script>
        <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        </script>

        
        <script src="js/highcharts/highcharts.js"></script>
        <script src="js/main.js"></script>


        <link href="https://fonts.googleapis.com/css?family=Roboto:700,400,100" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="css/normalize.css">
        <link rel="stylesheet" type="text/css" href="css/skeleton.css">
        <link rel="stylesheet" type="text/css" href="css/ring.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">

    </head>
    <body class="home">

        <div class="wrapper">
            <form id="person_form" action>
                <div>
                    <span class="person_wrapper"><input type="text" class="person" value="" placeholder="Search for an actor" /></span>
                </div>
            </form>

            <div class="push"></div>
        </div>
        

        <div class="footer">
            Powered by <a href="https://www.themoviedb.org/documentation/api">TMDB</a> and <a href="http://developer.rottentomatoes.com/">Rotten Tomatoes</a>. Made by <a href="http://twitter.com/iansilber">Ian Silber</a>
        </div>

        <div class="bg"></div>

    </body>
</html>
