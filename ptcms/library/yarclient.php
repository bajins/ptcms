<?php
if (!class_exists('Yar_Client')) {
    class Yar_client {

        protected $uri;

        /**
         * @param $uri
         */
        public function __construct($uri) {
            $this->uri = $uri;
        }

        public function __call($method, $params = array()) {
            $data = $this->pack($method, $params);
            if ($res = $this->exec($data)) {
                return $res;
            }
            exit('采集错误！方法' . $method . ' 参数：' . json_encode($params));
        }

        public function exec($data) {
            //执行最多5次，防止失败！
            for ($i = 1; $i < 5; $i++) {
                $content = http::post($this->uri, $data);
                if ($content) return $this->unpack($content);
            }
            return false;
        }

        /**
         * from Yar_Simple_Protocol
         *
         * @param $method
         * @param $params
         * @return array
         */
        public static function pack($method, $params) {
            $struct = array(
                'i' => time(),
                'm' => $method,
                'p' => $params,
            );
            $body = str_pad('PHP', 8, chr(0)) . serialize($struct);
            //transaction id
            $header = sprintf('%08x', mt_rand());
            //protocl version
            $header .= sprintf('%04x', 0);
            //magic_num, default is: 0x80DFEC60
            $header .= '80DFEC60';
            //reserved
            $header .= sprintf('%08x', 0);
            //reqeust from who
            $header .= sprintf('%064x', 0);
            //request token, used for authentication
            $header .= sprintf('%064x', 0);
            //request body len
            $header .= sprintf('%08x', strlen($body));
            $data = '';
            for ($i = 0; $i < strlen($header); $i = $i + 2)
                $data .= chr(hexdec('0x' . $header[$i] . $header[$i + 1]));
            $data .= $body;
            return $data;
        }

        /**
         * curl结果解析
         *
         * @param $con
         * @return mixed
         * @throws Exception
         */
        public static function unpack($con) {
            $ret = unserialize(substr($con, 82 + 8));
            if ($ret['s'] === 0) {
                return $ret['r'];
            } elseif (is_array($ret)) {
                throw new Exception($ret['e']);
            } else {
                return '';
                //throw new Exception('malformed response header');
            }
        }
    }
}
