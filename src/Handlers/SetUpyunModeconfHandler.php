<?php

namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class SetUpyunconfHandler.
 */
class SetUpyunModeconfHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * SetUpyunModeconfHandler constructor.
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
            'uploadModeImage' => 'required|in:ori,webp100,webp75',
        ], [
            'uploadModeImage.required' => '请选择上传图片保存类型',
            'uploadModeImage.in'       => '请在ori,webp100,web75中选择,webp75为默认',
        ]);
        $this->settings->set('upyun.uploadModeImage', $this->request->input('uploadModeImage'));

        $allData = $this->settings->all()->toArray();
        $data = array_only($allData, [
            'upyun.uploadModeImage',
        ]);

        if ($data) {
            $this->withCode(200)->withData($data)->withMessage('修改设置成功!');
        } else {
            $this->withCode(201)->withError('修改设置失败!');
        }
    }

}

