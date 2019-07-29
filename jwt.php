<?php
date_default_timezone_set('Asia/Tokyo');
require 'vendor/autoload.php';
use Firebase\JWT\JWT as JWT;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

$encodedHeader='';  //base64エンコードしたheader
$encodedClaims='';  //base64エンコードしたclaims
$encodedSignature='';      //署名

$header = [
    'alg'=>"RS256", //署名アルゴリズム
    'typ'=>'JWT'    //タイプ
];
$encodedHeader = base64_encode(json_encode($header));

$claims = [
//    'iss'=>'285087211861-am2l2v9sckhg821jb2cq5m7mm5ehg0mn.apps.googleusercontent.com',  //クライアントID部分に書かれてるメアド
    'iss'=>'285087211861-am2l2v9sckhg821jb2cq5m7mm5ehg0mn.apps.googleusercontent.com',  //クライアントID部分に書かれてるメアド
    'scope'=>'https://www.googleapis.com/auth/cloud-platform',    //利用するapiスコープ/必須ではない
//    'aud'=>'https://www.googleapis.com/oauth2/v4/token',      //クライアント識別子。今回はアクセストークン発行用
    'aud'=>'https://oauth2.googleapis.com/token',      //
    'exp'=>time()+3600,      //トークンの有効期限
    'iat'=>time()+60,      //トークンの発行日
];
$encodedClaims = base64_encode(json_encode($claims));

$file = 'ignore/fine-climber-240416-4aeb475d6ae7.json';
//$file = 'ignore/client_secret_285087211861-am2l2v9sckhg821jb2cq5m7mm5ehg0mn.apps.googleusercontent.com.json';
$json = file_get_contents($file);
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$arr = json_decode($json,true);
//print_r($arr['private_key']);
$privateKey = $arr['private_key'];
//$privateKey = $arr['web']['client_secret'];


//print_r(time());

//文字列を合体させた後、DeveloperConsoleからダウンロードしたJSONのprivate_keyにある秘密鍵を使用して署名する
$signature = $encodedHeader.$encodedClaims;

//$encodedSignature = JWT::encode($signature,$privateKey,'RS256');
$encodedSignature = JWT::encode([$encodedHeader,$encodedClaims],$privateKey,'RS256');


//$header = [//ヘッダ情報
//    "assertion:".$encodedSignature,
//    "grant_type:urn:ietf:params:oauth:grant-type:jwt-bearer"
//];

//$body = "grant_type=".urlencode("urn:ietf:params:oauth:grant-type:jwt-bearer")."&assertion=".$encodedSignature;
$body = "grant_type=".urlencode("urn:ietf:params:oauth:grant-type:jwt-bearer")."&assertion=".$encodedHeader.'.'.$encodedClaims.'.'.$encodedSignature;

$url = 'https://www.googleapis.com/oauth2/v4/token';

$data = [
    'grant_type'=>'urn:ietf:params:oauth:grant-type:jwt-bearer',//ここおかしい＃＃＃＃＃＃＃＃＃＃＃＃＃＃
    'assertion'=>$encodedHeader.'.'.$encodedClaims.'.'.$encodedSignature
];
$curl = curl_init();//cURL セッションを初期化する
//以下curl詳細設定
curl_setopt($curl, CURLOPT_URL, $url);//取得するURLの指定。curl_init() でセッションを初期化する際に指定することも可能。
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); //HTTPリクエストで"GET"あるいは"HEAD"以外に使用するカスタムメソッドの指定
curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //HTTP "POST" で送信するすべてのデータの指定
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']); //ヘッダーの指定

//curlはデフォルトでセッション終了後にレスポンスを出力する。以下の文でそれを止める
//TRUEを設定すると、curl_exec()の返り値を文字列で返します。通常はデータを直接出力
curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );

$response = curl_exec($curl);//cURLセッションを実行。レスポンスが返されるっぽい
echo $response;
//echo $header;
//print_r($privateKey);

//print_r($encodedHeader);