<?php
$username = 'openhub.org-email';
$password = 'openhub.org-passwork';
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://home.myopenhab.org/rest/persistence/items/{my_item}',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        "Authorization: Basic " . base64_encode($username . ":" . $password),
    ),
));

$response = curl_exec($curl);
curl_close($curl);
$rspdecode = json_decode($response);

/**
 * i extracted the "data from the JSON". you can see the data with print_r($rspdecode)
 */
$rspselect = $rspdecode->data;
$responseEncode = json_encode($rspselect);

?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div>
    <canvas id="Chart2" style="width:100%;max-width:1000px"></canvas>
</div>
</body>
<script>
    var obj= '<?= $responseEncode ?>'; //adding the json
    var key = JSON.parse(obj);
    var time_val = key.map(item => item.time);
    var state_val = key.map(item => item.state);
    var time_val_vector = [];
    time_val.forEach((entry) => {
        const dateObject = new Date(entry);
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
        const Day = dateObject.getDate(); // January is displayed as 0 not as 1
        const Month = monthNames[dateObject.getMonth()] //Displays the text of the month
        const Year = dateObject.getFullYear();
        const hour = dateObject.getHours();
        const minutes = dateObject.getMinutes();
        const dateFormat = Day + ' / ' + Month + ' / ' + Year + ' ' + hour + ':' + minutes //I arranged how the file would look
        time_val_vector.push(dateFormat);
    });

    myData = {
        labels: time_val_vector,
        datasets: [
            {
                label: "Display a label",
                backgroundColor: [
                    'rgb(207,207,207)' //color of the background
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)' //color of the lines
                ],
                data: state_val
            }
        ]
    };
    var ctx = document.getElementById('Chart2').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: myData,
        fontFamily: "montserrat",
        options: {
            maintainAspectRatio: false,
            responsive: true,
            legend: { display: false, }
        }
    });
</script>
</html>
