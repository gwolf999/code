<?php
namespace Admin\Model;
use Think\Model;

/**
 * 商品模型
 */
class GoodsModel extends Model{

    // 自动验证
	protected $_validate = array(
		array('name', 'require', '商品名称不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
		array('name', '', '商品名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
		array('img_id', 'require', '请上传商品图片', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
		array('price','/^([1-9]+.[0-9]{1,2})|([1-9]+)$/','商品价格格式错误！',self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
		array('sort','/^([0-9]+)$/','排序必须为数字！',self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
		array('repertory','/^([0-9]+)$/','库存必须为数字！',self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
	);

    //自动完成
	protected $_auto = array(
		array('create_time', NOW_TIME, self::MODEL_INSERT),
		array('update_time', NOW_TIME, self::MODEL_BOTH),
		array('status', '1', self::MODEL_BOTH),
	);
}
