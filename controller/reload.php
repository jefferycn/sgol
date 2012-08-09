<?php
class reload extends Object {

    public $id = 'reload';
    // inserted users for later use
    protected $users = array();

    protected function notice($message) {
        echo $message . "<br/>";
        ob_flush();
		flush();
    }

    protected function tables() {
        // table_name => model_name
        return array(
            'apps' => 'App',
            'tokens' => 'Token',
            'users' => 'User',
            'roles' => 'Role',
            'games' => 'Game',
            'game_types' => 'GameType',
            'game_type_roles' => 'GameTypeRole',
            'game_assignments' => 'GameAssignment',
        );
    }

    protected function getDBAttribByName($name) {
        switch($name) {
            case 'id':
                return "`id` INT UNSIGNED NOT NULL AUTO_INCREMENT";
            case 'fee':
                return "`fee` FLOAT NOT NULL";
            case 'password':
            case 'username':
            case 'appId':
            case 'email':
                $name = model::unCamelCase($name);
                return "`$name` VARCHAR(32) NOT NULL DEFAULT ''";
            case 'startDate':
            case 'endDate':
            case 'startTime':
            case 'endTime':
                $name = model::unCamelCase($name);
                return "`$name` CHAR(15) NOT NULL DEFAULT ''";
            case 'created':
            case 'updated':
            case 'credits':
                $name = model::unCamelCase($name);
                return "`$name` INT UNSIGNED NOT NULL DEFAULT 0";
            case 'status':
                return "`status` TINYINT NOT NULL DEFAULT 0";
            default:
                $name = model::unCamelCase($name);
                if(strstr($name, 'date')) {
                    return "`$name` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'";
                }
                if(strstr($name, '_id')) {
                    return "`$name` INT UNSIGNED NOT NULL DEFAULT 0";
                }
                if(strstr($name, '_name') || strstr($name, 'number')) {
                    return "`$name` VARCHAR(32) NOT NULL DEFAULT ''";
                }
                if(strstr($name, 'balance')) {
                    return "`$name` FLOAT NOT NULL";
                }
                if(strstr($name, 'description')) {
                    return "`$name` TEXT NOT NULL";
                }
                return "`$name` VARCHAR(256) NOT NULL DEFAULT ''";
        }
    }

    protected function dropTables() {
        $conn = $this->getDB();
        $tables = $this->tables();
        foreach($tables as $table => $model) {
            $sql = "DROP TABLE IF EXISTS " . $table;
            $conn->save($sql);
            $this->notice("Droping table:". $table);
        }
    }

    protected function createTables() {
        $conn = $this->getDB();
        $tables = $this->tables();
        foreach($tables as $table => $model) {
            $vars = get_class_vars($model);
            $sql = "CREATE TABLE IF NOT EXISTS `$table` (";
            $fieldsString = array();
            foreach($vars as $var => $val) {
                $fieldsString[$var] = $this->getDBAttribByName($var);
            }
            if(!isset($fieldsString['id'])) {
                throw new exception("no primary key id for $model model");
            }
            $sql .= implode(",", $fieldsString);
            $sql .= ", PRIMARY KEY (`id`)";
            if(isset($fieldsString['username'])) {
                $sql .= ", UNIQUE KEY `username` (`username`)";
            }
            $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 ;";
            $conn->save($sql);
            $this->notice("Creating table: " . $table);
        }
    }

    protected function initUsers() {
        $users = array(
            'jeffery' => array(
                'firstName' => 'Jeffery',
                'lastName' => 'You',
                'email' => 'i@youjf.com',
                'pwd' => '123',
            ),
            'brent' => array(
                'firstName' => 'Brent',
                'lastName' => 'Wang',
                'email' => 'brent.wang@modolabs.com',
                'pwd' => 'demo',
            ),
            'edward' => array(
                'firstName' => 'Edward',
                'lastName' => 'Liu',
                'email' => 'edward.liu@modolabs.com',
                'pwd' => 'demo',
            ),
            'newstar' => array(
                'firstName' => 'Newstar',
                'lastName' => 'Liu',
                'email' => 'newstar.liu@modolabs.com',
                'pwd' => 'demo',
            ),
            'wolf' => array(
                'firstName' => 'Wolf',
                'lastName' => 'Wang',
                'email' => 'yu2.wang@symbio.com',
                'pwd' => 'demo',
            ),
            'lay' => array(
                'firstName' => 'Lay',
                'lastName' => 'Xiao',
                'email' => 'lei2.xiao@symbio.com',
                'pwd' => 'demo',
            ),
            'kappa' => array(
                'firstName' => 'Kappa',
                'lastName' => 'Huang',
                'email' => 'zhibin.huang@symbio.com',
                'pwd' => 'demo',
            ),
            'xiemin' => array(
                'firstName' => 'Min',
                'lastName' => 'Xie',
                'email' => '',
                'pwd' => 'demo',
            ),
            'weiwei' => array(
                'firstName' => 'Weiwei',
                'lastName' => 'Wang',
                'email' => '',
                'pwd' => 'demo',
            ),
            'guest1' => array(
                'firstName' => 'Guest',
                'lastName' => 'Number1',
                'email' => '',
                'pwd' => 'demo',
            ),
            'guest2' => array(
                'firstName' => 'Guest',
                'lastName' => 'Number2',
                'email' => '',
                'pwd' => 'demo',
            ),
        );
        $conn = $this->getDB();
        foreach ($users as $userID => $student) {
            $insertData = array(
                'username' => $userID,
                'password' => md5($student['pwd']),
                'first_name' => $student['firstName'],
                'last_name' => $student['lastName'],
                'email' => $student['email'],
            );
            if ($conn->insert("users", $insertData)) {
                $this->notice("Init the Users:" . $userID);
            }
        }
    }

    protected function initRoles() {
        $roles = array(
            array(
                'name' => '主公',
            ),
            array(
                'name' => '忠臣',
            ),
            array(
                'name' => '内奸',
            ),
            array(
                'name' => '反贼',
            ),
        );
        $conn = $this->getDB();
        foreach ($roles as $item) {
            if ($conn->insert("roles", $item)) {
                $this->notice("Init the role:" . $item['name']);
            }
        }
    }

    protected function initGameTypes() {
        $items = array(
            array(
                'name' => '5人场',
            ),
            array(
                'name' => '6人场',
            ),
            array(
                'name' => '6人场(双内奸)',
            ),
            array(
                'name' => '7人场',
            ),
            array(
                'name' => '8人场',
            ),
            array(
                'name' => '8人场(双内奸)',
            ),
            array(
                'name' => '9人场',
            ),
            array(
                'name' => '10人场',
            ),
        );
        $conn = $this->getDB();
        foreach ($items as $item) {
            if ($conn->insert("game_types", $item)) {
                $this->notice("Init the gameType:" . $item['name']);
            }
        }
    }

    protected function initGameTypeRoles() {
        $items = array(
            array('id'=>'1','game_type_id'=>'1','role_id'=>'1'),
            array('id'=>'2','game_type_id'=>'1','role_id'=>'2'),
            array('id'=>'3','game_type_id'=>'1','role_id'=>'3'),
            array('id'=>'4','game_type_id'=>'1','role_id'=>'4'),
            array('id'=>'5','game_type_id'=>'1','role_id'=>'4'),
            array('id'=>'6','game_type_id'=>'2','role_id'=>'1'),
            array('id'=>'7','game_type_id'=>'2','role_id'=>'2'),
            array('id'=>'8','game_type_id'=>'2','role_id'=>'3'),
            array('id'=>'9','game_type_id'=>'2','role_id'=>'4'),
            array('id'=>'10','game_type_id'=>'2','role_id'=>'4'),
            array('id'=>'11','game_type_id'=>'2','role_id'=>'4'),
            array('id'=>'12','game_type_id'=>'3','role_id'=>'1'),
            array('id'=>'13','game_type_id'=>'3','role_id'=>'2'),
            array('id'=>'14','game_type_id'=>'3','role_id'=>'3'),
            array('id'=>'15','game_type_id'=>'3','role_id'=>'3'),
            array('id'=>'16','game_type_id'=>'3','role_id'=>'4'),
            array('id'=>'17','game_type_id'=>'3','role_id'=>'4'),
            array('id'=>'18','game_type_id'=>'4','role_id'=>'1'),
            array('id'=>'19','game_type_id'=>'4','role_id'=>'2'),
            array('id'=>'20','game_type_id'=>'4','role_id'=>'2'),
            array('id'=>'21','game_type_id'=>'4','role_id'=>'3'),
            array('id'=>'22','game_type_id'=>'4','role_id'=>'4'),
            array('id'=>'23','game_type_id'=>'4','role_id'=>'4'),
            array('id'=>'24','game_type_id'=>'4','role_id'=>'4'),
            array('id'=>'25','game_type_id'=>'5','role_id'=>'1'),
            array('id'=>'26','game_type_id'=>'5','role_id'=>'2'),
            array('id'=>'27','game_type_id'=>'5','role_id'=>'2'),
            array('id'=>'28','game_type_id'=>'5','role_id'=>'3'),
            array('id'=>'29','game_type_id'=>'5','role_id'=>'4'),
            array('id'=>'30','game_type_id'=>'5','role_id'=>'4'),
            array('id'=>'31','game_type_id'=>'5','role_id'=>'4'),
            array('id'=>'32','game_type_id'=>'5','role_id'=>'4'),
            array('id'=>'33','game_type_id'=>'6','role_id'=>'1'),
            array('id'=>'34','game_type_id'=>'6','role_id'=>'2'),
            array('id'=>'35','game_type_id'=>'6','role_id'=>'2'),
            array('id'=>'36','game_type_id'=>'6','role_id'=>'3'),
            array('id'=>'37','game_type_id'=>'6','role_id'=>'3'),
            array('id'=>'38','game_type_id'=>'6','role_id'=>'4'),
            array('id'=>'39','game_type_id'=>'6','role_id'=>'4'),
            array('id'=>'40','game_type_id'=>'6','role_id'=>'4'),
            array('id'=>'41','game_type_id'=>'7','role_id'=>'1'),
            array('id'=>'42','game_type_id'=>'7','role_id'=>'2'),
            array('id'=>'43','game_type_id'=>'7','role_id'=>'2'),
            array('id'=>'44','game_type_id'=>'7','role_id'=>'2'),
            array('id'=>'45','game_type_id'=>'7','role_id'=>'3'),
            array('id'=>'46','game_type_id'=>'7','role_id'=>'4'),
            array('id'=>'47','game_type_id'=>'7','role_id'=>'4'),
            array('id'=>'48','game_type_id'=>'7','role_id'=>'4'),
            array('id'=>'49','game_type_id'=>'7','role_id'=>'4'),
            array('id'=>'50','game_type_id'=>'8','role_id'=>'1'),
            array('id'=>'51','game_type_id'=>'8','role_id'=>'2'),
            array('id'=>'52','game_type_id'=>'8','role_id'=>'2'),
            array('id'=>'53','game_type_id'=>'8','role_id'=>'2'),
            array('id'=>'54','game_type_id'=>'8','role_id'=>'3'),
            array('id'=>'55','game_type_id'=>'8','role_id'=>'3'),
            array('id'=>'56','game_type_id'=>'8','role_id'=>'4'),
            array('id'=>'57','game_type_id'=>'8','role_id'=>'4'),
            array('id'=>'58','game_type_id'=>'8','role_id'=>'4'),
            array('id'=>'59','game_type_id'=>'8','role_id'=>'4')
        );
        $conn = $this->getDB();
        foreach ($items as $item) {
            if ($conn->insert("game_type_roles", $item)) {
                $this->notice("Init the gameTypeRole:" . $item['id']);
            }
        }
    }

    protected static function appsData() {
        return array(
            'web' => array(
                'secret'=> "123456"
            ),
            'ios' => array(
                'secret'=> "123456"
            ),
            'android' => array(
                'secret'=> "123456"
            )
        );
    }

    protected function initApps() {
        $conn = $this->getDB();
        $apps = self::appsData();
        foreach ($apps as $type => $item) {
            $insertData = array(
                'type' => $type,
                'secret' => $item['secret'],
            );
            if ($conn->insert("apps", $insertData)) {
                $this->notice("Init the app:" . $type);
            }
        }
    }

    public function run() {
        //set_time_limit(0);
        //ob_end_clean();
        echo str_repeat(" ", 4096);
        //drop tables
        $this->notice("--------     Drop tables       --------");
        $this->dropTables();
        //create tables
        $this->notice("--------     Create tables       --------");
        $this->createTables();
        $this->notice("--------     Initialed users data       --------");
        $this->initUsers();
        $this->notice("--------     Initialed apps data       --------");
        $this->initApps();
        $this->notice("--------     Initialed roles data       --------");
        $this->initRoles();
        $this->notice("--------     Initialed gameTypes data       --------");
        $this->initGameTypes();
        $this->notice("--------     Initialed gameTypeRoles data       --------");
        $this->initGameTypeRoles();
        ob_end_flush();
    }
}
