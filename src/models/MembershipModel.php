<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;

class MembershipModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MembershipModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuMembershipStatus::REMOVED;
    $this->softdelete_StatusField    = 'mshpStatus';
    $this->softdelete_RemovedAtField = 'mshpRemovedAt';
    $this->softdelete_RemovedByField = 'mshpRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_Membership}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mshpCreatedAt',
				'createdByAttribute' => 'mshpCreatedBy',
				'updatedAtAttribute' => 'mshpUpdatedAt',
				'updatedByAttribute' => 'mshpUpdatedBy',
			],
		];
	}

}
