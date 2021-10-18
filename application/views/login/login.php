<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" class="login" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>sgzihao Inventory Login</title>
        <link rel="stylesheet" type="text/css" href="<? echo base_url() ?>/assets/login/css/combined.css" />
        <link rel="stylesheet" media="screen, projection" href="<? echo base_url() ?>/assets/login/css/screen_bridge_plugins.css" />
        <script type="text/javascript" src="<?php echo base_url() . 'assets/jqueryui/js/jquery.js';?>"></script>
        <style type="text/css">
            #branding {
                margin: 20px auto 0;
                width: 100%;
            }
        </style>
       <script>
            $(function() {
                $("#username").focus();
            });
        </script>
    </head>
    <body class="">
        <div id="page">
            <div id="header">
                <div class="page_container">
                    <div id="branding">
                        <h1><a>Global Sources</a></h1>
                        <p>Inventory System 1.2</p>
                        <br />
                    </div>
                </div>
            </div>

            <div id="content">
                <div class="page_container">

                    <form method="post" action="<?php echo site_url("welcome/login"); ?>" >

                        <div class="header field">
                            <h1>Login to Infrastructure Servers</h1>
                        </div>
                        <div class="field">
                            <?php if (isset($loginfomsg)) { ?>
                                <ul class="errorlist">
                                    <li><?php echo $loginfomsg; ?></li>
                                </ul>
                            <?php } ?>
                            <label for="id_username">User Name</label>
                            <input name="username" maxlength="75" class="required" id="username" type="text" />
                        </div>

                        <div class="field">
                            <label for="id_password">Password</label>
                            <input name="password" id="password" type="password" />
                        </div>

                        <div class="field">
                            <input class="green_button" value="Go ?" type="submit" />
                        </div>

                    </form>

                    <div id="footer">
                        <ul>
                            <li><a href="">Home</a></li>
                            <li><a href="">Forgot your password?</a></li>
                            <li><a href="">Sign up </a></li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>

    </body>
</html>
