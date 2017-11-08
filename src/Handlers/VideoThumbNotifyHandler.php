<?php
/**
 * This file is part of Notadd.
 *
 * @author        aen233<zhanghe@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-08-04 22:49
 */
namespace Notadd\Cloud\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Cloud\Models\Detail;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Log;
use Notadd\Cloud\Cloud;
use Illuminate\Support\Facades\Request;

/**
 * Class NotifyHandler.
 */
class VideoThumbNotifyHandler extends Handler
{
    /**
     * @var \Notadd\Cloud\Cloud
     */
    protected $cloud;

    /**
     * NotifyHandler constructor.
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
        $data = Request::all();
        log::info($data);
        $detail = Detail::query()->where('task_id', $data['task_id'])->first();
        log::info($detail->path);

        $is_token = $detail->is_token;
        $this->cloud->setIsToken($is_token);

        $detail->video_thumb = $data['path'][0];

        if ($detail->save()) {
            log::info('modify data success');
        } else {
            log::error('modify data fail');
        }

    }
}
