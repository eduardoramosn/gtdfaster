<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>TO DO List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body class="bg-img body-class" onload="initialyze()">

    <div class="container">
        <div class="center-screen">
            <div class="row">
                <div class="col-4 ">
                    <form>
                        <div class="form-group">
                            <div class="centered-element">
                                <img src="/gtdfaster-logo.png"
                                    class="img-fluid logo" alt="" srcset="">
                            </div>
                            <label for="taskInput" class="h5">New task</label>
                            <input type="text" class="form-control" id="taskInput" aria-describedby="emailHelp"
                                placeholder="Enter task">
                            <small id="emailHelp" class="form-text text-muted">And do it!</small>
                        </div>

                    </form>
                    <button class="btn btn-primary" onclick="saveTasks()">Save</button>
                </div>
                <div class="col-7 offset-1">
                    <div>
                        <h1 class="h1">To do list</h1>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Descricão</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="editionModal" tabindex="-1" role="dialog" aria-labelledby="editionModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editionModalLabel">Task edition</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <input type="hidden" id="task-id">
                                    <label for="task-description" class="col-form-label">Description:</label>
                                    <input type="text" class="form-control" id="task-description">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="edit()">Save</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        function initialyze() {
            getTasks();
        }

        function getTasks() {
            $.ajax({
                type: "GET",
                url: "/tasks",
                success: function (data) {
                    console.log(data);
                    var table = document.getElementsByTagName('tbody')[0];
                    table.innerHTML = "";
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            try {
                                var row = table.insertRow(i);
                                var cell1 = row.insertCell(0);
                                var cell2 = row.insertCell(1);
                                var cell3 = row.insertCell(2);
                                var cell4 = row.insertCell(3);
                                cell1.innerHTML = data[i].id;
                                cell2.innerHTML = data[i].description;
                                cell3.innerHTML = `<button class="btn btn-primary" onclick="openEditModal(${data[i].id},'${data[i].description}')"><i class="fa fa-edit"></i></button>`;
                                cell4.innerHTML = '<button class="btn btn-danger" onclick="deleteTask(' + data[i].id + ')"><i class="fa fa-trash"></i></button>';
                            } catch (error) {
                                console.log(error);
                            }

                        }
                    } else {
                        var row = table.insertRow(0);
                        var cell = row.insertCell(0);
                        cell.innerHTML = 'No tasks';
                    }
                },
                error: function (error) {
                    alert(`Error ${error}`);
                }
            })
        }

        function saveTasks() {
            var task = document.getElementById('taskInput').value;
            $.ajax({
                type: "POST",
                url: "/tasks",
                data: {
                    description: task
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log(data);
                    getTasks();
                },
                error: function (error) {
                    alert(`Error ${error}`);
                }
            })
        }

        function deleteTask(id) {
            $.ajax({
                type: "DELETE",
                url: "/tasks/" + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log(data);
                    getTasks();
                },
                error: function (error) {
                    alert(`Error ${error}`);
                }
            })
        }

        function openEditModal(id, description) {
            $('#editionModal').modal('show');
            $('#task-id').val(id);
            $('#task-description').val(description);
        }

        function edit() {
            var id = $('#task-id').val();
            var description = $('#task-description').val();
            $.ajax({
                type: "PUT",
                url: "/tasks/" + id,
                data: {
                    description: description
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log(data);
                    getTasks();
                },
                error: function (error) {
                    alert(`Error ${error}`);
                }
            })
        }
    </script>

</body>

</html>