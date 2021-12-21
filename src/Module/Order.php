<?php
/**
 * Date: 2021/8/25
 * Describe:
 */

namespace FastElephant\OpenCater\Module;


trait Order
{
    /**
     * 获取取消订单原因选项
     * @return array
     */
    public function getCancelOrderReason()
    {
        return $this->call('order/cancel-reason');
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
     * 批量获取订单详情
     * @param $bindShopId
     * @param $orderId
     * @return array
     */
    public function batchQueryOrder($bindShopId, $orderId)
    {
        if (!is_array($orderId)) $orderId = [$orderId];
        $param = [
            'bind_shop_id' => $bindShopId,
            'order_id' => implode(',', $orderId)
        ];
        return $this->call('order/batch-query-detail', $param);
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
        return $this->call('order/confirm-order', $param);
    }

    /**
     * 获取原始订单数据
     * @param $orderId
     * @return array
     */
    public function getOriginOrderDetail($orderId)
    {
        $param = [
            'order_id' => $orderId
        ];
        return $this->call('order/origin-detail', $param);
    }

    /**
     * 获取菜品数据
     * @param $orderId
     * @return array
     */
    public function getDishDetail($orderId)
    {
        $param = [
            'order_id' => $orderId
        ];
        return $this->call('order/dish-detail', $param);
    }

    /**
     * 刷新菜品数据
     * @param $orderId
     * @param string $notifyUrl
     * @return array
     */
    public function flushDish($orderId, $notifyUrl = '')
    {
        $param = [
            'order_id' => $orderId,
            'notify_url' => $notifyUrl
        ];
        return $this->call('order/flush-dish', $param);
    }

    /**
     * 取消订单
     * @param $orderId
     * @param $type
     * @param $remark
     * @return array
     */
    public function cancelOrder($orderId, $type, $remark)
    {
        $param = [
            'order_id' => $orderId,
            'type' => $type,
            'remark' => $remark
        ];
        return $this->call('order/cancel-order', $param);
    }

    /**
     * 完成订单
     * @param $orderId
     * @return array
     */
    public function completeOrder($orderId)
    {
        $param = [
            'order_id' => $orderId,
        ];
        return $this->call('order/complete-order', $param);
    }

    /**
     * 获取当日订单统计数据
     * @return array
     */
    public function getTodayStatistics()
    {
        return $this->call('order/today-statistics');
    }
}
