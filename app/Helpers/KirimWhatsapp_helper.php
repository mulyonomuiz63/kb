<?php

if (!function_exists('kirim_wa')) {
    /**
     * Helper untuk mengirim WhatsApp via TapTalk SendTalk API
     * 
     * @param string $phone   Nomor tujuan (bisa 08... atau 628...)
     * @param string $message Isi pesan yang ingin dikirim
     * @return array          Status dan respons dari API
     */
    function kirim_wa($phone, $message)
    {
        $client = \Config\Services::curlrequest();

        $url = 'https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp';
        $apiKey = '56bfec8f8f04590e5cd8228b30023ce1f53c087380a0d0a157021c6f591670a1'; // Ganti dengan API Key TapTalk Anda

        // Format nomor telepon otomatis (ubah 08xx menjadi 628xx)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'API-Key'      => $apiKey, 
                ],
                'json' => [
                    'phone'       => '6281532423436',
                    'body'        => 'tes',     // Ubah 'message' menjadi 'body'
                    'messageType' => 'text'
                ]
            ]);

            $body = json_decode($response->getBody(), true);
            return ['status' => true, 'data' => $body];
        } catch (\Exception $e) {
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null;
            return [
                'status' => false, 
                'error' => $e->getMessage(),
                'response' => json_decode($responseBody, true)
            ];
        }
    }
}
