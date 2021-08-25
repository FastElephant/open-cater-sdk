<?php
/**
 * Date: 2021/8/25
 * Describe:
 */

namespace FastElephant\OpenCater\Module;


trait Delivery
{
    /**
     * 同步自配送状态
     * @param $orderId
     * @param $state
     * @param $riderName
     * @param $riderPhone
     * @return array
     */
    public function syncDeliveryState($orderId, $state, $riderName = '', $riderPhone = '')
    {
        $param = [
            'order_id' => $orderId,
            'rider_name' => $riderName,
            'rider_phone' => $riderPhone,
            'state' => $state
        ];
        return $this->call('order/delivery/sync-state', $param);
    }

    /**
     * 获取配送费
     * @param $orderId
     * @return array
     */
    public function getDeliveryFee($orderId)
    {
        $param = [
            'order_id' => $orderId,
        ];
        return $this->call('order/delivery/fee', $param);
    }

    /**
     * 呼叫配送
     * @param $partnerOrderId
     * @param $orderId
     * @param int $deliveryFee
     * @param int $gratuityFee
     * @param string $couponViewId
     * @return array
     */
    public function callDelivery($partnerOrderId, $orderId, $deliveryFee = 0, $gratuityFee = 0, $couponViewId = '')
    {
        $param = [
            'partner_order_id' => $partnerOrderId,
            'order_id' => $orderId,
            'delivery_fee' => $deliveryFee,
            'gratuity_fee' => $gratuityFee,
            'coupon_view_id' => $couponViewId,
        ];
        return $this->call('order/delivery/call', $param);
    }

    /**
     * 取消配送
     * @param $orderId
     * @return array
     */
    public function cancelDelivery($orderId)
    {
        $param = [
            'order_id' => $orderId,
        ];
        return $this->call('order/delivery/cancel', $param);
    }
}
