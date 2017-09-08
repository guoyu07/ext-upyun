<?php

namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class SetUpyunconfHandler.
 */
class SetUpyunModeAudioHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * SetUpyunModeAudioHandler constructor.
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
            'uploadModeAudio' => 'required|in:ori,MP3,AAC',
        ], [
            'uploadModeAudio.required' => '请选择上传音频保存类型',
            'uploadModeAudio.in'       => '请在ori,MP3,AAC中选择',
        ]);
        $this->settings->set('upyun.uploadModeAudio', $this->request->input('uploadModeAudio'));

        $allData = $this->settings->all()->toArray();

        $data = array_only($allData, [
            'upyun.uploadModeAudio',
        ]);

        if ($data) {
            $this->withCode(200)->withData($data)->withMessage('修改设置成功!');
        } else {
            $this->withCode(201)->withMessage('修改设置失败!');
        }
    }

}

