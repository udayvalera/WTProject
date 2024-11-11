<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Fetch user ID
$username = $_SESSION['username'];
$user_sql = "SELECT id FROM users WHERE username='$username'";
$user_result = $conn->query($user_sql);
$user_row = $user_result->fetch_assoc();
$user_id = $user_row['id'];

// Handle new task submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_task'])) {
    $new_task = $_POST['new_task'];
    $insert_sql = "INSERT INTO todo (user_id, text, completed) VALUES ('$user_id', '$new_task', 0)";
    $conn->query($insert_sql);
}

// Fetch user's tasks
$todo_sql = "SELECT * FROM todo WHERE user_id='$user_id'";
$todo_result = $conn->query($todo_sql);
$todos = [];
if ($todo_result->num_rows > 0) {
    while($todo_row = $todo_result->fetch_assoc()) {
        $todos[] = $todo_row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background: #f5f5f5;
            min-height: 100vh;
            padding: 2rem;
            display: flex;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            position: relative;
        }

        h1 {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .input-group {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        input[type="text"] {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #2563eb;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        button:hover {
            background: #1d4ed8;
        }

        .todo-list {
            list-style: none;
            position: relative;
        }

        .todo-item {
            background: #fff;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: grab;
            transition: all 0.2s;
        }

        .todo-item:hover {
            border-color: #2563eb;
        }

        .todo-item.dragging {
            opacity: 0.5;
            background: #f8fafc;
        }

        .todo-item .drag-handle {
            color: #94a3b8;
            cursor: grab;
            user-select: none;
        }

        .todo-item .text {
            flex: 1;
        }

        .todo-item input[type="checkbox"] {
            width: 1.2rem;
            height: 1.2rem;
            cursor: pointer;
        }

        .todo-item.completed .text {
            text-decoration: line-through;
            color: #94a3b8;
        }

        .delete-btn {
            background: #ef4444;
            padding: 0.5rem;
            border-radius: 6px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #dc2626;
        }

        .placeholder {
            background: #f0f9ff;
            border: 2px dashed #2563eb;
            height: 60px;
            margin-bottom: 0.5rem;
            border-radius: 8px;
        }

        .top-right-buttons {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .top-right-buttons form {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-right-buttons">
            <form method="post" action="edit_profile.php">
                <button type="submit">Edit Profile</button>
            </form>
            <form method="post" action="logout.php">
                <button type="submit">Logout</button>
            </form>
        </div>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <div class="input-group">
            <form method="post" action="dashboard.php">
                <input type="text" name="new_task" id="todoInput" placeholder="Add a new task...">
                <button type="submit">Add Task</button>
            </form>
        </div>
        <ul class="todo-list" id="todoList">
            <?php foreach ($todos as $todo): ?>
                <li class="todo-item <?php echo $todo['completed'] ? 'completed' : ''; ?>">
                    <span class="drag-handle">⋮⋮</span>
                    <input type="checkbox" <?php echo $todo['completed'] ? 'checked' : ''; ?> 
                        onchange="toggleTodo(<?php echo $todo['task_id']; ?>)">
                    <span class="text"><?php echo htmlspecialchars($todo['text']); ?></span>
                    <button class="delete-btn" onclick="deleteTodo(<?php echo $todo['task_id']; ?>)">✕</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
        let todos = <?php echo json_encode($todos); ?>;
    
        let draggingElement = null;
        let placeholder = null;
    
        function renderTodos() {
            const todoList = document.getElementById('todoList');
            todoList.innerHTML = '';
            
            todos.forEach((todo, index) => {
                const li = document.createElement('li');
                li.className = `todo-item ${todo.completed ? 'completed' : ''}`;
                li.draggable = true;
                li.dataset.index = index;
                
                li.innerHTML = `
                    <span class="drag-handle">⋮⋮</span>
                    <input type="checkbox" ${todo.completed ? 'checked' : ''} 
                        onchange="toggleTodo(${index})">
                    <span class="text">${todo.text}</span>
                    <button class="delete-btn" onclick="deleteTodo(${index})">✕</button>
                `;
    
                li.addEventListener('dragstart', handleDragStart);
                li.addEventListener('dragend', handleDragEnd);
                li.addEventListener('dragover', handleDragOver);
                li.addEventListener('drop', handleDrop);
    
                todoList.appendChild(li);
            });
        }
    
        function addTodo() {
            const input = document.getElementById('todoInput');
            const text = input.value.trim();
            
            if (text) {
                todos.push({
                    id: Date.now(),
                    text: text,
                    completed: false
                });
                input.value = '';
                renderTodos();
            }
        }
    
        function toggleTodo(index) {
            todos[index].completed = !todos[index].completed;
            renderTodos();
        }
    
        function deleteTodo(index) {
            todos.splice(index, 1);
            renderTodos();
        }
    
        function handleDragStart(e) {
            draggingElement = this;
            this.classList.add('dragging');
    
            // Create and insert the placeholder
            placeholder = document.createElement('li');
            placeholder.className = 'placeholder';
            this.parentNode.insertBefore(placeholder, this.nextSibling);
    
            e.dataTransfer.effectAllowed = 'move';
        }
    
        function handleDragEnd() {
            this.classList.remove('dragging');
            if (placeholder && placeholder.parentNode) {
                placeholder.parentNode.removeChild(placeholder);
            }
            placeholder = null;
            draggingElement = null;
        }
    
        function handleDragOver(e) {
            e.preventDefault();
            const todoItem = e.target.closest('.todo-item');
            if (todoItem && todoItem !== draggingElement) {
                const rect = todoItem.getBoundingClientRect();
                const midY = rect.top + rect.height / 2;
    
                // Move the placeholder based on mouse position
                if (e.clientY < midY) {
                    todoItem.parentNode.insertBefore(placeholder, todoItem);
                } else {
                    todoItem.parentNode.insertBefore(placeholder, todoItem.nextSibling);
                }
            }
        }
    
        function handleDrop(e) {
            e.preventDefault();
            if (!draggingElement || !placeholder) return;
    
            const fromIndex = parseInt(draggingElement.dataset.index);
            const toIndex = Array.from(placeholder.parentNode.children).indexOf(placeholder);
    
            // Update the todos array to reflect the new order
            const [movedItem] = todos.splice(fromIndex, 1);
            todos.splice(toIndex, 0, movedItem);
    
            renderTodos();
        }
    
        document.getElementById('todoInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                addTodo();
            }
        });
    </script>    
</body>
</html>
<?php $conn->close(); ?>