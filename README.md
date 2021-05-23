# Convert RMB amount to Chinese uppercase.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/chuoke/rmb-capital.svg?style=flat-square)](https://packagist.org/packages/chuoke/rmb-capital)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/chuoke/rmb-capital/run-tests?label=tests)](https://github.com/chuoke/rmb-capital/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/chuoke/rmb-capital/Check%20&%20fix%20styling?label=code%20style)](https://github.com/chuoke/rmb-capital/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/chuoke/rmb-capital.svg?style=flat-square)](https://packagist.org/packages/chuoke/rmb-capital)

---

将阿拉伯金额数字转换为汉字大写形式。

---

## 安装

使用 Composer 安装:

```bash
composer require chuoke/rmb-capital
```

## 使用

```php
$rmbCapital = new Chuoke\RmbCapital();

echo $rmbCapital->convert(123456789.01);
// 人民币壹亿贰仟叁佰肆拾伍万陆仟柒佰捌拾玖元零壹分
```

如果不需要返回”人民币“ 前缀

```php
$rmbCapital->withoutPrefix()->convert(123456789.01);
// 壹亿贰仟叁佰肆拾伍万陆仟柒佰捌拾玖元零壹分
```

> 在转换前一定确保数字的正确性。最大金额到万亿级。

---

## 规范

详查 《会计基础工作规范》，此处总结性的罗列关键点。

-   所有以元为单位
-   汉字大写数字金额如：零、壹、贰、叁、肆、伍、陆、柒、捌、玖、拾、佰、仟、万、亿等
-   大写金额数字到元或者角为止的，在“元”或者“角”字之后应当写“整”字或者“正”字（本项目使用“整”），大写金额数字有分的，分字后面不写“整”或者“正”字
-   大写金额数字前未印有货币名称的，应当加填货币名称，即“人民币”，货币名称与金额数字之间不得留有空白
-   阿拉伯金额数字中间有“0”时，汉字大写金额要写“零”字
-   阿拉伯数字金额中间连续有几个“0”时，汉字大写金额中可以只写一个“零”字；
    阿拉伯金额数字元位是“0”，或者数字中间连续有几个“0”、元位也是“0”但角位不是 “0”时，汉字大写金额可以只写一个“零”字，也可以不写“零”字

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
