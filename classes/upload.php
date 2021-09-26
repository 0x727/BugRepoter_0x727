<?php

/**
 * Created by PhpStorm.
 * User: Yang
 * Date: 2019/8/13
 * Time: 13:49
 */
class Upload
{
    //文件上传路径
    protected $path = './public/Uploads/';

    //允许文件上传的后缀
    protected $allowSuffix = ['jpg', 'jpeg', 'gif', 'png'];

    //mime类型
    protected $allowMime = ['image/jpg', 'image/jpeg', 'image/gif', 'image/wbmp', 'image/png'];

    //允许上传的大小
    protected $maxSize = 5 * 1024 * 1024;

    //是否启用默认的前缀
    protected $isRandName = true;

    //文件的前缀
    protected $prefix = 'up_';

    //错误号和错误信息
    protected $errorNumber;
    protected $errorInfo;

    //文件的信息

    //文件名
    protected $oldName;

    //文件的后缀
    protected $suffix;

    //文件的大小
    protected $size;

    //文件的mime
    protected $mime;

    //文件的临时文件的路径
    protected $tmpName;

    //文件新名字
    protected $newName;

    /**
     * 构造方法
     * Upload constructor.
     * @param array $arr
     */
    public function __construct($arr = [])
    {
        foreach ($arr as $key => $value) {
            $this->setOption($key, $value);
        }
    }

    /**
     * 判断$key是不是我的成员属性，如果是就设置
     * @param $key
     * @param $value
     */
    protected function setOption($key, $value)
    {
        //得到所有的成员属性
        $keys = array_keys(get_class_vars(__CLASS__));
        if (in_array($key, $keys)) {
            $this->$key = $value;
        }
    }

    /**
     * 文件上传函数
     * key 就是input框中的name属性值
     * @param $key
     * @return bool|string
     */
    public function uploadFile($key)
    {
        //判断有没有设置路径 path
        if (empty($this->path)) {
            $this->setOption('errorNumber', -1);
            return false;
        }
        //判断该路径是否存在是否可写
        if (!$this->check()) {
            $this->setOption('errorNumber', -2);
            return false;
        }
        //判断$_FILES里面的error信息是否为0，如果为0则说明文件信息在服务器端可以直接获取，提取信息保存到成员属性中
        $error = $_FILES[$key]['error'];
        if ($error) {
            $this->setOption('errorNumber', -3);
            return false;
        } else {
            //提取文件相关信息并且保存到成员属性中
            $this->getFileInfo($key);
        }
        //判断文件的大小、mime、后缀是否符合
        if (!$this->checkSize() || !$this->checkMime() || !$this->checkSuffix()) {
            return false;
        }
        //得到新的文件名字
        $this->newName = $this->createNewName();
        //判断是否是上传文件，并且是移动上传文件
        if (is_uploaded_file($this->tmpName)) {
            if (move_uploaded_file($this->tmpName, $this->path . $this->newName)) {
                return $this->path . $this->newName;
            } else {
                $this->setOption('errorNumber', -7);
                return false;
            }
        } else {
            $this->setOption('errorNumber', -6);
            return false;
        }
    }

    /**
     * 检测文件夹是否存在，是否可写
     * @return bool
     */
    protected function check()
    {
        //文件夹不存在或者不是目录。创建文件夹
        if (!file_exists($this->path) || !is_dir($this->path)) {
            return mkdir($this->path, 0777, true);
        }
        //判断文件是否可写
        if (!is_writeable($this->path)) {
            return chmod($this->path, 0777);
        }
        return true;
    }

    /**
     * 根据key得到文件信息
     * @param $key
     */
    protected function getFileInfo($key)
    {
        //得到文件的名字
        $this->oldName = $_FILES[$key]['name'];
        //得到文件的mime类型
        $this->mime = $_FILES[$key]['type'];
        //得到文件的临时文件
        $this->tmpName = $_FILES[$key]['tmp_name'];
        //得到文件大小
        $this->size = $_FILES[$key]['size'];
        //得到文件后缀
        $this->suffix = pathinfo($this->oldName)['extension'];
    }

    /**
     * 判断文件大小
     * @return bool
     */
    protected function checkSize()
    {
        if ($this->size > $this->maxSize) {
            $this->setOption('errorNumber', -3);
            return false;
        }
        return true;
    }

    /**
     * 判断mime类型
     * @return bool
     */
    protected function checkMime()
    {
        if (!in_array($this->mime, $this->allowMime)) {
            $this->setOption('errorNumber', -4);
            return false;
        }
        return true;
    }

    /**
     * 判断后缀
     * @return bool
     */
    protected function checkSuffix()
    {
        if (!in_array($this->suffix, $this->allowSuffix)) {
            $this->setOption('errorNumber', -5);
            return false;
        }
        return true;
    }

    /**
     * 创建新名字
     * @return string
     */
    protected function createNewName()
    {
        if ($this->isRandName) {
            $name = $this->prefix . uniqid() . '.' . $this->suffix;
        } else {
            $name = $this->prefix . $this->oldName;
        }
        return $name;
    }

    /**
     * 读取不可访问属性的值时，__get() 会被调用。也就是，当想要获取一个类的私有属性，或者获取一个类并为定义的属性时。该魔术方法会被调用。
     * @param $name
     * @return string
     */
    public function __get($name)
    {
        if ($name == 'errorNumber') {
            return $this->errorNumber;
        } elseif ($name == 'errorInfo') {
            return $this->getErrorInfo();
        }
    }

    /**
     * 获取错误信息
     * @return string
     */
    protected function getErrorInfo()
    {
        switch ($this->errorNumber) {
            case -1:
                $str = '文件路径没有设置';
                break;
            case -2:
                $str = '文件不是目录或者不可写';
                break;
            case -3:
                $str = '文件超过指定大小';
                break;
            case -4:
                $str = 'mime类型不符合';
                break;
            case -5:
                $str = '文件后缀不符合';
                break;
            case -6:
                $str = '不是上传文件';
                break;
            case -7:
                $str = '移动失败';
                break;
            case 1:
                $str = '超出ini设置大小';
                break;
            case 2:
                $str = '超出html表单大小';
                break;
            case 3:
                $str = '文章只有部分上传';
                break;
            case 4:
                $str = '没有文件上传';
                break;
            case 6:
                $str = '找不到临时文件';
                break;
            case 7:
                $str = '文件写入失败';
                break;
        }
        return $str;
    }

}