<?php

class collect
{

    public static function getcontent($data)
    {
        if (is_string($data)) $data = array('rule' => $data, 'charset' => 'auto');
        $content = http::get($data['rule']);
        if ($content) {
            // 处理编码
            if (empty($data['charset']) || !in_array($data['charset'], array('auto', 'utf-8', 'gbk'))) {
                $data['charset'] = 'auto';
            }
            // 检测编码
            if ($data['charset'] == 'auto') {
                if (preg_match('/charset[=\'\s]+big/i', $content)) {
                    $data['charset'] = 'big5';
                } elseif (preg_match('/charset[=\'"\s]+gb/i', $content) || preg_match('/encoding[=\'"\s]+gb/i', $content)) {
                    $data['charset'] = 'gbk';
                } elseif (mb_detect_encoding($content) != 'UTF-8') {
                    $data['charset'] = 'gbk';
                }
            }
            // 转换
            switch ($data['charset']) {
                case 'gbk':
                    $content = mb_convert_encoding($content, 'UTF-8', 'GBK');
                    break;
                case 'big5':
                    $content = mb_convert_encoding($content, 'UTF-8', 'big-5');
                    $content = big5::toutf8($content);
                    break;
                default:
            }
            //错误标识
            if (!empty($data['error']) && strpos($content, $data['error']) !== false) {
                return '';
            }
            if (!empty($data['replace'])) {
                $content = collect::replace($content, $data['replace']);
            }
            return $content;
        }
        return '';
    }

    /**
     * 根据正则批量获取
     *
     * @param array  $pregArr      正则
     * @param string $code         源内容
     * @param int    $needposition 确定是否需要间距数字
     * @return array|bool
     */
    public static function getMatchAll($pregArr, $code, $needposition = 0)
    {
        if (is_numeric($pregArr)) {
            return $pregArr;
        } elseif (is_string($pregArr)) {
            $pregArr = array('rule' => self::parseMatchRule($pregArr));
        } elseif (empty($pregArr['rule'])) {
            return array();
        }
        if (!collect::isreg($pregArr['rule'])) return array();
        $pregstr = '{' . $pregArr['rule'] . '}';
        $pregstr .= empty($pregArr['option']) ? '' : $pregArr['option'];
        $matchvar = $match = array();
        if (!empty($pregstr)) {
            if ($needposition) {
                preg_match_all($pregstr, $code, $match, PREG_SET_ORDER + PREG_OFFSET_CAPTURE);
            } else {
                preg_match_all($pregstr, $code, $match);
            }
        }
        if (is_array($match)) {
            if ($needposition) {
                foreach ($match as $var) {
                    if (is_array($var)) {
                        $matchvar[] = $var[count($var) - 1];
                    } else {
                        $matchvar[] = $var;
                    }
                }
            } else {
                if (isset($match['2'])) {
                    $count = count($match);
                    foreach ($match['1'] as $k => $v) {
                        if ($v == '') {
                            for ($i = 2; $i < $count; $i++) {
                                if (!empty($match[$i][$k])) {
                                    $match['1'][$k] = $match[$i][$k];
                                    break;
                                }
                            }
                        }
                    }
                }
                if (isset($match['1'])) {
                    $matchvar = $match['1'];
                } else {
                    return false;
                }
            }
            if (!empty($pregArr['replace'])) {
                foreach ($matchvar as $k => $v) {
                    $matchvar[$k] = collect::replace($v, $pregArr['replace']);
                }
            }
            return $matchvar;
        }
        return array();
    }

    /**
     * 根据正则获取指定数据 单个
     *
     * @param mixed  $pregArr 正则
     * @param string $code    源内容
     * @return bool|string
     */
    public static function getMatch($pregArr, $code)
    {
        if (is_numeric($pregArr)) {
            return $pregArr;
        } elseif (empty($pregArr) || (isset($pregArr['rule']) && empty($pregArr['rule']))) {
            return '';
        } else if (is_string($pregArr)) {
            $pregArr = array('rule' => self::parseMatchRule($pregArr), 'replace' => array());
        }
        if (!collect::isreg($pregArr['rule'])) return $pregArr['rule'];
        $pregstr = '{' . $pregArr['rule'] . '}';
        $pregstr .= empty($pregArr['option']) ? '' : $pregArr['option'];
        preg_match($pregstr, $code, $match);
        if (isset($match['1'])) {
            if (empty($pregArr['replace'])) {
                return $match['1'];
            } else {
                return collect::replace($match[1], $pregArr['replace']);
            }
        }
        return '';
    }

    /**
     * 内容替换 支持正则批量替换
     *
     * @param string $con 代替换的内容
     * @param array  $arr 替换规则数组 单个元素如下
     *                    array(
     *                    'rule'=>'规则1',//♂后面表示要替换的 内容
     *                    'option'=>'参数',
     *                    'method'=>1,//1 正则 0普通
     *                    v                ),
     * @return mixed
     */
    public static function replace($con, array $arr)
    {
        foreach ($arr as $v) {
            if (!empty($v['rule'])) {
                $tmp         = explode('♂', $v['rule']);
                $rule        = $tmp['0'];
                $replace     = isset($tmp['1']) ? $tmp['1'] : '';
                $v['option'] = isset($v['option']) ? $v['option'] : '';
                if ($v['method'] == 1) { //正则
                    $con = preg_replace("{" . $rule . "}{$v['option']}", $replace, $con);
                } else {
                    if (strpos($v['option'], 'i') === false) {
                        $con = str_replace($rule, $replace, $con);
                    } else {
                        $con = str_ireplace($rule, $replace, $con);
                    }
                }
            }
        }
        return $con;
    }

    /**
     * 处理链接，根据当前页面地址得到完整的链接地址
     *
     * @param string $url  当前链接
     * @param string $path 当前页面地址
     * @return string
     */
    public static function parseUrl($url, $path)
    {
        if ($url) {
            if (strpos($url, '://') === false) {
                if (substr($url, 0, 1) == '/') {
                    $tmp = parse_url($path);
                    $url = $tmp['scheme'] . '://' . $tmp['host'] . $url;
                } elseif (substr($url, 0, 3) == '../') {
                    $url = dirname($path) . substr($url, 2);
                } elseif (substr($path, -1) == '/') {
                    $url = $path . $url;
                } else {
                    $url = dirname($path) . '/' . $url;
                }
            }
            return $url;
        } else {
            return '';
        }
    }

    /**
     * 内容切割方式
     *
     * @param string $strings 要切割的内容
     * @param string $argl    左侧标识 如果带有.+?则为正则模式
     * @param string $argr    右侧标识 如果带有.+?则为正则模式
     * @param bool   $lt      是否包含左切割字符串
     * @param bool   $gt      是否包含右切割字符串
     * @return string
     */
    public static function cut($strings, $argl, $argr, $lt = false, $gt = false)
    {
        if (!$strings) return ("");
        if (strpos($argl, ".+?")) {
            $argl = strtr($argl, array("/" => "\/"));
            if (preg_match("/" . $argl . "/", $strings, $match)) $argl = $match[0];
        }
        if (strpos($argr, ".+?")) {
            $argr = strtr($argr, array("/" => "\/"));
            if (preg_match("/" . $argr . "/", $strings, $match)) $argr = $match[0];
        }
        $args = explode($argl, $strings);
        $args = explode($argr, $args[1]);
        $args = $args[0];
        if ($args) {
            if ($lt) $args = $argl . $args;
            if ($gt) $args .= $argr;
        } else {
            $args = "";
        }
        return ($args);
    }

    public static function parseMatchRule($rules)
    {
        $replace_pairs = array(
            '{'    => '\{',
            '}'    => '\}',
            '[内容]' => '(.*?)',
            '[数字]' => '\d*',
            '[空白]' => '\s*',
            '[任意]' => '.*?',
            '[参数]' => '[^\>\<]*?',
            '[属性]' => '[^\>\<\'"]*?',
        );
        if (is_array($rules)) {
            $rules['rule'] = strtr($rules['rule'], $replace_pairs);
            return $rules;
        }
        return strtr($rules, $replace_pairs);
    }

    public static function isreg($str)
    {
        return (strpos($str, ')') !== false || strpos($str, '(') !== false);
    }

    public static function parseListData($data)
    {
        $list = array();
        $num  = 0;
        foreach ($data as $v) {
            if ($v) {
                if ($num) {
                    if ($num != count($v)) return array();
                } else {
                    $num = count($v);
                }
            }
        }
        foreach ($data as $k => $v) {
            if ($v) {
                foreach ($v as $kk => $vv) {
                    $list[$kk][$k] = $vv;
                }
            } else {
                for ($i = 0; $i < $num; $i++) {
                    $list[$i][$k] = '';
                }
            }
        }
        return $list;
    }


}