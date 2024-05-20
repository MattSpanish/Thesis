<?php

require_once __DIR__ . 'vendor/autoload.php'; // Adjust path as needed

use PhpOffice\PhpSpreadsheet\IOFactory;

function calculate_professors_needed($students, $total_minutes_per_professor, $total_needed_per_professor) {
    // Calculate the ratio of total needed minutes to available minutes per professor
    $ratio = $total_needed_per_professor / $total_minutes_per_professor;
    
    // Calculate the number of professors needed for the given number of students
    $professors_needed = $students * $ratio;
    
    return $professors_needed;
}

function main() {
    $excel_file = 'COMPUTATION.xlsx'; // Path to your Excel file
    
    try {
        // Load the Excel file
        $spreadsheet = IOFactory::load($excel_file);
        
        // Select worksheet
        $sheet = $spreadsheet->getActiveSheet();
        
        // Assuming the relevant data starts from row 2 and columns B, F, and H (1-based index)
        $students_list = [];
        $total_minutes_per_professor_list = [];
        $total_needed_per_professor_list = [];
        
        // Iterate through rows starting from the second row (index 1 in PHPExcel)
        foreach ($sheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Iterate through all cells
            
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            
            // Assuming data columns are in B, F, and H (1-based index in Excel, 0-based in PhpSpreadsheet)
            $students_list[] = $cells[1]; // Adjust index as per your Excel file
            $total_minutes_per_professor_list[] = $cells[5]; // Adjust index as per your Excel file
            $total_needed_per_professor_list[] = $cells[7]; // Adjust index as per your Excel file
        }
        
        // Process each scenario
        for ($i = 0; $i < count($students_list); $i++) {
            $students = $students_list[$i];
            $total_minutes_per_professor = $total_minutes_per_professor_list[$i];
            $total_needed_per_professor = $total_needed_per_professor_list[$i];
            
            // Calculate the number of professors needed
            $professors_needed = calculate_professors_needed($students, $total_minutes_per_professor, $total_needed_per_professor);
            
            // Output the result
            echo "Scenario " . ($i + 1) . ": Professors needed for $students students: " . number_format($professors_needed, 2) . PHP_EOL;
            
            // Example of adding an if-else condition based on the number of students
            if ($students > 2500) {
                echo "Warning: High number of students ($students) may require additional resources." . PHP_EOL;
            } else {
                echo "No additional resources needed." . PHP_EOL;
            }
            echo str_repeat("-", 50) . PHP_EOL;
        }
        
    } catch (Exception $e) {
        echo "Error reading Excel file or performing calculations: " . $e->getMessage() . PHP_EOL;
    }
}

// Run the main function
main();
