let currentYear = 2024;
let currentMonth = 4; // Avril

function updateCalendar(year, month) {
    fetch(`calendar.php?year=${year}&month=${month}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById("calendarBody").innerHTML = data;
            document.getElementById("monthName").innerText = new Date(year, month - 1).toLocaleString('default', { month: 'long', year: 'numeric' });
        });
}

function prevMonth() {
    if (currentMonth === 1) {
        currentYear--;
        currentMonth = 12;
    } else {
        currentMonth--;
    }
    updateCalendar(currentYear, currentMonth);
}

function nextMonth() {
    if (currentMonth === 12) {
        currentYear++;
        currentMonth = 1;
    } else {
        currentMonth++;
    }
    updateCalendar(currentYear, currentMonth);
}

document.addEventListener("DOMContentLoaded", function() {
    updateCalendar(currentYear, currentMonth);
});
