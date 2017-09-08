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

class DeleteHandler extends Handler
{
    /**
     * @var \Notadd\Cloud\Cloud
     */
    protected $cloud;

    /**
     * DeleteHandler constructor.
     *
     * @param \Illuminate\Container\Container $container
     * @param \Notadd\Cloud\Cloud             $cloud
     */
    public function __construct(Container $container, Cloud $cloud)
    {
        parent::__construct($container);
        $this->cloud = $cloud;
    }


    public function Execute()
    {
        $this->validate($this->request, [
            'name'   => 'required',
            'folder' => 'required',
        ], [
            'name.required'   => 'name为必填参数',
            'folder.required' => 'folder为必填参数',
        ]);
        $name = $this->request->name;
        $folder = $this->request->folder;
        $URI = $folder ? '/' . $folder . '/' . $name : '/' . $name;
        if ($this->cloud->Delete($URI)) {
            return $this->withCode(200)->withMessage('删除成功');
        } else {
            return $this->withCode(201)->withMessage('删除失败');
        }

    }
}
