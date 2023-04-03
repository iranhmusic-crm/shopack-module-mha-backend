<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend;

use yii\base\BootstrapInterface;

class Module
	extends \shopack\base\common\base\BaseModule
	implements BootstrapInterface
{
	public function init()
	{
		if (empty($this->id))
			$this->id = 'mha';

		parent::init();
	}

	public function bootstrap($app)
	{
		if ($app instanceof \yii\web\Application)
		{
			$rules = [
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/specialty'],
					'pluralize' => false,
					'tokens' => [
						'{id}' => '<id:\\d[\\d,]*>',
						'{parentid}' => '<parentid:\\d[\\d,]*>',
					],
					'extraPatterns' => [
						'POST {parentid}' => 'create',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-specialty'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/master-insurer'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/master-insurer-type'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/supplementary-insurer'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-master-insurance'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-master-ins-doc'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-master-ins-doc-history'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-supplementary-ins-doc'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-supplementary-ins-doc-history'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/kanoon'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-kanoon'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-sponsorship'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/membership'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-membership'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/document'],
					'pluralize' => false,
					'extraPatterns' => [
						'GET member-document-types' => 'member-document-types',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-document'],
					'pluralize' => false,
				],
			];

			$app->urlManager->addRules($rules, false);

		} elseif ($app instanceof \yii\console\Application) {
			$this->controllerNamespace = 'iranhmusic\shopack\mha\backend\commands';
		}
	}

}
