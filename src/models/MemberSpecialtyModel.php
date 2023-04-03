<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\backend\models\MemberModel;
use iranhmusic\shopack\mha\backend\models\SpecialtyModel;

class MemberSpecialtyModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberSpecialtyModelTrait;

	public static function tableName()
	{
		return '{{%MHA_Member_Specialty}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrspcCreatedAt',
				'createdByAttribute' => 'mbrspcCreatedBy',
				// 'updatedAtAttribute' => 'mbrspcUpdatedAt',
				// 'updatedByAttribute' => 'mbrspcUpdatedBy',
			],
		];
	}

	public function getMember() {
		return $this->hasOne(MemberModel::class, ['mbrUserID' => 'mbrspcMemberID']);
	}

	public function getSpecialty() {
		return $this->hasOne(SpecialtyModel::class, ['spcID' => 'mbrspcSpecialtyID']);
	}

}
