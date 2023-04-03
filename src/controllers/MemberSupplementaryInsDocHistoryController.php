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
use iranhmusic\shopack\mha\backend\models\MemberSupplementaryInsDocHistoryModel;

class MemberSupplementaryInsDocHistoryController extends BaseRestController
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

	// protected function findModel($id)
	// {
	// 	if (($model = MemberSupplementaryInsDocHistoryModel::findOne([
	// 				'mbrsinsdochstID' => $id,
	// 			])) !== null)
	// 		return $model;

	// 	throw new NotFoundHttpException('The requested item not exist.');
	// }

	public function actionIndex()
	{
		$filter = [];
		if (PrivHelper::hasPriv('mha/member-supplementary-ins-doc/crud', '0100') == false)
			$filter = ['mbrsinsdocMemberID' => Yii::$app->user->identity->usrID];

		$searchModel = new MemberSupplementaryInsDocHistoryModel;
		$query = $searchModel::find()
			->select(MemberSupplementaryInsDocHistoryModel::selectableColumns())
			->with('memberSupplementaryInsDoc.member.user')
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

	/*
	public function actionView($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-supplementary-ins-doc/crud', '0100') == false) {
			$justForMe = true;
		}

		$model = MemberSupplementaryInsDocHistoryModel::find()
			->select(MemberSupplementaryInsDocHistoryModel::selectableColumns())
			->with('memberSupplementaryInsDoc.member.user')
			->andWhere(['mbrsinsdochstID' => $id])
			->asArray()
			->one()
		;

		if ($model !== null) {
			if ($justForMe && ($model->mbrsinsdochstSupplementaryInsDocID != Yii::$app->user->identity->usrID))
				throw new ForbiddenHttpException('access denied');

			return $model;
		}

		throw new NotFoundHttpException('The requested item not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	public function actionCreate()
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-supplementary-ins-doc/crud', '1000') == false) {
			$justForMe = true;
		}

		$model = new MemberSupplementaryInsDocHistoryModel();
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrsinsdochstSupplementaryInsDocID != Yii::$app->user->identity->usrID))
			throw new ForbiddenHttpException('access denied');

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
				'mbrsinsdochstID' => $model->mbrsinsdochstID,
				'mbrsinsdochstAction' => $model->mbrsinsdochstAction,
				'mbrsinsdochstCreatedAt' => $model->mbrsinsdochstCreatedAt,
				'mbrsinsdochstCreatedBy' => $model->mbrsinsdochstCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-supplementary-ins-doc/crud', '0010') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrsinsdochstSupplementaryInsDocID != Yii::$app->user->identity->usrID))
			throw new ForbiddenHttpException('access denied');

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				'mbrsinsdocUpdatedAt' => $model->mbrsinsdocUpdatedAt,
				'mbrsinsdocUpdatedBy' => $model->mbrsinsdocUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-supplementary-ins-doc/crud', '0001') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);

		if ($justForMe && ($model->mbrsinsdochstSupplementaryInsDocID != Yii::$app->user->identity->usrID))
			throw new ForbiddenHttpException('access denied');

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			'result' => 'ok',
			// 'result' => [
				// 'message' => 'deleted',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				// 'mbrsinsdocRemovedAt' => $model->mbrsinsdocRemovedAt,
				// 'mbrsinsdocRemovedBy' => $model->mbrsinsdocRemovedBy,
			// ],
		];
	}
	*/

}
