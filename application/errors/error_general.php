<html>
    <head>
        <title>Error</title>
        <style type="text/css">

            body {
                background-color:	#fff;
                margin:				40px;
                font-family:		Lucida Grande, Verdana, Sans-serif;
                font-size:			12px;
                color:				#000;
            }

            #content  {
                border:				#999 1px solid;
                background-color:	#fff;
                padding:			20px 20px 12px 20px;
            }

            h1 {
                font-weight:		normal;
                font-size:			14px;
                color:				#990000;
                margin:				0 0 4px 0;
            }
        </style>
    </head>
    <body>
        <div id="content">
            <h1><?php echo $heading; ?></h1>
            <?php echo $message; ?>

            <p>
                You will be redirected to our homepage within <span id="timeLeft">5</span> seconds, or you can go to Global Sources  Inventory directly.
            </p>
        </div>
    </body>
    <script type="text/javascript">
        function runDirect(){
            if(times<0)
            {
                return false;
            }
            --times;
            var s = document.getElementById("timeLeft");
            s.innerHTML = times;
            if(times == 0){
                times=-1;
                window.location='http://sginventory.example.com';
                return false;
            }
        }
        var times=5;
        var redirectHandle = setInterval("runDirect();", 1000);
    </script>
</html>
