<?php

class ApiWhatsapp extends CI_Controller
{
    public function enviar_mensaje()
    {
        $token = 'necd7ej39i9noubf';

        $message = 'Hola! esto es una hdp mensaje de prueba';
        $phone = '3177742714';

        $data = [
            'phone' => $phone, // Receivers phone
            'body' => "Holas", // Message
        ];
        $json = json_encode($data); // Encode data to JSON
        // URL for request POST /message
        $instanceId = '268849';
        $url = 'https://api.chat-api.com/instance' . $instanceId . '/message?token=' . $token;
        // Make a POST request
        $options = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);
        // Send a request
        $result = file_get_contents($url, true, $options);
        echo $result;
    }

    public function get_msn()
    {
        $token = 'necd7ej39i9noubf';
        $instanceId = '268849';
        $url = 'https://api.chat-api.com/instance' . $instanceId . '/messages?token=' . $token;
        $result = file_get_contents($url); // Send a request
        $data = json_decode($result, 1); // Parse JSON
        foreach ($data['messages'] as $message) { // Echo every message
            echo "Sender:" . $message['author'] . "<br>";
            echo "Message: " . $message['body'] . "<br>";
        }
    }
}
