<?php
/**
 * Date: 2021/8/25
 * Describe:
 */

namespace FastElephant\OpenCater\Module;


trait Ai
{
    /**
     * 语音合成
     * @param $text
     * @param array $option
     * @return mixed
     */
    public function tts($text, $option = [])
    {
        $option['text'] = $text;
        return $this->call('ai/tts', $option);
    }
}
