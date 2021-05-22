<?php

namespace Chuoke\RmbCapital;

class RmbCapital
{
    /**
     * @var array
     */
    protected $capitalNumbers = [
        '零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖',
    ];

    /**
     * @var array
     */
    protected $integerUnits = ['', '拾', '佰', '仟',];

    /**
     * @var array
     */
    protected $placeUnits = ['', '万', '亿',];

    /**
     * 小数位单位
     *
     * 按规范到“分”即可，但有时会有厘
     *
     * @var array
     */
    protected $decimalUnits = ['角', '分', '厘', '毫'];

    /**
     * @var string
     */
    protected $prefix = '人民币';

    /**
     * @var bool
     */
    protected $usePrefix = true;

    /**
     * 设置为不使用“人民币”前缀
     *
     * @return $this
     */
    public function withoutPrefix()
    {
        $this->usePrefix = false;

        return $this;
    }

    /**
     * 进行大写转换
     *
     * @param  int|float|string  $amount
     * @return string
     */
    public function convert($amount)
    {
        // 转换整数部分
        $integerResult = $this->convertInteger($amount);

        // 转换小数位
        $decimalResult = $this->convertDecimal($amount);

        // 没有则为 零元
        // 注意，类似：0.07元，应为 人民币柒分
        if (!$integerResult && !$decimalResult) {
            $integerResult .= $this->capitalNumber(0);
        }

        // 整数位后要跟元
        if ($integerResult) {
            $integerResult .= '元';
        }

        if (!$decimalResult) {
            $integerResult .= '整';
        }

        if (strpos((string) $amount, '-') === 0) {
            $integerResult = '负' . $integerResult;
        }

        return ($this->usePrefix ? $this->prefix : '') . $integerResult . $decimalResult;
    }

    /**
     * 转换整数部分
     *
     * @param  int|float|string  $amount
     * @return string
     */
    protected function convertInteger($amount)
    {
        $parts = explode('.', (string) $amount);
        $integer = trim($parts[0] ?? '', '-');

        $result = [];

        // 反着按日常习惯，从个位开始转换
        $integerNumbers = $integer ? array_reverse(str_split($integer)) : [];

        // 阿拉伯金额数字中间有“0”时，汉字大写金额要写“零”字；
        // 数字中间连续有几个“0”，汉字大写金额可以只写一个“零”字

        $last = null;
        foreach (array_chunk($integerNumbers, 4) as $chunkKey => $chunk) {
            if (!((int) implode('', $chunk))) {
                // 全是 0 则直接跳过
                continue;
            }

            array_unshift($result, $this->placeUnits[$chunkKey]);

            foreach ($chunk as $key => $number) {
                // 去除重复 零，以及第一位的 零，类似：1002、110，应为 壹仟零贰元整、壹佰壹拾元整
                if (!$number && (!$last || $key === 0)) {
                    $last = $number;
                    continue;
                }
                $last = $number;

                // 类似 1022，应为 壹仟零贰拾贰元整，中间的 0 是不需要 佰 的
                if ($number) {
                    array_unshift($result, $this->integerUnits[$key]);
                }

                array_unshift($result, $this->capitalNumber((int) $number));
            }
        }

        return implode('', $result);
    }

    /**
     * 转换小数部分
     *
     * @param  int|float|string  $amount
     *
     * @return string
     */
    protected function convertDecimal($amount)
    {
        $result = [];

        $parts = explode('.', (string) $amount);
        $integer = trim($parts[0] ?? '', '-');
        $decimal = $parts[1] ?? '';

        if (!((int) $decimal)) {
            $decimal = '';
        }

        $decimalNumbers = $decimal ? str_split($decimal) : [];
        $jiao = (int) array_shift($decimalNumbers); // 角比较特殊

        if ($jiao) {
            array_push($result, $this->capitalNumber($jiao), $this->decimalUnits[0]);

            if (!count(array_filter($decimalNumbers))) {
                array_push($result, '整');
            }
        } else {
            // 如：0.07元，应为 人民币柒分
            // 而类似 23.05 应为 贰拾叁元零伍分
            if (count(array_filter($decimalNumbers)) && $integer) {
                array_push($result, $this->capitalNumber(0));
            }
        }

        foreach ($decimalNumbers as $key => $number) {
            if ($number) {
                array_push($result, $this->capitalNumber((int) $number), $this->decimalUnits[$key + 1]);
            }
        }

        return implode('', $result);
    }

    /**
     * 获取数字的大写
     *
     * @param  integer  $number
     * @return string
     */
    public function capitalNumber(int $number)
    {
        return $this->capitalNumbers[$number] ?? '';
    }
}
