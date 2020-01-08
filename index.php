<?php
session_start();

#################
#変数宣言
#################
$isGameEnd = false;     //true ゲーム終了 false 進行中
$isPlayerBurst = false; //true バースト false 21以下
$isEnemyBurst = false;  //true バースト false 21以下
$isPlayerStand = false; //true カード確定 false カード未確定
$isEnemyStand = false;  //true カード確定 false カード未確定
$deck   = array();      //デッキ
$player  = array();     //プレイヤー
$enemy = array();       //敵AI

##################################################
#デッキ構築
##################################################
function InitDeck(){

    $tmpDeck = array();
    $suits = array("s","d","c","h");

    //4種x13枚のジョーカー無しデッキ
    foreach($suits as $suit){
        for($i=1;$i<=13;$i++){
            $deck[] = array(
                'num'  => $i,     //1~13
                'suit' => $suit   //s,d,c,h
            );
        }
    }

    //シャッフル
    shuffle($deck);

    return $deck;
}

##################################################
#手札の点数
##################################################
function HandScore($_someOne){
  $totalScore = 0;
  foreach ($_someOne as $deck) {
    //11以上の数字は10へ
    if($deck['num'] >= 11){
      $totalScore += 10;
    }
    else{
      $totalScore += $deck['num'];
    }
  }

  return $totalScore;
}
##################################################
#バーストしているかチェックをする
#していればゲーム終了
##################################################
function BurstCheck($_someOne){
  if(HandScore($_someOne) > 21){
    return true;    //バースト
  }
  else{
    return false;
  }
}

#################
#初期化
#################
if(!isset($_GET['reset'])){
    if(isset($_SESSION['deck']))         $deck  = $_SESSION['deck'];
    if(isset($_SESSION['player']))       $player = $_SESSION['player'];
    if(isset($_SESSION['enemy']))        $enemy    = $_SESSION['enemy'];
  }
if(isset($_GET['reset'])){
  $deck = InitDeck();               //デッキの生成
  $player[] = array_shift($deck);   //プレイヤー1枚目ドロー
  $player[] = array_shift($deck);   //プレイヤー2枚目ドロー
  $enemy[] = array_shift($deck);    //敵1枚目ドロー
  $enemy[] = array_shift($deck);    //敵2枚目ドロー

  //$deck  = $_SESSION['deck'];
  //$player = $_SESSION['player'];
  //$enemy    = $_SESSION['enemy'];
}
//リセットされていないなら
else{
  $deck = InitDeck();               //デッキの生成
  $player[] = array_shift($deck);   //プレイヤー1枚目ドロー
  $player[] = array_shift($deck);   //プレイヤー2枚目ドロー
  $enemy[] = array_shift($deck);    //敵1枚目ドロー
  $enemy[] = array_shift($deck);    //敵2枚目ドロー

  $deck  = $_SESSION['deck'];
  $player = $_SESSION['player'];
  $enemy    = $_SESSION['enemy'];
}

$_SESSION['deck']  = $deck;
$_SESSION['player'] = $player;
$_SESSION['enemy'] = $enemy;

#################
#リンク処理
#################

#カードを引く
if(isset($_GET['draw'])){
  $player[] = array_shift($deck);     //3枚目以降ドロー

  //敵AI
  //15以下ならカードを一枚引く
  //プレイヤー
  if((HandScore($enemy) <= 15 && $isPlayerBurst == false) ||
        ($isPlayerStand == true && HandScore($enemy) < HandScore($player))){
    $enemy[] = array_shift($deck);
  }
  //15以上なら確定
  else{
    $isEnemyStand = true;
  }
}

#スタンド
if(isset($_GET['stand'])){
  $isPlayerStand = true;

  //敵AI
  if((HandScore($enemy) <= 15 && $isPlayerBurst == false) ||
        ($isPlayerStand == true && HandScore($enemy) < HandScore($player))){
    //15以上になるまでカードを引く
    while(HandScore($enemy) <= 15 || HandScore($enemy) < HandScore($player)){
      $enemy[] = array_shift($deck);
    }
    //バーストしていないなら確定状態へ
    if(BurstCheck($enemy) == false){
      $isEnemyStand = true;
    }
    else{
      $isEnemyBurst = true;
    }
  }
  else if(BurstCheck($enemy) == false){
    $isEnemyStand = true;
  }
}

//更新
$_SESSION['deck']  = $deck;
$_SESSION['player'] = $player;
$_SESSION['enemy'] = $enemy;

#################
#プレイヤーの状況
#################
echo "【P手札】SCORE:";
$score = HandScore($player);
echo $score."<br>";

//バーストしているか確認
if(BurstCheck($player) == true){
  echo "BURST<br>";
  $isPlayerBurst = true;
}

//確定しているか表示
if($isPlayerStand == true){
  echo "STAND<br>";
}

//手札の中身を表示
foreach($player as $deck){
  echo $deck['num']."　　";
}
echo "<br>";

#################
#エネミーの状況
#################
echo "【E手札】SCORE:";
$score = HandScore($enemy);
echo $score."<br>";


//バーストしているか確認
if(BurstCheck($enemy) == true){
  echo "BURST<br>";
  $isEnemyBurst = true;
}

//確定しているか表示
if($isEnemyStand == true){
  echo "STAND<br>";
}

//手札の中身を表示
//if($isGameEnd == true){
  foreach($enemy as $deck){
    echo $deck['num']."　　";
  }
//}


echo "<br>";
echo "<br>";

#################
#勝敗判定
#################

//バースト
if($isPlayerBurst == false && $isEnemyBurst == true){
  echo "PLAYER WIN";
  $isGameEnd = true;
}
else if($isPlayerBurst == true && $isEnemyBurst == false){
  echo "PLAYER LOSE";
  $isGameEnd = true;
}
else if($isPlayerBurst == true && $isEnemyBurst == true){
  echo "DRAW";
  $isGameEnd = true;
}

//スタンド
if($isPlayerStand == true && $isEnemyStand == true){
  if(HandScore($player) > HandScore($enemy)){
    echo "PLAYER WIN";
  }
  else if(HandScore($player) < HandScore($enemy)){
    echo "PLAYER LOSE";
  }
  else{
    echo "DRAW";
  }
  $isGameEnd = true;
}


?>

<?php
##################################################
#リンクの生成
#クリックすることでアクションが起こる
##################################################
?>

<ul>
  <?php #バーストした際にはドローができないように
  if($isGameEnd == false){ ?>
  <li><a href="?draw">ドロー</a></li>
  <li><a href="?stand">確定</a></li>
<?php } ?>
  <li><a href="?reset">最初から</a></li>
</ul>
