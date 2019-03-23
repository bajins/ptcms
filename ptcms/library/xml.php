<?php
class xml{

    public function Encode($data, $root = 'ptcms', $attr = '', $encoding = 'utf-8') {
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml  = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= $this->dataToXml($data);
        $xml .= "</{$root}>";
        return preg_replace('/[\x00-\x1f]/', '', $xml);
    }

    /**
     * 数据XML编码
     *
     * @param mixed  $data 数据
     * @param string $parentkey
     * @return string
     */
    protected function dataToXml($data, $parentkey = '') {
        $xml = '';
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $key = $parentkey;
            }
            $key=$key?$key:'xmldata';
            $xml .= "<{$key}>";
            if (is_array($val) || is_object($val)) {
                $len = strlen("<{$key}>");
                $con = $this->dataToXml($val, $key);
                if (strpos($con, "<{$key}>") === 0) {
                    $con = substr($con, $len, -($len + 1));
                }
                $xml .= $con;
            } elseif (strlen($val) > 150 || preg_match('{[<>&\'|"]+}', $val)) {
                $xml .= '<![CDATA[' . $val . ']]>';
            } else {
                $xml .= $val;
            }
            $xml .= "</{$key}>";
        }
        return $xml;
    }

    public function decode($con) {
        if($con{0}=='<'){
            $con=simplexml_load_string($con);
        }else{
            $con=simplexml_load_file($con);
        }
        return json_decode(json_encode($con),true);
    }


}