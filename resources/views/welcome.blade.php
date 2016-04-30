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

        <style>
            body {
                font-family: "Roboto";
                background: 
                    linear-gradient(
                      rgba(0, 0, 0, 1.0), 
                      rgba(0, 0, 0, 1.0)
                    );
                background-size: cover;
            }

            .stat_center {display: none; padding: 0px 0 40px 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 40px; color: #fff;}

            .stat_center h3 {
                text-transform: uppercase;
                font-size: 12px;
                font-weight: 700;
            }

            h3.average {color: #86C8E9;}
            h3.best {color: #FF5252;}
            h3.worst {color: #E3FF86;}

            .averages > div {
                width: 50%;
                float: left;
            }

            .averages .value {
                font-size: 60px; font-weight: 100;
                line-height: 60px;
            }

            .bestworst .thumbnail {
                float: left;
                margin: 0 20px 0 0;
                box-shadow: inset 0px 0px 0px 2px rgba(255,255,255,0.2);
            }

            #person_form div {
                text-align: center;
                margin: 50px auto;
            }

            .fade {
                opacity: 0.4;
            }

            #chart {
                text-align: center;
            }

            .loader {
                text-align: center;
                color: rgba(255,255,255,0.3);
            }
            .uil-default-css {margin: 120px auto 0;}

            input.person {
                border-radius: 100px;
                text-align: center;
                padding: 16px;
                background-color: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255,255,255, 0.4);
                color: #fff;   
                backdrop-filter: blur(50px);
                -webkit-backdrop-filter: blur(50px);
            }

            input.person:focus {
                border: 1px solid rgba(255,255,255, 0.6);
            }

            .tooltip_thumbnail {
                /*padding-right: 160px;*/
            }
            .tooltip_thumbnail img {
                float: left;
                margin: 0 20px 0 0;
                border-radius: 4px;
            }

            .footer {
                padding: 40px;
                color: rgba(255,255,255,0.3);
                font-size: 11px; text-align: center;
            }

            .footer a {color: rgba(255,255,255,0.3);}
            .footer a:hover {color: rgba(255,255,255,1);}


/*            .test {margin: 20px; background-color: green;}
            .test:after {content: ""; box-shadow: inset 0px 0px 0px 5px rgba(255,255,255,0.2);}
            .test img {position: relative}
            .test_img {background: url(images/ocean.png); background-size: 50px 50px}
*/
            .ui-menu {
                /*border: none;*/
                border-radius: 8px;
                font-size: 16px;
                line-height: 28px;
                overflow: hidden;
            }
            .ui-menu-item {
                font-family: Roboto;
                padding: 16px!important;

                /*border: none!important;*/
            }
            .ui-menu-item .imdbTitle{
                font-size: 0.9em;
                font-weight: bold;
            }
            .ui-menu-item .imdbCast{
                font-size: 0.7em;
                font-style: italic;
                line-height: 110%;
                color: #666;
            }
            .ui-menu-item .imdbImage{
                float: left;
                border-radius: 100px;
                overflow:hidden;
                width: 28px; height: 28px;
                margin-right: 12px;
            }
            .ui-menu-item .clear{
                clear: both;
            }

            .ui-menu-divider {
                border-bottom: 1px solid green;
            }

        </style>
    </head>
    <body>
        <form id="person_form">
            <div>
                <span class="person_wrapper"><input type="text" class="person" value="" placeholder="Actor" /></span>
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

            <div id="chart" style="height:500px">
            <div class="loader">
                <div class='uil-default-css' style='transform:scale(0.21);'><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(0deg) translate(0,-60px);transform:rotate(0deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(30deg) translate(0,-60px);transform:rotate(30deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(60deg) translate(0,-60px);transform:rotate(60deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(90deg) translate(0,-60px);transform:rotate(90deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(120deg) translate(0,-60px);transform:rotate(120deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(150deg) translate(0,-60px);transform:rotate(150deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(180deg) translate(0,-60px);transform:rotate(180deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(210deg) translate(0,-60px);transform:rotate(210deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(240deg) translate(0,-60px);transform:rotate(240deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(270deg) translate(0,-60px);transform:rotate(270deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(300deg) translate(0,-60px);transform:rotate(300deg) translate(0,-60px);border-radius:10px;position:absolute;'></div><div style='top:80px;left:93px;width:14px;height:40px;background:#00b2ff;-webkit-transform:rotate(330deg) translate(0,-60px);transform:rotate(330deg) translate(0,-60px);border-radius:10px;position:absolute;'></div></div>
                gimme a sec...
                </div>
            
            </div>

            <div class="footer">
            Powered by <a href="https://www.themoviedb.org/documentation/api">TMDB</a> and <a href="http://developer.rottentomatoes.com/">Rotten Tomatoes</a>. Made by <a href="http://twitter.com/iansilber">Ian Silber</a>
            </div>



        </div>
    </body>
</html>
