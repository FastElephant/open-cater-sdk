<?php
/**
 * Date: 2021/8/25
 * Describe:
 */

namespace FastElephant\OpenCater\Module;


trait Oauth
{
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
     * @param $bindShopId
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
        return $this->call('shop/oauth/rebind', $param);
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
        return $this->call('shop/oauth/unbind', $param);
    }

    /**
     * 根据手机号同步授权
     * @param $phone
     * @return array
     */
    public function syncAuth($phone)
    {
        $param = [
            'phone' => $phone,
        ];
        return $this->call('shop/oauth-mirror/sync', $param);
    }

    /**
     * 获取商户已绑定的平台
     * @return array
     */
    public function getBindPlatformList()
    {
        return $this->call('shop/oauth/bind-platform-list');
    }
}
