<?php
/**
 * Date: 2021/8/25
 * Describe:
 */

namespace FastElephant\OpenCater\Module;


trait Shop
{
    /**
     * 根据发货门店ID获取已绑定的商铺列表
     * @return array
     */
    public function getBindShopList()
    {
        return $this->call('shop/bind-list');
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
     * 获取店铺详情
     * @param $bindShopId
     * @return array
     */
    public function getShopDetail($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
        ];
        return $this->call('shop/detail', $param);
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
        return $this->call('shop/delete', $param);
    }
}
