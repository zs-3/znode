<?php
/**
 * Simplified ACME Client Skeleton (Library-free)
 */
class ACME {
    private $directory;
    private $accountKey;

    public function __construct($directory, $accountKey) {
        $this->directory = $directory;
        $this->accountKey = $accountKey;
    }

    /**
     * Sign data using JWS
     */
    private function jws($payload, $url, $nonce = null, $kid = null) {
        $protected = [
            "alg" => "RS256",
            "url" => $url
        ];

        if ($nonce) $protected["nonce"] = $nonce;
        if ($kid) {
            $protected["kid"] = $kid;
        } else {
            $protected["jwk"] = $this->getJWK();
        }

        $base64Protected = $this->base64UrlEncode(json_encode($protected));
        $base64Payload = $payload === null ? "" : $this->base64UrlEncode(json_encode($payload));

        $signingInput = $base64Protected . "." . $base64Payload;
        openssl_sign($signingInput, $signature, $this->accountKey, OPENSSL_ALGO_SHA256);

        return [
            "protected" => $base64Protected,
            "payload" => $base64Payload,
            "signature" => $this->base64UrlEncode($signature)
        ];
    }

    private function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function getJWK() {
        $details = openssl_pkey_get_details(openssl_pkey_get_private($this->accountKey));
        return [
            "kty" => "RSA",
            "n" => $this->base64UrlEncode($details["rsa"]["n"]),
            "e" => $this->base64UrlEncode($details["rsa"]["e"]),
        ];
    }

    public function request($url, $payload, $nonce, $kid = null) {
        $jws = $this->jws($payload, $url, $nonce, $kid);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jws));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/jose+json']);

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        return [
            "status" => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            "body" => json_decode($body, true),
            "headers" => $headers
        ];
    }

    /**
     * Get Nonce from ACME server
     */
    public function getNonce($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $response = curl_exec($ch);
        preg_match('/replay-nonce: (.*)/i', $response, $matches);
        return trim($matches[1] ?? '');
    }
}
