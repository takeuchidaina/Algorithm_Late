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

#デッキ作成
for ($i=0; $i < 52; $i++) {
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
$deckCnt = 52;

#デッキの中身を表示
function ShowDeck($deckCnt,$deck){
  for($i=0;$i < $deckCnt;$i++){
    for ($j=0; $j < 3; $j++) {
      //echo $deck[$i][$j]."\n";
    }
    //echo '<br>';
  }
}

function ShowHands($player){
  echo "手札を確認します"."<br>";
  echo "枚数:".sizeof($player)."<br>";
  for($i=0;$i<sizeof($player);$i++){
    echo "suit :".$player[$i][0]."\n";
    echo "score:".$player[$i][1]."\n";
    echo "num  :".$player[$i][2]."\n";
    echo "<br>";
  }

}


#デッキをシャッフル

#プレイヤー1にカードを配布
#1枚目ドロー
$player1[] = [$deck[0][0],$deck[0][1],$deck[0][2]];
array_shift($deck);  //配列の先頭を削除
$deckCnt--;
ShowDeck($deckCnt,$deck);

#2枚目ドロー
$player1[] = [$deck[0][0],$deck[0][1],$deck[0][2]];
array_shift($deck);  //配列の先頭を削除
$deckCnt--;
ShowDeck($deckCnt,$deck);

#プレイヤー2にカードを配布
#1枚目ドロー
$player2[] = [$deck[0][0],$deck[0][1],$deck[0][2]];
array_shift($deck);  //配列の先頭を削除
$deckCnt--;
ShowDeck($deckCnt,$deck);

#2枚目ドロー
$player2[] = [$deck[0][0],$deck[0][1],$deck[0][2]];
array_shift($deck);  //配列の先頭を削除
$deckCnt--;
ShowDeck($deckCnt,$deck);

#手札参照
ShowHands($player1);
ShowHands($player2);

$isGameEnd = false;
$nowPlayer = 1;   //現在のプレイヤー 0と1

#ゲーム
while ($isGameEnd == false) {

  #ターンチェンジ
  if($nowPlayer == 1){$nowPlayer = 1;}
  else{$nowPlayer = 0;}
  echo "<br>"."ターンを変更しました"."<br>";

  echo "<br>"."カードを引きますか"."<br>";
  
$isGameEnd = true;
}


?>
