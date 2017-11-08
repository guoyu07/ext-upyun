<?php
namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Cloud\Cloud;
use Notadd\Cloud\Models\Detail;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class GetUploadInfoHandler.
 */
class GetStatusHandler extends Handler
{
    /**
     * @var \Notadd\Cloud\Cloud
     */
    protected $cloud;

    /**
     * GetStatusHandler constructor.
     *
     * @param \Illuminate\Container\Container $container
     * @param \Notadd\Cloud\Cloud             $cloud
     */
    public function __construct(Container $container, Cloud $cloud)
    {
        parent::__construct($container);
        $this->cloud = $cloud;
    }

    public function execute()
    {
        $this->validate($this->request, [
            'task_id' => 'required',
        ], [
            'task_id.required' => 'task_id不能为空',
        ]);
        $task_id = $this->request->task_id;
        $detail = Detail::query()->where('task_id', $task_id)->first();
        $is_token = $detail->is_token;
        $this->cloud->setIsToken($is_token);
        $status = $this->cloud->getStatus($task_id, 'status');
        $statusResult = $status->tasks->$task_id;
        if ($statusResult == 100) {
            $res = $this->cloud->getStatus($task_id, 'result');
            parse_str($res->tasks->$task_id, $resdata);

            return $this->withCode(200)->withData($resdata['path'])->withMessage('异步处理已完成,结果如下');
        } elseif ($statusResult == '-1') {
            return $this->withCode(201)->withError('任务处理失败');
        } elseif ($statusResult == 'null') {
            return $this->withCode(202)->withError('任务尚未开始');
        } else {
            return $this->withCode(200)->withData([
                'percent' => round($statusResult,2).'%',
            ])->withMessage('任务正在处理,请稍等');
        }

    }
}
