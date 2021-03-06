<?php
/**
 * This file is part of Notadd.
 *
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-23 下午3:31
 */
namespace Notadd\Cloud\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Illuminate\Container\Container;
use Notadd\Cloud\Cloud;

class DirDeleteHandler extends Handler
{
    /**
     * @var \Notadd\Cloud\Cloud
     */
    protected $cloud;

    /**
     * DirDeleteHandler constructor.
     *
     * @param \Illuminate\Container\Container $container
     * @param \Notadd\Cloud\Cloud             $cloud
     */
    public function __construct(Container $container, Cloud $cloud)
    {
        parent::__construct($container);
        $this->cloud = $cloud;
    }

    /**
     * @return $this
     */
    public function Execute()
    {
        $name = $this->request->name;
        $data = $this->cloud->dirDelete($name);
        if ($data) {
            return $this->withCode(200)->withData($data)->withMessage('成功删除云空间文件夹');
        } else {
            return $this->withCode(500)->withError('folder not exist');
        }

    }
}