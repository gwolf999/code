<?php
namespace Admin\Model;
use Think\Model;

/**
 * 图集模型
 */
class PhotosModel extends Model{

    // 自动验证
    protected $_validate = array(
        array('name', 'require', '图集名称不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', 'require', '标识已经存在', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('img_id', 'require', '图集图片不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', '1,255', '图集简介不能超过255个字符', self::VALUE_VALIDATE , 'length', self::MODEL_BOTH),
    );

    // 自动完成
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_BOTH),
    );

}
