<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MemberSupplementaryInsDocHistoryModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberSupplementaryInsDocHistoryModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberSupplementaryInsDocHistory}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrsinsdochstCreatedAt',
				'createdByAttribute' => 'mbrsinsdochstCreatedBy',
				// 'updatedAtAttribute' => 'mbrsinsdochstUpdatedAt',
				// 'updatedByAttribute' => 'mbrsinsdochstUpdatedBy',
			],
		];
	}

}
