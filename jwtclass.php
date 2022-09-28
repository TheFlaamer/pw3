<?php
class MyJWT {
    private $senha = "SenhaSecreta";
    private $expTime = 100000000;
    public function criaToken($payload){
        $header = [
            'alg' => 'SHA256',
            'typ' => 'JWT'
         ];
         
         $header = json_encode($header);
         $header = base64_encode($header);

         $exp = $this->expTime + time();

         array_push($payload, $exp);

         echo $payload;

         $payload = json_encode($payload);
         $payload = base64_encode($payload);
        
         $signature = hash_hmac('sha256',"$header.$payload",$this->senha,true);
         $signature = base64_encode($signature);
        
         return "$header.$payload.$signature";
    }
    public function validaToken($jwt){
         $part = explode(".",$jwt);
         $header = $part[0];
         $payload = $part[1];
         $signature = $part[2];
        
         $signatureCheck = hash_hmac('sha256',"$header.$payload",$this->senha,true);
         $signatureCheck = base64_encode($signatureCheck);
         if ($signature == $signatureCheck){
            $retorno = true;
         }else {
            $retorno = false;
         }
        
         return $retorno;
    }

    public function refreshToken($token) {

    }
}
?>
