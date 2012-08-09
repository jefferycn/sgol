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
<?php
$gameId = $role['game_id'];
$username = $role['username'];
$gameRole = $role['name'];
$gameSeat = $role['seat'];
$credits = $role['credits'];
?>
        <!-- Home -->
        <div data-role="page" id="page1">
            <div data-theme="a" data-role="header">
                <h5>
                    <?php echo $username; ?>, You are in game #<?php echo $gameId; ?>
                </h5>
            </div>
            <div data-role="content" style="padding: 15px">
                <ul data-role="listview" data-divider-theme="b" data-inset="false">
                    <li data-theme="e">
                        身份: <?php echo $gameRole; ?></span>
                    </li>
                    <li data-theme="c">
                        座位: <?php echo $gameSeat; ?>
                    </li>
<?php
if($credits) {
?>
                    <li data-theme="c">
                        得分: <?php echo $credits; ?>
                    </li>
<?php
}
?>
                    <li data-theme="c">
                        <a href="/games/observe?id=<?php echo $gameId; ?>" data-transition="slide">
                            详细信息
                        </a>
                    </li>
                </ul>
            </div>
<?php include "footer.tpl"; ?>
        </div>
        <script>
            //App custom javascript
        </script>
    </body>
</html>
