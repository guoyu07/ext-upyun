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
use Notadd\Cloud\Models\Detail;
use Notadd\Cloud\Cloud;

/**
 * Class DeleteAllHandler.
 */
class DeleteAllHandler extends Handler
{
    protected $cloud;

    public function __construct(Container $container, Cloud $cloud)
    {
        parent::__construct($container);
        $this->cloud = $cloud;
    }

    /*
     * Execute Handler
     */
    public function Execute()
    {
        $this->validate($this->request, [
            'id' => 'required|json',
        ], [
            'id.required' => 'id为必填参数',
            'id.json'     => 'id必须为json',
        ]);
        $id = json_decode($this->request->id, true);

        $listKey = Detail::query()->whereIn('id', $id)->pluck('key')->toArray();

        foreach ($listKey as $item) {
            $this->cloud->delete($item);
        }

        $res = Detail::destroy($id);
        if ($res) {
            return $this->withCode(200)->withMessage('批量删除成功');
        } else {
            return $this->withCode(400)->withError('批量删除失败');
        }

    }
}
