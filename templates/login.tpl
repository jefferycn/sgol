<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>
        </title>
        <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
        <link rel="stylesheet" href="/css/my.css" />
        <style>
            /* App custom styles */
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
        </script>
        <script src="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.0/jquery.mobile-1.1.0.min.js">
        </script>
        <script src="/js/my.js">
        </script>
    </head>
    <body>
        <!-- Home -->
        <div data-role="page" id="page1">
            <div data-theme="a" data-role="header">
                <h5>
                    Sanguo Sha OL
                </h5>
            </div>
            <div data-role="content" style="padding: 15px">
            <form action="/auth" data-ajax="false">
                <div data-role="fieldcontain">
                    <fieldset data-role="controlgroup">
                        <label for="textinput1">
                            Username
                        </label>
                        <input name="username" id="textinput1" placeholder="" value="" type="text" />
                    </fieldset>
                </div>
                <div data-role="fieldcontain">
                    <fieldset data-role="controlgroup">
                        <label for="textinput2">
                            Password
                        </label>
                        <input name="password" id="textinput2" placeholder="" value="" type="password" />
                    </fieldset>
                </div>
                <input type="submit" data-theme="a" value="Login" />
                </form>
            </div>
        </div>
        <script>
            //App custom javascript
        </script>
    </body>
</html>
