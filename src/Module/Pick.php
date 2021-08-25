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
}
