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
        <div data-role="page" id="ranking">
            <div data-theme="a" data-role="header">
                <h5>
                    积分
                </h5>
            </div>
            <div data-role="content" style="padding: 15px">
                <ol data-role="listview" data-divider-theme="b" data-inset="false">
<?php
foreach($items as $item) {
?>
                    <li data-theme="c">
                        <?php echo $item['first_name'] . " " . $item['last_name']; ?>
                        <span class="ui-li-count ui-btn-up-c ui-btn-corner-all"><?php echo $item['credits']; ?></span>
                    </li>
<?php
}
?>
                </ol>
            </div>
<?php include "footer.tpl"; ?>
        </div>
        <script>
            //App custom javascript
        </script>
    </body>
</html>
