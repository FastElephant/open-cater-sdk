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
     * @param int $logisticsId
     * @param string $riderName
     * @param string $riderPhone
     * @param string $latitude
     * @param string $longitude
     * @return array
     */
    public function syncDeliveryState($orderId, $state, $logisticsId = -1, $riderName = '', $riderPhone = '', $latitude = '', $longitude = '')
    {
        $param = [
            'order_id' => $orderId,
            'rider_name' => $riderName,
            'rider_phone' => $riderPhone,
            'state' => $state,
            'logistics_id' => $logisticsId,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        return $this->call('order/delivery/sync-state', $param);
    }

    /**
     * 同步自配送骑手位置
     * @param $orderId
     * @param $latitude
     * @param $longitude
     * @param string $state
     * @return array
     */
    public function syncRiderLocation($orderId, $latitude, $longitude, $state = '')
    {
        $param = [
            'order_id' => $orderId,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'altitude' => '19.12',
            'state' => $state
        ];
        return $this->call('order/delivery/sync-location', $param);
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

    /**
     * 配送追加小费
     * @param $orderId
     * @param $amount
     * @return mixed
     */
    public function appendDeliveryGratuity($orderId, $amount)
    {
        $param = [
            'order_id' => $orderId,
            'amount' => $amount
        ];
        return $this->call('order/delivery/append-gratuity', $param);
    }
}
