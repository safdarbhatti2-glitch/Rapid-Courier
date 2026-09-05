<?php
if (($_GET['key'] ?? '') !== 'rc_deploy_2026') {
    http_response_code(403);
    exit('Forbidden');
}

header('Content-Type: text/plain');
echo "Pulling latest git changes...\n";
$output = [];
$return_var = 0;
exec('git pull origin main 2>&1', $output, $return_var);
echo implode("\n", $output) . "\n";
echo "Exit code: " . $return_var;
