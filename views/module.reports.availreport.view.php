<?php declare(strict_types = 1);

$this->addJsFile('multiselect.js');
$this->addJsFile('layout.mode.js');
$this->addJsFile('gtlc.js');
$this->addJsFile('class.calendar.js');
$this->addJsFile('class.tabfilter.js');
$this->addJsFile('class.tabfilteritem.js');

$this->enableLayoutModes();
$web_layout_mode = $this->getLayoutMode();

$widget = (new CHtmlPage())
	->setTitle(_('Availability report'))
	->setWebLayoutMode($web_layout_mode)
	->setControls(
		(new CTag('nav', true,
			(new CList())
				->addItem((new CRedirectButton(_('Export to CSV'),
					(new CUrl())->setArgument('action', 'problem.view.csv')
				))->setId('export_csv'))
				->addItem(get_icon('kioskmode', ['mode' => $web_layout_mode]))
		))->setAttribute('aria-label', _('Content controls'))
	);

if ($web_layout_mode == ZBX_LAYOUT_NORMAL) {
	$filter = (new CTabFilter())
		// ->setId('reports_availreport_filter')
		->setId('monitoring_problem_filter')
		->setOptions($data['tabfilter_options'])
		->addTemplate(new CPartial($data['filter_view'], $data['filter_defaults']));

	foreach ($data['filter_tabs'] as $tab) {
		$tab['tab_view'] = $data['filter_view'];
		$filter->addTemplatedTab($tab['filter_name'], $tab);
	}

	// Set javascript options for tab filter initialization in module.reports.availreport.js.php file.
	$data['filter_options'] = $filter->options;
	$widget->addItem($filter);
}
else {
	$data['filter_options'] = null;
}

$widget->addItem((new CForm())->setName('availreport_view')->addClass('is-loading'));
$widget->show();

// $this->includeJsFile('module.reports.availreport.js.php', $data);
$this->includeJsFile('monitoring.host.view.js.php', $data);


(new CScriptTag('
	view.init('.json_encode([
		'filter_options' => $data['filter_options'],
		'refresh_url' => $data['refresh_url'],
		'refresh_interval' => $data['refresh_interval'],
		'filter_defaults' => $data['filter_defaults']
	]).');
'))
	->setOnDocumentReady()
	->show();
