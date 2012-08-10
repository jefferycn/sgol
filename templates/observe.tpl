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
        <div data-role="page" id="observe">
            <div data-theme="a" data-role="header">
                <h5>
                    游戏 #<?php echo $id; ?>
                </h5>
            </div>
            <div data-role="content" style="padding: 15px">
                <div data-role="collapsible-set" data-theme="" data-content-theme="c">
<?php
foreach($players as $item) {
    $userId = $item["user_id"];
    $username = $item["username"];
    $seat = $item["seat"];
?>
                    <div data-role="collapsible" data-collapsed="false">
                        <h3>
                            <?php echo $username; ?>
                        </h3>
<?php
    if($status) {
?>
                        <ul data-role="listview" data-divider-theme="b" data-inset="false">
<?php
        if(empty($item['killed_by'])) {
            foreach($users as $player) {
?>
                            <li data-theme="d" data-mini="true">
                                <a href="/games/killed?id=<?php echo $userId; ?>&game_id=<?php echo $id; ?>&by=<?php echo $player['id']; ?>" data-transition="slide">
                                    Kill by <?php echo ($userId == $player['id']) ? "god" : $player['username']; ?>
                                </a>
                            </li>
<?php
            }
        }else {
            $killed = $users[$item['killed_by']];
?>
                            <li data-theme="e">
                                Killed by: <?php echo ($userId == $killed['id']) ? "god" : $killed['username']; ?> -> <?php echo $item['name']; ?>
                            </li>
                            <li data-theme="d">
                                <a href="/games/killed?id=<?php echo $userId; ?>&game_id=<?php echo $id; ?>" data-transition="slide">
                                    Not killed
                                </a>
                            </li>
<?php
        }
?>
                        </ul>
<?php
}
?>
                    </div>
<?php
}
?>
<div class="ui-grid-a" data-inset="false">
<?php
if($status == 1) {
?>
    <a href="/games/finish?id=<?php echo $id; ?>" data-role="button" data-inline="true" data-theme="a">Finish</a>
    <a href="/games/oneonone?id=<?php echo $id; ?>" data-role="button" data-inline="true" data-theme="a">1 on 1</a>
<?php
}
if($status == 1 || $status == 0) {
?>
    <a href="/games/close?id=<?php echo $id; ?>" data-role="button" data-inline="true" data-theme="a">Close</a>
<?php
}
if($status >= 2) {
?>
    <a href="/games/open?id=<?php echo $id; ?>" data-role="button" data-inline="true" data-theme="a">Open</a>
<?php
}
?>
    <a href="/games/myrole?id=<?php echo $id; ?>" data-role="button" data-inline="true" data-theme="e">
        My role
    </a>
</div>
                </div>
            </div>
<?php include "footer.tpl"; ?>
        </div>
        <script>
            //App custom javascript
        </script>
    </body>
</html>
