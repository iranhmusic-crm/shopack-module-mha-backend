<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MemberMembershipModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberMembershipModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberMembership}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrshpCreatedAt',
				'createdByAttribute' => 'mbrshpCreatedBy',
				'updatedAtAttribute' => 'mbrshpUpdatedAt',
				'updatedByAttribute' => 'mbrshpUpdatedBy',
			],
		];
	}

}
