<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/30
 * Time: 16:49
 */

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019-01-10
 * Time: 14:06
 */
namespace Joinive\Avatar;
use Illuminate\Config\Repository;
class Avatar {
    protected $config;

    /**
     * 构造方法
     * Avatar constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('avatar');
    }
    /**
     * 生成图像
     * @return resource 图片资源
     */
    private function generate($name)
    {
        // 创建图片资源
        $img_res = imagecreate($this->config['width'], $this->config['height']);
        // 背景颜色
        $bg_color = imagecolorallocate($img_res, mt_rand(120, 190), mt_rand(120, 190), mt_rand(120, 190));
        // 文字颜色
        $font_color = imagecolorallocate($img_res, mt_rand(190, 255), mt_rand(190, 255), mt_rand(190, 255));
        // 填充背景色
        imagefill($img_res, 1, 1, $bg_color);
        // 计算文字的宽高
        $pos = imagettfbbox($this->config['size'], 0, $this->config['font_file'], mb_substr($name, 0, 1));
        $font_width = $pos[2] - $pos[0] + 0.32 * $this->config['size'];
        $font_height = $pos[1] - $pos[5] + -0.16 * $this->config['size'];
        // 写入文字
        imagettftext($img_res, $this->config['size'], 0, ($this->config['width'] - $font_width) / 2, ($this->config['height'] - $font_height) / 2 + $font_height, $font_color, $this->config['font_file'], mb_substr($name, 0, 1));
        return $img_res;
    }

    /**
     * 输出图片（默认输出到浏览器，给定输出文件位置则输出到文件）
     * @param $name
     * @param string|false $path 保存路径
     */
    public function output($name, $path = false)
    {
        $img_res = $this->generate($name);
        // 确定输出类型和生成用的方法名
        $content_type = 'image/' . $this->config['type'];
        $generateMethodName = 'image' . $this->config['type'];
        // 确定是否输出到浏览器
        if (!$path) {
            header("Content-type: " . $content_type);
            $generateMethodName($img_res);
        } else {
            $this->createPath($path);
            $generateMethodName($img_res, $path);
        }
        // 释放图片内存
        imagedestroy($img_res);
    }

    /**
     * 创建目录
     * @param $path
     * @return bool
     */
    public function createPath($path) {
        try {
            $dir_path = dirname($path);
            if (!file_exists($dir_path)) {
                @mkdir($dir_path, '0777', true);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}