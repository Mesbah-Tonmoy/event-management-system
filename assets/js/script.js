document.addEventListener('DOMContentLoaded', function() {
    const countdown = document.getElementById('countdown');
    if (countdown) {
        const countdownDate = countdown.getAttribute('data-date');
        // Set the date we're counting down to
        const countDownDate = new Date(countdownDate).getTime();

        // Update the count down every 1 second
        const x = setInterval(function() {

        // Get today's date and time
        const now = new Date().getTime();

        // Find the distance between now and the count down date
        const distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="countdown"
        document.getElementById("days").innerHTML = days;
        document.getElementById("hours").innerHTML = hours;
        document.getElementById("minutes").innerHTML = minutes;
        document.getElementById("seconds").innerHTML = seconds;

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "Event Started!";
        }
        }, 1000);
    }

    let modal, currentEventId;
    const modalElement = document.getElementById('registrationModal');
    modalElement && (modal = new bootstrap.Modal(modalElement));

    // Event registration handling
    document.querySelectorAll('.register-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Check if button is disabled or marked as registered
            if (button.classList.contains('disabled') || button.classList.contains('btn-success')) {
                return;
            }
            
            // Find the capacity badge for this event
            const eventCard = button.closest('.event-card') || button.closest('.hero-event');
            const capacityBadge = eventCard?.querySelector('.badge');
            
            if (capacityBadge) {
                const [current, capacity] = capacityBadge.textContent.split('/').map(Number);
                
                // Check if event is full
                if (current >= capacity) {
                    showNotification('This event is already full', 'danger');
                    return;
                }
            }
            
            // If we get here, event has capacity - proceed with registration
            currentEventId = button.dataset.eventId;
            document.getElementById('modalEventId').value = currentEventId;
            modal.show();
        });
    });

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} fixed-top m-3`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 3000);
    }

    // Handle form submission
    const registrationForm = document.getElementById('registrationForm');
    registrationForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        
        try {
            const response = await fetch('/evs-home/includes/attend.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message);
            }

            // Update UI
            modal.hide();
            showNotification(result.message, 'success');
            form.reset();
            
            // Update registration count and buttons
            document.querySelectorAll(`[data-event-id="${currentEventId}"]`).forEach(btn => {
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-success', 'disabled');
                btn.innerHTML = '✓ Registered';
            });

            // Update capacity numbers
            document.querySelectorAll(`[data-event-id="${currentEventId}"] .badge`).forEach(badge => {
                const current = parseInt(badge.textContent.split('/')[0]);
                badge.textContent = `${current + 1}/${badge.dataset.capacity}`;
            });

        } catch (error) {
            showNotification(error.message, 'danger');
        } finally {
            submitBtn.disabled = false;
        }
    });

    // Auto dismiss flash messages
    const flashMessages = document.querySelectorAll('.alert.auto-dismiss');
    flashMessages.forEach(function(message) {
        // Set timeout to remove the message after 3 seconds
        setTimeout(function() {
            const alert = bootstrap.Alert.getOrCreateInstance(message);
            alert.close();
        }, 2000);
    });
});


const validateLoginForm = () => {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (email.trim() === '' || password.trim() === '') {
        alert('Email and password are required.');
        return false;
    }

    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    return true;
};


function validateRegisterForm() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirm_password = document.getElementById('confirm_password').value;

    if (name.trim() === '' || email.trim() === '' || password.trim() === '' || confirm_password.trim() === '') {
        alert('All fields are required.');
        return false;
    }

    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    if (password !== confirm_password) {
        alert('Passwords do not match.');
        return false;
    }

    return true;
};


// Show loader on page load
window.addEventListener('load', () => {
    document.querySelector('.loader').style.display = 'none';
});