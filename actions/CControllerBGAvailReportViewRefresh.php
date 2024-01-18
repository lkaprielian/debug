<?php declare(strict_types = 1);

namespace Modules\BGmotAR\Actions;

use CUrl;
use CControllerResponseData;
use CTabFilterProfile;
use CControllerResponseFatal;
use CRoleHelper;


class CControllerBGAvailReportViewRefresh extends CControllerBGAvailReportView {

	// protected function init() {
	// 	$this->disableCsrfValidation();
	// }

	// protected function checkInput() {
	// 	$fields = [
	// 		'name' =>			'string',
	// 		'mode' =>			'in '.AVAILABILITY_REPORT_BY_HOST.','.AVAILABILITY_REPORT_BY_TEMPLATE,
	// 		'tpl_groupids' =>		'array_id',
	// 		'templateids' =>		'array_id',
	// 		'tpl_triggerids' =>		'array_id',
	// 		'hostgroupids' =>		'array_id',
	// 		'hostids' =>			'array_id',
	// 		'filter_reset' =>		'in 1',
	// 		'only_with_problems' =>		'in 0,1',
	// 		'page' =>			'ge 1',
	// 		'counter_index' =>		'ge 0',
	// 		'from' =>			'range_time',
	// 		'to' =>				'range_time',
	// 		'filter_counters' =>		'in 1'
	// 	];
	// 	$ret = $this->validateInput($fields) && $this->validateTimeSelectorPeriod();

	// 	if (!$ret) {
	// 		$this->setResponse(new CControllerResponseFatal());
	// 	}

	// 	return $ret;
	// }

	// protected function checkPermissions() {
	// 	return $this->checkAccess(CRoleHelper::UI_REPORTS_AVAILABILITY_REPORT);
	// }

	protected function doAction(): void {

		// $filter = static::FILTER_FIELDS_DEFAULT;
		$filter = [
			'name' => '',
			'mode' => AVAILABILITY_REPORT_BY_TEMPLATE,
			'tpl_groupids' => [],
			'templateids' => [],
			'tpl_triggerids' => [], 
			'hostgroupids' => [],
			'hostids' => [],
			'only_with_problems' => 1,
			'page' => null,
			'from' => 'now-7d',
			'to' => 'now'
		];

		// if ($this->getInput('filter_counters', 0)) {
		// $profile = (new CTabFilterProfile(static::FILTER_IDX, static::FILTER_FIELDS_DEFAULT))->read();
		// // $filters = $this->hasInput('counter_index')
		// // 	? [$profile->getTabFilter($this->getInput('counter_index'))]
		// // 	: $profile->getTabsWithDefaults();
		
		// $filters = $profile->getTabsWithDefaults();
		// $data['filter_counters'] = [];

		// foreach ($filter as $tabfilter) {
		// 	if (!$tabfilter['filter_custom_time']) {
		// 		$tabfilter = [
		// 			'from' => 'now-7d',
		// 			'to' => 'now'
		// 		] + $tabfilter;
		// 	}
		// }

		// $filter += $tabfilter;

			// 	$data['filter_counters'][$index] = $tabfilter['filter_show_counter'] ? $this->getCount($tabfilter) : 0;
			// }
		// }

		$this->getInputs($filter, array_keys($filter));
		// $filter = $this->cleanInput($filter);
		$prepared_data = $this->getData($filter);

		$view_url = (new CUrl())
			->setArgument('action', 'availreport.view')
			->removeArgument('page');

		$data = [
			'filter' => $filter,
			'view_curl' => $view_url
		] + $prepared_data;

		$response = new CControllerResponseData($data);
		$this->setResponse($response);
	}
}
?>
