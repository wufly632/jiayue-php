<?php

declare(strict_types=1);

namespace App\Common\IdGenerator;

use Carbon\Carbon;
use Hyperf\Snowflake\IdGenerator\SnowflakeIdGenerator;
use Hyperf\Snowflake\Meta;

/**
 * ID生成器
 * @package App\Common\IdGenerator
 */
class Uuid
{
    const DEFAULT_PREFIX = 'ID';
    const DEFAULT_SUFFIX = '';
    const DEFAULT_SHOW_DATE = false;
    const PAD_STRING = '0';
    const UNDERLINE = '_';
    const FROM_BASE = 10;
    const TO_BASE = 32;
    const DATE_STR_LEN = 6;
    const ID_STR_LEN = 14;

    /**
     * @var SnowflakeIdGenerator
     */
    protected $idGenerator;

    /**
     * ID前缀
     * @var string
     */
    protected $prefix;

    /**
     * ID后缀
     * @var string
     */
    protected $suffix;

    /**
     * 是否显示日期
     * @var bool
     */
    protected $show_date;

    public function __construct(SnowflakeIdGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
        $this->prefix = self::DEFAULT_PREFIX;
        $this->suffix = self::DEFAULT_SUFFIX;
        $this->show_date = self::DEFAULT_SHOW_DATE;
    }

    /**
     * 设置ID前缀
     * @param string $prefix
     * @return $this
     */
    public function setPrefix(string $prefix)
    {
        $prefix = trim(trim($prefix), self::UNDERLINE);

        // 分布式ID前缀必须指定
        if (!empty($prefix) && preg_match('/^[a-zA-Z][0-9a-zA-Z]*/', $prefix)) {
            $this->prefix = $prefix;
        }

        return $this;
    }

    /**
     * 设置ID后缀
     * @param string $suffix
     * @return $this
     */
    public function setSuffix(string $suffix = '')
    {
        $suffix = trim(trim($suffix), self::UNDERLINE);

        // 分布式ID后缀可由任意数字字母组成
        if (!empty($suffix) && preg_match('/^[0-9a-zA-Z]*/', $suffix)) {
            $this->suffix = $suffix;
        }

        return $this;
    }

    /**
     * @param bool $show_date
     * @return $this
     */
    public function showData(bool $show_date = true)
    {
        $this->show_date = $show_date;

        return $this;
    }

    /**
     * 生成分布式ID，格式: PREFIX_ [DATE] 0_PAD SNOWFLAKE_ID [SUFFIX]
     * 将10进制ID转换成N（N>10）进制，以缩短ID字符长度
     * @return string
     */
    public function generate()
    {
        $snowflake_id = $this->idGenerator->generate();
        $date = '';
        if ($this->show_date) {
            $snowflake_meta = $this->idGenerator->degenerate($snowflake_id);
            $datetime = Carbon::createFromTimestampMs($snowflake_meta->getTimestamp());
            $date = $datetime->format('ymd');
        }

        return implode('', [
            $this->prefix,
            self::UNDERLINE,
            $date,
            str_pad(base_convert(
                (string)$snowflake_id,
                self::FROM_BASE,
                self::TO_BASE
            ), self::ID_STR_LEN, self::PAD_STRING, STR_PAD_LEFT),
            $this->suffix,
        ]);
    }

    /**
     * 解析分布式ID，逆向转换得到原始雪花算法ID，再逆向获取雪花算法Meta信息
     * @param string $id
     * @return Meta|null
     */
    public function degenerate(string $id)
    {
        $id = trim(trim($id), self::UNDERLINE);

        // 分布式ID格式不正确，无法解析
        if (substr($id, strrpos($id, '_')) <= self::ID_STR_LEN) {
            return null;
        }

        // 修剪date，如果存在的话
        $date_sign = substr($id, (strrpos($id, '_') + 1), 1);
        $date_str_len = 0;
        if ($date_sign !== '0') {
            $date_str_len = self::DATE_STR_LEN;
        }
        $snowflake_id = substr($id, (strrpos($id, '_') + $date_str_len + 1), self::ID_STR_LEN);

        // 修剪前缀后，取从`_`往后固定位数，得到N进制ID，并做进制转换，最后解析得到Meta信息
        return $this->idGenerator->degenerate(
            intval(
                base_convert(
                    $snowflake_id,
                    self::TO_BASE,
                    self::FROM_BASE
                )
            )
        );
    }
}
