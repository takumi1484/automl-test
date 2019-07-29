<?php
date_default_timezone_set('Asia/Tokyo');
require 'vendor/autoload.php';
use Firebase\JWT\JWT as JWT;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

$encodedHeader='';  //base64�G���R�[�h����header
$encodedClaims='';  //base64�G���R�[�h����claims
$encodedSignature='';      //����

$header = [
    'alg'=>"RS256", //�����A���S���Y��
    'typ'=>'JWT'    //�^�C�v
];
$encodedHeader = base64_encode(json_encode($header));

$claims = [
//    'iss'=>'285087211861-am2l2v9sckhg821jb2cq5m7mm5ehg0mn.apps.googleusercontent.com',  //�N���C�A���gID�����ɏ�����Ă郁�A�h
    'iss'=>'285087211861-am2l2v9sckhg821jb2cq5m7mm5ehg0mn.apps.googleusercontent.com',  //�N���C�A���gID�����ɏ�����Ă郁�A�h
    'scope'=>'https://www.googleapis.com/auth/cloud-platform',    //���p����api�X�R�[�v/�K�{�ł͂Ȃ�
//    'aud'=>'https://www.googleapis.com/oauth2/v4/token',      //�N���C�A���g���ʎq�B����̓A�N�Z�X�g�[�N�����s�p
    'aud'=>'https://oauth2.googleapis.com/token',      //
    'exp'=>time()+3600,      //�g�[�N���̗L������
    'iat'=>time()+60,      //�g�[�N���̔��s��
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

//����������̂�������ADeveloperConsole����_�E�����[�h����JSON��private_key�ɂ���閧�����g�p���ď�������
$signature = $encodedHeader.$encodedClaims;

//$encodedSignature = JWT::encode($signature,$privateKey,'RS256');
$encodedSignature = JWT::encode([$encodedHeader,$encodedClaims],$privateKey,'RS256');


//$header = [//�w�b�_���
//    "assertion:".$encodedSignature,
//    "grant_type:urn:ietf:params:oauth:grant-type:jwt-bearer"
//];

//$body = "grant_type=".urlencode("urn:ietf:params:oauth:grant-type:jwt-bearer")."&assertion=".$encodedSignature;
$body = "grant_type=".urlencode("urn:ietf:params:oauth:grant-type:jwt-bearer")."&assertion=".$encodedHeader.'.'.$encodedClaims.'.'.$encodedSignature;

$url = 'https://www.googleapis.com/oauth2/v4/token';

$data = [
    'grant_type'=>'urn:ietf:params:oauth:grant-type:jwt-bearer',//����������������������������������������
    'assertion'=>$encodedHeader.'.'.$encodedClaims.'.'.$encodedSignature
];
$curl = curl_init();//cURL �Z�b�V����������������
//�ȉ�curl�ڍאݒ�
curl_setopt($curl, CURLOPT_URL, $url);//�擾����URL�̎w��Bcurl_init() �ŃZ�b�V����������������ۂɎw�肷�邱�Ƃ��\�B
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); //HTTP���N�G�X�g��"GET"���邢��"HEAD"�ȊO�Ɏg�p����J�X�^�����\�b�h�̎w��
curl_setopt($curl, CURLOPT_POSTFIELDS, $body); //HTTP "POST" �ő��M���邷�ׂẴf�[�^�̎w��
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']); //�w�b�_�[�̎w��

//curl�̓f�t�H���g�ŃZ�b�V�����I����Ƀ��X�|���X���o�͂���B�ȉ��̕��ł�����~�߂�
//TRUE��ݒ肷��ƁAcurl_exec()�̕Ԃ�l�𕶎���ŕԂ��܂��B�ʏ�̓f�[�^�𒼐ڏo��
curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );

$response = curl_exec($curl);//cURL�Z�b�V���������s�B���X�|���X���Ԃ������ۂ�
echo $response;
//echo $header;
//print_r($privateKey);

//print_r($encodedHeader);