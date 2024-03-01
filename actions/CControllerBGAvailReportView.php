<?php declare(strict_types = 1);

namespace Modules\LMFR\Actions;

use CRoleHelper;
use CControllerResponseData;
use CControllerResponseFatal;
use CTabFilterProfile;
use CUrl;
use CWebUser;

class CControllerBGAvailReportView extends CControllerBGAvailReport {

	protected function init() {
		$this->disableCsrfValidation();
	}

	// protected function checkInput() {
	// 	$fields = [
	// 		'name' =>			'string',
	// 		'mode' =>			'in '.AVAILABILITY_REPORT_BY_HOST.','.AVAILABILITY_REPORT_BY_TEMPLATE,
	// 		'tpl_groupids' =>		'array_id',
	// 		'templateids' =>		'array_id',
	// 		'tpl_triggerids' =>		'array_id',
	// 		'triggerids' =>			'array_id',
	// 		'hostgroupids' =>		'array_id',
	// 		'hostids' =>			'array_id',
	// 		'filter_reset' =>		'in 1',
	// 		'only_with_problems' =>		'in 0,1',
	// 		'page' =>			'ge 1',
	// 		'counter_index' =>		'ge 0',
	// 		'sort' =>					'in clock,host,severity,name',
	// 		'sortorder' =>				'in '.ZBX_SORT_DOWN.','.ZBX_SORT_UP,	
	// 		'from' =>			'range_time',
	// 		'to' =>				'range_time',
	// 		'filter_name' =>			'string',
	// 		'filter_custom_time' =>		'in 1,0',
	// 		'filter_show_counter' =>	'in 1,0',
	// 		'filter_counters' =>		'in 1',
	// 		'filter_set' =>				'in 1'
	// 	];

	protected function checkInput(): bool {
		$fields = [
			'show' =>					'in '.TRIGGERS_OPTION_RECENT_PROBLEM.','.TRIGGERS_OPTION_IN_PROBLEM.','.TRIGGERS_OPTION_ALL,
			'groupids' =>				'array_id',
			'hostids' =>				'array_id',
			'triggerids' =>				'array_id',
			'name' =>					'string',
			'severities' =>				'array',
			'age_state' =>				'in 0,1',
			'age' =>					'int32',
			'inventory' =>				'array',
			'evaltype' =>				'in '.TAG_EVAL_TYPE_AND_OR.','.TAG_EVAL_TYPE_OR,
			'tags' =>					'array',
			'show_tags' =>				'in '.SHOW_TAGS_NONE.','.SHOW_TAGS_1.','.SHOW_TAGS_2.','.SHOW_TAGS_3,
			'show_symptoms' =>			'in 0,1',
			'show_suppressed' =>		'in 0,1',
			'unacknowledged' =>			'in 0,1',
			'compact_view' =>			'in 0,1',
			'show_timeline' =>			'in '.ZBX_TIMELINE_OFF.','.ZBX_TIMELINE_ON,
			'details' =>				'in 0,1',
			'highlight_row' =>			'in 0,1',
			'show_opdata' =>			'in '.OPERATIONAL_DATA_SHOW_NONE.','.OPERATIONAL_DATA_SHOW_SEPARATELY.','.OPERATIONAL_DATA_SHOW_WITH_PROBLEM,
			'tag_name_format' =>		'in '.TAG_NAME_FULL.','.TAG_NAME_SHORTENED.','.TAG_NAME_NONE,
			'tag_priority' =>			'string',
			'from' =>					'range_time',
			'to' =>						'range_time',
			'sort' =>					'in clock,host,severity,name',
			'sortorder' =>				'in '.ZBX_SORT_DOWN.','.ZBX_SORT_UP,
			'page' =>					'ge 1',
			'uncheck' =>				'in 1',
			'filter_name' =>			'string',
			'filter_custom_time' =>		'in 1,0',
			'filter_show_counter' =>	'in 1,0',
			'filter_counters' =>		'in 1',
			'filter_set' =>				'in 1',
			'filter_reset' =>			'in 1',
			'counter_index' =>			'ge 0'
		];
		
		$ret = $this->validateInput($fields) && $this->validateTimeSelectorPeriod();

		if (!$ret) {
			$this->setResponse(new CControllerResponseFatal());
		}

		return $ret;
	}

	protected function checkPermissions() {
		return $this->checkAccess(CRoleHelper::UI_REPORTS_AVAILABILITY_REPORT);
	}

	protected function doAction() {
		$filter_tabs = [];

		// $profile = (new CTabFilterProfile(static::FILTER_IDX, static::FILTER_FIELDS_DEFAULT))->read();
		// if ($this->hasInput('filter_reset')) {
		// 	$profile->reset();
		// }

		$filter_tabs = [];
		$profile = (new CTabFilterProfile(static::FILTER_IDX, static::FILTER_FIELDS_DEFAULT))
			->read()
			->setInput($this->cleanInput($this->getInputAll()));
		// elseif ($this->hasInput('filter_set')) {
		// 	$profile->setTabFilter(0, ['filter_name' => ''] + $this->cleanInput($this->getInputAll()));
		// 	$profile->update();
		// }
		// else {
		// 	$profile->setInput($this->cleanInput($this->getInputAll()));
		// }

		foreach ($profile->getTabsWithDefaults() as $index => $filter_tab) {
			if ($index == $profile->selected) {
				// Initialize multiselect data for filter_scr to allow tabfilter correctly handle unsaved state.
				$filter_tab['filter_src']['filter_view_data'] = $this->getAdditionalData($filter_tab['filter_src']);
			}

			$filter_tabs[] = $filter_tab + ['filter_view_data' => $this->getAdditionalData($filter_tab)];
		}

		// filter
		$filter = $filter_tabs[$profile->selected];
		$refresh_curl = (new CUrl('zabbix.php'));
		$filter['action'] = 'availreport.view.refresh';
		$filter['action_from_url'] = $this->getAction();
		array_map([$refresh_curl, 'setArgument'], array_keys($filter), $filter);
		// $timeselector_from = $filter['filter_custom_time'] == 0 ? $filter['from'] : $profile->from;
		// $timeselector_to = $filter['filter_custom_time'] == 0 ? $filter['to'] : $profile->to;
		
		$data = [
			'action' => $this->getAction(),
			'tabfilter_idx' => static::FILTER_IDX,
			'filter' => $filter,
			'filter_view' => 'reports.availreport.filter',
			'filter_defaults' => $profile->filter_defaults,
			'tabfilter_options' => [
				'idx' => static::FILTER_IDX,
				'selected' => $profile->selected,
				'support_custom_time' => 1,
				'expanded' => $profile->expanded,
				'page' => $filter['page'],
				// 'timeselector' => [
				// 	'from' => $profile->from,
				// 	'to' => $profile->to,
				// 	'disabled' => true
				// ] + getTimeselectorActions($profile->from, $profile->to)
			],
			'filter_tabs' => $filter_tabs,
			'refresh_url' => $refresh_curl->getUrl(),
			'refresh_interval' => 3600000, //+++1000,
			'page' => $this->getInput('page', 1)
		] + $this->getData($filter);

		$response = new CControllerResponseData($data);
		$response->setTitle(_('Availability report'));

		if ($data['action'] === 'availreport.view.csv') {
			$response->setFileName('zbx_availability_report_export.csv');
		}

		$this->setResponse($response);
	}
}
?>
