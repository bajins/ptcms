<?php

function subid($zym_29)
{
    return floor($zym_29 / 1000);
}
function file_size_format($zym_28 = 0, $zym_17 = 2)
{
    $zym_16 = array("B", "KB", "MB", "GB", "TB", "PB");
    $zym_21 = 0;
    while ($zym_28 >= 1024) {
        $zym_28 /= 1024;
        $zym_21++;
    }
    $zym_23['size'] = round($zym_28, $zym_17);
    $zym_23['unit'] = $zym_16[$zym_21];
    return $zym_23['size'] . $zym_23['unit'];
}
function truncate($zym_24, $zym_22, $zym_15 = '', $zym_20 = 0)
{
    if (empty($zym_24) or empty($zym_22) or strlen($zym_24) < $zym_22) {
        return $zym_24;
    }
    if (function_exists('mb_substr')) {
        $zym_12 = mb_substr($zym_24, $zym_20, $zym_22, 'utf-8');
    } elseif (function_exists('iconv_substr')) {
        $zym_12 = iconv_substr($zym_24, $zym_20, $zym_22, 'utf-8');
    } else {
        preg_match_all('/[\\x01-\\x7f]|[\\xc2-\\xdf][\\x80-\\xbf]|[\\xe0-\\xef][\\x80-\\xbf]{2}|[\\xf0-\\xff][\\x80-\\xbf]{3}/', $zym_24, $zym_8);
        $zym_12 = implode('', array_slice(reset($zym_8), $zym_20, $zym_22));
    }
    return $zym_12 . $zym_15;
}
function msort($zym_27, $zym_31, $zym_32 = 'asc')
{
    $zym_7 = $zym_14 = array();
    foreach ($zym_27 as $zym_13 => $zym_33) {
        $zym_7[$zym_13] = $zym_33[$zym_31];
    }
    if ($zym_32 == 'asc') {
        asort($zym_7);
    } else {
        arsort($zym_7);
    }
    foreach ($zym_7 as $zym_13 => $zym_33) {
        $zym_14[] = $zym_27[$zym_13];
    }
    return $zym_14;
}
function pt_strlen($zym_24)
{
    if (function_exists('mb_strlen')) {
        $zym_22 = mb_strlen($zym_24, 'utf-8');
    } elseif (function_exists('iconv_strlen')) {
        $zym_22 = iconv_strlen($zym_24, 'utf-8');
    } else {
        preg_match_all('/[\\x01-\\x7f]|[\\xc2-\\xdf][\\x80-\\xbf]|[\\xe0-\\xef][\\x80-\\xbf]{2}|[\\xf0-\\xff][\\x80-\\xbf]{3}/', $zym_24, $zym_8);
        $zym_22 = count(reset($zym_8));
    }
    return $zym_22;
}
function showintro($zym_30)
{
    $zym_30 = str_replace('<br />', '<br />　　', nl2br($zym_30));
    $zym_30 = str_replace("\n", '', $zym_30);
    return '　　' . $zym_30;
}
function showchapter($zym_30)
{
    return '　　' . str_replace("\n", '<br/><br/>　　', $zym_30);
}
function wan($zym_11)
{
    if ($zym_11 > 10000) {
        return ceil($zym_11 / 10000) . '万';
    } else {
        return $zym_11;
    }
}
function cntime($zym_10)
{
    $zym_15 = NOW_TIME > $zym_10 ? '前' : '后';
    $zym_11 = abs(NOW_TIME - $zym_10);
    if ($zym_11 < 60) {
        return $zym_11 . '秒' . $zym_15;
    } elseif ($zym_11 < 3600) {
        return ceil($zym_11 / 60) . '分钟' . $zym_15;
    } elseif (date('d') == date('d', $zym_10)) {
        return ceil($zym_11 / 3600) . '小时' . $zym_15;
    } elseif (date('m') == date('m', $zym_10)) {
        return ceil($zym_11 / 86400) . '天' . $zym_15;
    } else {
        return date('Y-m-d', $zym_10);
    }
}
function br2p($zym_25)
{
    $zym_25 = str_replace(['<br>', '<br />'], '<br/>', $zym_25);
    $zym_25 = str_replace('<br/><br/>', '<br/>', $zym_25);
    $zym_7 = explode('<br/>', $zym_25);
    foreach ($zym_7 as &$zym_33) {
        if (substr($zym_33, 0, 6) == '　　') {
            $zym_33 = substr($zym_33, 6);
        }
    }
    return '<p>' . implode('</p><p>', $zym_7) . '</p>';
}
function r($zym_9)
{
    $zym_26 = http::get('http://www.ptcms.com/api/app/check.json?domain=' . PT_Base::getInstance()->request->getSiteCode() . '&product_id=2');
    if (!$zym_26) {
        PT_Base::getInstance()->response->error('连接官网服务器失败，请访问查看服务器是否可以访问www.ptcms.com', 'w');
    }
    $zym_26 = json_decode($zym_26, true);
    F($zym_9, base64_encode(convert_uuencode(serialize($zym_26['data']))));
}
function l($zym_9)
{
    if (!is_file($zym_9)) {
        r($zym_9);
    }
    $zym_5 = unserialize(convert_uudecode(base64_decode(F($zym_9))));
    if (empty($zym_5['domain']) && filemtime($zym_9) + 60 < NOW_TIME) {
        r($zym_9);
        $zym_5 = unserialize(convert_uudecode(base64_decode(F($zym_9))));
    }
    return $zym_5;
}
function a($zym_6)
{
    $zym_18 = PT_Base::getInstance();
    $zym_19 = $zym_18->request->getSiteCode();
    if (empty($zym_6['domain'])) {
        $zym_18->response->error('您的域名暂未获得授权！', 'w');
    } elseif (strpos('.' . $zym_19, $zym_6['domain']) || $_SERVER['REQUEST_METHOD'] == 'cli') {
        if (strrev(base64_decode(base64_decode($zym_6['license']))) == md5(md5($zym_6['domain']) . '2')) {
            define('A', 1);
            if (md5($zym_6['version']) == 'd4d034fefda050bdac304409c8c82f5d') {
                $zym_18->config->set('formatchapter', true);
            }
        } else {
            $zym_18->response->error('校验授权失败，请通过合法渠道获得授权！', 'w');
        }
    } else {
        $zym_18->response->error('当前域名与授权域名不正确！', 'w');
    }
}
//a(l(APP_PATH . '/common/data/license'));