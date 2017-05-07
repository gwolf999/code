<?php
namespace Admin\Model;
use Think\Model;

/**
* 商品分类模型
*/
class GoodsCatesModel extends Model{

	protected $_validate = array(
		array('name', 'require', '分类名称不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
		array('name', '', '分类名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
		array('img_id', 'require', '请上传系列图片', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
	);

	protected $_auto = array(
		array('create_time', NOW_TIME, self::MODEL_INSERT),
		array('update_time', NOW_TIME, self::MODEL_BOTH),
		array('status', '1', self::MODEL_BOTH),
	);
}
