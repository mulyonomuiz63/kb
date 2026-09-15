<?php
if (!function_exists('kirim_wa')) {
    /**
     * Mengirim pesan WhatsApp menggunakan API Watzap.id
     * Mendukung pesan teks biasa & pesan template.
     * 
     * @param string $phone Nomor telepon tujuan
     * @param string $message Isi pesan (kosongkan jika menggunakan template)
     * @param array $template_data Data template (jika mengirim template)
     * @return array|mixed Hasil response API
     */
    function kirim_wa($phone, $message = '', $template_data = []) {
        $api_key    = setting('whatsapp_token'); 
        $number_key = setting('whatsapp_secret_key'); 
        
        $api_key    = !empty($api_key) ? $api_key : 'YOUR_WATZAP_API_KEY';
        $number_key = !empty($number_key) ? $number_key : 'YOUR_WATZAP_NUMBER_KEY';

        // ==========================================
        // PERBAIKAN 1: Auto-Format Nomor HP
        // ==========================================
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        if (substr($phone, 0, 1) === '8') {
            $phone = '62' . $phone;
        }
        // ==========================================

        $url = setting('whatsapp_url');

        // ==========================================
        // PERBAIKAN 2: Logika Pemilihan Payload
        // ==========================================
        if (!empty($template_data)) {
            // Jika $template_data diisi, gunakan payload format Template
            $data = [
                "api_key"           => $api_key,
                "phone_no"          => $phone,
                "template_name"     => $template_data['template_name'],
                "template_language" => isset($template_data['template_language']) ? $template_data['template_language'] : 'id',
                "parameter"         => isset($template_data['parameter']) ? $template_data['parameter'] : [],
                "apps_source"       => isset($template_data['apps_source']) ? $template_data['apps_source'] : ''
            ];
            
            // Opsional: Beberapa versi Watzap tetap butuh number_key meski pakai template
            if (!empty($number_key)) {
                $data["number_key"] = $number_key;
            }
        } else {
            // Jika $template_data kosong, gunakan payload format Teks Biasa
            $data = [
                "api_key"    => $api_key,
                "number_key" => $number_key,
                "phone_no"   => $phone,
                "message"    => $message
            ];
        }

        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data), 
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return [
                'status'  => false,
                'message' => 'Request failed: ' . $error_msg
            ];
        }

        curl_close($curl);

        $response = json_decode($result, true);
        
        return $response;
    }
}