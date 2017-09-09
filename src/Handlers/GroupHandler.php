<?php
/**
 * Created by PhpStorm.
 * User: bc021
 * Date: 17-6-20
 * Time: 上午11:26
 */

namespace Notadd\Cloud\Handlers;

use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Cloud\Models\Group;

class GroupHandler extends Handler
{
    public function execute()
    {
        $data = Group::paginate(20)->toArray();

        return $this->withCode(200)->withData($data)->withMessage('成功返回云空间文件夹');
    }
}

