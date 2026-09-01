<?php

$host = '45.130.228.176';
$port = 65002;
$user = 'u682518057';
$pass = 'Safdar1818@';

echo "Attempting SSH connection to {$user}@{$host}:{$port}...\n";

$descriptors = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w']
];

// Use ssh with StrictHostKeyChecking=no
$cmd = "ssh -o StrictHostKeyChecking=no -p {$port} {$user}@{$host}";
$process = proc_open($cmd, $descriptors, $pipes);

if (is_resource($process)) {
    stream_set_blocking($pipes[1], 0);
    stream_set_blocking($pipes[2], 0);
    
    usleep(500000); // wait 0.5s for password prompt
    
    $out = fread($pipes[1], 4096) . fread($pipes[2], 4096);
    echo "Initial output: " . $out . "\n";
    
    // Write password
    fwrite($pipes[0], $pass . "\n");
    fflush($pipes[0]);
    
    usleep(1000000); // wait 1s
    
    // Execute deployment commands
    fwrite($pipes[0], "cd domains/rapid-courier.com/public_html && git clone https://github.com/safdarbhatti2-glitch/Rapid-Courier.git temp_repo && cp -rn temp_repo/* . && cp -rn temp_repo/.* . 2>/dev/null; rm -rf temp_repo && echo 'DEPLOY_SUCCESS'\n");
    fflush($pipes[0]);
    
    sleep(3);
    
    $out = fread($pipes[1], 8192) . fread($pipes[2], 8192);
    echo "Result output: " . $out . "\n";
    
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
}
