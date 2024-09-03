let btn = document.querySelector('#btn');
let sidebar = document.querySelector('.sidebar');
let logoutArea = document.querySelector('#logout-area');
let logoutOptions = document.querySelector('#logout-options');
let editBtn = document.querySelector('#editbtn');
let popupOverlay = document.getElementById('popup-overlay');
let mainContainer = document.querySelector('.main-container');

// Toggle sidebar visibility
btn.onclick = function() {
    sidebar.classList.toggle('active');
}
// confirmation pop-up
document.addEventListener('DOMContentLoaded', function() {
    const confirmationOverlay = document.getElementById('confirmation-overlay');
    const confirmDeleteButton = document.getElementById('confirm-delete');
    const cancelDeleteButton = document.getElementById('cancel-delete');
    let deleteLink = '';

    // Show confirmation popup
    document.querySelectorAll('a[id="del"]').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link action
            deleteLink = this.href; // Store the link URL
            confirmationOverlay.style.display = 'flex'; // Show the popup
        });
    });

    // Confirm deletion
    confirmDeleteButton.addEventListener('click', function() {
        window.location.href = deleteLink; // Redirect to delete link
    });

    // Cancel deletion
    cancelDeleteButton.addEventListener('click', function() {
        confirmationOverlay.style.display = 'none'; // Hide the popup
    });
});
// Close the logout options when clicking outside the logout area
document.addEventListener('click', function(event) {
    if (!logoutArea.contains(event.target) && !logoutOptions.contains(event.target)) {
        logoutOptions.style.display = 'none';
    }
});

// Toggle logout options visibility
logoutArea.onclick = function() {
    logoutOptions.style.display = logoutOptions.style.display === "flex" ? "none" : "flex";
}

// Show the popup form when clicking the add button
editBtn.addEventListener('click', function() {
    popupOverlay.style.display = 'flex';
    mainContainer.classList.add('blurred');
});

// Close the popup when clicking outside the form
popupOverlay.addEventListener('click', function(event) {
    if (event.target === popupOverlay) {
        popupOverlay.style.display = 'none';
        mainContainer.classList.remove('blurred');
    }
});

// Trigger the hidden file input when clicking the upload icon
document.getElementById('icon-upload').addEventListener('click', function() {
    document.getElementById('image').click();
});

// Handle image upload and preview
function uploadImage() {
    const fileInput = document.getElementById('image');
    const file = fileInput.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        document.querySelector('.side-main img').src = e.target.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}

// Show the message box for 3 seconds
window.onload = function() {
    const messageBox = document.getElementById('message-box');
    if (messageBox && messageBox.innerText.trim() !== "") {
        messageBox.style.display = 'block';
        setTimeout(function() {
            messageBox.style.display = 'none';
        }, 3000);  // 3 seconds
    }
}

// Handle form submission with AJAX
document.getElementById('add-collab-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting traditionally
    
    let formData = new FormData(this);
    
    fetch('add_collab.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add the new row to the table
            let table = document.querySelector('table tbody');
            let newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td>${data.collaborateur.firstname}</td>
                <td>${data.collaborateur.lastname}</td>
                <td>${data.collaborateur.matricule}</td>
                <td>${data.collaborateur.adresse}</td>
                <td>${data.collaborateur.phoneNumber}</td>
                <td>${data.collaborateur.ServiceID}</td>
                <td>
                    <a href='editCollab.php?id=${data.collaborateur.id}'><ion-icon name='create-outline'></ion-icon></a>
                    <a href='deleteCollab.php?id=${data.collaborateur.id}'><ion-icon name='trash-outline'></ion-icon></a>
                </td>
            `;
            table.appendChild(newRow);

            // Clear the form fields
            this.reset();
            popupOverlay.style.display = 'none';
            mainContainer.classList.remove('blurred');
        } else {
            // Handle error and display the error message
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
document.getElementById('add-collab-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting traditionally
    
    let formData = new FormData(this);
    
    fetch('add_collab.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add the new row to the table
            let table = document.querySelector('table tbody');
            let newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td>${data.collaborateur.firstname}</td>
                <td>${data.collaborateur.lastname}</td>
                <td>${data.collaborateur.matricule}</td>
                <td>${data.collaborateur.adresse}</td>
                <td>${data.collaborateur.phoneNumber}</td>
                <td>${data.collaborateur.ServiceID}</td>
                <td>
                    <a href='editCollab.php?id=${data.collaborateur.id}'><ion-icon name='create-outline'></ion-icon></a>
                    <a href='deleteCollab.php?id=${data.collaborateur.id}'><ion-icon name='trash-outline'></ion-icon></a>
                </td>
            `;
            table.appendChild(newRow);

            // Clear the form fields
            this.reset();
            popupOverlay.style.display = 'none';
            mainContainer.classList.remove('blurred');
        } else {
            // Handle error and display the error message
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
document.getElementById('add-secretaire-form').addEventListener('Submit3', function(event) {
    event.preventDefault();
    
    let formData = new FormData(this);
    
    fetch('secr.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.error) {
            // Create a new row for the table
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${data.firstname}</td>
                <td>${data.lastname}</td>
                <td>${data.Matricule}</td>
                <td>${data.email}</td>
                <td>${data.adresse}</td>
                <td>${data.phoneNumber}</td>
                <td>${data.service_description}</td>
                <td>
                    <a href="editSec.php?id=${data.id}" id="ed"><ion-icon name="create"></ion-icon></a>
                    <a href="deleteSec.php?id=${data.id}" id="del"><ion-icon name="trash"></ion-icon></a>
                </td>
            `;
            document.querySelector('table tbody').appendChild(newRow);
            this.reset();
            popupOverlay.style.display = 'none';
            mainContainer.classList.remove('blurred');
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});
document.getElementById('add-secretaire-form').addEventListener('Submit3', function(event) {
    event.preventDefault();
    
    let formData = new FormData(this);
    
    fetch('secr.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.error) {
            // Create a new row for the table
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${data.firstname}</td>
                <td>${data.lastname}</td>
                <td>${data.Matricule}</td>
                <td>${data.email}</td>
                <td>${data.adresse}</td>
                <td>${data.phoneNumber}</td>
                <td>${data.service_description}</td>
                <td>
                    <a href="editSec.php?id=${data.id}" id="ed"><ion-icon name="create"></ion-icon></a>
                    <a href="deleteSec.php?id=${data.id}" id="del"><ion-icon name="trash"></ion-icon></a>
                </td>
            `;
            document.querySelector('table tbody').appendChild(newRow);
            this.reset();
            popupOverlay.style.display = 'none';
            mainContainer.classList.remove('blurred');
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});
