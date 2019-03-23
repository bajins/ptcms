<?php

class PT_Filter
{

    public function filter($value, $filter, $default)
    {
        //指定值
        if (is_array($filter)) return in_array($value, $filter) ? $value : $default;
        //判断filter的合法性
        if (!is_string($filter)) return $value;
        //过滤数据
        switch ($filter) {
            case 'int':
                return is_null($value) ? (is_null($default) ? 0 : $default) : intval($value);
            case 'str':
            case 'string':
                return is_null($value) ? (is_null($default) ? '' : $default) : strval($value);
            case 'arr':
                return is_array($value) ? $value : (is_array($default) ? $default : array());
            case 'time':
                $res = strtotime($value);
                return $res ? $res : 0;
            default:
                return empty($value) ? $default : ($this->regex($value, $filter) ? $value : $default);
        }
    }

    public function regex($value, $rule)
    {
        $validate = [
            //必填
            'require'    => '/.+/',
            //邮箱
            'email'      => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            //链接
            'url'        => '/^http:\/\/[a-zA-Z0-9]+\.[a-zA-Z0-9]+[\/=\?%\-&_~`@\[\]\':+!]*([^<>\"\"])*$/',
            //货币
            'currency'   => '/^\d+(\.\d+)?$/',
            //数字
            'number'     => '/^\d+$/',
            //邮编
            'zip'        => '/^[0-9]\d{5}$/',
            //电话
            'tel'        => '/^1[\d]{10}$/',
            //整型
            'integer'    => '/^[-\+]?\d+$/',
            //带小数点
            'double'     => '/^[-\+]?\d+(\.\d+)?$/',
            //英文字母
            'english'    => '/^[a-zA-Z]+$/',
            //中文汉字
            'chinese'    => '/^[\x{4e00}-\x{9fa5}]+$/u',
            //拼音
            'pinyin'     => '/^[a-zA-Z0-9\-\_]+$/',
            //用户名
            'username'   => '/^(?!_)(?!.*?_$)[a-zA-Z0-9_\x{4e00}-\x{9fa5}]{3,15}$/u',
            //英文字符
            'en'         => '/^[a-zA-Z0-9_\s\-\.]+$/',
            //中文字符
            'cn'         => '/^[\w\s\-\x{4e00}-\x{9fa5}]+$/u',
            //安全字符串
            'safestring' => '/^[^\$\?]+$/'
        ];
        // 检查是否有内置的正则表达式
        if (isset($validate[strtolower($rule)])) $rule = $validate[strtolower($rule)];
        return preg_match($rule, strval($value)) === 1;
    }

    //安全的剔除字符 单行等 用于搜索 链接等地方
    public function safeStrip($kw)
    {
        if (strlen($kw) == 0) return '';
        $kw        = strip_tags($kw);
        $badString = '~!@#$%^&*()+|=\\{}[];\'"/<>?';
        $length    = strlen($badString);
        $pos       = 0;
        while ($pos < $length) {
            $kw = str_replace($badString{$pos}, '', $kw);
            $pos++;
        }
        return preg_replace('/([\:\r\n\t]+)/', '', $kw);
    }

    /**
     * 过滤掉html字符
     *
     * @param string $text
     * @param string $tags 允许的html标签
     * @return mixed|string
     */
    public function safetext($text, $tags = 'br')
    {
        $text = trim($text);
        //完全过滤注释
        $text = preg_replace('/<!--?.*-->/', '', $text);
        //完全过滤动态代码
        $text = preg_replace('/<\?|\?' . '>/', '', $text);
        //完全过滤js
        $text = preg_replace('/<script?.*\/script>/', '', $text);

        $text = str_replace('[', '&#091;', $text);
        $text = str_replace(']', '&#093;', $text);
        $text = str_replace('|', '&#124;', $text);
        //br
        $text = preg_replace('/<br(\s\/)?' . '>/i', '[br]', $text);
        $text = preg_replace('/<p(\s\/)?' . '>/i', '[br]', $text);
        $text = preg_replace('/(\[br\]\s*){10,}/i', '[br]', $text);
        //过滤危险的属性，如：过滤on事件lang js
        while (preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i', $text, $mat)) {
            $text = str_replace($mat[0], $mat[1], $text);
        }
        while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
            $text = str_replace($mat[0], $mat[1] . $mat[3], $text);
        }
        //允许的HTML标签
        $text = preg_replace('/<(' . $tags . ')( [^><\[\]]*)>/i', '[\1\2]', $text);
        $text = preg_replace('/<\/(' . $tags . ')>/Ui', '[/\1]', $text);
        //过滤多余html
        $text = preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|table|td|th|tr|i|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a)[^><]*>/i', '', $text);
        //过滤合法的html标签
        while (preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i', $text, $mat)) {
            $text = str_replace($mat[0], str_replace('>', ']', str_replace('<', '[', $mat[0])), $text);
        }
        //转换引号
        while (preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i', $text, $mat)) {
            $text = str_replace($mat[0], $mat[1] . '|' . $mat[3] . '|' . $mat[4], $text);
        }
        //过滤错误的单个引号
        while (preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i', $text, $mat)) {
            $text = str_replace($mat[0], str_replace($mat[1], '', $mat[0]), $text);
        }
        //转换其它所有不合法的 < >
        $text = str_replace('<', '&lt;', $text);
        $text = str_replace('>', '&gt;', $text);
        $text = str_replace('"', '&quot;', $text);
        //反转换
        $text = str_replace('[', '<', $text);
        $text = str_replace(']', '>', $text);
        $text = str_replace('|', '"', $text);
        //过滤多余空格
        $text = str_replace('  ', ' ', $text);
        return $text;
    }
}

class filter extends PT_filter
{

}