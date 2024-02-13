<?php declare(strict_types = 0);

$filter_column = (new CFormList())
// ->addRow((new CLabel(_('Template groups'), 'tpl_groupids_#{uniqid}_ms')),
// 	(new CMultiSelect([
// 		'name' => 'tpl_groupids[]',
// 		'object_name' => 'hostGroup',
// 		'data' => array_key_exists('tpl_groups_multiselect', $data) ? $data['tpl_groups_multiselect'] : [],
// 		'popup' => [
// 			'parameters' => [
// 				'srctbl' => 'template_groups',
// 				'srcfld1' => 'groupid',
// 				'dstfrm' => 'zbx_filter',
// 				'dstfld1' => 'tpl_groupids_',
// 				'with_templates' => true,
// 				'editable' => true,
// 				'enrich_parent_groups' => true
// 			]
// 		]
// 	]))
// 		->setWidth(ZBX_TEXTAREA_FILTER_STANDARD_WIDTH)
// 		->setId('tpl_groupids_#{uniqid}')
// )
// ->addRow((new CLabel(_('Templates'), 'templateids_#{uniqid}_ms')),
// 	(new CMultiSelect([
// 		'name' => 'templateids[]',
// 		'object_name' => 'templates',
// 		'data' => array_key_exists('templates_multiselect', $data) ? $data['templates_multiselect'] : [],
// 		'popup' => [
// 			'filter_preselect' => [
// 				'id' => 'tpl_groupids_',
// 				'submit_as' => 'templategroupid'
// 			],
// 			'parameters' => [
// 				'srctbl' => 'templates',
// 				'srcfld1' => 'hostid',
// 				'dstfrm' => 'zbx_filter',
// 				'dstfld1' => 'templateids_'
// 			]
// 		]
// 	]))
// 		->setWidth(ZBX_TEXTAREA_FILTER_STANDARD_WIDTH)
// 		->setId('templateids_#{uniqid}')
// )
->addRow((new CLabel(_('Template triggers'), 'tpl_triggerids_#{uniqid}_ms')),
	(new CMultiSelect([
		'name' => 'tpl_triggerids[]',
		'object_name' => 'triggers',
		'data' => array_key_exists('tpl_triggers_multiselect', $data) ? $data['tpl_triggers_multiselect'] : [],
		'popup' => [
			// 'filter_preselect' => [
			// 	'id' => 'templateids_',
			// 	'submit_as' => 'templateid'
			// ],
			'parameters' => [
				'srctbl' => 'template_triggers',
				'srcfld1' => 'triggerid',
				'dstfrm' => 'zbx_filter',
				'dstfld1' => 'tpl_triggerids_',
				// 'templateid' => '4'
			]
		]
	]))
		->setWidth(ZBX_TEXTAREA_FILTER_STANDARD_WIDTH)
		->setId('tpl_triggerids_#{uniqid}')
)

->addRow((new CLabel(_('Host triggers'), 'triggerids_#{uniqid}_ms')),
	(new CMultiSelect([
		'name' => 'triggerids[]',
		'object_name' => 'triggers',
		'data' => array_key_exists('triggers', $data) ? $data['triggers'] : [],
		'popup' => [
			'filter_preselect' => [
				'id' => 'hostids_',
				'submit_as' => 'hostid'
			],
			'parameters' => [
				'srctbl' => 'triggers',
				'srcfld1' => 'triggerid',
				'dstfrm' => 'zbx_filter',
				'dstfld1' => 'triggerids_',
				'monitored_hosts' => true,
				'with_monitored_triggers' => true
			]
		]
	]))
		->setWidth(ZBX_TEXTAREA_FILTER_STANDARD_WIDTH)
		->setId('triggerids_#{uniqid}')
)

->addRow((new CLabel(_('Host groups'), 'groupids_#{uniqid}_ms')),
	(new CMultiSelect([
		'name' => 'hostgroupids[]',
		'object_name' => 'hostGroup',
		'data' => array_key_exists('hostgroups_multiselect', $data) ? $data['hostgroups_multiselect'] : [],
		'popup' => [
			'parameters' => [
				'srctbl' => 'host_groups',
				'srcfld1' => 'groupid',
				'dstfrm' => 'zbx_filter',
				'dstfld1' => 'hostgroupids_',
				'real_hosts' => true,
				'enrich_parent_groups' => true
			]
		]
	]))
		->setWidth(ZBX_TEXTAREA_FILTER_STANDARD_WIDTH)
		->setId('hostgroupids_#{uniqid}')
)
->addRow((new CLabel(_('Hosts'), 'hostids_#{uniqid}_ms')),
	(new CMultiSelect([
		'name' => 'hostids[]',
		'object_name' => 'hosts',
		'data' => array_key_exists('hosts_multiselect', $data) ? $data['hosts_multiselect'] : [],
		'popup' => [
			'parameters' => [
				'srctbl' => 'hosts',
				'srcfld1' => 'hostid',
				'dstfrm' => 'zbx_filter',
				'dstfld1' => 'hostids_',
				'real_hosts' => true
			]
		]
	]))
		->setWidth(ZBX_TEXTAREA_FILTER_STANDARD_WIDTH)
		->setId('hostids_#{uniqid}')
			);
// ->addRow(_('Show only hosts with problems'),
// 	(new CCheckBox('only_with_problems'))
// 		->setChecked($data['only_with_problems'] == 1)
// 		->setUncheckedValue(0)
// 		->setId('only_with_problems_#{uniqid}')
// 	);


$template = (new CDiv())
	->addClass(ZBX_STYLE_TABLE)
	->addClass(ZBX_STYLE_FILTER_FORMS)
	->addItem([
		(new CDiv($filter_column))->addClass(ZBX_STYLE_CELL)
	]);
$template = (new CForm('get'))
	->setName('zbx_filter')
	->addItem([
		$template,
		(new CSubmitButton(null))->addClass(ZBX_STYLE_FORM_SUBMIT_HIDDEN),
		(new CVar('filter_name', '#{filter_name}'))->removeId(),
		(new CVar('filter_show_counter', '#{filter_show_counter}'))->removeId(),
		(new CVar('filter_custom_time', '#{filter_custom_time}'))->removeId(),
		(new CVar('from', '#{from}'))->removeId(),
		(new CVar('to', '#{to}'))->removeId()
		// (new CVar('sort', '#{sort}'))->removeId(),
		// (new CVar('sortorder', '#{sortorder}'))->removeId()
	]);

if (array_key_exists('render_html', $data)) {
	/**
	 * Render HTML to prevent filter flickering after initial page load. PHP created content will be replaced by
	 * javascript with additional event handling (dynamic rows, etc.) when page will be fully loaded and javascript
	 * executed.
	 */
	$template->show();

	return;
}

(new CTemplateTag('filter-reports-availreport'))
	->setAttribute('data-template', 'reports.availreport.filter')
	->addItem($template)
	->show();

(new CTemplateTag('filter-inventory-row'))
	->addItem(
		(new CRow([
			(new CSelect('inventory[#{rowNum}][field]'))
				->addOptions(CSelect::createOptionsFromArray($inventories)),
			(new CTextBox('inventory[#{rowNum}][value]', '#{value}'))
				->removeId()
				->setWidth(ZBX_TEXTAREA_FILTER_SMALL_WIDTH),
			(new CCol(
				(new CButton('inventory[#{rowNum}][remove]', _('Remove')))
					->addClass(ZBX_STYLE_BTN_LINK)
					->addClass('element-table-remove')
					->removeId()
			))->addClass(ZBX_STYLE_NOWRAP)
		]))->addClass('form_row')
	)
	->show();

// (new CTemplateTag('filter-tag-row-tmpl'))
// 	->addItem(
// 		(new CRow([
// 			(new CTextBox('tags[#{rowNum}][tag]', '#{tag}'))
// 				->setAttribute('placeholder', _('tag'))
// 				->removeId()
// 				->setWidth(ZBX_TEXTAREA_FILTER_SMALL_WIDTH),
// 			(new CSelect('tags[#{rowNum}][operator]'))
// 				->addOptions(CSelect::createOptionsFromArray([
// 					TAG_OPERATOR_EXISTS => _('Exists'),
// 					TAG_OPERATOR_EQUAL => _('Equals'),
// 					TAG_OPERATOR_LIKE => _('Contains'),
// 					TAG_OPERATOR_NOT_EXISTS => _('Does not exist'),
// 					TAG_OPERATOR_NOT_EQUAL => _('Does not equal'),
// 					TAG_OPERATOR_NOT_LIKE => _('Does not contain')
// 				]))
// 				->setValue(TAG_OPERATOR_LIKE)
// 				->setFocusableElementId('tags-#{rowNum}#{uniqid}-operator-select')
// 				->setId('tags_#{rowNum}#{uniqid}_operator'),
// 			(new CTextBox('tags[#{rowNum}][value]', '#{value}'))
// 				->setAttribute('placeholder', _('value'))
// 				->setId('tags_#{rowNum}#{uniqid}_value')
// 				->setWidth(ZBX_TEXTAREA_FILTER_SMALL_WIDTH),
// 			(new CCol(
// 				(new CButton('tags[#{rowNum}][remove]', _('Remove')))
// 					->removeId()
// 					->addClass(ZBX_STYLE_BTN_LINK)
// 					->addClass('element-table-remove')
// 					->removeId()
// 			))->addClass(ZBX_STYLE_NOWRAP)
// 		]))->addClass('form_row')
// 	)
// 	->show();

?>
<script type="text/javascript">
	let template = document.querySelector('[data-template="reports.availreport.filter"]');

	function render(data, container) {
		// "Save as" can contain only home tab, also home tab cannot contain "Update" button.
		$('[name="filter_new"],[name="filter_update"]').hide()
			.filter(data.filter_configurable ? '[name="filter_update"]' : '[name="filter_new"]').show();


		// Show timeline default value is checked and it will be rendered in template therefore initialize if unchecked.
		$('[name="show_timeline"][unchecked-value="' + data['show_timeline'] + '"]', container).removeAttr('checked');


		// Template groups multiselect.
		$('#tpl_groupids_' + data.uniqid, container).multiSelectHelper({
			id: 'tpl_groupids_' + data.uniqid,
			object_name: 'hostGroup',
			name: 'tpl_groupids[]',
			data: data.filter_view_data.tpl_groups_multiselect || [],
			objectOptions: {
				enrich_parent_groups: 1
			},
			selectedLimit: 1,
			popup: {
				parameters: {
					srctbl: 'template_groups',
					srcfld1: 'groupid',
					dstfrm: 'zbx_filter',
					dstfld1: 'tpl_groupids_' + data.uniqid,
					with_templates: 1,
					editable: 1,
					enrich_parent_groups: 1
				}
			}
		});

		// Templates multiselect.
		$('#templateids_' + data.uniqid, container).multiSelectHelper({
			id: 'templateids_' + data.uniqid,
			object_name: 'templates',
			name: 'templateids[]',
			data: data.filter_view_data.templates_multiselect || [],
			selectedLimit: 1,
			popup: {
				filter_preselect: {
					id: 'tpl_groupids_' + data.uniqid,
					submit_as: 'templategroupid'
				},
				parameters: {
					srctbl: 'templates',
					srcfld1: 'hostid',
					dstfrm: 'zbx_filter',
					dstfld1: 'templateids_' + data.uniqid,
					multiselect: 1
				}
			}
		});

		// Template triggers multiselect.
		$('#tpl_triggerids_' + data.uniqid, container).multiSelectHelper({
			id: 'tpl_triggerids_' + data.uniqid,
			object_name: 'templates',
			name: 'tpl_triggerids[]',
			data: data.filter_view_data.tpl_triggers_multiselect || [],
			popup: {
				// filter_preselect: {
				// 	id: 'templateids_' + data.uniqid,
				// 	submit_as: 'templateid'
				// },
				parameters: {
					multiselect: '1',
					srctbl: 'template_triggers',
					srcfld1: 'triggerid',
					dstfrm: 'zbx_filter',
					dstfld1: 'tpl_triggerids_' + data.uniqid
				}
			}
		});

		// Triggers multiselect.
		$('#triggerids_' + data.uniqid, container).multiSelectHelper({
			id: 'triggerids_' + data.uniqid,
			object_name: 'triggers',
			name: 'triggerids[]',
			data: data.filter_view_data.triggers || [],
			popup: {
				filter_preselect: {
					id: 'hostids_' + data.uniqid,
					submit_as: 'hostid'
				},
				parameters: {
					srctbl: 'triggers',
					srcfld1: 'triggerid',
					dstfrm: 'zbx_filter',
					dstfld1: 'triggerids_' + data.uniqid,
					multiselect: 1,
					monitored_hosts: 1,
					with_monitored_triggers: 1
				}
			}
		});

		// Host groups multiselect.
		$('#hostgroupids_' + data.uniqid, container).multiSelectHelper({
			id: 'hostgroupids_' + data.uniqid,
			object_name: 'hostGroup',
			name: 'hostgroupids[]',
			data: data.filter_view_data.hostgroups_multiselect || [],
			objectOptions: {
				real_hosts: 1,
				enrich_parent_groups: 1
			},
			popup: {
				parameters: {
					multiselect: '1',
					srctbl: 'host_groups',
					srcfld1: 'groupid',
					dstfrm: 'zbx_filter',
					dstfld1: 'hostgroupids_' + data.uniqid,
					real_hosts: 1,
					enrich_parent_groups: 1
				}
			}
		});

		// Hosts multiselect.
		$('#hostids_' + data.uniqid, container).multiSelectHelper({
			id: 'hostids_' + data.uniqid,
			object_name: 'hosts',
			name: 'hostids[]',
			data: data.filter_view_data.hosts_multiselect || [],
			objectOptions: {
				real_hosts: 1
			},
			popup: {
				parameters: {
					multiselect: '1',
					srctbl: 'hosts',
					srcfld1: 'hostid',
					dstfrm: 'zbx_filter',
					dstfld1: 'hostids_' + data.uniqid,
					real_hosts: 1
				}
			}
		});

		// let only_with_problems_checkbox = $('[name="only_with_problems"]');
		// if (only_with_problems_checkbox.attr('unchecked-value') === data['only_with_problems']) {
		// 	only_with_problems_checkbox.removeAttr('checked');
		// }

		// $('#show_' + data.uniqid, container).change(eventHandler.show).trigger('change');
		// $('[name="age_state"]').change(eventHandler.age_state).trigger('change');
		// $('[name="compact_view"]', container).change(eventHandler.compact_view).trigger('change');
		// $('[name="show_tags"]', container).change(eventHandler.show_tags).trigger('change');

		// Initialize src_url.
		this.resetUnsavedState();
		this.on(TABFILTERITEM_EVENT_ACTION, update.bind(this));

		if (this._parent) {
			this._parent.on(TABFILTER_EVENT_UPDATE, (ev) => {
				let form = this.getForm(),
					tabfilter = ev.detail.target;

				if (ev.detail.filter_property !== 'properties' || tabfilter._active_item !== this) {
					return;
				}

				if ($(form).find('[name="filter_custom_time"]').val() == 1) {
					this.updateUnsavedState();
					this.setBrowserLocationToApplyUrl();
				}
			});

			this._parent.on(TABFILTER_EVENT_NEWITEM, () => {
				let form = this.getForm();

				if ($(form).find('[name="filter_custom_time"]').val() == 1) {
					$(form).prop('checked', true);
				}
			});
		}
	}

	function expand(data, container) {
		// "Save as" can contain only home tab, also home tab cannot contain "Update" button.
		$('[name="filter_new"],[name="filter_update"]').hide()
			.filter(data.filter_configurable ? '[name="filter_update"]' : '[name="filter_new"]').show();

		// // Trigger change to update timeselector ui disabled state.
		// $('#show_' + data.uniqid, container).trigger('change');
	}

	// function select(data, container) {
	// 	if (this._template_rendered) {
	// 		// Template rendered, use element events, trigger change to update timeselector ui disabled state.
	// 		$('#show_' + data.uniqid, container).trigger('change');
	// 	}
	// 	else if (this._parent) {
	// 		// Template is not rendered, use input data.
	// 		this._parent.updateTimeselector(this, (data.show != <?= TRIGGERS_OPTION_ALL ?>));
	// 	}
	// }

	/**
	 * On filter apply or update buttons press update disabled UI fields.
	 *
	 * @param {CustomEvent} ev    CustomEvent object.
	 */
	function update(ev) {
		let action = ev.detail.action,
			container = this._content_container;

		if (action !== 'filter_apply' && action !== 'filter_update') {
			return;
		}

		// $('[name="highlight_row"],[name="details"],[name="show_timeline"]', container)
		// 	.filter(':disabled')
		// 	.prop('checked', false);

		// $('[name="show_opdata"]:disabled', container)
		// 	.prop('checked', false)
		// 	.filter('[value="' + <?= CControllerProblem::FILTER_FIELDS_DEFAULT['show_opdata'] ?> +'"]')
		// 	.prop('checked', true);

		// if ($('[name="age_state"]', container).not(':checked').length) {
		// 	$('[name="age"]').val(<?= CControllerProblem::FILTER_FIELDS_DEFAULT['age'] ?>);
		// }
	}

	// Tab filter item events handlers.
	template.addEventListener(TABFILTERITEM_EVENT_RENDER, function(ev) {
		render.call(ev.detail, ev.detail._data, ev.detail._content_container);
	});
	template.addEventListener(TABFILTERITEM_EVENT_EXPAND, function(ev) {
		expand.call(ev.detail, ev.detail._data, ev.detail._content_container);
	});
	// template.addEventListener(TABFILTERITEM_EVENT_SELECT, function(ev) {
	// 	select.call(ev.detail, ev.detail._data, ev.detail._content_container);
	// });
</script>