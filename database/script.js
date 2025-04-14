// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.querySelector('.md\\:hidden');
    if (mobileMenuButton) {
        const mobileMenu = document.querySelector('.hidden.md\\:flex');
        
        mobileMenuButton.addEventListener('click', function() {
            if (mobileMenu) {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('flex');
                mobileMenu.classList.toggle('flex-col');
                mobileMenu.classList.toggle('absolute');
                mobileMenu.classList.toggle('top-16');
                mobileMenu.classList.toggle('left-0');
                mobileMenu.classList.toggle('right-0');
                mobileMenu.classList.toggle('bg-green-600');
                mobileMenu.classList.toggle('p-4');
                mobileMenu.classList.toggle('z-50');
            }
        });
    }
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    
                    // Add error message if it doesn't exist
                    const errorId = `${field.id}-error`;
                    if (!document.getElementById(errorId)) {
                        const errorMsg = document.createElement('p');
                        errorMsg.id = errorId;
                        errorMsg.className = 'text-red-500 text-xs mt-1';
                        errorMsg.textContent = 'This field is required';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('border-red-500');
                    
                    // Remove error message if it exists
                    const errorMsg = document.getElementById(`${field.id}-error`);
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
    
    // Password confirmation validation
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm_password');
    
    if (passwordField && confirmPasswordField) {
        confirmPasswordField.addEventListener('input', function() {
            if (passwordField.value !== confirmPasswordField.value) {
                confirmPasswordField.classList.add('border-red-500');
                
                // Add error message if it doesn't exist
                const errorId = 'confirm_password-error';
                if (!document.getElementById(errorId)) {
                    const errorMsg = document.createElement('p');
                    errorMsg.id = errorId;
                    errorMsg.className = 'text-red-500 text-xs mt-1';
                    errorMsg.textContent = 'Passwords do not match';
                    confirmPasswordField.parentNode.appendChild(errorMsg);
                }
            } else {
                confirmPasswordField.classList.remove('border-red-500');
                
                // Remove error message if it exists
                const errorMsg = document.getElementById('confirm_password-error');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
    }
    
    // Print vaccination certificate
    const printCertificateBtn = document.getElementById('print-certificate');
    if (printCertificateBtn) {
        printCertificateBtn.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Appointment date picker enhancement
    const appointmentDate = document.getElementById('appointment_date');
    if (appointmentDate) {
        // Set min date to today
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        appointmentDate.min = `${yyyy}-${mm}-${dd}`;
        
        // Set max date to 3 months from now
        const maxDate = new Date();
        maxDate.setMonth(maxDate.getMonth() + 3);
        const maxYyyy = maxDate.getFullYear();
        const maxMm = String(maxDate.getMonth() + 1).padStart(2, '0');
        const maxDd = String(maxDate.getDate()).padStart(2, '0');
        appointmentDate.max = `${maxYyyy}-${maxMm}-${maxDd}`;
    }
    
    // Dashboard charts (if Chart.js is included)
    if (typeof Chart !== 'undefined') {
        // Appointments by day chart
        const appointmentsByDayCtx = document.getElementById('appointmentsByDayChart');
        if (appointmentsByDayCtx) {
            new Chart(appointmentsByDayCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Appointments',
                        data: [12, 19, 15, 8, 22, 14, 5],
                        backgroundColor: 'rgba(72, 187, 120, 0.2)',
                        borderColor: 'rgba(72, 187, 120, 1)',
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Vaccines by type chart
        const vaccinesByTypeCtx = document.getElementById('vaccinesByTypeChart');
        if (vaccinesByTypeCtx) {
            new Chart(vaccinesByTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['COVID-19', 'Influenza', 'Hepatitis B', 'MMR', 'Tetanus'],
                    datasets: [{
                        data: [45, 25, 10, 15, 5],
                        backgroundColor: [
                            'rgba(72, 187, 120, 0.7)',
                            'rgba(66, 153, 225, 0.7)',
                            'rgba(237, 100, 166, 0.7)',
                            'rgba(246, 173, 85, 0.7)',
                            'rgba(160, 174, 192, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
});

// Function to generate QR code for vaccination certificate
let QRCode; // Declare QRCode variable
function generateQRCode(data, elementId) {
    if (typeof QRCode !== 'undefined' && document.getElementById(elementId)) {
        new QRCode(document.getElementById(elementId), {
            text: data,
            width: 128,
            height: 128,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }
}

// Function to export data to CSV
function exportToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            // Replace any commas in the cell text with spaces to avoid CSV issues
            let text = cols[j].innerText.replace(/,/g, ' ');
            // Wrap the text in quotes to handle any special characters
            row.push('"' + text + '"');
        }
        
        csv.push(row.join(','));
    }
    
    // Download CSV file
    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], {type: "text/csv"});
    const downloadLink = document.createElement("a");
    
    // File name
    downloadLink.download = filename;
    
    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);
    
    // Hide download link
    downloadLink.style.display = "none";
    
    // Add the link to DOM
    document.body.appendChild(downloadLink);
    
    // Click download link
    downloadLink.click();
    
    // Remove link from DOM
    document.body.removeChild(downloadLink);
}