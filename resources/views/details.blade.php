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
    <body>
        <div class="wrapper">
            <form id="person_form">
                <div>
                    <span class="person_wrapper"><input type="text" class="person" value="" placeholder="Search for an actor" /></span>
                </div>
            </form>
            
            <div class="container">
                <div class="stat_center row">
                    <div class="six columns averages">
                        <h3 class="average">Average</h3>
                        <div id="average_critic"><span class="value"></span><br /><span class="fade">critics</span></div>
                        <div id="average_audience"><span class="value"></span><br /><span class="fade">audience</span></div>
                    </div>
                    <div class="three columns bestworst" id="highest_scoring">
                        <h3 class="best">Best</h3>
                        <a href="#" target="_blank"><div class="thumbnail_wrapper"><img class="thumbnail" /></div></a>
                        <span class="value"></span>
                    </div>
                    <div class="three columns bestworst" id="worst_scoring">
                        <h3 class="worst">Worst</h3>
                        <a href="#" target="_blank"><img class="thumbnail"></a>
                        <span class="value"></span>
                    </div>
                </div>

                <div class="message"></div>

                <div id="chart" style="height:500px">
                <div class="loader">
                    <div class='uil-default-css' style='transform:scale(0.21);'><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(0deg) translate(0,-60px);transform:rotate(0deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(30deg) translate(0,-60px);transform:rotate(30deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(60deg) translate(0,-60px);transform:rotate(60deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(90deg) translate(0,-60px);transform:rotate(90deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(120deg) translate(0,-60px);transform:rotate(120deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(150deg) translate(0,-60px);transform:rotate(150deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(180deg) translate(0,-60px);transform:rotate(180deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(210deg) translate(0,-60px);transform:rotate(210deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(240deg) translate(0,-60px);transform:rotate(240deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(270deg) translate(0,-60px);transform:rotate(270deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(300deg) translate(0,-60px);transform:rotate(300deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(330deg) translate(0,-60px);transform:rotate(330deg) translate(0,-60px);border-radius:10px;position:absolute;'></div></div>
                    gimme a sec...
                    </div>
                
                </div>


            </div>

            <div class="push">
        </div>

        <div class="footer">
            Powered by <a href="https://www.themoviedb.org/documentation/api">TMDB</a> and <a href="http://developer.rottentomatoes.com/">Rotten Tomatoes</a>. Made by <a href="http://twitter.com/iansilber">Ian Silber</a>
            </div>

        <div class="bg"></div>

        <script>
            $(function() {
                var person = getUrlVars()["p"] || "";
                $("input.person").val(decodeURIComponent(person.replace("+", " ")));
                find_actor(person);    
            })
        </script>
    </body>
</html>
