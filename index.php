<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table thead th {
            border: none;
        }
        .table tbody tr td {
            vertical-align: middle;
        }
        .status-active {
            color: #fff;
            background-color: #28a745;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .status-inactive {
            color: #fff;
            background-color: #dc3545;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .btn-primary {
            background-color: #6f42c1;
            color: #fff;
            border-radius: 5px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .btn-check:hover {
            background-color: #5a359c;
        }
        #loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
        #loading img {
            width: 50px;
        }
        #loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
        #loading img {
            width: 50px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3>All Devices</h3>
        <p class="text-primary">Active devices</p>
        <div class="table-responsive">
            <table class="table" id="deviceTable">
                <thead>
                    <tr>
                        <th>Device Name</th>
                        <th>IP Address</th>
                        <th>Response Status</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <button id="scanButton" class="btn btn-primary">Check</button>
        <div id="loading">
            <img src="https://i.imgur.com/6RMhx.gif" alt="Loading...">
            <p>Scanning network, please wait...</p>
        </div>
    </div>
    <script>
       document.getElementById('scanButton').addEventListener('click', function() {
    console.log("Button clicked, starting scan...");
    document.getElementById('loading').style.display = 'block';
    fetch('scan.php', { method: 'GET', timeout: 60000 })
        .then(response => {
            console.log("Received response from scan.php");
            return response.json();
        })
        .then(data => {
            console.log("Data received:", data);
            document.getElementById('loading').style.display = 'none';
            const tableBody = document.querySelector('#deviceTable tbody');
            tableBody.innerHTML = '';
            data.forEach(device => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${device.name}</td>
                    <td>${device.ip}</td>
                    <td>${device.responseStatus}</td>
                    <td><span class="${device.statusClass}">${device.status}</span></td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loading').style.display = 'none';
        });
});

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
