<?php
/**
 * Created by PhpStorm.
 * User: bc021
 * Date: 17-6-17
 * Time: 下午2:02
 */
namespace Notadd\Cloud\Controllers;

use Notadd\Foundation\Mail\Handlers\TestHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Cloud\Handlers\DeleteAllHandler;

/**
 * Class QueryController
 *
 * @package Notadd\Cloud\Controllers
 */
class QueryController extends Controller
{
    /**
     * @param \Notadd\Cloud\Handlers\DeleteAllHandler $handler
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function deleteAll(DeleteAllHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }

    public function test(TestHandler $handler)
    {
        return $handler->toResponse()->generateHttpResponse();
    }
}