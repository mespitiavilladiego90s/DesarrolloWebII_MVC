<?php
class JWT {
    private $secretKey;

    public function __construct() {
        $this->secretKey = $_ENV['SECRET_KEY'];
    }

    public function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public function decode($jwt) {
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);

        $header = json_decode($this->base64UrlDecode($base64UrlHeader), true);
        $payload = json_decode($this->base64UrlDecode($base64UrlPayload), true);
        $signatureProvided = $this->base64UrlDecode($base64UrlSignature);

        // Aseguramos que estamos utilizando las versiones codificadas en Base64 de los encabezados y el payload
        $signatureValid = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);

        if ($signatureProvided !== $signatureValid) {
            return false;
        }

        return $payload;
    }

    private function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function base64UrlDecode($data) {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}
