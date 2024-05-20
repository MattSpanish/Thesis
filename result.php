<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Prediction Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h2 {
            text-align: center;
        }
        .result {
            margin-top: 20px;
            text-align: center;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Professor Prediction Result</h2>
        <div class="result">
            <?php
                if (isset($_POST['current_students'])) {
                    $current_students = $_POST['current_students'];

                    // Send request to Flask API
                    $url = 'http://localhost:5000/predict';
                    $data = json_encode(array('current_students' => $current_students));
                    
                    $options = array(
                        'http' => array(
                            'header' => "Content-type: application/json\r\n",
                            'method' => 'POST',
                            'content' => $data
                        )
                    );
                    $context = stream_context_create($options);
                    $result = file_get_contents($url, false, $context);

                    // Parse JSON response
                    $response = json_decode($result, true);
                    if ($response && isset($response['professors_needed'])) {
                        echo 'Professors Needed: ' . number_format($response['professors_needed'], 2);
                    } else {
                        echo 'Error: Unable to retrieve prediction.';
                    }
                } else {
                    echo 'Error: No input received.';
                }
            ?>
        </div>
    </div>
</body>
</html>
