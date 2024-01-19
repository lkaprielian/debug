<?php
/*
** Zabbix
** Copyright (C) 2001-2024 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/


/**
 * Controller for the "Problems" asynchronous refresh page.
 */
class CControllerProblemViewRefresh extends CControllerProblemView {

	protected function init(): void {
		$this->disableCsrfValidation();
	}

	protected function checkInput(): bool {
		$fields = [
			'name' =>			'string',
			'mode' =>			'in '.AVAILABILITY_REPORT_BY_HOST.','.AVAILABILITY_REPORT_BY_TEMPLATE,
			'tpl_groupids' =>		'array_id',
			'templateids' =>		'array_id',
			'tpl_triggerids' =>		'array_id',
			'hostgroupids' =>		'array_id',
			'hostids' =>			'array_id',
			'filter_reset' =>		'in 1',
			'only_with_problems' =>		'in 0,1',
			'page' =>			'ge 1',
			'counter_index' =>		'ge 0',
			'sort' =>					'in clock,host,severity,name',
			'sortorder' =>				'in '.ZBX_SORT_DOWN.','.ZBX_SORT_UP,	
			'from' =>			'range_time',
			'to' =>				'range_time',
			'filter_name' =>			'string',
			'filter_show_counter' =>	'in 1,0',
			'filter_counters' =>		'in 1'
		];

		$ret = $this->validateInput($fields) && $this->validateTimeSelectorPeriod();

		if (!$ret) {
			$this->setResponse(new CControllerResponseFatal());
		}

		return $ret;
	}

	protected function checkPermissions(): bool {
		return $this->getUserType() >= USER_TYPE_ZABBIX_USER;
	}

	protected function doAction(): void {
		$data = [];

		if ($this->getInput('filter_counters', 0)) {
			$profile = (new CTabFilterProfile(static::FILTER_IDX, static::FILTER_FIELDS_DEFAULT))->read();
			$filters = $this->hasInput('counter_index')
				? [$profile->getTabFilter($this->getInput('counter_index'))]
				: $profile->getTabsWithDefaults();
			$data['filter_counters'] = [];

			foreach ($filters as $index => $tabfilter) {
				if (!$tabfilter['filter_custom_time']) {
					$tabfilter = [
						'from' => $profile->from,
						'to' => $profile->to
					] + $tabfilter;
				}
				else {
					$tabfilter['show'] = TRIGGERS_OPTION_ALL;
				}

				$data['filter_counters'][$index] = $tabfilter['filter_show_counter'] ? $this->getCount($tabfilter) : 0;
			}

			if (($messages = getMessages()) !== null) {
				$data['messages'] = $messages->toString();
			}

			$this->setResponse(
				(new CControllerResponseData(['main_block' => json_encode($data)]))->disableView()
			);
		}
		else {
			$data = [
				'page' => $this->getInput('page', 1),
				'action' => $this->getInput('action'),
				'filter' => [
					'name' => $this->getInput('name', ''),
					'from' => $this->hasInput('from') ? $this->getInput('from') : null,
					'to' => $this->hasInput('to') ? $this->getInput('to') : null
				],
				'tabfilter_idx' => 'reports.availreport.filter'
			];

			$this->setResponse(new CControllerResponseData($data));
		}
	}
}