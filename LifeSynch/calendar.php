<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Interactive Calendar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: rgba(0, 255, 0, 0.1); 
            color: #333;
        }

        .navbar-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            cursor: pointer;
            font-size: 30px;
            z-index: 1001;
        }

        .navbar {
            width: 50px;
            transition: width 0.3s ease;
        }

        .navbar.open {
            width: 200px;
        }

        .navbar .hamburger {
            display: block;
            width: 25px;
            height: 3px;
            background-color: #4CAF50;
            margin: 5px 0;
            transition: transform 0.3s ease;
        }

        .navbar.open .hamburger {
            transform: rotate(90deg);
        }

        .navbar {
            background-color: #4CAF50;
            color: #fff;
            position: fixed;
            top: 0;
            left: -200px;
            height: 100%;
            width: 200px;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            transition: left 0.3s ease-in-out;
        }

        .navbar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .navbar ul li {
            margin: 15px 0;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease-in-out;
        }

        .navbar ul li a:hover {
            background-color: #4CAF50;
            transform: translateY(-2px);
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .sub-dashboard {
            background-color: #fff;
            padding: 15px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sub-dashboard nav ul {
            list-style-type: none;
            display: flex;
            justify-content: space-evenly;
            padding: 10px 0;
        }
        .sub-dashboard nav ul li {
            margin: 10px 0;
        }

        .sub-dashboard nav ul li a {
            text-decoration: none;
            color: #555;
            font-size: 14px;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sub-dashboard nav ul li a:hover {
            background-color: #4CAF50;
            color: white;
        }

        #event-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        #event-form {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        #event-form input,
        #event-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        #event-form button {
            background-color: #3a7dff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #event-form button:hover {
            background-color: #4CAF50;
        }

        @media (max-width: 768px) {
            .navbar {
                left: -100%;
            }

            .navbar ul {
                flex-direction: column;
                align-items: center;
            }

            .navbar ul li {
                margin-bottom: 10px;
            }

            .sub-dashboard nav ul {
                flex-direction: column;
                align-items: center;
            }
        }

        #calendar {
            max-width: 900px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        #calendar header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        #calendar header button {
            background-color: #4CAF50;
            color: white;
            border: none;
            font-size: 20px;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #calendar header button:hover {
            background-color: #4CAF50;
        }

        #month-year {
            font-size: 24px;
            font-weight: 500;
        }

        #days-of-week {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        #days-of-week span {
            color: #555;
        }

        #calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        #calendar-days div {
            padding: 15px;
            text-align: center;
            background-color: #f1f3f8;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #calendar-days div:hover {
            background-color:#4CAF50;
            color: white;
        }

        #event-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        #event-form {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        #event-form input,
        #event-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        #event-form button {
            background-color: #3a7dff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #event-form button:hover {
            background-color: #4CAF50;
        }

        @media (max-width: 768px) {
            .navbar ul {
                flex-direction: column;
                align-items: center;
            }

            .navbar ul li {
                margin-bottom: 10px;
            }

            .sub-dashboard nav ul {
                flex-direction: column;
                align-items: center;
            }

            #calendar {
                width: 100%;
                margin: 20px;
            }

            #calendar header {
                flex-direction: column;
                align-items: center;
            }

            #calendar header button {
                margin-top: 10px;
            }
        }

        .navbar-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            cursor: pointer;
            font-size: 30px;
            z-index: 1001;
        }

    </style>
</head>
<body>
    <div class="navbar-toggle" onclick="toggleNavbar()">
        <i class="fas fa-bars"></i>
    </div>

    <div class="navbar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i>  Dashboard</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i>  Profile</a></li>
            <li><a href="logout_action.php"><i class="fas fa-sign-out-alt"></i>  Logout</a></li>
        </ul>
    </div>

    <div class="sub-dashboard">
        <nav>
            <ul>
                <li><a href="calendar.php"> Main</a></li>
                <li><a href="notifications"> Notifications</a></li>
                <li><a href="eventmanagement"> Event Management</a></li>
                <li><a href="settings"> Settings</a></li>
            </ul>
        </nav>
    </div>

    <!-- Calendar Section -->
    <div id="calendar">
        <header>
            <button id="prev" onclick="changeMonth(-1)">&#10094;</button>
            <h2 id="month-year"></h2>
            <button id="next" onclick="changeMonth(1)">&#10095;</button>
        </header>
        <div id="days-of-week">
            <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
        </div>
        <div id="calendar-days"></div>
    </div>

    <!-- Event Modal -->
    <div id="event-modal" class="modal">
        <form id="event-form">
            <label for="event-name">Event Name:</label>
            <input type="text" id="event-name" name="event-name" required>
            
            <label for="event-notes">Notes:</label>
            <textarea id="event-notes" name="event-notes"></textarea>
            
            <input type="hidden" id="event-date" name="event-date">
            <button type="submit">Save Event</button>
            <button type="button" onclick="closeModal()">Close</button>
        </form>
    </div>

    <script>
        const currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        document.addEventListener('DOMContentLoaded', () => {
            loadCalendar();
        });

        function loadCalendar() {
            const monthYear = document.getElementById('month-year');
            const calendarDays = document.getElementById('calendar-days');
            
            const monthName = new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long' });
            monthYear.textContent = `${monthName} ${currentYear}`;
            
            const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            calendarDays.innerHTML = '';

            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyDiv = document.createElement('div');
                calendarDays.appendChild(emptyDiv);
            }

            for (let i = 1; i <= daysInMonth; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.textContent = i;
                dayDiv.onclick = () => openEventModal(i);
                calendarDays.appendChild(dayDiv);
            }
        }

        function changeMonth(direction) {
            currentMonth += direction;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            } else if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            loadCalendar();
        }

        function openEventModal(day) {
            const eventModal = document.getElementById('event-modal');
            const eventDateInput = document.getElementById('event-date');
            eventDateInput.value = `${currentYear}-${currentMonth + 1}-${day}`;
            eventModal.style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('event-modal').style.display = 'none';
        }

        document.getElementById('event-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const eventName = document.getElementById('event-name').value;
            const eventNotes = document.getElementById('event-notes').value;
            const eventDate = document.getElementById('event-date').value;

            fetch('save_event.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ eventName, eventNotes, eventDate })
            })
            .then(response => response.json())
            .then(data => {
                alert('Event saved!');
                closeModal();
                loadCalendar();
            })
            .catch(error => console.error('Error saving event:', error));
        });

        // Function to toggle the visibility of the navbar
        function toggleNavbar() {
            var navbar = document.querySelector('.navbar');
            var currentLeft = window.getComputedStyle(navbar).left;

            if (currentLeft === '0px') {
                // If navbar is visible, slide it out
                navbar.style.left = '-200px'; // Hides the navbar
            } else {
                // If navbar is hidden, slide it in
                navbar.style.left = '0px'; // Shows the navbar
            }
        }

    </script>
</body>
</html>
