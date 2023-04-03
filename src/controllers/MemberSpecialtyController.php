<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\data\ActiveDataProvider;
use shopack\base\backend\controller\BaseRestController;
use shopack\base\backend\helpers\PrivHelper;
use iranhmusic\shopack\mha\backend\models\MemberSpecialtyModel;

class MemberSpecialtyController extends BaseRestController
{
	// public function behaviors()
	// {
	// 	$behaviors = parent::behaviors();
	// 	return $behaviors;
	// }

	public function actionOptions()
	{
		return 'options';
	}

	protected function findModel($mbrid, $spcid)
	{
		if (($model = MemberSpecialtyModel::findOne([
					'mbrspcMemberID' => $mbrid,
					'mbrspcSpecialtyID' => $spcid,
				])) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item not exist.');
	}

	public function actionIndex()
	{
		$filter = [];
		if (PrivHelper::hasPriv('mha/member-specialty/crud', '0100') == false)
			$filter = ['mbrspcMemberID' => Yii::$app->user->identity->usrID];

		$searchModel = new MemberSpecialtyModel;
		$query = $searchModel::find()
			->select(MemberSpecialtyModel::selectableColumns())
			->with('member.user')
			->with('specialty')
			->asArray()
		;

		$searchModel->fillQueryFromRequest($query);

		if (empty($filter) == false)
			$query->andWhere($filter);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (Yii::$app->request->getMethod() == 'HEAD') {
			$totalCount = $dataProvider->getTotalCount();
			// $totalCount = $query->count();
			Yii::$app->response->headers->add('X-Pagination-Total-Count', $totalCount);
			return [];
		}

		return [
			'data' => $dataProvider->getModels(),
			// 'pagination' => [
			// 	'totalCount' => $totalCount,
			// ],
		];
	}

	public function actionView($mbrid, $spcid)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-specialty/crud', '0100') == false) {
			$justForMe = true;
		}

		$model = MemberSpecialtyModel::find()
			->select(MemberSpecialtyModel::selectableColumns())
			->with('member.user')
			->with('specialty')
			->andWhere(['mbrspcMemberID' => $mbrid])
			->andWhere(['mbrspcSpecialtyID' => $spcid])
			->asArray()
			->one()
		;

		if ($model !== null) {
			if ($justForMe && ($model->mbrspcMemberID != Yii::$app->user->identity->usrID))
				throw new ForbiddenHttpException('access denied');

			return $model;
		}

		throw new NotFoundHttpException('The requested item not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	public function actionCreate()
	{
		PrivHelper::checkPriv('mha/member-specialty/crud', '1000');

		$model = new MemberSpecialtyModel();
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		try {
			if ($model->save() == false)
				throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));
		} catch(\Exception $exp) {
			$msg = $exp->getMessage();
			if (stripos($msg, 'duplicate entry') !== false)
				$msg = 'DUPLICATE';
			throw new UnprocessableEntityHttpException($msg);
		}

		return [
			// 'result' => [
				// 'message' => 'created',
				// 'mbrspcID' => $model->mbrspcID,
				// 'mbrStatus' => $model->mbrspcStatus,
				'mbrCreatedAt' => $model->mbrspcCreatedAt,
				'mbrCreatedBy' => $model->mbrspcCreatedBy,
			// ],
		];
	}

	public function actionUpdate($mbrid, $spcid)
	{
		if (PrivHelper::hasPriv('mha/member-specialty/crud', '0010') == false) {
			if (Yii::$app->user->identity->usrID != $mbrid)
				throw new ForbiddenHttpException('access denied');
		}

		$model = $this->findModel($mbrid, $spcid);

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				'mbrUserID' => $model->mbrUserID,
				'mbrStatus' => $model->mbrStatus,
				'mbrUpdatedAt' => $model->mbrUpdatedAt,
				'mbrUpdatedBy' => $model->mbrUpdatedBy,
			// ],
		];
	}

	public function actionDelete($mbrid, $spcid)
	{
		if (PrivHelper::hasPriv('mha/member-specialty/crud', '0001') == false) {
			if (Yii::$app->user->identity->usrID != $mbrid)
				throw new ForbiddenHttpException('access denied');
		}

		$model = $this->findModel($mbrid, $spcid);

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'deleted',
				'mbrUserID' => $model->mbrUserID,
				'mbrStatus' => $model->mbrStatus,
				'mbrRemovedAt' => $model->mbrRemovedAt,
				'mbrRemovedBy' => $model->mbrRemovedBy,
			// ],
		];
	}

}
