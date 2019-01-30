<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/30
 * Time: 16:52
 */

namespace Joinive\Avatar\Facades;
use Illuminate\Support\Facades\Facade;
class Avatar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'avatar';
    }
}