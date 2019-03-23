<?php

class Driver_View_Mc extends PT_View {

    // 编译解析
    public function compile($content) {
        $left  = preg_quote('{', '/');
        $right = preg_quote('}', '/');
        if (strpos($content, '<?xml') !== false) {
            $content = str_replace('<?xml', '<?php echo "<?xml";?>', $content);
        }
        if (!preg_match('/' . $left . '.*?' . $right . '/s', $content)) return $content;
        // 解析载入
        $content = preg_replace_callback('/' . $left . 'include\s+file\s*\=\s*(\'|\")([^\}]*?)\1\s*' . $right . '/i', array('self', 'parseInlcude'), $content);
        // 解析代码
        $content = preg_replace_callback('/' . $left . '(code|php)' . $right . '(.*?)' . $left . '\/\1' . $right . '/is', array('self', 'parseEncode'), $content);
        // 模板注释
        $content = preg_replace('/' . $left . '\/\*.*?\*\/' . $right . '/s', '', $content);
        $content = preg_replace('/' . $left . '\/\/.*?' . $right . '/', '', $content);
        // 解析变量
        $content = preg_replace_callback('/' . $left . '(\$\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.[\w\-]+)*))((?:\s*\|\s*[\w\:]+(?:\s*=\s*(?:@|"[^"]*"|\'[^\']*\'|#[\w\-]+|\$[\w\-]+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.[\w\-]+)*)|[^\|\:,"\'\s]*?)(?:\s*,\s*(?:@|"[^"]*"|\'[^\']*\'|#[\w\-]+|\$[\w\-]+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.[\w\-]+)*)|[^\|\:,"\'\s]*?))*)?)*)\s*' . $right . '/', array('self', 'parseVariable'), $content);
        // 解析函数
        $content = preg_replace_callback('/' . $left . '(\=|~)\s*(.+?)\s*' . $right . '/', array('self', 'parseFunction'), $content);
        // 解析判断
        $content = preg_replace_callback('/' . $left . '(if|else\s*if)\s+(.+?)\s*' . $right . '/', array('self', 'parseJudgment'), $content);
        $content = preg_replace('/' . $left . 'else\s*' . $right . '/i', '<?php else:?>', $content);
        $content = preg_replace('/' . $left . 'sectionelse\s*' . $right . '/i', '<?php endforeach;else:foreach(array(1) as $__loop):?>', $content);
        $content = preg_replace('/' . $left . '\/if\s*' . $right . '/i', '<?php endif;?>', $content);
        // 解析链接
        $content = preg_replace_callback('/' . $left . 'link\=((?:"[^"]*"|\'[^\']*\'|#\w+|\$\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.\w+)*)|[^"\'\s]+?)(?:(?:\s+\w+\s*\=\s*(?:"[^"]*"|\'[^\']*\'|#\w+|\$\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.\w+)*)|[^"\'\s]+?))*?))\s*' . $right . '/i', array('self', 'parseLink'), $content);
        // 解析微件
        $content = preg_replace_callback('/' . $left . 'block((?:\s+\w+\s*\=\s*(?:"[^"]*"|\'[^\']*\'|#\w+|\$\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.\w+)*)|[^"\'\s]+?))+)\s*' . $right . '/i', array('self', 'parseBlock'), $content);
        // 解析循环
        $content = preg_replace_callback('/' . $left . 'loop\s*=([\'|"]?)(\$?\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.\w+)*))\1\s*' . $right . '/i', array('self', 'parseLoop'), $content);
        $content = preg_replace_callback('/' . $left . 'loop' . $right . '/i', array('self', 'parseLoop'), $content);
        $content = preg_replace_callback('/' . $left . 'section((?:\s+\w+\s*\=\s*(?:"[^"]*"|\'[^\']*\'|#\w+|\$\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.\w+)*)|[^"\'\s]+?))+)\s*' . $right . '/i', array('self', 'parseSection'), $content);
        $content = preg_replace('/' . $left . '\/(?:loop|section)\s*' . $right . '/i', '<?php endforeach; endif;?>', $content);
        // 还原代码
        $content = preg_replace_callback('/' . chr(2) . '(.*?)' . chr(3) . '/', array('self', 'parseDecode'), $content);
        // 内容后续处理

        /*if (!APP_DEBUG) {
            $content = preg_replace_callback('/<style[^>]*>([^<]*)<\/style>/isU', array('self', 'compressCss'), $content);
            $content = preg_replace_callback('/<script[^>]*>([^<]+?)<\/script>/isU', array('self', 'compressJs'), $content);
            $content = preg_replace(array("/>\s+</"), array('> <'), $content);
            $content = preg_replace('/\?>\s*<\?php/', '', $content);
            $content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    '), ' ', $content);
            $content = strip_whitespace($content);
        }*/
        // 返回内容
        return $content;
    }

    // css压缩
    public function compressCss($match) {
        return '<style type = "text/css">' . compressCss($match['1']) . '</style>';
    }

    // js压缩
    public function compressJs($march) {
        return str_replace($march['1'], compressJs($march['1']), $march['0']);
    }

    // 解析变量名
    private function parseVar($var) {
        $var = strtolower(is_array($var) ? reset($var) : trim($var));
        if (substr($var, 0, 1) !== '$') $var = '$' . $var;
        if (preg_match('/^\$\w+(\.[\w\-]+)+$/', $var)) {
            if (substr($var, 0, 4) === '$pt.') {
                $vars = array_pad(explode('.', $var, 3), 3, '');
                switch ($vars[1]) {
                    case 'server':
                        $var = '$_SERVER[\'' . strtoupper($vars[2]) . '\']';
                        break;
                    case 'const':
                        $var = strtoupper($vars[2]);
                        break;
                    case 'config':
                        $var = '$this->pt->config->get("' . $vars[2] . '")';
                        break;
                    case 'get':
                        $var = '$_GET[\'' . $vars[2] . '\']';
                        break;
                    case 'post':
                        $var = '$_POST[\'' . $vars[2] . '\']';
                        break;
                    case 'request':
                        $var = '$_REQUEST[\'' . $vars[2] . '\']';
                        break;
                    case 'cookie':
                        $var = 'Cookie("' . $vars[2] . '")';
                        break;
                    case 'getad':
                        // 当广告js存在时才会解析出来 否则不会解析
                        if (is_file(PT_ROOT . "/public/" . $this->pt->config->get('addir') . "/" . $vars[2] . ".js")) {
                            $var = "'<script type=\"text/javascript\" src=\"'.PT_DIR . '/public/" . $this->pt->config->get('addir') . "/" . $vars[2] . ".js\"></script>'";
                        } else {
                            $var = '""';
                        }
                        break;
                    default:
                        $var = strtoupper($vars[1]);
                        break;
                }
            } else {
                $var = preg_replace('/\.(\w+)/', '[\'\1\']', $var);
            }
        }
        return $var;
    }

    /**
     * @param $string
     * @param $format
     * @return array
     * $format中值true则按照变量解析 其他为默认值
     */
    private function parseAttribute($string, $format) {
        $attribute = array('_etc' => array());
        preg_match_all('/(?:^|\s+)(\w+)\s*\=\s*(?|(")([^"]*)"|(\')([^\']*)\'|(#)(\w+)|(\$)(\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.\w+)*))|()([^"\'\s]+?))(?=\s+\w+\s*\=|$)/', $string, $match);
        foreach ($match[0] as $key => $value) {
            $name  = strtolower($match[1][$key]);
            $value = trim($match[3][$key]);
            if (isset($format[$name]) && is_bool($format[$name])) {
                $attribute[$name] = $format[$name] ? self::parseVar($value) : $value;
            } else {
                switch ($match[2][$key]) {
                    case '#':
                        $value = strtoupper($value);
                        break;
                    case '$':
                        $value = self::parseVar($value);
                        break;
                    case '"':
                    case '\'':
                        $value = $match[2][$key] . $value . $match[2][$key];
                        break;
                    default:
                        $value = is_numeric($value) ? $value : var_export($value, true);
                }
                if (isset($format[$name])) {
                    $attribute[$name] = $value;
                } else {
                    $attribute['_etc'][$name] = $value;
                }
            }
        }
        return array_merge($format, $attribute);
    }

    // 解析变量
    private function parseVariable($matches) {
        $variable = self::parseVar($matches[1]);
        if ($matches[2]) {
            preg_match_all('/\s*\|\s*([\w\:]+)(\s*=\s*(?:@|"[^"]*"|\'[^\']*\'|#\w+|\$\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.\w+)*)|[^\|\:,"\'\s]*?)(?:\s*,\s*(?:@|"[^"]*"|\'[^\']*\'|#\w+|\$\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.\w+)*)|[^\|\:,"\'\s]*?))*)?(?=\||$)/', $matches[2], $match);
            foreach ($match[0] as $key => $value) {
                $function = $match[1][$key];
                if (strtolower($function) == 'parsetpl') {
                    return "<?php if (\$_parsetplfile=parseTpl($variable)){include \$_parsetplfile;}?>";
                } elseif (in_array($function, array('date', 'default'))) {
                    $function .= 'var';
                }
                $param = array($variable);
                preg_match_all('/(?:=|,)\s*(?|(@)|(")([^"]*)"|(\')([^\']*)\'|(#)(\w+)|(\$)(\w+(?:(?:\[(?:[^\[\]]+|(?R))*\])*|(?:\.\w+)*))|()([^\|\:,"\'\s]*?))(?=,|$)/', $match[2][$key], $mat);
                if (array_search('@', $mat[1]) !== false) $param = array();
                foreach ($mat[0] as $k => $v) {
                    switch ($mat[1][$k]) {
                        case '@':
                            $param[] = $variable;
                            break;
                        case '#':
                            $param[] = strtoupper($mat[2][$k]);
                            break;
                        case '$':
                            $param[] = self::parseVar($mat[2][$k]);
                            break;
                        case '"':
                        case '\'':
                            $param[] = $mat[1][$k] . $mat[2][$k] . $mat[1][$k];
                            break;
                        default:
                            $param[] = is_numeric($mat[2][$k]) ? $mat[2][$k] : var_export($mat[2][$k], true);
                    }
                }
                $variable = $function . '(' . implode(',', $param) . ')';
            }
        }
        return "<?php echo $variable;?>";
    }


    // 解析载入
    private function parseInlcude($matches) {
        //20141215 防止写空导致调用死循环
        if ($matches['2']) {
            $includeFile = $this->getTplFile($matches['2']);
            $truereturn  = realpath($includeFile);
            if ($truereturn) {
                $content = file_get_contents($truereturn);
                return $this->compile($content);
            }
            halt("include参数有误，得不到设置的模版，参数[{$matches['2']}]，解析模版路径[{$includeFile}]");
        }
        return '';
    }

    // 解析函数
    private function parseFunction($matches) {
        $operate    = $matches[1] === '=' ? 'echo' : '';
        $expression = preg_replace_callback('/\$\w+(?:\.\w+)+/', array('self', 'parseVar'), $matches[2]);
        return "<?php $operate $expression;?>";
    }

    // 解析判断
    private function parseJudgment($matches) {
        $judge     = strtolower($matches[1]) === 'if' ? 'if' : 'elseif';
        $condition = preg_replace_callback('/\$\w+(?:\.\w+)+/', array('self', 'parseVar'), $matches[2]);
        return "<?php $judge($condition):?>";
    }

    // 解析链接
    private function parseLink($matches) {
        $attribute = self::parseAttribute('_type_=' . $matches[1], array('_type_' => false));
        if (!is_string($attribute['_type_'])) return $matches[0];
        $var = array();
        foreach ($attribute['_etc'] as $key => $value) {
            $var[] = "'$key'=>$value";
        }
        return "<?php echo U(\"{$attribute['_type_']}\",array(" . implode(',', $var) . "));?>";
    }

    // 解析微件
    private function parseBlock($matches) {
        $attribute = self::parseAttribute($matches[1], array('method' => false, 'name' => false));
        $var       = array();
        foreach ($attribute['_etc'] as $key => $value) {
            $var[] = "'$key'=>$value";
        }
        if (empty($attribute['name']) || $attribute['name'] === false) {
            return "<?php echo \$this->pt->block->getdata('{$attribute['method']}',array(" . implode(',', $var) . "));?>";
        } else {
            $name = '$' . $attribute['name'];
            return "<?php $name=\$this->pt->block->getdata('{$attribute['method']}',array(" . implode(',', $var) . "));?>";
        }
    }

    // 解析循环
    private function parseLoop($matches) {
        $loop = empty($matches[2]) ? '$list' : (self::parseVar($matches[2]));
        return "<?php if(is_array($loop)): foreach($loop as \$key =>\$loop):?>";
    }

    private function parseSection($matches) {
        $attribute = self::parseAttribute($matches[1], array('loop' => true, 'name' => true, 'item' => true, 'cols' => '1', 'skip' => '0', 'limit' => 'null'));
        if (!is_string($attribute['loop'])) return $matches[0];
        $name = is_string($attribute['name']) ? $attribute['name'] : '$i';
        $list = is_string($attribute['item']) ? $attribute['item'] : '$loop';
        return "<?php if(is_array({$attribute['loop']}) && (array()!={$attribute['loop']})): $name=array(); {$name}['loop']=array_slice({$attribute['loop']},{$attribute['skip']},{$attribute['limit']},true); {$name}['total']=count({$attribute['loop']}); {$name}['count']=count({$name}['loop']); {$name}['cols']={$attribute['cols']}; {$name}['add']={$name}['count']%{$attribute['cols']}?{$attribute['cols']}-{$name}['count']%{$attribute['cols']}:0; {$name}['order']=0; {$name}['row']=1;{$name}['col']=0;foreach(array_pad({$name}['loop'],{$name}['add'],array()) as {$name}['index']=>{$name}['list']): $list={$name}['list']; {$name}['order']++; {$name}['col']++; if({$name}['col']=={$attribute['cols']}): {$name}['col']=0; {$name}['row']++; endif; {$name}['first']={$name}['order']==1; {$name}['last']={$name}['order']=={$name}['count']; {$name}['extra']={$name}['order']>{$name}['count'];?>";
    }

    // 解析代码
    private function parseEncode($matches) {
        return chr(2) . base64_encode(strtolower($matches[1]) === 'php' ? "<?php {$matches[2]};?>" : trim($matches[2])) . chr(3);
    }

    // 还原代码
    private function parseDecode($matches) {
        return base64_decode($matches[1]);
    }
}


function compressJS($content) {
    $lines = explode("\n", $content);
    foreach ($lines as &$line) {
        $line = trim($line) . "\n";
    }
    return implode('', $lines);
    /* $content = preg_replace('/{(\/\/[^\n]*)/', '{', $content); // {//注释情况特殊处理
     $content = preg_replace('/(^\/\/[^\n]*)|([\s]+\/\/[^\n]*)/', '', $content); //行注释
     $content = preg_replace('/\)\s*[\n\r]+/', ');', $content); //圆括号换行处理
     $content = preg_replace('/([\w\$\'""]+?)\s*[\n\r]+\s*([\w\$\'""]+?)/', '$1;$2', $content); //圆括号换行处理
     $content = preg_replace('/[\n\r\t]+/', ' ', $content); //换行空格等过滤
     $content = preg_replace('/>\\s</', '><', $content);
     $content = preg_replace('/\\/\\*.*?\\*\\//i', '', $content);
     $content = preg_replace("/[\n\r\t]+}/", "}", $content);
     $content = preg_replace("/}[\n\r\t]+/", "}", $content);
     $content = preg_replace("/[\n\r\t]+{/", "{", $content);
     $content = preg_replace("/{[\n\r\t]+/", "{", $content);
     $content = preg_replace("/[\n\r\t]+;/", ";", $content);
     $content = preg_replace("/;[\n\r\t]+/", ";", $content);
     $content = preg_replace("/[\n\r\t]+:/", ":", $content);
     $content = preg_replace("/:[\n\r\t]+/", ":", $content);
     $content = preg_replace("/[\n\r\t]+=/", "=", $content);
     $content = preg_replace("/=[\n\r\t]+/", "=", $content);
     $content = preg_replace("/,[\n\r\t]{2,}/", ", ", $content);
     $content = preg_replace("/[\n\r\t]{2,}/", " ", $content);
     //js特殊处理补全
     $content = preg_replace("/;}/", "}", $content);
     $content = preg_replace("/}var/", "};var", $content);
     $content = preg_replace("/}return/", "};return", $content);
     return $content;*/
}

function compressCss($content) {

    $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content); //删除注释
    $content = preg_replace('![ ]{2,}!', ' ', $content); //删除注释
    $content = str_replace(array("\r\n", "\r", "\n", "\t"), '', $content); //删除空白
    return $content;
}
