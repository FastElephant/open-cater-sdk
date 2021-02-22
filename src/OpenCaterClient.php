<?php

namespace FastElephant\OpenCater;

use GuzzleHttp\Client;

class OpenCaterClient
{
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
     * 请求值
     * @var array
     */
    protected $request = [];

    /**
     * 返回值
     * @var array
     */
    protected $response = [];

    public function __construct($merchantId = 0, $platform = '', $shopId = 0)
    {
        $this->platform = $platform;
        $this->shopId = $shopId;
        $this->merchantId = $merchantId;
        $this->url = config('open-cater.apiUrl');
        $this->partnerCode = config('open-cater.partnerCode');
        $this->partnerSecret = config('open-cater.partnerSecret');
        $this->timeout = config('open-cater.timeout', 5);
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
     * 获取绑定地址
     * @param $bindShopId
     * @return array
     */
    public function getBindUrl($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
        ];
        return $this->call('shop/oauth/bind-url', $param);
    }

    /**
     * 获取解绑地址
     * @return array
     */
    public function getUnbindUrl($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
        ];
        return $this->call('shop/oauth/unbind-url', $param);
    }

    /**
     * 更换发货门店
     * @param $bindShopId
     * @return array
     */
    public function rebind($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
        ];
        return $this->call('shop/oauth/rebind', $param, true);
    }

    /**
     * 直接解绑绑定（软解绑）
     * @param $bindShopId
     * @return array
     */
    public function unbind($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
        ];
        return $this->call('shop/oauth/unbind', $param, true);
    }

    /**
     * 根据发货门店ID获取已绑定的商铺列表
     * @return array
     */
    public function getBindShopList()
    {
        return $this->call('shop/bind-list');
    }

    /**
     * 获取商户已绑定的平台
     * @return array
     */
    public function getBindPlatformList()
    {
        return $this->call('shop/oauth/bind-platform-list');
    }

    /**
     * 获取授权过的店铺列表
     * @return array
     */
    public function getShopList()
    {
        return $this->call('shop/list');
    }

    /**
     * 获取取消订单原因选项
     * @return array
     */
    public function getCancelOrderReason()
    {
        return $this->call('order/cancel-reason');
    }

    /**
     * 删除授权门店
     * @param $bindShopId
     * @return array
     */
    public function deleteShop($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
        ];
        return $this->call('shop/delete', $param, true);
    }

    /**
     * 一次性获取所有商品列表
     * @param $bindShopId
     * @return array
     */
    public function getAllProduct($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
        ];
        return $this->call('product/list', $param);
    }

    /**
     * 获取订单列表
     * @param $bindShopId
     * @param $date
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function getOrderList($bindShopId, $date, $page, $pageSize)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
            'date' => $date,
            'page' => $page,
            'page_size' => $pageSize
        ];
        return $this->call('order/list', $param);
    }

    /**
     * 确认接单
     * @param $orderId
     * @return array
     */
    public function confirmOrder($orderId)
    {
        $param = [
            'order_id' => $orderId
        ];
        return $this->call('order/confirm-order', $param, true);
    }

    /**
     * 取消订单
     * @param $orderId
     * @return array
     */
    public function cancelOrder($orderId, $type, $remark)
    {
        $param = [
            'order_id' => $orderId,
            'type' => $type,
            'remark' => $remark
        ];
        return $this->call('order/cancel-order', $param, true);
    }

    /**
     * 订单预计出餐时间
     * @param $orderId
     * @param $datetime
     * @return array
     */
    public function predictOrderFinishTime($orderId, $datetime)
    {
        $param = [
            'order_id' => $orderId,
            'datetime' => $datetime
        ];
        return $this->call('order/predict-order-finish-time', $param, true);
    }

    /**
     * 同步自配送状态
     * @param $orderId
     * @param $state
     * @param $riderName
     * @param $riderPhone
     * @return array
     */
    public function syncDeliveryState($orderId, $state, $riderName, $riderPhone)
    {
        $param = [
            'order_id' => $orderId,
            'rider_name' => $riderName,
            'rider_phone' => $riderPhone,
            'state' => $state
        ];
        return $this->call('order/delivery/sync-state', $param, true);
    }

    /**
     * 获取当日订单统计数据
     * @return array
     */
    public function getTodayStatistics()
    {
        return $this->call('order/today-statistics');
    }

    /**
     * 获取门店IM状态
     * @param $bindShopId
     * @return array
     */
    public function getImStatus($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId
        ];
        return $this->call('im/status', $param);
    }

    /**
     * 设置门店IM状态
     * @param $bindShopId
     * @param $status
     * @return array
     */
    public function setImStatus($bindShopId, $status)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
            'status' => $status
        ];
        return $this->call('im/update-status', $param, true);
    }

    /**
     * 获取消息已读状态
     * @param $bindShopId
     * @param $openUserId
     * @param $msgId
     * @return array
     */
    public function getMsgReadStatus($bindShopId, $openUserId, $msgId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
            'open_user_id' => $openUserId,
            'msg_id' => $msgId
        ];
        return $this->call('im/msg-read-status', $param);
    }

    /**
     * 获取会话最新已读时间
     * @param $bindShopId
     * @param $openUserId
     * @return array
     */
    public function getUserLastReadTime($bindShopId, $openUserId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
            'open_user_id' => $openUserId,
        ];
        return $this->call('im/user-last-read-time', $param);
    }

    /**
     * 同步授权店铺至外卖小蜜
     * @return array
     */
    public function syncToCaterTool($caterToolCode)
    {
        return $this->call('shop/sync-to-cater-tool', ['cater_tool_code' => $caterToolCode]);
    }

    /**
     * @param $path
     * @param $param
     * @param bool $isPost
     * @return array
     */
    protected function call($path, $param = [], $isPost = false)
    {
        $apiUrl = $this->url . $path;

        $param['partner_secret'] = $this->partnerSecret;
        $param['partner_code'] = $this->partnerCode;
        $param['platform'] = $this->platform;
        $param['partner_shop_id'] = $this->shopId;
        $param['partner_merchant_id'] = $this->merchantId;

        $client = new Client(['verify' => false, 'timeout' => $this->timeout]);

        $this->request = $param;

        if ($isPost) {
            $strResponse = $client->post($apiUrl, ['json' => $param])->getBody()->getContents();
        } else {
            $strResponse = $client->get($apiUrl, ['query' => $param])->getBody()->getContents();
        }

        $this->monitorProcess($path, json_encode($param, JSON_UNESCAPED_UNICODE), $strResponse);

        if (!$strResponse) {
            return ['code' => 500, 'message' => '响应值为空'];
        }

        $arrResponse = json_decode($strResponse, true);
        if (!$arrResponse) {
            return ['code' => 500, 'message' => '响应值格式错误'];
        }

        $this->response = $arrResponse;

        if ($arrResponse['code'] != 0) {
            return ['code' => 500, 'message' => $arrResponse['message']];
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
            'product/list' => '获取全部商品',
            'shop/oauth/bind-url' => '获取绑定地址',
            'shop/oauth/unbind-url' => '获取解绑地址',
            'shop/bind-list' => '获取已绑定的商铺列表',
            'shop/oauth/bind-platform-list' => '获取商户已绑定的平台',
            'shop/oauth/rebind' => '更换发货门店',
            'shop/oauth/unbind' => '直接解绑门店（软解绑）',
            'shop/list' => '获取已绑定店铺列表',
            'shop/delete' => '删除绑定店铺',
            'order/list' => '获取订单列表',
            'order/confirm-order' => '确认接单',
            'order/predict-order-finish-time' => '订单预计出餐时间',
            'order/delivery/sync-state' => '同步自配送状态',
            'order/today-statistics' => '获取当日订单统计数据',
            'order/cancel-reason' => '获取取消订单原因选项',
            'order/cancel-order' => '取消订单',
            'im/status' => '获取门店IM状态',
            'im/update-status' => '设置门店IM状态',
            'im/msg-read-status' => '获取消息已读状态',
            'im/user-last-read-time' => '获取会话最新已读时间',
            'shop/sync-to-cater-tool' => '同步授权店铺至外卖小蜜'
        ];
        return $toEventName[$path] ?? '未定义事件';
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
