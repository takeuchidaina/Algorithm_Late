<?php

//repuire('class.php');
/*
class Card
{
  public $suit = 0;
  public $num = 0;
  public $score = 0;

  function __construct($_suit,$_num,$_score){
  $this->suit = $_suit;
  $this->num = $_num;
  $this->score = $_score;
  }
}

for ($i=0; $i < 2; $i++) {
  $suit = $i%4;
  $num = $i%13;
  $score = $i%10;
  $deck = array(new card($suit,$num,$score));


  echo $deck[$i]->suit."\n";
  echo $deck[$i]->num."\n";
  echo $deck[$i]->score."\n";
}
*/

session_start();

$_SESSION['isGameEnd'] = false;    //true 終了 false 継続
$_SESSION['nowPlayer'] = 0;        //現在のプレイヤー 0 player 1 enemy
$_SESSION['deckCnt'] = 52;         //デッキ枚数
$_SESSION['deck'] = $deck;

######################################
#デッキ構築
######################################
for ($i=0; $i < $deckCnt; $i++) {
  for ($j=0; $j < 4; $j++) {

    #柄の区別
    $suit = $j;
    if($suit == 0){
      $suit = "s";
    }
    else if($suit == 1){
      $suit = "c";
    }
    else if($suit == 2){
      $suit = "h";
    }
    else if($suit == 3){
      $suit = "d";
    }

    #11以上の値の得点を10へ
    $score = $i%13;
    if($score >= 11){
      $score = 10;
    }

    #配列に挿入
    $deck[] = [$suit,$i%13,$score];
  }
}

#デッキの中身を表示
function ShowDeck($deckCnt,$deck){
  for($i=0;$i < $deckCnt;$i++){
    for ($j=0; $j < 3; $j++) {
      echo $deck[$i][$j]."\n";
    }
    echo '<br>';
  }
}

#手札を表示
function ShowHands($player){
  echo "枚数:".sizeof($player)."<br>";
  for($i=0;$i<sizeof($player);$i++){
    echo "suit :".$player[$i][0]."\n";
    echo "score:".$player[$i][1]."\n";
    echo "num  :".$player[$i][2]."\n";
    echo "<br>";
  }
}

#デッキをシャッフル

######################################
#カード配布
######################################
#1枚目ドロー
$player[] = [$deck[0][0],$deck[0][1],$deck[0][2]];
array_shift($deck);  //配列の先頭を削除
$deckCnt--;

#2枚目ドロー
$player[] = [$deck[0][0],$deck[0][1],$deck[0][2]];
array_shift($deck);
$deckCnt--;

#デッキ参照(deblug)
//ShowDeck($deckCnt,$deck);

#手札参照
ShowHands($player);

######################################
#ゲーム内処理
######################################
#バースト確認
function BurstCheck($_player){
  $total = 0;
  for($i=0;$i<sizeof($_player);$i++){
    $total += $_player[$i][1];
  }
  if(1){

  }
}

######################################
#カードを引く
######################################
#ボタンが押された処理
if(isset($_GET['draw'])) {
    echo "<br>"."player ドロー"."<br>";
    $player[] = [$deck[0][0],$deck[0][1],$deck[0][2]];
    array_shift($deck);  //配列の先頭を削除
    $deckCnt--;

    //BurstCheck($player);
    ShowHands($player);
    $nowPlayer = 1;
}

######################################
#スタンド
######################################
$playerStand1 = false;

#両者スタンド
function StandCheck($playerStand1,$playerStand2){
  if($playerStand1 == true && $playerStand2 == true){
    echo "ゲーム終了";
    return true;
  }
  return false;
}

#ボタンが押された処理
if(isset($_GET['stand'])) {
    echo "<br>"."player スタンド";
    $playerStand1 = true;
    //$isGameEnd = StandCheck($playerStand1,$playerStand2);
    if($isGameEnd == false){
      //$nowPlayer = TurnChange($nowPlayer);
    }
}

?>
<li><a href="?draw">Hit</a></li>
<li><a href="?stand">Stand</a></li>
