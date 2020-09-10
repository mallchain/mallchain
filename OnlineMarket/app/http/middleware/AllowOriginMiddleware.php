<?php


namespace app\http\middleware;


use app\Request;
use crmeb\interfaces\MiddlewareInterface;
use think\facade\Config;
use think\Response;
use app\common\logic\gwlp\GwlpGoods;
/**
 * 跨域中间件
 * Class AllowOriginMiddleware
 * @package app\http\middleware
 */
class AllowOriginMiddleware implements MiddlewareInterface
{
    /**
     * header头
     * @var array
     */
    protected $header = [
        'Access-Control-Allow-Origin'   => '*',
        'Access-Control-Allow-Headers'  => 'Authori-zation,Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
        'Access-Control-Allow-Methods'  => 'GET,POST,PATCH,PUT,DELETE,OPTIONS,DELETE',
        'Access-Control-Max-Age'        =>  '1728000'
    ];

    /**
     * 允许跨域的域名
     * @var string
     */
    protected $cookieDomain;

    /**
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle(Request $request, \Closure $next)
    {
        $this->cookieDomain = Config::get('cookie.domain', '');
        $header = $this->header;
        $origin = $request->header('origin');

        if ($origin && ('' != $this->cookieDomain && strpos($origin, $this->cookieDomain)))
            $header['Access-Control-Allow-Origin'] = $origin;

        if ($request->method(true) == 'OPTIONS') {
            $response = Response::create('ok')->code(200)->header($header);
        } else {
            $response = $next($request)->header($header);
        }


         if( cache("updateProductPrice_page_time")!=1&& cache("updateProductPrice_page_time_is_ok") != 1){
             cache("updateProductPrice_page_time_is_ok",1,120);
            $page=cache("updateProductPrice_page");

            if(empty($page)){
                $page=1;
            }

            $gwlp_goods = new GwlpGoods();
            $next_page= $gwlp_goods->updateProductPrice($page);
            if($next_page==1){
                $page++;
                cache("updateProductPrice_page",$page);
            }else{
                cache("updateProductPrice_page",0);
                cache("updateProductPrice_page_time",1,300);
            }
                 cache("updateProductPrice_page_time_is_ok",0,120);
         }

        return $response;
    }
}