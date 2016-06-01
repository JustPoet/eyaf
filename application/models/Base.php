<?php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BaseModel
 * 模型基类
 */
class BaseModel extends Model
{
    //软删除
    use SoftDeletes;

    /**
     * 需要被转换成日期的属性。
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 自增主键
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Base constructor.
     *
     * 这里重写是为了使用uuid作为主键,可以自定义
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        if (!$this->incrementing) {
            $id = $this->getKeyName();
            $this->$id = uniqid();
        }
    }
}