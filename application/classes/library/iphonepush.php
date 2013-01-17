<?php
class Library_Iphonepush
{
        public  $deviceTokens     = array();
        public  $message          = '';
        public  $badge            = 1;
        public  $data             = false;
        private $_passphrase      = '1234';
        private $_certificatePath = './docs/certificates/UnleashedDisPushCK.pem';
        private $_ctx             = '';
        private $_fp              = '';

        public function __construct()
        {
                return $this;
        }
        
        public static function instance()
        {
            return new Library_Iphonepush();
        }

        public function setTokens($deviceTokens = array())
        {
            $this->deviceTokens = $deviceTokens;
            return $this;
        }
        
        public function setBadge($badge = 1)
        {
            $this->badge = $deviceTokens;
            return $this;
        }


        public function setMessage($message = '')
        {
            $body['aps'] = array(
                    'badge' => $this->badge,
                    'alert' => $message,
                    'sound' => 'default'
                    );
            
            if($this->data != false)
            {
                foreach($this->data as $key=>$value)
                {
                    $body[$key] = $value ;
                }
            }
            $payload = json_encode($body);
            $this->message = $payload;
            return $this;
        }
        
        public function setData($data = array())
        {
            $this->data = $data;
            return $this;
        }
        
        public function openConnect()
        {
            $this->_ctx = stream_context_create();
            stream_context_set_option($this->_ctx, 'ssl', 'local_cert', $this->_certificatePath);
            stream_context_set_option($this->_ctx, 'ssl', 'passphrase', $this->_passphrase);

            $this->_fp = stream_socket_client(
                        'ssl://gateway.push.apple.com:2195', $err,
                        $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $this->_ctx);
            
            return $this;
        }

        public function send()
        {
            foreach ($this->deviceTokens as $token)
            {
                $msg    = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($this->message)) . $this->message;
                $result = fwrite($this->_fp, $msg, strlen($msg));
            }
            return $this;
        }
        public function closeConnect()
        {
            fclose($this->_fp);
        }
    
}
?>
