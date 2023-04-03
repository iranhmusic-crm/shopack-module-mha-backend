<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MemberKanoonModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberKanoonModelTrait;

	public static function tableName()
	{
		return '{{%MHA_Member_Kanoon}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrknnCreatedAt',
				'createdByAttribute' => 'mbrknnCreatedBy',
				'updatedAtAttribute' => 'mbrknnUpdatedAt',
				'updatedByAttribute' => 'mbrknnUpdatedBy',
			],
		];
	}

}
