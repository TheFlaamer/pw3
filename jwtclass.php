<?php
require_once "connection.php";

class MyJWT {
    private $senha = "SenhaSecreta";
    private $conn = null;

    function __construct() {
        $connection = new Conn();
        $this->conn = $connection->connect();
    }

    public function criaToken($payload){
        $header = [
            'alg' => 'SHA256',
            'typ' => 'JWT'
         ];
         
         $header = json_encode($header);
         $header = base64_encode($header);

         $payload = json_encode($payload);
         $payload = base64_encode($payload);
        
         $signature = hash_hmac('sha256',"$header.$payload",$this->senha,true);
         $signature = base64_encode($signature);
        
         return "$header.$payload.$signature";
    }
    public function validaToken($jwt, $expires){
         $part = explode(".",$jwt);
         $header = $part[0];
         $payload = $part[1];
         $signature = $part[2];
        
         $signatureCheck = hash_hmac('sha256',"$header.$payload",$this->senha,true);
         $signatureCheck = base64_encode($signatureCheck);
         if ($signature == $signatureCheck){
            if($expires == true) {
                $payload = base64_decode($payload);
                $payload = json_decode($payload, true);
         
                 if($payload['exp'] >= time()) {
                    $retorno = true;
                 } else {
                    $retorno = false;
                 }
            } else {
                $retorno = true;
            }
         }else {
            $retorno = false;
         }
        
         return $retorno;
    }

    public function extractToken($token, $pos) {
      $token_extracted = explode(".",$token);
      $payloadData = base64_decode($token_extracted[$pos]);
      $payloadData = json_decode($payloadData, true);
      print_r($payloadData);

      return $payloadData;

      // $signatureCheck = hash_hmac('sha256',"$header.$payload",$this->senha,true);
      // $signatureCheck = base64_encode($signatureCheck);
    }

    public function getBlacklist($token) {
      $q = "SELECT * FROM token WHERE refresh_token = '".$token."'";
      $sql = mysqli_query($this->conn, $sql);
      return $sql->num_rows;
    }
    public function insertBlackList($hash, $id) {
        $q = "INSERT INTO token (refresh_token, id_user) VALUES ('$hash', $id)";
        $sql = mysqli_query($this->conn, $q);
        return $sql;
    }
}
?>
