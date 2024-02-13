<?php declare(strict_types = 1);

namespace Modules\BGmotAR\Actions;

use CUrl;
use CControllerResponseData;
use CTabFilterProfile;
use CControllerResponseFatal;
use CRoleHelper;


class CControllerBGAvailReportViewRefresh extends CControllerBGAvailReportView {

	protected function init(): void {
		$this->disableCsrfValidation();
	}
	protected function doAction(): void {
		$filter = static::FILTER_FIELDS_DEFAULT;

		if ($this->getInput('filter_counters', 0)) {
			$profile = (new CTabFilterProfile(static::FILTER_IDX, static::FILTER_FIELDS_DEFAULT))->read();
			$filters = $this->hasInput('counter_index')
				? [$profile->getTabFilter($this->getInput('counter_index'))]
				: $profile->getTabsWithDefaults();
			$filter_counters = [];

			foreach ($filters as $index => $tabfilter) {
				$filter_counters[$index] = $tabfilter['filter_show_counter'] ? $this->getCount($tabfilter) : 0;
			}

			$this->setResponse(
				(new CControllerResponseData([
					'main_block' => json_encode(['filter_counters' => $filter_counters])
				]))->disableView()
			);
		}
		else {
			$this->getInputs($filter, array_keys($filter));
			$filter = $this->cleanInput($filter);

			$view_url = (new CUrl())
				->setArgument('action', 'availreport.view')
				->removeArgument('page');

			$data = [
				'filter' => $filter,
				'view_curl' => $view_url,
				// 'sort' => $filter['sort'],
				// 'sortorder' => $filter['sortorder'],
				// 'allowed_ui_latest_data' => $this->checkAccess(CRoleHelper::UI_MONITORING_LATEST_DATA),
				// 'allowed_ui_problems' => $this->checkAccess(CRoleHelper::UI_MONITORING_PROBLEMS)
			] + $this->getData($filter);

			$response = new CControllerResponseData($data);
			$this->setResponse($response);
		}
	}
}
// 	protected function doAction(): void {

// 		$filter = static::FILTER_FIELDS_DEFAULT;


// 		$this->getInputs($filter, array_keys($filter));
// 		$filter = $this->cleanInput($filter);
// 		$prepared_data = $this->getData($filter);

// 		$view_url = (new CUrl())
// 			->setArgument('action', 'availreport.view')
// 			->removeArgument('page');

// 		$data = [
// 			'filter' => $filter,
// 			'view_curl' => $view_url,
// 		] + $prepared_data;

// 		$response = new CControllerResponseData($data);
// 		$this->setResponse($response);
// 	}
// }
// ?>
