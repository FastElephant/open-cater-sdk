<?php

namespace FastElephant\OpenCater;

use FastElephant\OpenCater\Module\Comment;
use FastElephant\OpenCater\Module\Delivery;
use FastElephant\OpenCater\Module\Im;
use FastElephant\OpenCater\Module\Oauth;
use FastElephant\OpenCater\Module\Order;
use FastElephant\OpenCater\Module\Pick;
use FastElephant\OpenCater\Module\Product;
use FastElephant\OpenCater\Module\Shop;
use GuzzleHttp\Client;

class OpenCaterClient
{
    use Order, Delivery, Product, Shop, Oauth, Comment, Im, Pick;

    /**
     * 请求地址
     * @var string
     */
    protected $url = '';

    /**
     * 合作码
     * @var string
     */
    protected $partnerCode = '';

    /**
     * 合作秘钥
     * @var string
     */
    protected $partnerSecret = '';

    /**
     * 请求超时时长
     * @var int
     */
    protected $timeout = 0;

    /**
     * 平台
     * @var string
     */
    protected $platform = '';

    /**
     * @var int
     */
    protected $shopId = 0;

    /**
     * @var int
     */
    protected $merchantId = 0;

    /**
     * @var string
     */
    protected $requestUrl = '';

    /**
     * 请求值
     * @var array
     */
    protected $request = [];

    /**
     * 返回值
     * @var array
     */
    protected $response = [];

    /**
     * 绑定类型[1：接单类；2：非接单类]
     * @var int
     */
    protected $bindType = 1;

    /**
     * 版本号
     * @var int
     */
    protected $version = 1;

    public function __construct($merchantId = 0, $platform = '', $shopId = 0)
    {
        $this->platform = $platform;
        $this->shopId = $shopId;
        $this->merchantId = $merchantId;
        $this->url = in_array($platform, ['eleme', 'eleme.retail']) ? config('open-cater.jstApiUrl') : config('open-cater.apiUrl');
        $this->partnerCode = config('open-cater.partnerCode');
        $this->partnerSecret = config('open-cater.partnerSecret');
        $this->timeout = config('open-cater.timeout', 5);
    }

    /**
     * @return string
     */
    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    /**
     * @param $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @param int $bindType
     */
    public function setBindType($bindType = 2)
    {
        $this->bindType = $bindType;
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param $path
     * @param $param
     * @return array
     */
    protected function call($path, $param = [])
    {
        $apiUrl = $this->url . $path;

        $this->requestUrl = $apiUrl;

        $param['partner_code'] = $this->partnerCode;
        $param['platform'] = $this->platform;
        $param['partner_shop_id'] = $this->shopId;
        $param['partner_merchant_id'] = $this->merchantId;
        $param['bind_type'] = $this->bindType;
        $param['version'] = $this->version;
        $param['timestamp'] = time();
        $param['sign'] = $this->makeSign($this->partnerCode, $this->partnerSecret, $param, $param['timestamp']);

        $client = new Client(['verify' => false, 'timeout' => $this->timeout]);

        $this->request = $param;

        $errMsg = '';

        try {
            $strResponse = $client->post($apiUrl, ['json' => $param])->getBody()->getContents();
        } catch (\Exception $e) {
            $errMsg = $e->getMessage();
            $strResponse = '';
        }

        $this->monitorProcess($path, json_encode($param, JSON_UNESCAPED_UNICODE), $strResponse);

        if (!$strResponse) {
            return ['code' => 555, 'message' => $errMsg ?: '响应值为空'];
        }

        $arrResponse = json_decode($strResponse, true);
        if (!$arrResponse) {
            return ['code' => 555, 'message' => '响应值格式错误'];
        }

        $this->response = $arrResponse;

        if ($arrResponse['code'] != 0) {
            return ['code' => $arrResponse['code'], 'message' => $arrResponse['message']];
        }

        return ['code' => 0, 'result' => $arrResponse['result']];
    }

    /**
     * @param $path
     * @return string
     */
    protected function pathToEventName($path)
    {
        $toEventName = [
            'product/report' => '查询菜品报告地址',
            'product/sync-result' => '查询同步菜品结果',
            'product/sync' => '同步菜品',
            'shop/oauth/bind-url' => '获取绑定地址',
            'shop/oauth/unbind-url' => '获取解绑地址',
            'shop/bind-list' => '获取已绑定的商铺列表',
            'shop/oauth/bind-platform-list' => '获取商户已绑定的平台',
            'shop/oauth/rebind' => '更换发货门店',
            'shop/oauth/unbind' => '直接解绑门店（软解绑）',
            'shop/list' => '获取已绑定店铺列表',
            'shop/delete' => '删除绑定店铺',
            'shop/oauth-mirror/sync' => '同步授权',
            'shop/detail' => '获取店铺详情',
            'order/list' => '获取订单列表',
            'order/batch-query-detail' => '批量获取订单详情',
            'order/confirm-order' => '确认接单',
            'order/pick/predict-finish-time' => '预计出餐时间',
            'order/pick/finish' => '完成出餐',
            'order/delivery/sync-state' => '同步自配送状态',
            'order/delivery/sync-location' => '同步骑手位置',
            'order/today-statistics' => '获取当日订单统计数据',
            'order/cancel-reason' => '获取取消订单原因选项',
            'order/cancel-order' => '取消订单',
            'order/origin-detail' => '获取订单原始数据',
            'order/dish-detail' => '获取菜品详情',
            'order/complete-order' => '完成订单',
            'order/delivery/fee' => '获取配送费',
            'order/delivery/call' => '呼叫配送',
            'order/delivery/cancel' => '取消配送',
            'im/status' => '获取门店IM状态',
            'im/update-status' => '设置门店IM状态',
            'im/read-msg' => '阅读IM消息',
            'im/user-last-read-time' => '获取会话最新已读时间',
            'comment/list' => '获取评论列表',
            'comment/reply' => '回复评论',
            'comment/score' => '获取门店评分',
        ];
        return $toEventName[$path] ?? '未定义事件';
    }

    /**
     * 签名
     * @param $code
     * @param $secret
     * @param $param
     * @param $time
     * @return string
     */
    protected function makeSign($code, $secret, $param, $time)
    {
        $tmpArr = array(
            "partner_code" => $code,
            "timestamp" => $time,
        );

        foreach ($param as $k => $v) {
            $tmpArr[$k] = $v;
        }

        ksort($tmpArr);

        $str = $secret;

        foreach ($tmpArr as $k => $v) {
            if ($v === false) {
                $v = 'false';
            }
            if ($v === true) {
                $v = 'true';
            }
            if (empty($v) && $v != 0) {
                continue;
            }
            $str .= $k . $v;
        }

        $signature = sha1($str);
        return strtolower($signature);
    }

    /**
     * 监控请求过程（交给子类实现）
     * @param $path
     * @param $strRequest
     * @param $strResponse
     */
    public function monitorProcess($path, $strRequest, $strResponse)
    {
    }
}
