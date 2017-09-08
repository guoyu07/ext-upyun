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

class ListHandler extends Handler
{
    /**
     * @var \Notadd\Cloud\Cloud
     */
    protected $cloud;

    /**
     * ListHandler constructor.
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

        $name = $this->request->name;
        $data = $this->cloud->fileList($name);
        dd($data);
        if ($data) {
            $this->withCode(200)->withData($data)->withMessage('success');
        } else {
            $this->withCode(200)->withMessage('folder not exist');
        }

    }
}