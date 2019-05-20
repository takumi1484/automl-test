<?php
//リクエスト用url
$url = "https://automl.googleapis.com/v1beta1/projects/fine-climber-240416/locations/us-central1/models/ICN8753464127353970294:predict";

//セキュリティトークン。gitとかに挙げる時は要注意。乱用されたら死
//$token = "ya29.c.EloLB9wt3Umx8X6xFyCz7C491SzbO5DP1kIWFHxoQrIBX39Wlp2azI4eRxvB5HYkYda5egica6ucYgDKO6ge97aTKjuWHzh7qF0aPQ1mKqRVh-WRYItI8OMsirw";
$token = "ya29.c.EloOB7iGA4E1vXTiWW_pLencoD850mpvNQvVowGmcB1v0ENRkrlmQOwLXt_WCIH3vHbMpeuqM1d_An6e-0GBBaQrFQUuqRikYjKKaZvel09xix8ti9T8DRU4JnI";

//画像をbase64文字列にエンコード
$img = base64_encode(file_get_contents('./img/g.jpg'));

//以下の形のjsonを作成
//{
//  "payload": {
//    "image": {
//      "imageBytes": "＃BASE64code＃"
//    }
//  }
//}
$data = array("payload"=>array("image"=>array("imageBytes"=>$img)));

$header = [//ヘッダ情報
    'Authorization: Bearer '.$token,//トークン
    'Content-Type: application/json',//テキストタイプ
];

$curl = curl_init();//cURL セッションを初期化する
//以下curl詳細設定
curl_setopt($curl, CURLOPT_URL, $url);//取得するURLの指定。curl_init() でセッションを初期化する際に指定することも可能。
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); //HTTPリクエストで"GET"あるいは"HEAD"以外に使用するカスタムメソッドの指定
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); //HTTP "POST" で送信するすべてのデータの指定
curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //ヘッダーの指定
//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//FALSEを設定するとサーバー証明書の検証を行わない
//curl_setopt($curl, CURLOPT_HEADER, true);//TRUEを設定するとヘッダの内容を出力

//curlはデフォルトでセッション終了後にレスポンスを出力する。以下の文でそれを止める
//TRUEを設定すると、curl_exec()の返り値を文字列で返します。通常はデータを直接出力
curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );

$response = curl_exec($curl);//cURLセッションを実行。レスポンスが返されるっぽい

//$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
//$header = substr($response, 0, $header_size);
//$body = substr($response, $header_size);
//$result = json_decode($body, true);

curl_close($curl);//cURLセッションを閉じる

$arr = json_decode($response,true);//trueで連想配列の指定

//echo $arr["payload"][0]["displayName"];
//echo $response;

