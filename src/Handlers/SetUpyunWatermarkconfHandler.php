<?php

namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class SetUpyunconfHandler.
 */
class SetUpyunWatermarkconfHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * SetHandler constructor.
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
            'enable' => 'required',
        ], [
            'enable.required' => '请选择是否打开水印',
        ]);
        $this->settings->set('upyun.watermark', $this->request->input('enable'));
        $allData = $this->settings->all()->toArray();

        $data = array_only($allData, [
            'upyun.watermark',
        ]);

        if ($data) {
            $this->withCode(200)->withData($data)->withMessage('修改设置成功!');
        } else {
            $this->withCode(201)->withError('修改设置失败!');
        }
    }

}

