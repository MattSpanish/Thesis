<?php

function main() {
    $result_file = 'result.txt';

    if (!file_exists($result_file)) {
        echo "Error: Result file not found.\n";
        return;
    }

    $professors_needed = file_get_contents($result_file);

    if ($professors_needed === false) {
        echo "Error: Unable to read result file.\n";
        return;
    }

    // Print the results
    echo "Professors Needed (predicted): " . $professors_needed . "\n";
}

// Execute the main function
main();

?>
