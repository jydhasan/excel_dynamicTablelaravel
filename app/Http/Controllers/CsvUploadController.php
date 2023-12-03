<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CsvUploadController extends Controller
{
    public function upload(Request $request)
    {
        // receive csv file
        $file = $request->file('file');
        // read csv content
        $csvData = file_get_contents($file);
        // convert csv to array
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        // get the first row, and use it as the column name
        $header = array_shift($rows);
        // convert the remaining rows into array
        $data = [];
    
        // Build the table schema
        $tableSchema = [];
        foreach ($header as $columnName) {
            $tableSchema[$columnName] = 'text'; // You can change 'text' to the appropriate data type for each column
        }
    
        // Create the table if it doesn't exist
        $tableName = 'dynamic_data';
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function ($table) use ($tableSchema) {
                foreach ($tableSchema as $columnName => $dataType) {
                    $table->$dataType($columnName);
                }
            });
        }
    
        // Convert the remaining rows into array
        foreach ($rows as $row) {
            // Check if the number of elements in $header is the same as in $row
            if (count($header) == count($row)) {
                $data[] = array_combine($header, $row);
            } else {
                // Handle the case where the number of elements doesn't match (you may log an error or skip the row)
                // For now, we'll skip the row
                continue;
            }
        }
    
        // Insert data into the dynamically created table
        foreach ($data as $row) {
            DB::table($tableName)->insert($row);
        }
    
        // Redirect back
        return back();
    }
    
    
}
