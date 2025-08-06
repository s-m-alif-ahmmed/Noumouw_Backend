<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .support-form {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .support-form h5 {
            font-size: 1.25rem;
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 5px;
            color: #333;
        }

        .support-form input,
        .support-form select,
        .support-form textarea {
            border: 1px solid #d2e1c8;
            border-radius: 8px;
            color: #333;
        }

        .support-form input::placeholder,
        .support-form textarea::placeholder {
            color: #b1b1b1;
        }

        .support-form input:focus,
        .support-form select:focus,
        .support-form textarea:focus {
            box-shadow: 0 0 0 0.2rem rgba(138, 194, 115, 0.25);
            border-color: #d2e1c8;
        }

        .support-form .btn-submit {
            width: 100%;
            background-color: #d2e1c8;
            color: #fff;
            border-radius: 8px;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            cursor: pointer;
            background-color: #b5d180;
        }

        .btn-submit:disabled {
            background-color: #e0e0e0;
        }

        .support-form .back-arrow {
            position: absolute;
            top: 40px;
            left: 40px;
            font-size: 1.2rem;
            color: #d2e1c8;
            cursor: pointer;
            border: 1px solid #d2e1c8;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s, border-color 0.3s;
        }

        .back-arrow:hover {
            color: #c3d4a4;
            border-color: #c3d4a4;
        }
    </style>
</head>

<body>

    <div class="support-form">
        <a href="#" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
        <h5>Support</h5>
        <form action="{{ route('support.send') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="fullName" id="fullName" placeholder="Your Full Name"
                    value="{{ old('fullName') }}">
                @error('fullName')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="emailAddress" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="emailAddress" name="email"
                    placeholder="Your Email Address" value="{{ old('email') }}">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>

                <select class="form-select" name="country" id="country-select">
                    <option selected disabled>Select your country</option>

                </select>
                @error('country')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Your Message">{{ old('message') }}</textarea>
                @error('message')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-submit">Submit</button>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- JavaScript to Fetch Data and Populate the Dropdown -->
    <script>
        // Function to fetch country data from JSON file and populate the select input
        fetch('/countries.json') // Make sure the path is correct
            .then(response => response.json())
            .then(data => {
                const countrySelect = document.getElementById('country-select');

                // Loop through the data and add options to the select dropdown
                data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.code; // Country code as value
                    option.textContent = country.name; // Country name as text
                    countrySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading country data:', error);
            });
    </script>




    <script>
        // Enable the submit button only if all fields are filled
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', () => {
                const fullName = document.getElementById('fullName').value.trim();
                const emailAddress = document.getElementById('emailAddress').value.trim();
                const country = document.getElementById('country').value;
                const message = document.getElementById('message').value.trim();
                const submitButton = document.querySelector('.btn-submit');
                submitButton.disabled = !(fullName && emailAddress && country && message);
            });
        });
    </script>

</body>

</html>
