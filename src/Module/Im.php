<?php
/**
 * Date: 2021/8/25
 * Describe:
 */

namespace FastElephant\OpenCater\Module;


trait Im
{
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
        return $this->call('im/update-status', $param);
    }

    /**
     * 获取消息已读状态
     * @param $bindShopId
     * @param $openUserId
     * @param $msgId
     * @return array
     */
    public function readImMsg($bindShopId, $openUserId, $msgId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
            'open_user_id' => $openUserId,
            'msg_id' => $msgId
        ];
        return $this->call('im/read-msg', $param);
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
}
