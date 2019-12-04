<?php
session_start();

$isGameEnd = false;     //true ゲーム終了 false 進行中
$isBurst = false;       //true バースト false 21以下
$deck   = array();      //デッキ
$player  = array();     //手札

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
#敵の行動
##################################################
function EnemyTurn(){

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
if(isset($_GET['reset'])){
  $deck = InitDeck();               //デッキの生成
  $player[] = array_shift($deck);   //1枚目ドロー
  $player[] = array_shift($deck);   //2枚目ドロー
}
//リセットされていないなら
else{
  $deck  = $_SESSION['deck'];
  $player = $_SESSION['player'];
}

$_SESSION['deck']  = $deck;
$_SESSION['player'] = $player;

#################
#リンク処理
#################
#カードを引く
if(isset($_GET['draw'])){
  $player[] = array_shift($deck);     //3枚目以降ドロー
  EnemyTurn();
}
#スタンド
if(isset($_GET['stand'])){

}

//更新
$_SESSION['deck']  = $deck;
$_SESSION['player'] = $player;

#################
#手札を表示
#################
echo "【手札】SCORE:";
$score = HandScore($player);
echo $score."<br>";

//バーストしているか確認
if(BurstCheck($player) == true){
  echo "BURST<br>";
  $isBurst = true;
}

//手札の中身を表示
foreach($player as $deck){
  echo $deck['num']."　　";
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
  if($isBurst == false){ ?>
  <li><a href="?draw">ドロー</a></li>
  <li><a href="?stand">確定</a></li>
<?php } ?>
  <li><a href="?reset">最初から</a></li>
</ul>
