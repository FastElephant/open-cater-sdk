<?php
/**
 * Date: 2021/8/25
 * Describe:
 */

namespace FastElephant\OpenCater\Module;


trait Comment
{
    /**
     * 获取门店评论列表
     * @param $bindShopId
     * @param $startTime
     * @param $endTime
     * @param int $page
     * @param int $pageSize
     * @param int $replyStatus
     * @return array
     */
    public function getCommentByShopId($bindShopId, $startTime, $endTime, $page = 1, $pageSize = 20, $replyStatus = -1)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'page' => $page,
            'page_size' => $pageSize,
            'reply_status' => $replyStatus
        ];
        return $this->call('comment/list', $param);
    }

    /**
     * 回复评论
     * @param $bindShopId
     * @param $commentId
     * @param $content
     * @return array
     */
    public function replyComment($bindShopId, $commentId, $content)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
            'comment_id' => $commentId,
            'content' => $content
        ];
        return $this->call('comment/reply', $param);
    }

    /**
     * 获取门店评分
     * @param $bindShopId
     * @return array
     */
    public function getShopCommentScore($bindShopId)
    {
        $param = [
            'bind_shop_id' => $bindShopId,
        ];
        return $this->call('comment/score', $param);
    }
}
