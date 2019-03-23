<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : login.php
 */
class Image {

    public $img;
    public $info;
    /* 水印相关常量定义 */
    //常量，标识左上角水印
    const IMAGE_WATER_NORTHWEST = 1;
    //常量，标识上居中水印
    const IMAGE_WATER_NORTH = 2;
    //常量，标识右上角水印
    const IMAGE_WATER_NORTHEAST = 3;
    //常量，标识左居中水印
    const IMAGE_WATER_WEST = 4;
    //常量，标识居中水印
    const IMAGE_WATER_CENTER = 5;
    //常量，标识右居中水印
    const IMAGE_WATER_EAST = 6;
    //常量，标识左下角水印
    const IMAGE_WATER_SOUTHWEST = 7;
    //常量，标识下居中水印
    const IMAGE_WATER_SOUTH = 8;
    //常量，标识右下角水印
    const IMAGE_WATER_SOUTHEAST = 9;

    public function __construct($var) {
        if (stripos($var, 'http') === 0) {
            $content = http::get($var);
        } elseif (is_file($var)) {
            $content = F($var);
        } else {
            return false;
        }
        $this->info['type'] = $this->gettype($content);
        $this->info['mime'] = 'image/' . $this->info['type'];
        if ('gif' == $this->info['type']) {
            $this->gif = new extend_gif($content);
            $this->img = imagecreatefromstring($this->gif->image());
        } else {
            $this->img = imagecreatefromstring($content);
        }
        $this->info['width'] = imagesx($this->img);
        $this->info['height'] = imagesy($this->img);
    }

    /**
     * 返回图像宽度
     *
     * @return integer 图像宽度
     */
    public function width() {
        return $this->info['width'];
    }

    /**
     * 返回图像高度
     *
     * @return integer 图像高度
     */
    public function height() {
        return $this->info['height'];
    }

    /**
     * 返回图像类型
     *
     * @return string 图像类型
     */
    public function type() {
        return $this->info['type'];
    }

    /**
     * 返回图像MIME类型
     *
     * @return string 图像MIME类型
     */
    public function mime() {
        return $this->info['mime'];
    }

    /**
     * 返回图像尺寸数组 0 - 图像宽度，1 - 图像高度
     *
     * @return array 图像尺寸
     */
    public function size() {
        return array($this->info['width'], $this->info['height']);
    }

    public function resize($w) {
        $h = ceil($w * $this->info['height'] / $this->info['width']);
        do {
            //创建新图像
            $img = imagecreatetruecolor($w, $h);
            // 调整默认颜色
            $color = imagecolorallocate($img, 255, 255, 255);
            imagefill($img, 0, 0, $color);

            //裁剪
            imagecopyresampled($img, $this->img, 0, 0, 0, 0, $w, $h, $this->info['width'], $this->info['height']);
            //销毁原图
            imagedestroy($this->img);

            //设置新图像
            $this->img = $img;
        } while (!empty($this->gif) && $this->gifNext());

        $this->info['width'] = $w;
        $this->info['height'] = $h;
        return $this->img;
    }

    public function thumb($width, $height) {
        //判断尺寸
        if ($this->info['width'] < $width && $this->info['height'] < $height) {
            //创建图像资源 需要填充的
            $img = imagecreatetruecolor($width, $height);
            imagefill($img, 0, 0, imagecolorallocate($img, 255, 255, 255));
            //全都小于指定缩略图尺寸
            if ($width / $this->info['width'] > $height / $this->info['height']) {
                //按高放大
                $this->resize(floor($this->info['width'] * $height / $this->info['height']));
                $x = ceil(($width - $this->info['width']) / 2);
                imagecopyresampled($img, $this->img, $x, 0, 0, 0, $this->info['width'], $height, $this->info['width'], $this->info['height']);
            } else {
                //按宽放大
                $this->resize($width);
                $y = ceil(($height - $this->info['height']) / 2);
                imagecopyresampled($img, $this->img, 0, $y, 0, 0, $width, $this->info['height'], $this->info['width'], $this->info['height']);
            }
            //销毁原图
            imagedestroy($this->img);
            //设置新图像
            $this->img = $img;
            $this->info['width'] = $width;
            $this->info['height'] = $height;
        } else {
            if ($width / $this->info['width'] > $height / $this->info['height']) {
                //按宽缩小
                $this->resize($width);
                $y = ($this->info['height'] - $height) / 2;
                $this->crop($width, $height, 0, $y);
            } else {
                //按高缩小
                $this->resize(floor($this->info['width'] * $height / $this->info['height']));
                $x = ($this->info['width'] - $width) / 2;
                $this->crop($width, $height, $x, 0);
            }
        }
    }

    public function crop($w, $h, $x = 0, $y = 0) {
        //设置保存尺寸
        do {
            //创建新图像
            $img = imagecreatetruecolor($w, $h);
            // 调整默认颜色
            $color = imagecolorallocate($img, 255, 255, 255);
            imagefill($img, 0, 0, $color);
            //裁剪
            imagecopyresampled($img, $this->img, 0, 0, $x, $y, $w, $h, $w, $h);
            //销毁原图
            imagedestroy($this->img);
            //设置新图像
            $this->img = $img;
        } while (!empty($this->gif) && $this->gifNext());

        $this->info['width'] = $w;
        $this->info['height'] = $h;
        return $this->img;
    }

    public function water($source, $posotion = image::IMAGE_WATER_SOUTHEAST, $alpha = 60) {
        //资源检测
        if (!is_file($source)) return false;
        //获取水印图像信息
        $info = getimagesize($source);
        if ($info === false || $this->info['width'] < $info['0'] || $this->info['height'] < $info['1']) return false;
        //创建水印图像资源
        $fun = 'imagecreatefrom' . image_type_to_extension($info[2], false);
        $water = $fun($source);
        //设定水印图像的混色模式
        imagealphablending($water, true);
        switch ($posotion) {
            /* 右下角水印 */
            case Image::IMAGE_WATER_SOUTHEAST:
                $x = $this->info['width'] - $info[0];
                $y = $this->info['height'] - $info[1];
                break;

            /* 左下角水印 */
            case Image::IMAGE_WATER_SOUTHWEST:
                $x = 0;
                $y = $this->info['height'] - $info[1];
                break;

            /* 左上角水印 */
            case Image::IMAGE_WATER_NORTHWEST:
                $x = $y = 0;
                break;

            /* 右上角水印 */
            case Image::IMAGE_WATER_NORTHEAST:
                $x = $this->info['width'] - $info[0];
                $y = 0;
                break;

            /* 居中水印 */
            case Image::IMAGE_WATER_CENTER:
                $x = ($this->info['width'] - $info[0]) / 2;
                $y = ($this->info['height'] - $info[1]) / 2;
                break;

            /* 下居中水印 */
            case Image::IMAGE_WATER_SOUTH:
                $x = ($this->info['width'] - $info[0]) / 2;
                $y = $this->info['height'] - $info[1];
                break;

            /* 右居中水印 */
            case Image::IMAGE_WATER_EAST:
                $x = $this->info['width'] - $info[0];
                $y = ($this->info['height'] - $info[1]) / 2;
                break;

            /* 上居中水印 */
            case Image::IMAGE_WATER_NORTH:
                $x = ($this->info['width'] - $info[0]) / 2;
                $y = 0;
                break;

            /* 左居中水印 */
            case Image::IMAGE_WATER_WEST:
                $x = 0;
                $y = ($this->info['height'] - $info[1]) / 2;
                break;

            default:
                /* 自定义水印坐标 */
                if (is_array($posotion)) {
                    list($x, $y) = $posotion;
                } else {
                    return false;
                }
        }
        do {
            //添加水印
            $src = imagecreatetruecolor($info[0], $info[1]);
            // 调整默认颜色
            $color = imagecolorallocate($src, 255, 255, 255);
            imagefill($src, 0, 0, $color);

            imagecopy($src, $this->img, 0, 0, $x, $y, $info[0], $info[1]);
            imagecopy($src, $water, 0, 0, 0, 0, $info[0], $info[1]);
            imagecopymerge($this->img, $src, $x, $y, 0, 0, $info[0], $info[1], $alpha);

            //销毁零时图片资源
            imagedestroy($src);

        } while (!empty($this->gif) && $this->gifNext());

        //销毁水印资源
        imagedestroy($water);
    }

    /**
     * 图像添加文字
     *
     * @param  string $text    添加的文字
     * @param  string $font    字体路径
     * @param  integer $size   字号
     * @param  string $color   文字颜色
     * @param  integer $locate 文字写入位置
     * @param  string $margin  文字边距
     * @param  integer $offset 文字相对当前位置的偏移量
     * @param  integer $angle  文字倾斜角度
     * @return mixed
     */
    public function text($text, $font, $size = 20, $color = '#ff0000', $locate = Image::IMAGE_WATER_SOUTHEAST, $margin = '', $offset = 0, $angle = 0) {
        //资源检测
        //if (!is_file($font)) return false;
        if ($margin === '') $margin = ceil($size / 2);
        //获取文字信息

        $info = imagettfbbox($size, $angle, $font, $text);
        $minx = min($info[0], $info[2], $info[4], $info[6]);
        $maxx = max($info[0], $info[2], $info[4], $info[6]);
        $miny = min($info[1], $info[3], $info[5], $info[7]);
        $maxy = max($info[1], $info[3], $info[5], $info[7]);

        /* 计算文字初始坐标和尺寸 */
        $x = $minx;
        $y = abs($miny);
        $w = $maxx - $minx;
        $h = $maxy - $miny;

        /* 设定文字位置 */
        switch ($locate) {
            /* 右下角文字 */
            case Image::IMAGE_WATER_SOUTHEAST:
                $x += $this->info['width'] - $w - $margin;
                $y += $this->info['height'] - $h - $margin;
                break;

            /* 左下角文字 */
            case Image::IMAGE_WATER_SOUTHWEST:
                $x += $margin;
                $y += $this->info['height'] - $h - $margin;
                break;

            /* 左上角文字 */
            case Image::IMAGE_WATER_NORTHWEST:
                // 起始坐标即为左上角坐标，无需调整
                $x += $margin;
                $y += $margin;
                break;

            /* 右上角文字 */
            case Image::IMAGE_WATER_NORTHEAST:
                $x += $this->info['width'] - $w - $margin;
                $y += $margin;
                break;

            /* 居中文字 */
            case Image::IMAGE_WATER_CENTER:
                $x += ($this->info['width'] - $w) / 2;
                $y += ($this->info['height'] - $h) / 2;
                break;

            /* 下居中文字 */
            case Image::IMAGE_WATER_SOUTH:
                $x += ($this->info['width'] - $w) / 2;
                $y += $this->info['height'] - $h - $margin;
                break;

            /* 右居中文字 */
            case Image::IMAGE_WATER_EAST:
                $x += $this->info['width'] - $w - $margin;
                $y += ($this->info['height'] - $h) / 2;
                break;

            /* 上居中文字 */
            case Image::IMAGE_WATER_NORTH:
                $x += ($this->info['width'] - $w) / 2;
                $y += $margin;
                break;

            /* 左居中文字 */
            case Image::IMAGE_WATER_WEST:
                $x += $margin;
                $y += ($this->info['height'] - $h) / 2;
                break;

            default:
                /* 自定义文字坐标 */
                if (is_array($locate)) {
                    list($posx, $posy) = $locate;
                    $x += $posx;
                    $y += $posy;
                } else {
                    $this->crop($this->info['width'], $this->info['height'] + ceil($size * 1.4));
                    $x += $this->info['width'] - $w;
                    $y += $this->info['height'] - $h;
                }
        }

        /* 设置偏移量 */
        if (is_array($offset)) {
            $offset = array_map('intval', $offset);
            list($ox, $oy) = $offset;
        } else {
            $offset = intval($offset);
            $ox = $oy = $offset;
        }

        /* 设置颜色 */
        if (is_string($color) && 0 === strpos($color, '#')) {
            $color = str_split(substr($color, 1), 2);
            $color = array_map('hexdec', $color);
            if (empty($color[3]) || $color[3] > 127) {
                $color[3] = 0;
            }
        } elseif (!is_array($color)) {
            return false;
        }

        do {
            /* 写入文字 */
            $col = imagecolorallocatealpha($this->img, $color[0], $color[1], $color[2], $color[3]);
            imagettftext($this->img, $size, $angle, $x + $ox, $y + $oy, $col, $font, $text);
        } while (!empty($this->gif) && $this->gifNext());
    }

    /**
     * 保存图像
     *
     * @param  string $type       图像类型
     * @param  boolean $interlace 是否对JPEG类型图像设置隔行扫描
     * @return string
     */
    public function save($type = null, $interlace = true) {
        if (empty($this->img)) return '';

        //自动获取图像类型
        if (is_null($type)) {
            $type = $this->info['type'];
        } else {
            $type = strtolower($type);
        }
        //保存图像
        if ('jpeg' == $type || 'jpg' == $type) {
            //JPEG图像设置隔行扫描
            imageinterlace($this->img, $interlace);
            ob_start();
            imagejpeg($this->img);
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        } elseif ('gif' == $type && !empty($this->gif)) {
            return $this->gif->save();
        } else {
            $fun = 'image' . $type;
            ob_start();
            $fun($this->img);
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    protected function gettype($content) {
        switch (substr($content, 0, 4)) {
            case chr('137') . 'PNG':
                return 'png';
                break;
            case 'GIF8':
                return 'gif';
                break;
            case chr('255') . chr('216') . chr('255') . chr('225'):
            case chr('255') . chr('216') . chr('255') . chr('224'):
                return 'jpg';
                break;
            default:
                exit;
        }
    }

    /* 切换到GIF的下一帧并保存当前帧，内部使用 */
    private function gifNext() {
        ob_start();
        ob_implicit_flush(0);
        imagegif($this->img);
        $img = ob_get_clean();

        $this->gif->image($img);
        $next = $this->gif->nextImage();

        if ($next) {
            $this->img = imagecreatefromstring($next);
            return $next;
        } else {
            $this->img = imagecreatefromstring($this->gif->image());
            return false;
        }
    }

    /**
     * 析构方法，用于销毁图像资源
     */
    public function __destruct() {
        empty($this->img) || imagedestroy($this->img);
    }
}