<?php
/**
 * This file is part of Notadd.
 *
 * @author        linxing <linxing@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      17-6-22 下午7:15
 */
namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class GetUpyunModeVideoHandler.
 */
class GetUpyunModeVideoHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * GetUpyunModeVideoHandler constructor.
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
        $this->withCode(200)->withData([
            'uploadModeVideo' => $this->settings->get('upyun.uploadModeVideo'),
            'uploadModeVideoSize' => $this->settings->get('upyun.uploadModeVideoSize'),
        ])->withMessage('获取视频上传模式成功!');
    }
}
