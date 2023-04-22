<?php 
namespace App\Service;

use DateTimeImmutable;
//json web token
class JWTservice {

    //JWT.io qui explique comment fonctionne les tokens / 10800 secondes c'est 3h
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if($validity > 0 )
        {
            $now = new DateTimeImmutable();
            $expiration = $now->getTimestamp() + $validity;
    
            //iat voir JWT.io
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $expiration;
            
        } 

        

        //choix de l'encode en base 64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        //clean opperateur math (crash sans)
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        //signrature create
        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true); //sha256 utilisé par les jwt
        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        $jwt = $base64Header .".". $base64Payload .".". $base64Signature;

        return $jwt;
    }

    //verification forme
    public function isValid(string $token):bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/', $token
        ) === 1;
    }

    //payload + extract
    public function getPayload(string $token): array
    {
        $array = explode('.', $token);

        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

    //header + extract
    public function getHeader(string $token): array
    {
        $array = explode('.', $token);

        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }
    
    //verife date
    public function isExpired(string $token): bool
    {

        $payload = $this->getPayload($token);

        $now = new DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }
    //verife signature
    public function check(string $token, string $secret)
    {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }



}
