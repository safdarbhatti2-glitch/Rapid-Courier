<?php
$token = $_GET['key'] ?? '';
if ($token !== 'rc_deploy_2026') { die('Access Denied'); }
$base = dirname(__DIR__);
echo shell_exec("cd " . escapeshellarg($base) . " && git fetch origin main 2>&1 && git reset --hard origin/main 2>&1");
