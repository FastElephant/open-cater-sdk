<?php
/**
 * Date: 2021/8/25
 * Describe:
 */

namespace FastElephant\OpenCater\Module;


trait Product
{
    /**
     * 查询商品报告地址
     * @param $bindShopId
     * @return array
     */
    public function queryProductReport($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
        ];
        return $this->call('product/report', $param);
    }

    /**
     * 同步菜品
     * @param $bindShopId
     * @param int $isAsync
     * @param string $notifyUrl
     * @return array
     */
    public function syncProduct($bindShopId, $isAsync = 0, $notifyUrl = '')
    {
        $param = [
            'bind_shop_id' => $bindShopId,
            'is_async' => $isAsync,
            'notify_url' => $notifyUrl
        ];
        return $this->call('product/sync', $param);
    }

    /**
     * 查询同步结果
     * @param $taskId
     * @return mixed
     */
    public function syncProductResult($taskId)
    {
        return $this->call('product/sync-result', ['task_id' => $taskId]);
    }
}
