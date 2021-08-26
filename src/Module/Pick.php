<?php
/**
 * Date: 2021/8/25
 * Describe:
 */

namespace FastElephant\OpenCater\Module;


trait Pick
{
    /**
     * 预计出餐时间
     * @param $orderId
     * @param $datetime
     * @return array
     */
    public function predictPickFinishTime($orderId, $datetime)
    {
        $param = [
            'order_id' => $orderId,
            'datetime' => $datetime
        ];
        return $this->call('order/pick/predict-finish-time', $param);
    }

    /**
     * 完成出餐
     * @param $orderId
     * @return array
     */
    public function pickFinish($orderId)
    {
        $param = [
            'order_id' => $orderId,
        ];
        return $this->call('order/pick/finish', $param);
    }
}
