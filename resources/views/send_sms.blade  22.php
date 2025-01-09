<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send SMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        /* Custom Styles */
        .numbers-box {
            max-height: 400px; /* Fixed height */
            overflow-y: auto; /* Allow scrolling if the list exceeds the height */
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 20px;
        }

        .number-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
        }

        .number-item button {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 5px 10px;
        }

        .row.number-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .col-number {
            width: calc(25% - 10px); /* 4 items per row */
            box-sizing: border-box;
        }

        /* Vertical layout for sender select box */
        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Add Phone Numbers</h1>

        <!-- Input Fields for Phone Number, Operator ID, Sender Name, and Message Content -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="PhoneNumber" class="form-label">Phone Number:</label>
                <input type="text" id="PhoneNumber" class="form-control" placeholder="201234567890">
            </div>
            <!-- <div class="col-md-3">
                <label for="OperatorID" class="form-label">Operator:</label>
                <select id="OperatorID" class="form-control">
                    <option value="1">Vodafone</option>
                    <option value="2">Orange</option>
                    <option value="3">Etisalat</option>
                    <option value="7">WE</option>
                </select>
            </div> -->
            <div class="col-md-2">
                <div class="form-group">
                    <label for="SenderName" class="form-label">Sender Name:</label>
                    <select id="SenderName" class="form-control">
                        <option value="Sender1">Sender 1</option>
                        <option value="Sender2">Sender 2</option>
                        <option value="Sender3">Sender 3</option>
                        <option value="Sender4">Sender 4</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button id="addNumber" class="btn btn-primary w-100">Add Number</button>
            </div>
        </div>

        <!-- Message Content -->
        <div class="mb-3">
            <label for="Message" class="form-label">Message Content:</label>
            <textarea id="Message" class="form-control" rows="4" placeholder="Enter your message here"></textarea>
        </div>

        <!-- Display the List of Added Numbers -->
        <h3 class="mt-4">Added Numbers</h3>
        <div id="numbersList" class="numbers-box">
            <!-- Dynamically added numbers will appear here -->
        </div>

        <!-- Send Button -->
        <button id="sendNumbers" class="btn btn-success mt-4">Send SMS</button>
    </div>

    <script>
        // Initialize an array to store the numbers
        let numbers = [];

        // Handle adding numbers to the list
        document.getElementById('addNumber').addEventListener('click', function () {
            const phoneNumber = document.getElementById('PhoneNumber').value.trim();
            // const operatorID = document.getElementById('OperatorID').value;
            const senderName = document.getElementById('SenderName').value;

            if (phoneNumber && senderName) {
                // Add the number and operator ID to the list
                numbers.push({ phoneNumber, senderName });


                // Update the displayed list
                const numbersList = document.getElementById('numbersList');
                const row = document.createElement('div');
                row.classList.add('row', 'number-row');

                // Create a list item for the number
                const col = document.createElement('div');
                col.classList.add('col-number');
                const listItem = document.createElement('div');
                listItem.classList.add('number-item');
                listItem.innerHTML = `
                    <span>Phone: ${phoneNumber}, Operator: ${operatorName}, Sender: ${senderName}</span>
                    <button class="btn btn-danger btn-sm" onclick="removeNumber('${phoneNumber}')">X</button>
                `;
                col.appendChild(listItem);
                row.appendChild(col);
                numbersList.appendChild(row);

                // Clear the input fields
                document.getElementById('PhoneNumber').value = '';
                // document.getElementById('OperatorID').value = '1';
                document.getElementById('SenderName').value = 'Sender1';
            } else {
                alert('Please fill out all fields.');
            }
        });

        // Function to get the operator name from operator ID
        function getOperatorName(operatorID) {
            switch (operatorID) {
                case '1': return 'Vodafone';
                case '2': return 'Orange';
                case '3': return 'Etisalat';
                case '7': return 'WE';
                default: return 'Unknown';
            }
        }

        // Function to remove a number from the list
        function removeNumber(phoneNumber) {
            // Remove from numbers array
            numbers = numbers.filter(item => item.phoneNumber !== phoneNumber);

            // Re-render the list
            const numbersList = document.getElementById('numbersList');
            numbersList.innerHTML = ''; // Clear the list
            // Re-populate the list
            numbers.forEach(number => {
                const operatorName = getOperatorName(number.operatorID);
                const row = document.createElement('div');
                row.classList.add('row', 'number-row');
                const col = document.createElement('div');
                col.classList.add('col-number');
                const listItem = document.createElement('div');
                listItem.classList.add('number-item');
                listItem.innerHTML = `
                    <span>Phone: ${number.phoneNumber}, Operator: ${operatorName}, Sender: ${number.senderName}</span>
                    <button class="btn btn-danger btn-sm" onclick="removeNumber('${number.phoneNumber}')">X</button>
                `;
                col.appendChild(listItem);
                row.appendChild(col);
                numbersList.appendChild(row);
            });
        }

        // Handle sending all the numbers to the backend
        document.getElementById('sendNumbers').addEventListener('click', function () {
            const messageContent = document.getElementById('Message').value.trim();

            if (numbers.length === 0) {
                alert('No numbers added!');
                return;
            }

            if (!messageContent) {
                alert('Please enter the message content.');
                return;
            }

            // Send the data to the backend using Axios
            axios.post('{{ route("send.sms") }}', {
                numbers: numbers, // Send the array of numbers
                message: messageContent, // Send the message content
            })
            .then(response => {
                alert('Message sent successfully!');
                // Clear the list and message content after sending
                numbers = [];
                document.getElementById('numbersList').innerHTML = '';
                document.getElementById('Message').value = '';
            })
            .catch(error => {
                alert('There was an error sending the message.');
                console.log(error);
            });
        });
    </script>
</body>
</html>
