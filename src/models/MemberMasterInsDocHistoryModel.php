<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MemberMasterInsDocHistoryModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberMasterInsDocHistoryModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberMasterInsDocHistory}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrminsdochstCreatedAt',
				'createdByAttribute' => 'mbrminsdochstCreatedBy',
				// 'updatedAtAttribute' => 'mbrminsdochstUpdatedAt',
				// 'updatedByAttribute' => 'mbrminsdochstUpdatedBy',
			],
		];
	}

}
