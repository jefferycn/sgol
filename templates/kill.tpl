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
        <div data-role="page" id="kill">
            <div data-theme="a" data-role="header">
                <h5>
                    我是 <?php echo $userName; ?> 在#<?php echo $id; ?>
                </h5>
            </div>
            <div data-role="content" style="padding: 15px">
                <ul data-role="listview" data-divider-theme="b" data-inset="false">
<?php
        if(empty($killedBy)) {
            foreach($users as $player) {
?>
                            <li data-theme="d" data-mini="true">
                                <a href="/games/killed?id=<?php echo $userId; ?>&game_id=<?php echo $id; ?>&by=<?php echo $player['id']; ?>" data-transition="slide">
                                    <?php echo ($userId == $player['id']) ? "把自己玩儿死了" : "被 " . $player['username'] . " 杀了?"; ?>
                                </a>
                            </li>
<?php
            }
        }else {
            $killed = $users[$killedBy];
?>
                            <li data-theme="e">
                                 怎么死的?: <?php echo ($userId == $killed['id']) ? "把自己玩儿死了" : "被" . $killed['username'] . " 杀了!"; ?>
                            </li>
                            <li data-theme="d">
                                <a href="/games/killed?id=<?php echo $userId; ?>&game_id=<?php echo $id; ?>" data-transition="slide">
                                    点错了!
                                </a>
                            </li>
<?php
        }
?>
                            <li data-theme="e">
                                <a href="/games/myrole?id=<?php echo $id; ?>" data-inline="true">
                                    返回我的角色
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
