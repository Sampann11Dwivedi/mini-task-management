// tasks.js
const API_URL = '/api/tasks';
let TOKEN = localStorage.getItem('jwtToken');

// Perform login to get token
function login(email, password) {
    $.ajax({
        url: '/api/login',
        type: 'POST',
        data: JSON.stringify({ email, password }),
        contentType: 'application/json',
        success: function(response) {
            TOKEN = response.token;
            localStorage.setItem('jwtToken', TOKEN);
            alert('Login successful!');
            fetchTasks();
        },
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.error || 'Login failed! Please check your credentials.';
            alert(errorMessage);
        }
    });
}

// Setup Ajax Headers with Bearer Token
function setAuthHeaders() {
    if (TOKEN) {
        console.log(TOKEN);
        $.ajaxSetup({
            
            headers: {
                'Authorization': `Bearer ${TOKEN}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
    }
}

// Perform Token Validation
function checkTokenValidity() {
    if (!TOKEN) {
        alert('Please log in to continue.');
        window.location.href = '/'; // Adjust the URL if needed
        // document.write('<meta http-equiv="refresh" content="0;url=/">');
        return false;
    }
    return true;
}

// Fetch and Display Tasks
function fetchTasks(page = 1) {
    if (!checkTokenValidity()) return;
    setAuthHeaders();

    const priority = $('#filterPriority').val();
    const status = $('#filterStatus').val();

    const queryParams = new URLSearchParams({ priority, status, page });
    const url = `/api/tasks?${queryParams}`;

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            let rows = '';
            data.data.forEach(task => {
                rows += `<tr>
                    <td>${task.id}</td>
                    <td>${task.title}</td>
                    <td>${task.priority}</td>
                    <td>${task.status}</td>
                    <td>
                        <button class='btn btn-warning btn-sm' onclick='editTask(${JSON.stringify(task)})'>Edit</button>
                        <button class='btn btn-danger btn-sm' onclick='deleteTask(${task.id})'>Delete</button>
                    </td>
                </tr>`;
            });

            $('#taskTableBody').html(rows);

            // Generate pagination
            generatePagination(data.current_page, data.last_page);
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.error || 'Failed to fetch tasks.');
        }
    });
}

// Generate Pagination Buttons
function generatePagination(currentPage, lastPage) {
    let paginationHtml = '';

    if (currentPage > 1) {
        paginationHtml += `<button class="btn btn-sm btn-primary" onclick="fetchTasks(${currentPage - 1})">Prev</button>`;
    }

    for (let i = 1; i <= lastPage; i++) {
        paginationHtml += `<button class="btn btn-sm ${i === currentPage ? 'btn-secondary' : 'btn-light'}" onclick="fetchTasks(${i})">${i}</button>`;
    }

    if (currentPage < lastPage) {
        paginationHtml += `<button class="btn btn-sm btn-primary" onclick="fetchTasks(${currentPage + 1})">Next</button>`;
    }

    $('#pagination').html(paginationHtml);
}

// Add or Update Task
$('#taskForm').submit(function (e) {
    e.preventDefault();
    if (!checkTokenValidity()) return;
    setAuthHeaders();
    const taskId = $('#taskId').val();
    const payload = JSON.stringify({
        title: $('#title').val(),
        description: $('#description').val(),
        priority: $('#priority').val(),
        due_date: $('#due_date').val(),
        status: $('#status').val()
    });

    if (taskId) {
        // Update Task
        $.ajax({
            url: `${API_URL}/${taskId}`,
            type: 'PUT',
            data: payload,
            success: function () {
                alert('Task updated successfully!');
                fetchTasks();
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.error || 'Failed to update task.');
            }
        });
    } else {
        // Create Task
        $.post({
            url: API_URL,
            data: payload,
            success: function () {
                alert('Task created successfully!');
                fetchTasks();
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.error || 'Failed to create task.');
            }
        });
    }
    $('#taskForm')[0].reset();
});

// Edit Task
function editTask(task) {
    $('#taskId').val(task.id);
    $('#title').val(task.title);
    $('#description').val(task.description);
    $('#priority').val(task.priority);
    $('#due_date').val(task.due_date);
    $('#status').val(task.status);
}

// Delete Task
function deleteTask(id) {
    if (!checkTokenValidity()) return;
    setAuthHeaders();
    if (confirm('Are you sure you want to delete this task?')) {
        $.ajax({
            url: `${API_URL}/${id}`,
            type: 'DELETE',
            success: function () {
                alert('Task deleted successfully!');
                fetchTasks();
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.error || 'Failed to delete task.');
            }
        });
    }
}

// Initial Fetch
if (TOKEN) {
    fetchTasks();
} else {
    alert('Please log in to continue.');
}
