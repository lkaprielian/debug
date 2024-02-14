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
 * @var CView $this
 */

?>
<script>
	const view = {
		refresh_url: null,
		refresh_simple_url: null,
		refresh_interval: null,
		filter_defaults: null,
		filter: null,
		global_timerange: null,
		active_filter: null,
		refresh_timer: null,
		filter_counter_fetch: null,
		running: false,
		timeout: null,
		deferred: null,
		opened_eventids: [],

		init({filter_options, refresh_url, refresh_interval, filter_defaults}) {
			this.refresh_url = new Curl(refresh_url);
			this.refresh_interval = refresh_interval;
			this.filter_defaults = filter_defaults;

			const url = new Curl('zabbix.php');
			url.setArgument('action', 'availreport.view.refresh');
			this.refresh_simple_url = url.getUrl();

			// this.initFilter(filter_options);
			// $.subscribe('event.rank_change', () => view.refreshNow());

			// this.initAcknowledge();
			// this.initExpandables();

			if (this.refresh_interval != 0) {
				this.running = true;
				this.scheduleRefresh();
			}

		}
	};

		

	// 	initFilter(filter_options) {
	// 		if (!filter_options) {
	// 			return;
	// 		}

	// 		this.filter = new CTabFilter($('#reports_availreport_filter')[0], filter_options);
	// 		this.active_filter = this.filter._active_item;
	// 		// this.global_timerange = {
	// 		// 	from: filter_options.timeselector.from,
	// 		// 	to: filter_options.timeselector.to
	// 		// };

	// 		/**
	// 		 * Update on filter changes.
	// 		 */
	// 		this.filter.on(TABFILTER_EVENT_URLSET, () => {
	// 			const url = new Curl();
	// 			url.setArgument('action', 'availreport.view.csv');
	// 			$('#export_csv').attr('data-url', url.getUrl());

	// 			this.refresh_url.setArgument('page', '1');

	// 			this.refreshResults();
	// 			this.refreshCounters();
	// 			// chkbxRange.clearSelectedOnFilterChange();

	// 			if (this.active_filter !== this.filter._active_item) {
	// 				this.active_filter = this.filter._active_item;
	// 				// chkbxRange.checkObjectAll(chkbxRange.pageGoName, false);
	// 			}
	// 		});

	// 		/**
	// 		 * Update filter item counter when filter settings updated.
	// 		 */
	// 		this.filter.on(TABFILTER_EVENT_UPDATE, (e) => {
	// 			if (!this.filter._active_item.hasCounter() || e.detail.filter_property !== 'properties') {
	// 				return;
	// 			}

	// 			if (this.filter_counter_fetch) {
	// 				this.filter_counter_fetch.abort();
	// 			}

	// 			this.filter_counter_fetch = new AbortController();
	// 			const filter_item = this.filter._active_item;

	// 			fetch(this.refresh_simple_url, {
	// 				method: 'POST',
	// 				signal: this.filter_counter_fetch.signal,
	// 				body: new URLSearchParams({filter_counters: 1, counter_index: filter_item._index})
	// 			})
	// 				.then(response => response.json())
	// 				.then(response => {
	// 					filter_item.updateCounter(response.filter_counters.pop());
	// 				});
	// 		});

	// 		this.refreshCounters();



	// 	// initAcknowledge() {
	// 	// 	$.subscribe('acknowledge.create', function(event, response) {
	// 	// 		// Clear all selected checkboxes in Monitoring->Problems.
	// 	// 		if (chkbxRange.prefix === 'problem') {
	// 	// 			chkbxRange.checkObjectAll(chkbxRange.pageGoName, false);
	// 	// 			chkbxRange.clearSelectedOnFilterChange();
	// 	// 		}

	// 	// 		view.refreshNow();

	// 	// 		clearMessages();
	// 	// 		addMessage(makeMessageBox('good', [], response.success.title));
	// 	// 	});

	// 	// 	$(document).on('submit', '#problem_form', function(e) {
	// 	// 		e.preventDefault();

	// 	// 		acknowledgePopUp({eventids: Object.keys(chkbxRange.getSelectedIds())}, this);
	// 	// 	});
	// 	},


	// 	getCurrentResultsTable() {
	// 		return $('div[id=reports_availreport_filter]');
	// 	},

	// 	getCurrentDebugBlock() {
	// 		return document.querySelector('.wrapper > .debug-output');
	// 	},

	// 	setLoading() {
	// 		// this.getCurrentResultsTable().classList.add('is-loading', 'is-loading-fadein', 'delayed-15s');
	// 		$('div[id=reports_availreport_filter]').addClass('is-loading is-loading-fadein');

	// 	},

	// 	clearLoading() {
	// 		// this.getCurrentResultsTable().classList.remove('is-loading', 'is-loading-fadein', 'delayed-15s');
	// 		$('div[id=reports_availreport_filter]').addClass('is-loading is-loading-fadein');
	// 	},

	// 	refreshBody(body) {
	// 		this.getCurrentResultsTable().replaceWith(
	// 			new DOMParser().parseFromString(body, 'text/html').body.firstElementChild
	// 		);
	// 		chkbxRange.init();
	// 		// this.initExpandables();
	// 	},

	// 	refreshDebug(debug) {
	// 		this.getCurrentDebugBlock().replaceWith(
	// 			new DOMParser().parseFromString(debug, 'text/html').body.firstElementChild
	// 		);
	// 	},

	// 	refresh() {
	// 		this.setLoading();

	// 		const params = this.refresh_url.getArgumentsObject();
	// 		const exclude = ['action', 'filter_src', 'filter_show_counter', 'filter_custom_time', 'filter_name'];
	// 		const post_data = Object.keys(params)
	// 			.filter(key => !exclude.includes(key))
	// 			.reduce((post_data, key) => {
	// 				post_data[key] = (typeof params[key] === 'object')
	// 					? [...params[key]].filter(i => i)
	// 					: params[key];
	// 				return post_data;
	// 			}, {});

	// 		this.deferred = $.ajax({
	// 			url: this.refresh_simple_url,
	// 			data: post_data,
	// 			type: 'post',
	// 			dataType: 'json'
	// 		});

	// 		return this.bindDataEvents(this.deferred);
	// 	},

	// 	refreshNow() {
	// 		this.unscheduleRefresh();
	// 		this.refresh();
	// 	},

	// 	scheduleRefresh() {
	// 		this.unscheduleRefresh();
	// 		this.timeout = setTimeout((function () {
	// 			this.timeout = null;
	// 			this.refresh();
	// 		}).bind(this), this.refresh_interval);
	// 	},

	// 	unscheduleRefresh() {
	// 		if (this.timeout !== null) {
	// 			clearTimeout(this.timeout);
	// 			this.timeout = null;
	// 		}
	// 	},

	// 	bindDataEvents(deferred) {
	// 		const that = this;

	// 		deferred
	// 			.done(function(response) {
	// 				that.onDataDone.call(that, response);
	// 			})
	// 			.always(this.onDataAlways.bind(this));

	// 		return deferred;
	// 	},

	// 	onDataAlways() {
	// 		if (this.running) {
	// 			this.deferred = null;
	// 			this.scheduleRefresh();
	// 		}
	// 	},

	// 	onDataDone(response) {
	// 		this.clearLoading();
	// 		this.refreshBody(response.body);

	// 		if ('messages' in response) {
	// 			clearMessages();
	// 			addMessage(makeMessageBox('good', [], response.messages, true, false));
	// 		}

	// 		('debug' in response) && this.refreshDebug(response.debug);
	// 	},

	// 	/**
	// 	 * Refresh results table.
	// 	 */
	// 	refreshResults() {
	// 		const url = new Curl();
	// 		const refresh_url = new Curl('zabbix.php');
	// 		const data = Object.assign({}, this.filter_defaults, this.global_timerange, url.getArgumentsObject());

	// 		// // Modify filter data.
	// 		// data.inventory = data.inventory
	// 		// 	? data.inventory.filter(inventory => 'value' in inventory && inventory.value !== '')
	// 		// 	: data.inventory;
	// 		// data.tags = data.tags
	// 		// 	? data.tags.filter(tag => !(tag.tag === '' && tag.value === ''))
	// 		// 	: data.tags;
	// 		// data.severities = data.severities
	// 		// 	? data.severities.filter((value, key) => value == key)
	// 		// 	: data.severities;
	// 		// data.page = this.refresh_url.getArgument('page') ?? 1;

	// 		// if (!data.filter_custom_time) {
	// 		// 	data.from = this.global_timerange.from;
	// 		// 	data.to = this.global_timerange.to;
	// 		// }

	// 		Object.entries(data).forEach(([key, value]) => {
	// 			if (['filter_show_counter', 'filter_custom_time', 'action'].indexOf(key) !== -1) {
	// 				return;
	// 			}

	// 			refresh_url.setArgument(key, value);
	// 		});

	// 		refresh_url.setArgument('action', 'availreport.view.refresh');
	// 		this.refresh_url = refresh_url;
	// 		this.refreshNow();
	// 	},

	// 	refreshCounters() {
	// 		clearTimeout(this.refresh_timer);

	// 		fetch(this.refresh_simple_url, {
	// 			method: 'POST',
	// 			body: new URLSearchParams({filter_counters: 1})
	// 		})
	// 			.then(response => response.json())
	// 			.then(response => {
	// 				if (response.filter_counters) {
	// 					this.filter.updateCounters(response.filter_counters);
	// 				}

	// 				if (this.refresh_interval > 0) {
	// 					this.refresh_timer = setTimeout(() => this.refreshCounters(), this.refresh_interval);
	// 				}
	// 			})
	// 			.catch(() => {
	// 				/**
	// 				 * On error restart refresh timer.
	// 				 * If refresh interval is set to 0 (no refresh) schedule initialization request after 5 sec.
	// 				 */
	// 				this.refresh_timer = setTimeout(() => this.refreshCounters(),
	// 					this.refresh_interval > 0 ? this.refresh_interval : 5000
	// 				);
	// 			});
	// 	},

	// 	// editHost(hostid) {
	// 	// 	this.openHostPopup({hostid});
	// 	// },

	// // 	openHostPopup(host_data) {
	// // 		clearMessages();

	// // 		const original_url = location.href;
	// // 		const overlay = PopUp('popup.host.edit', host_data, {
	// // 			dialogueid: 'host_edit',
	// // 			dialogue_class: 'modal-popup-large',
	// // 			prevent_navigation: true
	// // 		});

	// // 		overlay.$dialogue[0].addEventListener('dialogue.create', this.events.hostSuccess, {once: true});
	// // 		overlay.$dialogue[0].addEventListener('dialogue.update', this.events.hostSuccess, {once: true});
	// // 		overlay.$dialogue[0].addEventListener('dialogue.delete', this.events.hostSuccess, {once: true});
	// // 		overlay.$dialogue[0].addEventListener('overlay.close', () => {
	// // 			history.replaceState({}, '', original_url);
	// // 		}, {once: true});
	// // 	},

	// // 	events: {
	// // 		hostSuccess(e) {
	// // 			const data = e.detail;

	// // 			if ('success' in data) {
	// // 				const title = data.success.title;
	// // 				let messages = [];

	// // 				if ('messages' in data.success) {
	// // 					messages = data.success.messages;
	// // 				}

	// // 				addMessage(makeMessageBox('good', messages, title));
	// // 			}

	// // 			uncheckTableRows('problem');
	// // 			view.refreshResults();
	// // 			view.refreshCounters();
	// // 		}
	// // 	}
	// // };
</script>