<?php

namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Cloud\Cloud;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class SetUpyunModeVideoHandler.
 */
class SetUpyunModeVideoHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;
    protected  $cloud;

    /**
     * SetUpyunModeVideoHandler constructor.
     *
     * @param \Illuminate\Container\Container                         $container
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $settings
     */
    public function __construct(Container $container, SettingsRepository $settings)
    {
        parent::__construct($container);
        $this->settings = $settings;

    }

    public function execute()
    {
        $this->validate($this->request, [
            'uploadModeVideo'     => 'required|in:ori,VP9,H264,H265',
            'uploadModeVideoSize' => 'required|in:ori,1080,720,480',
        ], [
            'uploadModeVideo.required'     => '请选择上传视频保存类型',
            'uploadModeVideo.in'           => '请在ori,VP9,H264,H265中选择',
            'uploadModeVideoSize.required' => '请选择上传视频清晰度',
            'uploadModeVideoSize.in'       => '请在ori,1080,720,480中选择(单位:P)',
        ]);
        $this->settings->set('upyun.uploadModeVideo', $this->request->input('uploadModeVideo'));
        $this->settings->set('upyun.uploadModeVideoSize', $this->request->input('uploadModeVideoSize'));

        $allData = $this->settings->all()->toArray();

        $data = array_only($allData, [
            'upyun.uploadModeVideo',
            'upyun.uploadModeVideoSize',
        ]);

        if ($data) {
            $this->withCode(200)->withData($data)->withMessage('修改设置成功!');
        } else {
            $this->withCode(201)->withMessage('修改设置失败!');
        }
    }

}

