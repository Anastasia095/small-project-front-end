<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h2 class="text-center mb-4 text-primary fw-bold shadow-sm p-3 bg-white rounded">📇 Contact List</h2>

        <div class="table-responsive">
            <table class="table table-striped table-hover text-center shadow-sm bg-white rounded">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Number</th>
                        <th scope="col">Email</th>
                        <th scope="col" colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody id="contacts-table-body">
                
                </tbody>
            </table>
        </div>

        <div id="no-contacts-message" class="alert alert-warning text-center shadow-sm bg-white rounded d-none">
            No contacts found. Add a new contact below!
        </div>

        <div class="d-flex justify-content-center mt-3">
            <button type="button" class="btn btn-primary mx-1">➕ Add Contact</button>
        </div>
    </div>

    <script src="/js/contacts.js"></script>

</body>

</html>
