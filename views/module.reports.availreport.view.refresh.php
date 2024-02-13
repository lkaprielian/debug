<?php

$output = [
	'body' => (new CPartial('reports.availreport.view.html', $data))->getOutput()
];


// if ($data['warning']) {
// 	error($data['warning']);
// }

// if (($messages = getMessages()) !== null) {
// 	$output['messages'] = $messages->toString();
// }

if (($messages = getMessages()) !== null) {
	$output['messages'] = $messages->toString();
}

if (CWebUser::$data['debug_mode'] == GROUP_DEBUG_MODE_ENABLED) {
	CProfiler::getInstance()->stop();
	$output['debug'] = CProfiler::getInstance()->make()->toString();
}

echo json_encode($output);
?>
