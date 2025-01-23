<?php
    ini_set('display_errors', 1);
    require_once('player.php');
    session_start();


    function main()
    {
      //post通信が来た時
        if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['select']))){
          switch ($_POST['select'])
          {
            case "raise": //raiseが押された時に呼ぶ関数
              raise();
              break;
            case "call": //callが押された時に呼ぶ関数
              call();
              break;
            case "fold": //foldが押された時に呼ぶ関数
              fold();
              break;
          }
        //   $cardList = []; //judgeに渡すカードのリスト
        //   foreach ($cards as $_SESSION['pots']){
        //     array_push($cardList, $cards);
        //   }
        //   foreach ($cards as $_SESSION['your_status']){
        //     array_push($cardList, $cards);
        //   }
        $seven_cards = sevenCard();
        $result = [];
        foreach ($seven_cards as $key=>$value){
            // $result[$key] =

            echo "<p>".$key." : ".judge($value)."</p>";
        }
           //ここでjudgeに引数を渡したい(7枚のカード自分 + 場のカード)

        } else{
          start();
        }
    }

    function start()
    {
        //turnをSESSIONで管理したい
        $_SESSION['turn'] = 0;

        $_SESSION['judge'] = False;
        $_SESSION['player_turn'] = 'your'; #自分を1とする
        $_SESSION['your_status'] = new Player(10000, 100);
        $_SESSION['enemy2_status'] = new Player(10000, 100);
        $_SESSION['enemy3_status'] = new Player(10000, 100);
        $_SESSION['enemy4_status'] = new Player(10000, 100);
        $_SESSION['total_pot'] = 0;
        $_SESSION['participants'] = ['your','enemy2','enemy3','enemy4'];



        $loadedCards = dealCards(); //ランダムでカードを持ってきてる


        // 下の部分でプレイヤーのカードを全て管理してる(sessionに入れてる)
        $enemy3_card = array($loadedCards[0],$loadedCards[1]);
        $_SESSION['enemy3_status']->setCard($enemy3_card);

        $enemy2_card = array($loadedCards[2],$loadedCards[3]);
        $_SESSION['enemy2_status']->setCard($enemy2_card);

        $_SESSION['pots'] = array($loadedCards[4],$loadedCards[5],$loadedCards[6],$loadedCards[7],$loadedCards[8]);

        $enemy4_card = array($loadedCards[9],$loadedCards[10]);
        $_SESSION['enemy4_status']->setCard($enemy4_card);

        $your_card = array($loadedCards[11],$loadedCards[12]);
        $_SESSION['your_status']->setCard($your_card);
    }

    function dealCards()
    {
        $numbers = range(1, 52);
        shuffle($numbers);


        $cards = array_slice($numbers, 0, 13);
        foreach ($cards as $card) {
            $sql = sql($card);
            $i['num'] = $sql['num'];
            $i['type'] = $sql['type'];
            $i['image'] = $sql['image'];
            $result[] = $i;
        }
        return $result;
    }


    function raise(): void
    {
        $yourStack = $_SESSION['your_status']->getStack();
        $_SESSION['your_status']->setStack($yourStack+50);
        $yourStack = $_SESSION['your_status']->getStack();
        $_SESSION['enemy2_status']->setStack($yourStack+50);
        $yourStack = $_SESSION['your_status']->getStack();
        $_SESSION['enemy3_status']->setStack($yourStack+50);
        $yourStack = $_SESSION['your_status']->getStack();
        $_SESSION['enemy4_status']->setStack($yourStack+50);
        $_SESSION['total_pot'] += 200;
        turnControl();
    }

    function call()
    {
      turnControl();
        return 0;
    }

    function fold()
    {
        $user = $_SESSION['player_turn'];
        $_SESSION["{$user}_status"]->setPlaying(False);

        // switch ($_SESSION['player_turn'])
        // {
        //     case 1:
        //         $yourMoney = $_SESSION['your_status']->getMoney();
        //         $yourMoney -= $_SESSION['your_status']->getStack();
        //         $_SESSION['your_status']->setMoney($yourMoney);
        //         break;
        //     case 2:
        //         $enemy2Money = $_SESSION['enemy2_status']->getMoney();
        //         $enemy2Money -= $_SESSION['enemy2_status']->getStack();
        //         $_SESSION['enemy2_status']->setMoney($enemy2Money);
        //         break;
        //     case 3:
        //         $enemy3Money = $_SESSION['enemy3_status']->getMoney();
        //         $enemy3Money -= $_SESSION['enemy3_status']->getStack();
        //         $_SESSION['enemy3_status']->setMoney($enemy3Money);
        //         break;
        //     case 4:
        //         $enemy4Money = $_SESSION['enemy4_status']->getMoney();
        //         $enemy4Money -= $_SESSION['enemy4_status']->getStack();
        //         $_SESSION['enemy4_status']->setMoney($enemy4Money);
        //         break;
        //     default:
        //         var_dump($_SESSION['player_turn']);
        // }
        // turnControl();
    }

    function continueGame()
    {
      return 0;
    }

    function sevenCard()
    {
        $pot_cards = $_SESSION['pots'];
        // $player_cards = $_SESSION['your_status']->getCard();
        // array_push($player_cards, $pot_cards);
        // $enemy2_cards = $_SESSION['enemy2_status']->getCard();
        // $enemy3_cards = $_SESSION['enemy3_status']->getCard();
        // $enemy4_cards = $_SESSION['enemy4_status']->getCard();

        // $seven = [];
        // $seven['player'] = array_push($player_cards, $pot_cards);
        // $seven['enemy2'] = array_push($enemy2_cards, $pot_cards);
        // $seven['enemy3'] = array_push($enemy3_cards, $pot_cards);
        // $seven['enemy4'] = array_push($enemy4_cards, $pot_cards);

        $players = ['your','enemy2','enemy3','enemy4'];
        foreach($players as $player){
            $cards = $_SESSION["{$player}_status"]->getCard();
            $seven[$player] = array_merge($cards,$pot_cards,array());

        }
        return $seven;
    }

    /**
     * $_SESSION['pots'] はフィールド上のカード
     *
     * $cardsには、potの5枚とユーザーの手札の2枚の合計7枚欲しい
     */
    function judge($cards)
    {
        $values = [];
        $type = [];

        foreach ($cards as $card) {
            $values[] = $card["num"];
            $type[] = $card["type"];
        }
        sort($values);



        $flushcount = (array_count_values($type));
        $isFlush = False;
        foreach ($flushcount as $value => $count){
            if ($count >= 5) {
                $isFlush = True;
            }
        }
        $isStraight = isStraight($values);
        $valueCounts = array_count_values($values);
        $counts = array_values($valueCounts);
        sort($counts);

        if ($isFlush && $isStraight) {
            return 'ストレートフラッシュ';
        }
        if ($counts == [1, 1, 1, 4]) {
            return 'フォーカード';
        }
        if ($counts == [1, 1, 1, 2, 3]) {
            return 'フルハウス';
        }
        if ($isFlush) {
            return 'フラッシュ';
        }
        if ($isStraight) {
            return 'ストレート';
        }
        if ($counts == [1, 1, 1, 1, 3]) {
            return 'スリーカード';
        }
        if ($counts == [1, 1, 1, 2, 2]) {
            return 'ツーペア';
        }
        if ($counts == [1, 1, 1, 1, 1, 2]) {
            return 'ワンペア';
        }

        return 'ハイカード';
    }

    function isStraight($values) {
        $uniqueValues = array_unique($values);
        sort($uniqueValues);

        if (count($uniqueValues) <= 5) {
            return false;
        } else {
            for ($i=4;$i<=7;$i++){
                $j = $i - 4;
                if ($uniqueValues[0] === 1) {
                    $end = end($uniqueValues);
                    if ($end != 13) {
                        return false;
                    }
                    $count = count($uniqueValues);
                    if ($end - $uniqueValues[$count - 4] == 3){
                        return true;
                    }
                }
                if($uniqueValues[$i] - $uniqueValues[$j] != 4){
                    return false;
                } else {
                    return true;
                }
            }
        }


        // if ($uniqueValues == [2, 3, 4, 5, 14]) {
        //     return true;
        // }

        // return false;
  }

    function checkRole($your_cards)
    {

    }

    function enemyAction()
    {
        return 0;
    }

    function sql($id)
    {
        $dsn = 'mysql:dbname=play_card;host=localhost';
        $user = 'root';
        $password = '';

        try {
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            $query = "SELECT * FROM card_variations where id = {$id}";
            $stmt = $dbh->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = null;
            $dbh = null;
            $dsn = null;
            return $result;

        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    function sqljudge($id, $sql)
    {
        $dsn = 'mysql:dbname=play_card;host=localhost';
        $user = 'root';
        $password = '';

        try {
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            $query = $sql;
            $stmt = $dbh->query($query);

        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    //何ターン目かチェックしたい
    function turnControl(){
      $currentTurn = $_SESSION['turn'];
      if ($currentTurn == 5){
        //結果を計算する関数を呼びだず(結果も表示したい)
      }
      $_SESSION['turn'] += 1;
    }