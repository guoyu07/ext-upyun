<?php
namespace Notadd\Cloud\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

/**
 * Class GetUpyunconfHandler.
 */
class GetUpyunconfHandler extends Handler
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * GetUpyunconfHandler constructor.
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
        $data = [
            'bucketName'       => $this->settings->get('upyun.bucketName'),
            'operatorName'     => $this->settings->get('upyun.operatorName'),
            'operatorPassword' => $this->settings->get('upyun.operatorPassword'),
            'domain'           => $this->settings->get('upyun.domain'),
            'folder'           => $this->settings->get('upyun.folder'),
        ];
        $this->withCode(200)->withData($data)->withMessage('获取公有云空间信息成功!');
    }
}
