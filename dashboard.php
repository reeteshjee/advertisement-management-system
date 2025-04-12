<?php


// ad_management.php - Basic Ad Management System
require 'db.php';
checkLogin();
// Fetch ads
$user_id = $_SESSION['admin'];
$superadmins = [1,2];
if(in_array($user_id,$superadmins)){
    $sql = "SELECT * FROM ads ORDER BY id DESC";
}else{
    $sql = "SELECT * FROM ads where user_id=$user_id ORDER BY id DESC";
}
$ads = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
// Handle enabling/disabling ads
if (isset($_POST['toggle_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'] ? 0 : 1;
    $db->prepare("UPDATE ads SET status = ? WHERE id = ?")->execute([$status, $id]);
    header("Location: dashboard");
    exit;
}
// Handle delete ad
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $db->prepare("DELETE FROM ads WHERE id = ?")->execute([$id]);
    header("Location: dashboard");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Ad Management</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="add" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-plus mr-2"></i>Add New Ad
                        </a>
                        <a href="logout" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desktop Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Display Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($ads as $ad): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="<?= $ad['link'] ?>" class="text-blue-600 hover:text-blue-900" target="_blank">
                                        <?= $ad['id'] ?>. <?= $ad['name'] ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="<?= $ad['desktop_image'] ?>" class="w-24 h-16 object-cover rounded border border-primary" alt="Desktop preview">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="<?= $ad['mobile_image'] ?>" class="w-16 h-16 object-cover rounded border border-primary" alt="Mobile preview">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $ad['display_from'] ?><br>to<br><?= $ad['display_to'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <p>Impressions: <?php echo number_format($ad['impressions']) ?></p>
                                    <p>Clicks: <?php echo number_format($ad['clicks']) ?></p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $ad['status'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $ad['status'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                            
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <form method="POST" class="inline-flex space-x-2">
                                            <input type="hidden" name="id" value="<?= $ad['id'] ?>">
                                            <input type="hidden" name="status" value="<?= $ad['status'] ?>">
                                            <input type="hidden" value='<script src="<?php echo BASE_URL;?>sponsored?id=<?= $ad['slug'] ?>" async></script>' 
                                               class="text-sm border rounded px-2 py-1 w-48" readonly>
                                            <button onclick="navigator.clipboard.writeText(this.previousElementSibling.value)" 
                                                    class="text-gray-500 hover:text-gray-700">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <?php
                                                if($ad['status']==1){
                                                    $onoff_class = "fa-toggle-off";
                                                }else{
                                                    $onoff_class = "fa-toggle-on";
                                                }
                                            ?>
                                            <button type="submit" name="toggle_status" 
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas <?php echo $onoff_class;?>"></i>
                                            </button>
                                            <button type="submit" name="delete" 
                                                    onclick="return confirm('Delete this ad?');"
                                                    class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <a href="add?id=<?= $ad['id'] ?>" 
                                           class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        // Show notification when code is copied
        document.querySelectorAll('button[onclick]').forEach(button => {
            button.addEventListener('click', () => {
                const oldTitle = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => button.innerHTML = oldTitle, 1000);
            });
        });
    </script>
</body>
</html>