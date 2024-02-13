<?php

$output = [
	'body' => (new CPartial('reports.availreport.view.html', $data['filter']))->getOutput()
];

if ($data['warning']) {
	error($data['warning']);
}

if (($messages = getMessages()) !== null) {
	$output['messages'] = $messages->toString();
}

echo json_encode($output);
?>
