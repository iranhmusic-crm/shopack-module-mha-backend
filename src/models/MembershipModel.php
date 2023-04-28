<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use shopack\base\common\shop\ISaleableEntity;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\common\security\RsaPrivate;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MembershipModel extends MhaActiveRecord implements ISaleableEntity
{
	use \iranhmusic\shopack\mha\common\models\MembershipModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuMembershipStatus::Removed;
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

	//ISaleableEntity:
	public static function saleableKey()
	{
		return 'mbrshp';
	}

	public static function addToBasket($basketdata, $saleableID = null)
	{
		if (is_string($basketdata))
			$basketdata = json_decode(base64_decode($basketdata), true);

		if ($basketdata === null)
			$basketdata = [];

		//get saleable info
		list ($startDate, $endDate, $years, $unitPrice, $totalPrice, $saleableID)
			= MemberMembershipModel::getRenewalInfo(Yii::$app->user->identity->usrID);

		//todo: check user langauge from request header
		$desc = 'عضویت خانه موسیقی از '
			. Yii::$app->formatter->asJalali($startDate)
			. ' تا پایان'
			. Yii::$app->formatter->asJalali($endDate)
			. ' به مدت '
			. $years
			. ' سال'
		;

		$params = [
			'userid'		=> Yii::$app->user->identity->usrID,
			'service'		=> Yii::$app->controller->module->id,
			'slbkey'		=> self::saleableKey(),
			'slbid'			=> $saleableID,
			'desc' 			=> $desc,
			'unitprice' => $unitPrice,
			'qty'				=> $years,
		];
		$data = json_encode($params);

		if (empty(Yii::$app->controller->module->servicePrivateKey))
			$data = base64_encode($data);
		else
			$data = RsaPrivate::model(Yii::$app->controller->module->servicePrivateKey)->encrypt($data);

		list ($resultStatus, $resultData) = HttpHelper::callApi('aaa/basket/add',
			HttpHelper::METHOD_POST,
			[],
			[
				'data' => $data,
				'service'	=> Yii::$app->controller->module->id,
			]
		);

		if ($resultStatus < 200 || $resultStatus >= 300)
			throw new \Exception(Yii::t('mha', $resultData['message'], $resultData));

		return $resultData;
	}

}
