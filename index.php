<?php
session_start();

$isGameEnd = false;     //true ゲーム終了 false 進行中
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
#初期化
##################################################
if(isset($_GET['reset'])){
  $deck = InitDeck();               //デッキの生成
  $player[] = array_shift($deck);   //1枚目ドロー
  $player[] = array_shift($deck);   //2枚目ドロー
}
//リセットされていないなら
else{
  $deck  = $_SESSION['cards'];
  $player = $_SESSION['player'];
}

$_SESSION['cards']  = $deck;
$_SESSION['player'] = $player;


##################################################
#リンク処理
##################################################
#カードを引く
if(isset($_GET['draw'])){
  $player[] = array_shift($deck);     //3枚目以降ドロー
}
#スタンド
if(isset($_GET['stand'])){

}

//更新
$_SESSION['cards']  = $deck;
$_SESSION['player'] = $player;

##################################################
#手札を表示
##################################################
echo "手札 : ";
foreach($player as $card){
  echo $card['num'] . ' ';
}
?>

<?php
##################################################
#リンクの生成
#クリックすることでアクションが起こる
##################################################
?>

<ul>
  <li><a href="?draw">ドロー</a></li>
  <li><a href="?stand">確定</a></li>
  <li><a href="?reset">最初から</a></li>
</ul>
