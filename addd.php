<?php
require 'db.php';
checkLogin();
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM ads WHERE id = ?");
    $stmt->execute([$id]);
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);
}
// Replace only this portion in your existing code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['admin'];
    $link = $_POST['link'];
    $name = $_POST['name'];
    $display_from = $_POST['display_from'];
    $display_to = $_POST['display_to'];
    $target_dir = "uploads/";
    
    if(!is_dir($target_dir)){
        mkdir($target_dir,0755,true);
    }

    // Handle desktop image
    if($_FILES['desktop_image']['tmp_name']){
        $extension = strtolower(pathinfo($_FILES['desktop_image']['name'], PATHINFO_EXTENSION));
        $new_filename = 'desktop_' . uniqid() . '_' . date('Ymd') . '.' . $extension;
        move_uploaded_file($_FILES['desktop_image']['tmp_name'], $target_dir . $new_filename);
        $desktop_image = $target_dir . $new_filename;
    }else{
        $desktop_image = $ad['desktop_image'];
    }

    // Handle mobile image
    if($_FILES['mobile_image']['tmp_name']){
        $extension = strtolower(pathinfo($_FILES['mobile_image']['name'], PATHINFO_EXTENSION));
        $new_filename = 'mobile_' . uniqid() . '_' . date('Ymd') . '.' . $extension;
        move_uploaded_file($_FILES['mobile_image']['tmp_name'], $target_dir . $new_filename);
        $mobile_image = $target_dir . $new_filename;
    }else{
        $mobile_image = $ad['mobile_image'];
    }
    
    if(isset($_POST['id'])){
        $id = $_POST['id'];
        $stmt = $db->prepare("UPDATE ads SET name = ?, desktop_image = ?, mobile_image = ?, link = ?, display_from = ?, display_to = ? WHERE id = ?");
        $stmt->execute([$name, $desktop_image, $mobile_image, $link, $display_from, $display_to,$id]);
    }else{
        $stmt = $db->prepare("INSERT INTO ads (user_id,name,desktop_image, mobile_image, link, display_from, display_to, status) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute([$user_id, $name, $desktop_image, $mobile_image, $link, $display_from, $display_to]);
    }
    header('location:add');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Ad</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-800">
                            <?= isset($ad) ? 'Edit Advertisement' : 'Add New Advertisement' ?>
                        </h1>
                    </div>
                    <div class="flex items-center">
                        <a href="dashboard" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Ad Management
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php if(isset($ad['id'])){?>
                        <input type="hidden" name="id" value="<?php echo $ad['id'];?>">
                    <?php } ?>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Desktop Image</label>
                            <div class="mt-2 flex items-center space-x-4">
                                <input type="file" name="desktop_image" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       <?php if(!isset($ad)){ echo 'required';}?>>
                                <?php if(isset($ad)){?>
                                    <img src="<?php echo $ad['desktop_image'];?>" class="w-24 h-16 object-cover rounded">
                                <?php } ?>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mobile Image</label>
                            <div class="mt-2 flex items-center space-x-4">
                                <input type="file" name="mobile_image"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       <?php if(!isset($ad)){ echo 'required';}?>>
                                <?php if(isset($ad)){?>
                                    <img src="<?php echo $ad['mobile_image'];?>" class="w-16 h-16 object-cover rounded">
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ad Name</label>
                            <input type="text" name="name" 
                                   value="<?php echo (isset($ad))?$ad['name']:'';?>" 
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ad Link</label>
                            <input type="text" name="link" 
                                   value="<?php echo (isset($ad))?$ad['link']:'';?>" 
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Display From</label>
                                <input type="date" name="display_from" 
                                       value="<?php echo (isset($ad))?$ad['display_from']:'';?>" 
                                       required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Display To</label>
                                <input type="date" name="display_to" 
                                       value="<?php echo (isset($ad))?$ad['display_to']:'';?>" 
                                       required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-save mr-2"></i>Save Advertisement
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>