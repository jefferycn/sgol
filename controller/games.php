<?php
class games extends Object {

    public $id = 'games';
    public $token;

    public function __construct($app) {
        parent::__construct($app);
        $this->token = $this->params('token');
        $this->validateToken($this->token);
    }

    public function getTypes() {
        $conn = $this->getDB();
        $sql = "select id, name from game_types";
        $items = $conn->getRows($sql);
        return $items;
    }

    public function getAvailableGames() {
        $conn = $this->getDB();
        $sql = "select a.id, name from games a, game_types b where a.game_type_id = b.id and status in (0, 1)";
        $items = $conn->getRows($sql);
        return $items;
    }

    public function create() {
        $game = array();
        $game['game_type_id'] = $this->params('id', array(
            'validate' => array(
                'table' => 'game_types',
                'field' => 'id',
            )
        ));
        $game['owner_user_id'] = $this->userId;
        $game['status'] = 0;
        $game['created'] = time();
        $game['updated'] = time();
        $conn = $this->getDB();
        if($conn->insert("games", $game)) {
            $gameId = $conn->lastInsertId();
            if($this->assign($gameId, $this->userId)) {
                //$this->assign($gameId, 2);
                //$this->assign($gameId, 3);
                //$this->assign($gameId, 4);
                //$this->assign($gameId, 5);
                //$this->assign($gameId, 6);
                $response = $gameId;
            }else {
                $response = false;
            }
        }else {
            $response = false;
        }

        return $response;
    }

    public function join() {
        $gameId = $this->params('id', array(
            'validate' => array(
                'table' => 'games',
                'field' => 'id',
            )
        ));
        $this->assign($gameId, $this->userId);

        return $gameId;
    }

    public function myrole() {
        $gameId = $this->params('id', array(
            'validate' => array(
                'table' => 'games',
                'field' => 'id',
            )
        ));
        $players = $this->getCurrentPlayers($gameId);
        foreach($players as $item) {
            if($item['user_id'] == $this->userId) {
                return $item;
            }
        }
        return false;
    }

    public function observe() {
        $gameId = $this->params('id', array(
            'validate' => array(
                'table' => 'games',
                'field' => 'id',
            )
        ));
        $players = $this->getCurrentPlayers($gameId);
        $gameUsers = array();
        foreach($players as $item) {
            $userId = $item['user_id'];
            $username = $item['username'];
            $currentUser = array(
                'id' => $userId,
                'username' => $username
            );
            $gameUsers[$item['user_id']] = $currentUser;
        }
        return array(
            'id' => $gameId,
            'status' => $this->getGameStatus($gameId),
            'players' => $players,
            'users' => $gameUsers,
        );
    }

    protected function isAssigned($gameId, $userId) {
        $sql = "select count(*) from game_assignments where game_id = ? and user_id = ?";
        $options = array($gameId, $userId);
        $conn = $this->getDB();
        return (boolean) $conn->getOne($sql, $options);
    }

    protected function getGameStatus($gameId) {
        $sql = "select status from games where id = ?";
        $options = array($gameId);
        $conn = $this->getDB();
        return $conn->getOne($sql, $options);
    }

    protected function getGameType($gameId) {
        $sql = "select game_type_id from games where id = ?";
        $options = array($gameId);
        $conn = $this->getDB();
        return $conn->getOne($sql, $options);
    }

    protected function getGameInfo($gameId) {
        $sql = "select * from games where id = ?";
        $options = array($gameId);
        $conn = $this->getDB();
        return $conn->getRow($sql, $options);
    }

    protected function getGameAssignments($gameId) {
        $sql = "select * from game_assignments where game_id = ?";
        $options = array($gameId);
        $conn = $this->getDB();
        return $conn->getRows($sql, $options);
    }

    protected function getAllPlayers($gameTypeId) {
        $sql = "select role_id from game_type_roles where game_type_id = ?";
        $options = array($gameTypeId);
        $conn = $this->getDB();
        return $conn->getRows($sql, $options);
    }

    protected function getCurrentPlayers($gameId) {
        $sql = "select
            game_id, role_id, a.user_id, username, c.name, killed_by, seat, a.credits
            from game_assignments a, users b, roles c
            where game_id = ? and a.user_id = b.id and a.role_id = c.id";
        $options = array($gameId);
        $conn = $this->getDB();
        return $conn->getRows($sql, $options);
    }

    protected function assign($game_id, $user_id) {
        if(!$this->isAssigned($game_id, $user_id)) {
            // find game type
            $game_type_id = $this->getGameType($game_id);
            $supposedAttendees =$this->getAllPlayers($game_type_id);
            $realAttendees =$this->getCurrentPlayers($game_id);

            $allSeats = array();
			for($i = 1; $i <= count($supposedAttendees); $i++){
				$allSeats[] = $i;
			}

            if(count($realAttendees) < count($supposedAttendees)) {
                // assign for user
                $supposedAttendeesBak = $supposedAttendees;

                foreach($realAttendees as $att) {
                    foreach($supposedAttendees as $k => $item) {
                        if($att['role_id'] == $item['role_id']) {
                            unset($supposedAttendees[$k]);
                            break;
                        }
                    }
                    $key = array_search($att['seat'], $allSeats);
                    unset($allSeats[$key]);
                }
                $assignedRoleKey = array_rand($supposedAttendees);
                $assignedSeatKey = array_rand($allSeats);
                $assignedRole = $supposedAttendees[$assignedRoleKey]['role_id'];
                $assignedSeat = $allSeats[$assignedSeatKey];
                $assignment = array();
                $assignment['game_id'] = $game_id;
                $assignment['user_id'] = $user_id;
                $assignment['role_id'] = $assignedRole;
                $assignment['seat'] = $assignedSeat;
                $assignment['created'] = time();
                $assignment['updated'] = time();
                $conn = $this->getDB();
                $conn->insert("game_assignments", $assignment);
                if(count($supposedAttendeesBak) - count($realAttendees) == 1) {
                    $sql = "update games set status = 1 where id = ?";
                    $options = array($game_id);
                    $conn->save($sql, $options);
                }
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function killed() {
        $gameId = $this->params('game_id', array(
            'validate' => array(
                'table' => 'games',
                'field' => 'id',
            )
        ));
        $userId = $this->params('id', array(
            'validate' => array(
                'table' => 'users',
                'field' => 'id',
            )
        ));
        $killed = $this->params('by', array(
            'default' => 0
        ));
        $sql = "update game_assignments set killed_by = ? where game_id = ? and user_id = ?";
        $options = array($killed, $gameId, $userId);
        $conn = $this->getDB();
        $conn->save($sql, $options);
        return $gameId;
    }

    public function close() {
        $id = $this->params('id', array(
            'validate' => array(
                'table' => 'games',
                'field' => 'id',
            )
        ));
        $sql = "update games set status = ? where id = ?";
        $options = array(2, $id);
        $conn = $this->getDB();
        $conn->save($sql, $options);
        return $id;
    }

    public function open() {
        $id = $this->params('id', array(
            'validate' => array(
                'table' => 'games',
                'field' => 'id',
            )
        ));
        $sql = "update games set status = ? where id = ?";
        $options = array(1, $id);
        $conn = $this->getDB();
        $conn->save($sql, $options);
        return $id;
    }

    public function finish() {
        $id = $this->params('id', array(
            'validate' => array(
                'table' => 'games',
                'field' => 'id',
            )
        ));
        return $this->endGame($id, $this->userId, false);
    }

    private function endGame($id, $uid, $adjust_nj = false) {
        $game = $this->getGameInfo($id);
        $status = $game['status'];
        $owner_user_id = $game['owner_user_id'];
        $game_type_id = $game['game_type_id'];
        if($status == 1) {
            //if($owner_user_id == $uid) {
                $assignments = $this->getGameAssignments($id);
                $winner_role_id = $this->getWinnerRole($assignments);
                if($winner_role_id) {
                    $this->saveWinner($id, $winner_role_id, 3);
                }
                $this->CalAdditionCredit($winner_role_id, $assignments, $adjust_nj);
            //}else {
                //return false;
            //}
        }else {
            return false;
        }
    }

    private function saveWinner($id, $winner_role_id, $status) {
        $sql = "update games set winner_type_id = ?, status = ? where id = ?";
        $options = array($winner_role_id, $status, $id);
        $conn = $this->getDB();
        $conn->save($sql, $options);
        return $id;
    }

    private function getWinnerRole($assignments) {
        $zg = array('alive' => 0, 'dead' => 0, 'all' => 0);
        $zc = array('alive' => 0, 'dead' => 0, 'all' => 0);
        $nj = array('alive' => 0, 'dead' => 0, 'all' => 0);
        $fz = array('alive' => 0, 'dead' => 0, 'all' => 0);
        foreach($assignments as $assignment) {
            $role_id = $assignment['role_id'];
            $killed_by = $assignment['killed_by'];
            switch($role_id) {
            case 1:
                $optArrayName = 'zg';
                break;
            case 2:
                $optArrayName = 'zc';
                break;
            case 3:
                $optArrayName = 'nj';
                break;
            case 4:
                $optArrayName = 'fz';
                break;
            }
            if($killed_by) {
                ${$optArrayName}['dead'] ++;
            }else {
                ${$optArrayName}['alive'] ++;
            }
            ${$optArrayName}['all'] ++;
        }
        if($zg['alive'] > 0 && $fz['alive'] == 0 && $nj['alive'] == 0) {
            $winner_role_id = 1;
        }else if($zg['alive'] == 0 && $zc['alive'] == 0 && $fz['alive'] == 0 && $nj['alive'] > 0) {
            $winner_role_id =  3;
        }else if($zg['alive'] == 0 && ($zc['alive'] > 0 || $fz['alive'] > 0) && $nj['alive'] > 0){
            $winner_role_id =  4;
        }else if($zg['alive'] == 0 && $fz['alive'] > 0) {
            $winner_role_id =  4;
        }else {
            $winner_role_id =  false;
        }
        $credits = array();
        switch($winner_role_id) {
            case 1:
                $credits['zg'] = 4 + $zc['alive'] * 2;
                $credits['zc'] = 5 + $zc['alive'];
                if($zc['alive'] == 0 && $fz['alive'] == 0) {
                    $credits['nj'] = $zg['all'] + $zc['all'] + $nj['all'] + $fz['all'];
                }else {
                    $credits['nj'] = 0;
                }
                $credits['fz'] = 0;
                break;
            case 3:
                $credits['zg'] = 1;
                $credits['zc'] = 0;
                $credits['nj'] = 4 + ($zg['all'] + $zc['all'] + $nj['all'] + $fz['all']) * 2;
                $credits['fz'] = 0;
                break;
            case 4:
                $credits['zg'] = 0;
                $credits['zc'] = 0;
                $credits['nj'] = 0;
                $credits['fz'] = $fz['alive'] * 3;
                break;
        }
        $this->credits = $credits;
        return $winner_role_id;
    }

    private function CalAdditionCredit($winner_role_id, $assignments, $adjust_nj) {
        $killings = array();
        foreach($assignments as $assignment) {
            $role_id = $assignment['role_id'];
            $killed_by = $assignment['killed_by'];
            if($killed_by > 0) {
                $killings[$killed_by][] = $role_id;
            }
        }
        foreach($assignments as $assignment) {
            $id = $assignment['id'];
            $user_id = $assignment['user_id'];
            $role_id = $assignment['role_id'];
            $killed_by = $assignment['killed_by'];
            if($user_id == $killed_by) {
                // killed by god
                $killings[$user_id] = 5;
            }
            switch($role_id) {
                case 1:
                    if(isset($killings[$user_id]) & $winner_role_id == 1) {
                        $addition = 0;
                        foreach($killings[$user_id] as $killed_role) {
                            if($killed_role == 4 || $killed_role == 3) {
                                $addition ++; 
                            }
                        }
                        $credit = $this->credits['zg'] + $addition;
                    }else {
                        $credit = $this->credits['zg'];
                    }
                    break;
                case 2:
                    if(isset($killings[$user_id]) && $winner_role_id == 1) {
                        $addition = 0;
                        foreach($killings[$user_id] as $killed_role) {
                            if($killed_role == 4 || $killed_role == 3) {
                                $addition ++; 
                            }
                        }
                        $credit = $this->credits['zc'] + $addition;
                    }else {
                        $credit = $this->credits['zc'];
                    }
                    break;
                case 3:
                    if($winner_role_id == 4 && $killed_by == 0) {
                        $credit = $this->credits['nj'] + 1;
                    }else if($winner_role_id == 3) {
                        $credit = $this->credits['nj'];
                    }else if($winner_role_id == 1){
                        if($adjust_nj) {
                            $credit = $this->credits['nj'];
                        }else {
                            $credit = 0;
                        }
                    }else {
                        $credit = 0;
                    }
                    break;
                case 4:
                    if(isset($killings[$user_id]) && $winner_role_id == 4) {
                        $addition = 0;
                        foreach($killings[$user_id] as $killed_role) {
                            if($killed_role == 1) {
                                $addition += 2; 
                            }

                            if($killed_role == 2 || $killed_role == 3) {
                                $addition ++; 
                            }
                        }
                        $credit = $this->credits['fz'] + $addition;
                    }else {
                        $credit = $this->credits['fz'];
                    }
                    break;
            }
            $this->addCredit($id, $user_id, $credit);
        }
    }

    private function addCredit($assignment_id, $user_id, $credit) {
        $sql = "update game_assignments set credits = ? where id = ? and user_id = ?";
        $options = array($credit, $assignment_id, $user_id);
        $conn = $this->getDB();
        $conn->save($sql, $options);
        $sql = "update users set credits = credits + ? where id = ?";
        $options = array($credit, $user_id);
        $conn = $this->getDB();
        $conn->save($sql, $options);
        return $assignment_id;
    }

    public function oneonone() {
        $id = $this->params('id', array(
            'validate' => array(
                'table' => 'games',
                'field' => 'id',
            )
        ));
        return $this->endGame($id, $userId, true);
    }

    public function ranking() {
        $sql = "select id, first_name, last_name, credits from users order by credits desc limit 0, 10";
        $conn = $this->getDB();
        return $conn->getRows($sql);
    }
}
