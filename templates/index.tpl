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
                    Hello, <?php echo $user['first_name']; ?>
                </h5>
            </div>
            <div data-role="content" style="padding: 15px">
                <ul data-role="listview" data-divider-theme="b" data-inset="false">
<?php
foreach($games as $item) {
?>
                    <li data-theme="c">
                        <a href="/games/join?id=<?php echo $item['id']?>" data-transition="slide">
                            进入 #<?php echo $item['id']?> [<?php echo $item['name']?>]
                        </a>
                    </li>
<?php
}
?>
                </ul>
            </div>
<?php include "footer.tpl"; ?>
        </div>
        <script>
            //App custom javascript
        </script>
    </body>
</html>
