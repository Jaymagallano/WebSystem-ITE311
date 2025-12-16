<!DOCTYPE html>
<html>

<head>
    <title>LMS Users Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>LMS Users Report</h2>
    <p>Generated on: <?= date('F j, Y') ?></p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= ucfirst($user->role) ?></td>
                    <td><?= date('M d, Y', strtotime($user->created_at)) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>