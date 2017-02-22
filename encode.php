<?php 

function encrypt($str, $operation, $key) {
    //加密uid
    $key  = ord($key)+231;
    if ($operation == "E") {
        $len  = strlen($str);
        $rest = "";
        for ($i=0; $i < $len; $i++) { 
            $rest .=$key+ord($str{$i});
        }
    }
    //解密uid
    if ($operation == "D") {
        $arr = str_split($str,3);
        $len = count($arr);
        $rest= "";
        for ($i=0; $i < $len; $i++) { 
            $rest .= chr($arr[$i]-$key);
        }
    }
    return $rest;
}

    $base   = "68feeb0d-a59d-763c-c0ef-ecad3b2dd3d9";
    $encode = encrypt($base, "E", "lisp");
    $decode = encrypt($encode, "D", 'lisp');
    echo '原来---'.$base."<br/>";
    echo "加密---".$encode."<br/>";
    echo "解密---".$decode;

function StrCode($string, $action = 'ENCODE') {

	$action != 'ENCODE' && $string = base64_decode($string);

	$code = '';

	$key = 'weiou2017$#!'

	$keyLen = strlen($key);

	$strLen = strlen($string);

	for ($i = 0; $i < $strLen; $i++) {

		$k = $i % $keyLen;

		$code .= $string[$i] ^ $key[$k];

	}

	return ($action != 'DECODE' ? base64_encode($code) : $code);

}

<?php
error_reporting(-1);
function StrCode($string, $action = 'ENCODE') {
	$action != 'ENCODE' && $string = base64_decode($string);
	$code = '';
	$key  = 'test#ds!';
	$keyLen = strlen($key);
	$strLen = strlen($string);
	for ($i = 0; $i < $strLen; $i++) {
		$k = $i % $keyLen;
		$code .= $string[$i] ^ $key[$k];
	}
	return ($action != 'DECODE' ? base64_encode($code) : $code);
}

$en = StrCode("linux","ENCODE");
$de = StrCode($en,"DECODE");
echo "加密：".$en."<br/>";
echo "解密：".$de;

 ?>
