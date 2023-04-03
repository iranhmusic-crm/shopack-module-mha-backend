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
use iranhmusic\shopack\mha\backend\models\MemberKanoonModel;

class MemberKanoonController extends BaseRestController
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

	protected function findModel($id)
	{
		if (($model = MemberKanoonModel::findOne([
					'mbrknnID' => $id,
				])) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item not exist.');
	}

	public function actionIndex()
	{
		$filter = [];
		if (PrivHelper::hasPriv('mha/member-kanoon/crud', '0100') == false)
			$filter = ['mbrknnMemberID' => Yii::$app->user->identity->usrID];

		$searchModel = new MemberKanoonModel;
		$query = $searchModel::find()
			->select(MemberKanoonModel::selectableColumns())
			->with('member.user')
			->with('kanoon')
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

	public function actionView($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-kanoon/crud', '0100') == false) {
			$justForMe = true;
		}

		$model = MemberKanoonModel::find()
			->select(MemberKanoonModel::selectableColumns())
			->with('member.user')
			->with('kanoon')
			->andWhere(['mbrknnID' => $id])
			->asArray()
			->one()
		;

		if ($model !== null) {
			if ($justForMe && ($model->mbrknnMemberID != Yii::$app->user->identity->usrID))
				throw new ForbiddenHttpException('access denied');

			return $model;
		}

		throw new NotFoundHttpException('The requested item not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	public function actionCreate()
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-kanoon/crud', '1000') == false) {
			$justForMe = true;
		}

		$model = new MemberKanoonModel();
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrknnMemberID != Yii::$app->user->identity->usrID))
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
				// 'mbrknnID' => $model->mbrknnID,
				// 'mbrStatus' => $model->mbrknnStatus,
				'mbrknnCreatedAt' => $model->mbrknnCreatedAt,
				'mbrknnCreatedBy' => $model->mbrknnCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-kanoon/crud', '0010') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrknnMemberID != Yii::$app->user->identity->usrID))
			throw new ForbiddenHttpException('access denied');

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				'mbrknnUpdatedAt' => $model->mbrknnUpdatedAt,
				'mbrknnUpdatedBy' => $model->mbrknnUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-kanoon/crud', '0001') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);

		if ($justForMe && ($model->mbrknnMemberID != Yii::$app->user->identity->usrID))
			throw new ForbiddenHttpException('access denied');

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			'result' => 'ok',
			// 'result' => [
				// 'message' => 'deleted',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				// 'mbrknnRemovedAt' => $model->mbrknnRemovedAt,
				// 'mbrknnRemovedBy' => $model->mbrknnRemovedBy,
			// ],
		];
	}

}
