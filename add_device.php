<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Device</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Add Device</h3>
        <form id="addDeviceForm">
            <div class="mb-3">
                <label for="deviceName" class="form-label">Device Name</label>
                <input type="text" class="form-control" id="deviceName" required>
            </div>
            <div class="mb-3">
                <label for="ipAddress" class="form-label">IP Address</label>
                <input type="text" class="form-control" id="ipAddress" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Device</button>
        </form>
        <div id="loading" style="display: none;">
            <img src="https://i.imgur.com/6RMhx.gif" alt="Loading...">
            <p>Adding device, please wait...</p>
        </div>
    </div>
    <script>
        document.getElementById('addDeviceForm').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('loading').style.display = 'block';
            const deviceName = document.getElementById('deviceName').value;
            const ipAddress = document.getElementById('ipAddress').value;
            fetch('add_device_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name: deviceName, ip: ipAddress })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                if (data.success) {
                    alert('Device added successfully');
                    window.location.href = 'index.php';
                } else {
                    alert('Failed to add device');
                }
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
