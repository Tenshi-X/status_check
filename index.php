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
        .btn-delete {
            background-color: #dc3545;
            color: #fff;
            border-radius: 5px;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .btn-delete:hover {
            background-color: #c82333;
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
        <button id="addDeviceButton" class="btn btn-primary" onclick="location.href='add_device.php'">Add Device</button>
        <div class="table-responsive mt-4">
            <table class="table" id="deviceTable">
                <thead>
                    <tr>
                        <th>Device Name</th>
                        <th>IP Address</th>
                        <th>Response Status</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div id="loading">
            <img src="https://i.imgur.com/6RMhx.gif" alt="Loading...">
            <p>Loading devices, please wait...</p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Page loaded, starting to fetch devices...");
            document.getElementById('loading').style.display = 'block';
            fetch('fetch_devices.php')
                .then(response => {
                    console.log("Received response from fetch_devices.php");
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
                            <td><button class="btn btn-delete" onclick="deleteDevice('${device.id}')">Delete</button></td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loading').style.display = 'none';
                });
        });

        function deleteDevice(deviceId) {
            if(confirm('Are you sure you want to delete this device?')) {
                fetch('delete_device.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: deviceId })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Device deleted successfully');
                        location.reload();
                    } else {
                        alert('Failed to delete device');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
