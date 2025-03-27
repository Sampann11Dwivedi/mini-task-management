<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Task Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Task Management</h1>

        <!-- Task Form -->
        <form id="taskForm">
            <input type="hidden" id="taskId" />
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" required />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description"></textarea>
            </div>
            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-control" id="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due_date" required />
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" required>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- Filters -->
        <h2 class="mt-5">Filters</h2>
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="filterPriority" class="form-label">Filter by Priority</label>
                <select class="form-control" id="filterPriority">
                    <option value="">All</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterStatus" class="form-label">Filter by Status</label>
                <select class="form-control" id="filterStatus">
                    <option value="">All</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterLimit" class="form-label">Items per page</label>
                <select class="form-control" id="filterLimit">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <label for="searchTask" class="form-label">Search by Title</label>
                <input type="text" class="form-control" id="searchTask" onkeyup="fetchTaskData()" placeholder="Search by task title..." />
            </div>
        </div>

        <!-- Task List -->
        <h2 class="mt-5">Task List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="taskTableBody"></tbody>
        </table>
        <div id="pagination" class="mt-3"></div>

    </div>

    <script>
        // Function to fetch tasks with filters, pagination, and search
        function fetchTaskData(page = 1) {
            const priority = $('#filterPriority').val();
            const status = $('#filterStatus').val();
            const limit = $('#filterLimit').val();
            const search = $('#searchTask').val();

            const queryParams = new URLSearchParams({ priority, status, limit, search, page });
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

        // Function to generate pagination buttons
        function generatePagination(currentPage, lastPage) {
    let paginationHtml = '';

    if (currentPage > 1) {
        paginationHtml += `<button class="btn btn-sm btn-primary" onclick="fetchTaskData(${currentPage - 1})">Prev</button>`;
    }

    for (let i = 1; i <= lastPage; i++) {
        paginationHtml += `<button class="btn btn-sm ${i === currentPage ? 'btn-secondary' : 'btn-light'}" onclick="fetchTaskData(${i})">${i}</button>`;
    }

    if (currentPage < lastPage) {
        paginationHtml += `<button class="btn btn-sm btn-primary" onclick="fetchTaskData(${currentPage + 1})">Next</button>`;
    }

    $('#pagination').html(paginationHtml);
    }

        // Initial fetch tasks when the page loads
        $(document).ready(function() {
            fetchTaskData();
        });
    </script>

    <script src="/js/tasks.js"></script>
</body>
</html>
